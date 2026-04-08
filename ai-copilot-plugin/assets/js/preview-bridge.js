/**
 * AI Copilot - Preview Bridge
 * Injected into the preview iframe to enable element selection & hover highlights
 * Communicates with parent (copilot.js) via postMessage
 */
(function() {
    'use strict';

    // ─── Hide WordPress Admin Bar inside iframe ───
    // This ensures clean preview for ALL websites
    var adminBar = document.getElementById('wpadminbar');
    if (adminBar) adminBar.style.display = 'none';
    // Remove admin bar body margin/padding
    document.documentElement.style.marginTop = '0';
    document.body.style.marginTop = '0';
    document.documentElement.style.paddingTop = '0';
    document.body.style.paddingTop = '0';
    // Also override WP admin bar CSS class
    var hideStyle = document.createElement('style');
    hideStyle.textContent = '#wpadminbar{display:none!important}html{margin-top:0!important;padding-top:0!important}body.admin-bar{margin-top:0!important;padding-top:0!important}';
    document.head.appendChild(hideStyle);

    var selectedEl = null;
    var hoveredEl = null;
    var isActive = true;

    // Create highlight overlay (avoids modifying actual element styles)
    var hoverOverlay = document.createElement('div');
    hoverOverlay.id = 'aic-hover-overlay';
    hoverOverlay.style.cssText = 'position:fixed;pointer-events:none;border:2px solid #6366f1;border-radius:4px;z-index:999998;display:none;transition:all .15s ease;';
    document.body.appendChild(hoverOverlay);

    var selectOverlay = document.createElement('div');
    selectOverlay.id = 'aic-select-overlay';
    selectOverlay.style.cssText = 'position:fixed;pointer-events:none;border:2px solid #22c55e;border-radius:4px;z-index:999999;display:none;box-shadow:0 0 0 2px rgba(34,197,94,0.2);';
    document.body.appendChild(selectOverlay);

    // Label badge for selected element
    var selectLabel = document.createElement('div');
    selectLabel.id = 'aic-select-label';
    selectLabel.style.cssText = 'position:fixed;z-index:1000000;display:none;background:#22c55e;color:#fff;font-size:11px;font-family:Inter,system-ui,sans-serif;padding:2px 8px;border-radius:3px 3px 0 0;font-weight:600;white-space:nowrap;';
    document.body.appendChild(selectLabel);

    /**
     * Find the closest Elementor element from a click/hover target
     */
    function findElementorElement(target) {
        var el = target.closest('.elementor-element[data-id]');
        if (!el) return null;

        // Prefer widgets over sections/columns (more specific)
        var widget = target.closest('.elementor-widget[data-id]');
        if (widget) return widget;

        return el;
    }

    /**
     * Get element info for postMessage
     */
    function getElementInfo(el) {
        if (!el) return null;

        var id = el.getAttribute('data-id') || '';
        var elType = el.getAttribute('data-element_type') || '';
        var widgetType = el.getAttribute('data-widget_type') || '';
        // Clean widget type (e.g. "heading.default" → "heading")
        if (widgetType) widgetType = widgetType.split('.')[0];

        // Get visible text content
        var text = '';
        var type = widgetType || elType;

        if (widgetType === 'heading') {
            var h = el.querySelector('.elementor-heading-title');
            if (h) text = h.textContent.trim();
        } else if (widgetType === 'text-editor') {
            var te = el.querySelector('.elementor-text-editor');
            if (te) text = te.textContent.trim().substring(0, 80);
        } else if (widgetType === 'button') {
            var btn = el.querySelector('.elementor-button-text');
            if (btn) text = btn.textContent.trim();
        } else if (widgetType === 'image') {
            var img = el.querySelector('img');
            if (img) text = img.getAttribute('alt') || img.src.split('/').pop();
        } else if (elType === 'section' || elType === 'container') {
            // Get first heading text inside section as label
            var firstH = el.querySelector('.elementor-heading-title');
            if (firstH) text = firstH.textContent.trim().substring(0, 50);
            else text = elType + ' block';
        } else if (elType === 'column') {
            type = 'column';
            text = 'column';
        }

        if (!text) text = type;

        return {
            id: id,
            elType: elType,
            widgetType: widgetType,
            type: type,
            text: text
        };
    }

    /**
     * Position an overlay on an element
     */
    function positionOverlay(overlay, el) {
        var rect = el.getBoundingClientRect();
        overlay.style.top = rect.top + 'px';
        overlay.style.left = rect.left + 'px';
        overlay.style.width = rect.width + 'px';
        overlay.style.height = rect.height + 'px';
        overlay.style.display = 'block';
    }

    // ─── Quick Action Toolbar ───

    var toolbar = document.createElement('div');
    toolbar.id = 'aic-toolbar';
    toolbar.style.cssText = 'position:fixed;z-index:1000001;display:none;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);padding:4px;gap:2px;font-family:Poppins,system-ui,sans-serif;white-space:nowrap;border:1px solid #eee;';
    toolbar.style.display = 'none';
    document.body.appendChild(toolbar);

    var toolbarTimeout = null;

    // SVG icons for toolbar
    var svgIcons = {
        edit: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
        ai: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
        style: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>',
        remove: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>',
        image: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
        generate: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
        bg: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>',
        duplicate: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>'
    };

    function getToolbarButtons(info) {
        var type = info.widgetType || info.elType || '';
        var buttons = [];

        if (type === 'heading' || type === 'text-editor') {
            buttons.push({ icon: svgIcons.edit, label: 'Edit', action: 'edit', cmd: 'Edit this ' + type + ' text' });
            buttons.push({
                icon: svgIcons.ai, label: 'Write with AI', action: 'write-ai', isDropdown: true,
                items: [
                    { label: 'Improve Writing', cmd: 'Improve the writing quality of this ' + type + ': ' + info.text },
                    { label: 'Fix Grammar', cmd: 'Fix any grammar and spelling errors in this ' + type + ': ' + info.text },
                    { label: 'Make Shorter', cmd: 'Make this ' + type + ' text shorter and more concise: ' + info.text },
                    { label: 'Make Longer', cmd: 'Expand and make this ' + type + ' text longer with more detail: ' + info.text },
                    { label: 'Simplify Language', cmd: 'Simplify the language of this ' + type + ' to be easier to understand: ' + info.text },
                    { label: 'Professional Tone', cmd: 'Rewrite this ' + type + ' in a professional business tone: ' + info.text },
                    { label: 'Casual Tone', cmd: 'Rewrite this ' + type + ' in a casual friendly tone: ' + info.text },
                    { label: 'Persuasive Tone', cmd: 'Rewrite this ' + type + ' in a persuasive marketing tone: ' + info.text },
                    { label: 'Complete Rewrite', cmd: 'Completely rewrite this ' + type + ' with fresh content: ' + info.text },
                ]
            });
            buttons.push({ icon: svgIcons.style, label: 'Style', action: 'style', cmd: 'Change the style of this ' + type });
            buttons.push({ icon: svgIcons.remove, label: 'Remove', action: 'remove', cmd: 'Remove this element' });
        } else if (type === 'button') {
            buttons.push({ icon: svgIcons.edit, label: 'Edit', action: 'edit', cmd: 'Change this button text' });
            buttons.push({ icon: svgIcons.style, label: 'Style', action: 'style', cmd: 'Change this button color and style' });
        } else if (type === 'image') {
            buttons.push({ icon: svgIcons.image, label: 'Change', action: 'image', cmd: 'Find a new image to replace this one' });
            buttons.push({ icon: svgIcons.generate, label: 'Generate', action: 'generate-image', cmd: 'Generate an AI image for this element. Current image: ' + (info.text || 'image') });
            buttons.push({ icon: svgIcons.remove, label: 'Remove', action: 'remove', cmd: 'Remove this image' });
        } else if (type === 'section' || type === 'container') {
            buttons.push({ icon: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="18 15 12 9 6 15"/></svg>', label: 'Move Up', action: 'move-up', cmd: 'move_section_up:' + info.id });
            buttons.push({ icon: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>', label: 'Move Down', action: 'move-down', cmd: 'move_section_down:' + info.id });
            buttons.push({ icon: svgIcons.bg, label: 'Background', action: 'style', cmd: 'Change background of this section' });
            buttons.push({ icon: svgIcons.duplicate, label: 'Duplicate', action: 'duplicate', cmd: 'Duplicate this section' });
            buttons.push({ icon: svgIcons.remove, label: 'Remove', action: 'remove', cmd: 'Remove this section' });
        } else {
            buttons.push({ icon: svgIcons.edit, label: 'Edit', action: 'edit', cmd: 'Edit this element' });
            buttons.push({ icon: svgIcons.style, label: 'Style', action: 'style', cmd: 'Change the style of this element' });
        }

        return buttons;
    }

    // Dropdown submenu element
    var dropdown = document.createElement('div');
    dropdown.id = 'aic-dropdown';
    dropdown.style.cssText = 'position:fixed;z-index:1000002;display:none;background:#111;border:1px solid #333;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,0.4);padding:6px;font-family:Poppins,system-ui,sans-serif;min-width:190px;';
    document.body.appendChild(dropdown);

    function hideDropdown() { dropdown.style.display = 'none'; }

    function showToolbar(el, info) {
        var buttons = getToolbarButtons(info);
        toolbar.innerHTML = '';
        toolbar.style.display = 'flex';

        buttons.forEach(function(btn) {
            var b = document.createElement('button');
            b.style.cssText = 'display:flex;align-items:center;gap:5px;padding:7px 12px;border:none;background:none;cursor:pointer;font-size:11px;font-weight:500;color:#333;border-radius:8px;transition:all .15s;position:relative;font-family:Poppins,system-ui,sans-serif;';
            b.innerHTML = btn.icon + btn.label + (btn.isDropdown ? ' <svg width="8" height="8" viewBox="0 0 10 6" fill="none" style="opacity:.5"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>' : '');
            b.onmouseenter = function() { b.style.background = '#f5f5f5'; b.style.color = '#111'; };
            b.onmouseleave = function() { b.style.background = 'none'; b.style.color = '#333'; };

            if (btn.isDropdown) {
                b.onclick = function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    // Show dropdown menu
                    dropdown.innerHTML = '';
                    dropdown.style.display = 'block';
                    var bRect = b.getBoundingClientRect();
                    dropdown.style.left = bRect.left + 'px';
                    dropdown.style.top = bRect.bottom + 'px';

                    // Header
                    var header = document.createElement('div');
                    header.style.cssText = 'padding:6px 10px 4px;font-size:10px;font-weight:600;color:#666;text-transform:uppercase;letter-spacing:0.8px;';
                    header.textContent = 'Write with AI';
                    dropdown.appendChild(header);

                    btn.items.forEach(function(item) {
                        var d = document.createElement('button');
                        d.style.cssText = 'display:block;width:100%;text-align:left;padding:8px 12px;border:none;background:none;cursor:pointer;font-size:11px;font-weight:500;color:#aaa;border-radius:8px;transition:all .12s;font-family:Poppins,system-ui,sans-serif;';
                        d.textContent = item.label;
                        d.onmouseenter = function() { d.style.background = '#222'; d.style.color = '#fff'; };
                        d.onmouseleave = function() { d.style.background = 'none'; d.style.color = '#aaa'; };
                        d.onclick = function(ev) {
                            ev.stopPropagation();
                            ev.preventDefault();
                            selectElement(el);
                            window.parent.postMessage({
                                source: 'aic-bridge',
                                type: 'quick-action',
                                data: { action: 'write-ai', command: item.cmd, element: info, autoSend: true }
                            }, '*');
                            hideDropdown();
                            hideToolbar();
                        };
                        dropdown.appendChild(d);
                    });
                };
            } else {
                b.onclick = function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    hideDropdown();
                    selectElement(el);
                    window.parent.postMessage({
                        source: 'aic-bridge',
                        type: 'quick-action',
                        data: { action: btn.action, command: btn.cmd, element: info }
                    }, '*');
                    hideToolbar();
                };
            }
            toolbar.appendChild(b);
        });

        // Position toolbar above element
        var rect = el.getBoundingClientRect();
        var tbHeight = 36;
        var topPos = rect.top - tbHeight - 6;
        if (topPos < 4) topPos = rect.bottom + 6; // below if no room above

        toolbar.style.left = Math.max(4, rect.left) + 'px';
        toolbar.style.top = topPos + 'px';
    }

    function hideToolbar() {
        toolbar.style.display = 'none';
        hideDropdown();
    }

    // ─── Event Handlers ───

    document.addEventListener('mouseover', function(e) {
        if (!isActive) return;

        // Don't hide toolbar/dropdown when hovering them
        if (toolbar.contains(e.target) || dropdown.contains(e.target)) return;

        var el = findElementorElement(e.target);
        if (!el || el === selectedEl) {
            if (!el) {
                hoverOverlay.style.display = 'none';
                hoveredEl = null;
                // Don't hide if dropdown is open
                if (dropdown.style.display === 'block') return;
                // Delayed toolbar hide
                clearTimeout(toolbarTimeout);
                toolbarTimeout = setTimeout(hideToolbar, 300);
            }
            return;
        }

        hoveredEl = el;
        positionOverlay(hoverOverlay, el);

        // Show toolbar on hover (slight delay to avoid flicker)
        clearTimeout(toolbarTimeout);
        var info = getElementInfo(el);
        toolbarTimeout = setTimeout(function() {
            if (hoveredEl === el) showToolbar(el, info);
        }, 400);
    }, true);

    document.addEventListener('mouseout', function(e) {
        // Don't hide if moving to toolbar or dropdown
        if (toolbar.contains(e.relatedTarget) || dropdown.contains(e.relatedTarget)) return;
        // Don't hide if dropdown is open
        if (dropdown.style.display === 'block') return;

        if (hoveredEl && !hoveredEl.contains(e.relatedTarget)) {
            hoverOverlay.style.display = 'none';
            hoveredEl = null;
            clearTimeout(toolbarTimeout);
            toolbarTimeout = setTimeout(hideToolbar, 300);
        }
    }, true);

    // Keep toolbar visible when hovering it
    toolbar.addEventListener('mouseenter', function() {
        clearTimeout(toolbarTimeout);
    });
    toolbar.addEventListener('mouseleave', function() {
        if (dropdown.style.display === 'block') return;
        toolbarTimeout = setTimeout(hideToolbar, 200);
    });

    // Keep dropdown visible when hovering it
    dropdown.addEventListener('mouseenter', function() {
        clearTimeout(toolbarTimeout);
    });
    dropdown.addEventListener('mouseleave', function() {
        hideDropdown();
        toolbarTimeout = setTimeout(hideToolbar, 200);
    });

    document.addEventListener('click', function(e) {
        if (!isActive) return;

        // Let navigation links work naturally + tell parent to show loader
        var link = e.target.closest('a[href]');
        if (link) {
            var href = link.getAttribute('href') || '';
            if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
                window.parent.postMessage({ source: 'aic-bridge', type: 'navigating' }, '*');
                return;
            }
        }

        var el = findElementorElement(e.target);
        if (!el) {
            // Click on empty area → deselect
            clearSelection();
            window.parent.postMessage({ source: 'aic-bridge', type: 'element-deselected' }, '*');
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        // If clicking same element → deselect
        if (el === selectedEl) {
            clearSelection();
            window.parent.postMessage({ source: 'aic-bridge', type: 'element-deselected' }, '*');
            return;
        }

        selectElement(el);
    }, true);

    function selectElement(el) {
        selectedEl = el;
        positionOverlay(selectOverlay, el);
        hoverOverlay.style.display = 'none';

        // Show label above selection
        var rect = el.getBoundingClientRect();
        var info = getElementInfo(el);
        selectLabel.textContent = info.type + ': ' + info.text.substring(0, 30);
        selectLabel.style.left = rect.left + 'px';
        selectLabel.style.top = (rect.top - 22) + 'px';
        selectLabel.style.display = 'block';

        // Send to parent
        window.parent.postMessage({
            source: 'aic-bridge',
            type: 'element-selected',
            data: info
        }, '*');
    }

    function clearSelection() {
        selectedEl = null;
        selectOverlay.style.display = 'none';
        selectLabel.style.display = 'none';
    }

    // Update overlay positions on scroll/resize
    function updateOverlays() {
        if (selectedEl) positionOverlay(selectOverlay, selectedEl);
        if (selectedEl) {
            var rect = selectedEl.getBoundingClientRect();
            selectLabel.style.left = rect.left + 'px';
            selectLabel.style.top = (rect.top - 22) + 'px';
        }
        if (hoveredEl) positionOverlay(hoverOverlay, hoveredEl);
    }
    window.addEventListener('scroll', updateOverlays, true);
    window.addEventListener('resize', updateOverlays);

    // Listen for messages from parent (e.g., deselect, activate/deactivate)
    window.addEventListener('message', function(e) {
        if (!e.data || e.data.source !== 'aic-parent') return;

        if (e.data.type === 'deselect') {
            clearSelection();
        } else if (e.data.type === 'set-active') {
            isActive = !!e.data.active;
            if (!isActive) {
                clearSelection();
                hoverOverlay.style.display = 'none';
            }
        } else if (e.data.type === 'select-by-id') {
            // Select element by ID from parent
            var target = document.querySelector('.elementor-element[data-id="' + e.data.id + '"]');
            if (target) selectElement(target);
        }
    });

    // Notify parent that bridge is ready
    window.parent.postMessage({ source: 'aic-bridge', type: 'bridge-ready' }, '*');
})();
