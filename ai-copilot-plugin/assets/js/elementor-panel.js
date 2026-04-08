/**
 * AI Copilot - Elementor Editor Integration
 * Adds: Co-Pilot panel in editor sidebar + AI buttons on widgets
 */
(function($) {
    'use strict';

    // Wait for Elementor editor to load
    if (typeof elementor === 'undefined') return;

    var panelOpen = false;
    var chatHistory = [];

    // ─── 1. Add Co-Pilot Panel Button to Editor ───

    elementor.on('panel:init', function() {
        addCopilotButton();
    });

    // Also try on document ready (some Elementor versions)
    $(window).on('elementor:init', function() {
        setTimeout(addCopilotButton, 1000);
    });

    function addCopilotButton() {
        // Add button to Elementor header
        var headerBtns = $('#elementor-panel-header-menu-button').parent();
        if (headerBtns.length && !$('#aic-elementor-toggle').length) {
            var btn = $('<div id="aic-elementor-toggle" class="elementor-panel-header-button" title="AI Copilot">' +
                '<i class="eicon-ai" style="font-size:18px;"></i></div>');
            btn.on('click', toggleCopilotPanel);
            headerBtns.append(btn);
        }
    }

    function toggleCopilotPanel() {
        if (panelOpen) {
            closeCopilotPanel();
        } else {
            openCopilotPanel();
        }
    }

    function openCopilotPanel() {
        if ($('#aic-editor-panel').length) {
            $('#aic-editor-panel').show();
            panelOpen = true;
            return;
        }

        var panelHtml =
            '<div id="aic-editor-panel" class="aic-editor-panel">' +
                '<div class="aic-ep-header">' +
                    '<span class="aic-ep-title">AI Copilot</span>' +
                    '<button class="aic-ep-close" onclick="jQuery(\'#aic-editor-panel\').hide();">&times;</button>' +
                '</div>' +
                '<div class="aic-ep-messages" id="aic-ep-messages"></div>' +
                '<div class="aic-ep-input-wrap">' +
                    '<textarea id="aic-ep-input" placeholder="Ask AI to edit..." rows="1"></textarea>' +
                    '<button id="aic-ep-send" onclick="aicEditorSend()">&#9650;</button>' +
                '</div>' +
            '</div>';

        $('body').append(panelHtml);
        panelOpen = true;

        // Enter to send
        $('#aic-ep-input').on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                aicEditorSend();
            }
        });
    }

    function closeCopilotPanel() {
        $('#aic-editor-panel').hide();
        panelOpen = false;
    }

    // ─── 2. AI Chat from Elementor Editor ───

    window.aicEditorSend = function() {
        var input = $('#aic-ep-input');
        var msg = input.val().trim();
        if (!msg) return;

        input.val('');

        // Add user message
        $('#aic-ep-messages').append(
            '<div class="aic-ep-msg aic-ep-user">' + msg + '</div>'
        );

        // Get selected element in Elementor
        var selectedEl = null;
        if (elementor.selection) {
            var selected = elementor.selection.getElements();
            if (selected && selected.length > 0) {
                var model = selected[0].model || selected[0];
                selectedEl = {
                    id: model.get ? model.get('id') : (model.id || ''),
                    type: model.get ? model.get('widgetType') || model.get('elType') : '',
                };
            }
        }

        // Get page ID
        var pageId = elementor.config.document.id || 0;

        // Add loading
        var loadId = 'ep-load-' + Date.now();
        $('#aic-ep-messages').append(
            '<div class="aic-ep-msg aic-ep-loading" id="' + loadId + '">Thinking...</div>'
        );
        scrollEpBottom();

        chatHistory.push({ role: 'user', content: msg });

        // Call backend
        $.post(aicElementor.ajaxUrl, {
            action: 'ai_copilot_chat',
            nonce: aicElementor.nonce,
            message: msg,
            history: JSON.stringify(chatHistory.slice(-20)),
            page_id: pageId,
            selected_element: selectedEl ? JSON.stringify(selectedEl) : ''
        }, function(res) {
            $('#' + loadId).remove();

            if (res.success && res.data) {
                var reply = res.data.reply || 'Done.';
                $('#aic-ep-messages').append(
                    '<div class="aic-ep-msg aic-ep-ai">' + reply + '</div>'
                );
                chatHistory.push({ role: 'assistant', content: reply });

                // Reload Elementor preview if changes were made
                if (res.data.has_changes) {
                    // Refresh Elementor's preview
                    if (elementor.$preview && elementor.$preview[0]) {
                        elementor.$preview[0].contentWindow.location.reload();
                    }
                    // Also reload panel data
                    if (typeof $e !== 'undefined' && $e.run) {
                        try { $e.run('document/save/auto'); } catch(e) {}
                    }
                }
            } else {
                $('#aic-ep-messages').append(
                    '<div class="aic-ep-msg aic-ep-ai" style="color:#dc2626;">Error: ' + (res.data || 'Failed') + '</div>'
                );
            }
            scrollEpBottom();
        }).fail(function() {
            $('#' + loadId).remove();
            $('#aic-ep-messages').append(
                '<div class="aic-ep-msg aic-ep-ai" style="color:#dc2626;">Request failed</div>'
            );
        });
    };

    function scrollEpBottom() {
        var el = document.getElementById('aic-ep-messages');
        if (el) el.scrollTop = el.scrollHeight;
    }

    // ─── 3. Inline AI Buttons on Widget Panels ───

    // When a widget panel opens, add AI action button
    elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
        setTimeout(function() {
            addWidgetAiButton(panel, model, view);
        }, 300);
    });

    function addWidgetAiButton(panel, model, view) {
        var $panel = panel.$el || $(panel.el);
        if (!$panel.length || $panel.find('.aic-widget-ai-btn').length) return;

        var widgetType = model.get('widgetType') || '';
        var elId = model.get('id') || '';

        var buttons = [];

        // Text widgets: Write with AI, Rewrite
        if (['heading', 'text-editor', 'button'].includes(widgetType)) {
            buttons.push({ label: 'Write with AI', icon: '&#10024;', cmd: 'Write content for this ' + widgetType });
            buttons.push({ label: 'Rewrite', icon: '&#128260;', cmd: 'Rewrite this ' + widgetType + ' text' });
            buttons.push({ label: 'Expand', icon: '&#128200;', cmd: 'Expand this text to be longer' });
            buttons.push({ label: 'Shorten', icon: '&#128201;', cmd: 'Make this text shorter' });
        }

        // Image widgets: Generate Image
        if (['image', 'image-box'].includes(widgetType)) {
            buttons.push({ label: 'Find Image', icon: '&#128444;&#65039;', cmd: 'Find a new image for this element' });
            buttons.push({ label: 'Generate Image', icon: '&#10024;', cmd: 'Generate an AI image for this element' });
        }

        // All widgets: Style change
        buttons.push({ label: 'AI Style', icon: '&#127912;', cmd: 'Suggest a better style for this ' + widgetType });

        if (!buttons.length) return;

        // Create button bar
        var bar = $('<div class="aic-widget-ai-btn"></div>');
        bar.css({
            padding: '8px 12px',
            borderBottom: '1px solid #d5d8dc',
            display: 'flex',
            gap: '6px',
            flexWrap: 'wrap',
            background: '#f8f9ff',
        });

        buttons.forEach(function(btn) {
            var b = $('<button></button>');
            b.html(btn.icon + ' ' + btn.label);
            b.css({
                padding: '4px 10px',
                border: '1px solid #d5d8dc',
                borderRadius: '4px',
                background: '#fff',
                cursor: 'pointer',
                fontSize: '12px',
                transition: 'all .15s',
            });
            b.on('mouseenter', function() { b.css({ background: '#eef2ff', borderColor: '#6366f1' }); });
            b.on('mouseleave', function() { b.css({ background: '#fff', borderColor: '#d5d8dc' }); });
            b.on('click', function() {
                openCopilotPanel();
                $('#aic-ep-input').val(btn.cmd);
                aicEditorSend();
            });
            bar.append(b);
        });

        // Insert at top of widget controls
        var controls = $panel.find('.elementor-controls-stack');
        if (controls.length) {
            controls.first().prepend(bar);
        } else {
            $panel.find('.elementor-panel-navigation').after(bar);
        }
    }

    // ─── 4. Inject CSS for Editor Panel ───

    var css =
        '#aic-elementor-toggle { cursor:pointer; padding:8px; }' +
        '#aic-elementor-toggle:hover { color:#6366f1; }' +
        '.aic-editor-panel { position:fixed; right:0; top:40px; width:320px; height:calc(100vh - 40px); background:#fff; border-left:1px solid #d5d8dc; z-index:100000; display:flex; flex-direction:column; box-shadow:-4px 0 20px rgba(0,0,0,0.1); }' +
        '.aic-ep-header { padding:12px 16px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; }' +
        '.aic-ep-title { font-weight:700; font-size:14px; }' +
        '.aic-ep-close { background:none; border:none; font-size:20px; cursor:pointer; color:#888; }' +
        '.aic-ep-close:hover { color:#333; }' +
        '.aic-ep-messages { flex:1; overflow-y:auto; padding:12px; }' +
        '.aic-ep-msg { margin-bottom:10px; padding:8px 12px; border-radius:10px; font-size:13px; line-height:1.5; animation:aicSlide .2s ease; }' +
        '.aic-ep-user { background:#f3f4f6; text-align:right; border-radius:10px 10px 2px 10px; }' +
        '.aic-ep-ai { background:none; color:#333; }' +
        '.aic-ep-loading { color:#888; font-style:italic; }' +
        '.aic-ep-input-wrap { padding:10px 12px; border-top:1px solid #eee; display:flex; gap:6px; }' +
        '#aic-ep-input { flex:1; padding:8px 12px; border:1px solid #d5d8dc; border-radius:8px; font-size:13px; resize:none; outline:none; font-family:inherit; }' +
        '#aic-ep-input:focus { border-color:#6366f1; }' +
        '#aic-ep-send { width:34px; height:34px; border-radius:50%; background:#6366f1; border:none; color:#fff; cursor:pointer; font-size:14px; }' +
        '#aic-ep-send:hover { background:#4f46e5; }' +
        '@keyframes aicSlide { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }';

    $('<style>').text(css).appendTo('head');

})(jQuery);
