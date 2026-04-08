/**
 * AI Copilot - Frontend Chat Logic
 */
(function($) {
    let history = [];
    let isLoading = false;
    let selectedElement = null; // Currently selected element from iframe
    let lastActionType = '';    // Track last action for smart suggestions

    // Load pages on init and auto-select home
    $(document).ready(function() {
        loadPages();

        // When page selected, change preview + load session
        $('#aic-page-select').on('change', function() {
            var sel = $(this).find(':selected');
            var url = sel.data('url');
            if (url) {
                showPreviewLoader();
                var iframe = document.getElementById('aic-preview');
                if (iframe) iframe.src = url + '?t=' + Date.now();
            }
            clearSelection();
            loadChatSession();
        });

        // Auto-resize textarea
        $('#aic-input').on('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Enter to send
        $('#aic-input').on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                aiSendMessage();
            }
        });

        // Listen for postMessage from preview iframe (bridge script)
        window.addEventListener('message', handleBridgeMessage);

        // Inject bridge script when iframe loads + sync dropdown with nav clicks
        var iframe = document.getElementById('aic-preview');
        if (iframe) {
            iframe.addEventListener('load', function() {
                hidePreviewLoader();
                injectBridgeScript(iframe);
                syncDropdownWithIframe(iframe);
            });
        }
    });

    // ─── Bridge Communication ───

    function handleBridgeMessage(e) {
        if (!e.data || e.data.source !== 'aic-bridge') return;

        switch (e.data.type) {
            case 'element-selected':
                setSelection(e.data.data);
                break;
            case 'element-deselected':
                clearSelection();
                break;
            case 'quick-action':
                handleQuickAction(e.data.data);
                break;
            case 'navigating':
                showPreviewLoader();
                break;
            case 'bridge-ready':
                // Bridge loaded successfully in iframe
                break;
        }
    }

    /**
     * Inject the preview-bridge.js script into the iframe
     */
    function injectBridgeScript(iframe) {
        try {
            var doc = iframe.contentDocument || iframe.contentWindow.document;
            if (!doc || !doc.body) return;

            // Remove old bridge if re-injecting
            var old = doc.getElementById('aic-bridge-script');
            if (old) old.remove();

            var script = doc.createElement('script');
            script.id = 'aic-bridge-script';
            script.src = aiCopilot.bridgeUrl + '?v=' + Date.now();
            doc.body.appendChild(script);
        } catch (err) {
            // Cross-origin iframe - can't inject (different domain)
            console.log('AI Copilot: Cannot inject bridge script (cross-origin)');
        }
    }

    // ─── Element Selection ───

    function setSelection(info) {
        selectedElement = info;
        showSelectionChip(info);
    }

    function clearSelection() {
        selectedElement = null;
        $('#aic-selection-chip').remove();
        // Tell iframe to clear too
        var iframe = document.getElementById('aic-preview');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage({ source: 'aic-parent', type: 'deselect' }, '*');
        }
    }

    function showSelectionChip(info) {
        // Remove old chip
        $('#aic-selection-chip').remove();

        var typeLabel = info.widgetType || info.elType || info.type || 'element';
        var textPreview = info.text || '';
        if (textPreview.length > 35) textPreview = textPreview.substring(0, 35) + '...';

        var chipHtml =
            '<div id="aic-selection-chip" class="aic-selection-chip">' +
                '<span class="aic-chip-dot"></span>' +
                '<span class="aic-chip-type">' + typeLabel + '</span>' +
                (textPreview ? '<span class="aic-chip-text">' + textPreview + '</span>' : '') +
                '<button class="aic-chip-close" onclick="clearElementSelection()" title="Deselect">&times;</button>' +
            '</div>';

        $('.aic-input-area').before(chipHtml);
    }

    // Global function for chip close button
    window.clearElementSelection = function() {
        clearSelection();
    };

    /**
     * Handle quick action from iframe toolbar button
     */
    function handleQuickAction(data) {
        if (!data) return;
        // Set the element as selected
        if (data.element) setSelection(data.element);

        // Move section up/down: direct AJAX call
        if (data.action === 'move-up' || data.action === 'move-down') {
            var dir = data.action === 'move-up' ? 'up' : 'down';
            var secId = data.command.split(':')[1] || '';
            var pageId = $('#aic-page-select').val() || 0;
            if (!secId || !pageId) return;
            addMessage('user', 'Move section ' + dir);
            $.post(aiCopilot.ajaxUrl, {
                action: 'ai_copilot_move_section',
                nonce: aiCopilot.nonce,
                page_id: pageId,
                element_id: secId,
                direction: dir
            }, function(res) {
                if (res.success) {
                    addAction({ tool: 'move_section', result: res.data });
                    addMessage('ai', 'Section moved ' + dir + '!');
                    refreshPreviewAfterChange();
                    showUndoButton();
                } else {
                    addMessage('ai', 'Cannot move: ' + (res.data || 'Error'));
                }
                scrollToBottom();
            });
            return;
        }

        // Image change/generate: show styled modal
        if (data.action === 'image' || data.action === 'generate-image') {
            showImageSearchModal();
            return;
        }

        // Fill chat input with the command
        var cmd = data.command || '';
        if (cmd) {
            $('#aic-input').val(cmd).focus();
            // Auto-send for these actions (no user editing needed)
            if (data.action === 'remove' || data.action === 'duplicate' || data.autoSend) {
                aiSendMessage();
            }
            // For edit/style - user can modify before sending
        }
    }

    // ─── Preview Loader ───

    function showPreviewLoader() {
        if ($('#aic-preview-loader').length) return;
        $('.aic-preview-panel').append(
            '<div id="aic-preview-loader">' +
                '<div class="aic-preview-spinner"></div>' +
                '<span>Loading page...</span>' +
            '</div>'
        );
    }

    function hidePreviewLoader() {
        $('#aic-preview-loader').remove();
    }

    // ─── Handle nav link clicks inside iframe ───

    function syncDropdownWithIframe(iframe) {
        try {
            var iframeUrl = iframe.contentWindow.location.href.split('?')[0].replace(/\/$/, '');
            var sel = $('#aic-page-select');
            sel.find('option').each(function() {
                var optUrl = $(this).data('url');
                if (optUrl) {
                    optUrl = optUrl.split('?')[0].replace(/\/$/, '');
                    if (optUrl === iframeUrl) {
                        // Update dropdown without triggering change (which would reload iframe)
                        sel.val($(this).val());
                        return false; // break
                    }
                }
            });
        } catch (e) {
            // Cross-origin - can't read iframe URL
        }
    }

    // ─── Chat Panel Collapse/Expand ───

    window.toggleChatPanel = function() {
        var panel = $('.aic-chat-panel');
        panel.toggleClass('collapsed');
    };

    // ─── Options Toggle ───

    window.toggleOptions = function() {
        var bar = $('#aic-options-bar');
        var btn = $('.aic-options-toggle');
        if (bar.is(':visible')) {
            bar.hide();
            btn.removeClass('active');
        } else {
            bar.show();
            btn.addClass('active');
        }
    };

    /**
     * Build context suffix from tone/language/seo options
     */
    function getOptionsContext() {
        var parts = [];
        var tone = $('#aic-tone').val();
        var lang = $('#aic-language').val();
        var seo = $('#aic-seo-keywords').val();
        if (tone) parts.push('[Tone: ' + tone + ']');
        if (lang) parts.push('[Language: ' + lang + ']');
        if (seo) parts.push('[SEO Keywords: ' + seo + ']');
        return parts.join(' ');
    }

    // ─── Pages ───

    function loadPages() {
        $.post(aiCopilot.ajaxUrl, {
            action: 'ai_copilot_pages',
            nonce: aiCopilot.nonce
        }, function(res) {
            if (res.success && res.data) {
                var sel = $('#aic-page-select');
                sel.empty();
                // Build custom dropdown menu
                var menu = $('#aic-page-menu');
                menu.empty();
                res.data.forEach(function(p) {
                    var opt = $('<option></option>').val(p.id).text(p.title).data('url', p.url);
                    sel.append(opt);
                    var icon = p.title.toLowerCase() === 'home' ? '🏠' : '📄';
                    menu.append('<button class="aic-page-item" data-id="' + p.id + '" onclick="selectPage(' + p.id + ',\'' + p.title.replace(/'/g, "\\'") + '\')"><span class="page-icon">' + icon + '</span>' + p.title + '</button>');
                });
                // Auto-select first page (Home)
                if (res.data.length > 0) {
                    sel.val(res.data[0].id).trigger('change');
                    $('#aic-page-label').text(res.data[0].title);
                    menu.find('.aic-page-item:first').addClass('active');
                }
            }
        });
    }

    // ─── Chat ───

    // Global function for suggestion buttons
    window.aiSend = function(text) {
        $('#aic-input').val(text);
        aiSendMessage();
    };

    window.aiSendMessage = function() {
        var input = $('#aic-input');
        var msg = input.val().trim();
        if (!msg || isLoading) return;

        isLoading = true;
        input.val('').css('height', 'auto');
        $('#aic-send').prop('disabled', true);

        // Remove welcome
        $('.aic-welcome').remove();

        // Add user message (include selection context in display)
        var displayMsg = msg;
        if (selectedElement) {
            displayMsg = '<span class="aic-inline-chip">' + (selectedElement.widgetType || selectedElement.type) + '</span> ' + msg;
        }
        addMessage('user', displayMsg);
        history.push({ role: 'user', content: msg });

        // Add loading with progress steps
        var loadingId = 'loading-' + Date.now();
        $('#aic-messages').append(
            '<div class="aic-msg aic-msg-loading" id="' + loadingId + '">' +
            '<div class="aic-spinner"></div><span class="aic-loading-text">Reading page elements...</span></div>'
        );
        scrollToBottom();

        // Simulate progress steps
        setTimeout(function() {
            $('#' + loadingId + ' .aic-loading-text').text('AI is thinking...');
        }, 1500);
        setTimeout(function() {
            $('#' + loadingId + ' .aic-loading-text').text('Applying changes...');
        }, 5000);

        // Append tone/language/seo context to message
        var optCtx = getOptionsContext();
        var fullMsg = optCtx ? msg + ' ' + optCtx : msg;

        // Build request data
        var requestData = {
            action: 'ai_copilot_chat',
            nonce: aiCopilot.nonce,
            message: fullMsg,
            history: JSON.stringify(history.slice(-20)),
            page_id: $('#aic-page-select').val() || 0
        };

        // Include selected element if any
        if (selectedElement) {
            requestData.selected_element = JSON.stringify(selectedElement);
        }

        // Send to backend
        $.post(aiCopilot.ajaxUrl, requestData, function(res) {
            $('#' + loadingId).remove();
            isLoading = false;
            $('#aic-send').prop('disabled', false);

            if (res.success && res.data) {
                var reply = res.data.reply || 'Done.';
                var actions = res.data.actions || [];

                // Show actions
                actions.forEach(function(act) {
                    addAction(act);
                });

                // Show reply
                addMessage('ai', reply);
                history.push({ role: 'assistant', content: reply });

                // Refresh preview if changes were made
                if (res.data.has_changes) {
                    refreshPreviewAfterChange();
                    showUndoButton();
                }

                // Auto-switch to new page when created
                var createAction = actions.find(function(a) { return a.tool === 'create_page' && a.result && a.result.page_id; });
                if (createAction) {
                    // Delay to let WordPress save properly, then switch
                    setTimeout(function() {
                        loadNewPage(createAction.result.page_id);
                    }, 1500);
                }

                // Show smart suggestion chips
                if (actions.length > 0) {
                    lastActionType = actions[actions.length - 1].tool || '';
                    showSuggestionChips(lastActionType);
                    // Keep element selected so user can make multiple changes
                    // Selection only clears when user clicks X or clicks elsewhere
                }

                // Save chat session
                saveChatSession();
            } else {
                addMessage('ai', '&#10060; ' + (res.data || 'Something went wrong.'));
            }
            scrollToBottom();
        }).fail(function() {
            $('#' + loadingId).remove();
            isLoading = false;
            $('#aic-send').prop('disabled', false);
            addMessage('ai', '&#10060; Request failed. Please try again.');
            scrollToBottom();
        });
    };

    // ─── UI Helpers ───

    function addMessage(role, text) {
        var cls = role === 'user' ? 'aic-msg-user' : 'aic-msg-ai';
        // Convert markdown-like formatting
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        text = text.replace(/\n/g, '<br>');
        $('#aic-messages').append(
            '<div class="aic-msg ' + cls + '"><div class="aic-bubble">' + text + '</div></div>'
        );
        scrollToBottom();
    }

    function addAction(act) {
        var tool = act.tool || '';
        var result = act.result || {};
        var success = result.success !== false;
        var msg = result.message || result.error || tool;

        $('#aic-messages').append(
            '<div class="aic-action">' +
            '<div class="aic-action-title">' + (success ? '<span class="aic-icon-success"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg></span>' : '<span class="aic-icon-error"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span>') + ' ' + tool.replace(/_/g, ' ') + '</div>' +
            '<div class="aic-action-detail">' + msg + '</div>' +
            '</div>'
        );

        // If image search returned results, show picker
        if (tool === 'search_images' && result.needs_selection && result.images) {
            showImagePicker(result.images, act.input);
        }

        // If new page created, show link to view/edit it
        if (tool === 'create_page' && result.page_id) {
            var pageUrl = result.url || '';
            $('#aic-messages').append(
                '<div class="aic-action" style="background:#f4f4f5;border-color:#e4e4e7;">' +
                '<div class="aic-action-title" style="color:#111;">&#128196; New Page Created</div>' +
                '<div style="display:flex;gap:8px;margin-top:6px;">' +
                (pageUrl ? '<a href="' + pageUrl + '" target="_blank" style="padding:6px 14px;background:#111;color:#fff;border-radius:8px;font-size:11px;text-decoration:none;font-weight:600;">View Page</a>' : '') +
                '<button onclick="loadNewPage(' + result.page_id + ')" style="padding:6px 14px;background:#fff;color:#111;border:1px solid #ddd;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;">Edit in Copilot</button>' +
                '</div></div>'
            );
        }

        // Show diff card if available
        if (result.diff && (result.diff.before || result.diff.after)) {
            var d = result.diff;
            var diffHtml = '<div class="aic-diff-card">' +
                '<span class="aic-diff-field">' + (d.field || '') + '</span> ' +
                (d.before ? '<span class="aic-diff-before">' + d.before + '</span>' : '') +
                ' <span class="aic-diff-arrow">&rarr;</span> ' +
                '<span class="aic-diff-after">' + (d.after || '') + '</span>' +
            '</div>';
            $('#aic-messages').append(diffHtml);
        }
    }

    function showImagePicker(images, searchInput) {
        var query = searchInput.query || '';
        var html = '<div class="aic-image-picker">';
        html += '<div class="aic-image-picker-header"><span class="aic-image-picker-title">Choose an image</span><span class="aic-image-picker-count">' + images.length + ' results</span></div>';
        html += '<div class="aic-image-grid">';
        images.forEach(function(img, i) {
            var source = (img.source || '').toLowerCase();
            var badge = source === 'pexels' ? 'Pexels' : source === 'pixabay' ? 'Pixabay' : source === 'dalle' ? 'AI' : '';
            html += '<div class="aic-image-option" data-url="' + img.url + '" data-alt="' + (img.alt || '') + '">';
            html += '<div class="aic-image-thumb"><img src="' + img.thumb + '" alt="' + (img.alt || '') + '" loading="lazy">';
            if (badge) html += '<span class="aic-image-badge">' + badge + '</span>';
            html += '</div>';
            html += '<div class="aic-image-meta">';
            html += '<div class="aic-image-alt">' + (img.alt || 'Stock photo').substring(0, 40) + '</div>';
            if (img.credit) html += '<div class="aic-image-credit">' + img.credit + '</div>';
            html += '</div>';
            html += '<button class="aic-image-use-btn" onclick="aiUseImage(' + i + ')">Use This</button>';
            html += '</div>';
        });
        html += '</div>';
        if (query) {
            html += '<div class="aic-image-more"><button onclick="aiSearchMore(\'' + query.replace(/'/g, "\\'") + '\')">Search for more images</button></div>';
        }
        html += '</div>';

        // Store images data for use button
        window._aicImageOptions = images;
        window._aicImageTarget = searchInput;

        $('#aic-messages').append(html);
        scrollToBottom();
    }

    window.aiUseImage = function(index) {
        var images = window._aicImageOptions || [];
        var target = window._aicImageTarget || {};
        if (!images[index]) return;

        var img = images[index];
        var pageId = $('#aic-page-select').val() || 0;
        var elementId = (selectedElement ? selectedElement.id : '') || (target.element_id || '');

        // Disable all use buttons and show progress
        $('.aic-image-use-btn').prop('disabled', true).text('Downloading...');
        // Highlight selected image
        $('.aic-image-option').eq(index).css('border-color', '#22c55e');

        $.post(aiCopilot.ajaxUrl, {
            action: 'ai_copilot_use_image',
            nonce: aiCopilot.nonce,
            image_url: img.url,
            element_id: elementId,
            page_id: pageId,
            alt: img.alt || ''
        }, function(res) {
            if (res.success) {
                // Replace picker with success + image preview
                $('.aic-image-picker').replaceWith(
                    '<div class="aic-action"><div class="aic-action-title">&#9989; Image replaced!</div>' +
                    '<img src="' + img.thumb + '" style="width:100%;max-height:120px;object-fit:cover;border-radius:8px;margin-top:6px;">' +
                    '<div class="aic-action-detail">' + (res.data.message || 'Done') + '</div></div>'
                );
                showUndoButton();
                // Single refresh with enough delay for Elementor CSS regeneration
                var iframe = document.getElementById('aic-preview');
                if (iframe) {
                    setTimeout(function() {
                        iframe.src = iframe.src.split('?')[0] + '?nocache=' + Date.now();
                    }, 1500);
                }
            } else {
                $('.aic-image-use-btn').prop('disabled', false).text('Use This');
                addMessage('ai', '&#10060; ' + (res.data || 'Failed to use image'));
            }
            scrollToBottom();
        }).fail(function() {
            $('.aic-image-use-btn').prop('disabled', false).text('Use This');
        });
    };

    // ─── Image Search Modal ───

    function showImageSearchModal() {
        // Remove old modal if exists
        $('#aic-image-search-modal').remove();

        var html = '<div id="aic-image-search-modal" class="aic-modal-overlay">' +
            '<div class="aic-modal" style="max-width:400px;">' +
                '<div class="aic-modal-header">' +
                    '<h3>Find an Image</h3>' +
                    '<button class="aic-modal-close" onclick="$(\'#aic-image-search-modal\').remove()">&times;</button>' +
                '</div>' +
                '<p class="aic-modal-subtitle">Describe the image you want</p>' +
                '<input type="text" id="aic-image-search-input" class="aic-section-prompt" placeholder="e.g. coffee beans, modern office, sunset beach..." style="padding:12px 14px;border-radius:10px;width:100%;box-sizing:border-box;font-size:14px;">' +
                '<div class="aic-image-suggestions">' +
                    '<button onclick="aiImageQuick(\'coffee\')">coffee</button>' +
                    '<button onclick="aiImageQuick(\'food\')">food</button>' +
                    '<button onclick="aiImageQuick(\'office\')">office</button>' +
                    '<button onclick="aiImageQuick(\'nature\')">nature</button>' +
                    '<button onclick="aiImageQuick(\'team\')">team</button>' +
                    '<button onclick="aiImageQuick(\'technology\')">technology</button>' +
                '</div>' +
                '<div class="aic-modal-actions">' +
                    '<button class="aic-modal-btn-secondary" onclick="$(\'#aic-image-search-modal\').remove()">Cancel</button>' +
                    '<button class="aic-modal-btn-primary" onclick="submitImageSearch()">Search</button>' +
                '</div>' +
            '</div>' +
        '</div>';

        $('body').append(html);
        $('#aic-image-search-input').focus();

        // Enter key
        $('#aic-image-search-input').on('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); submitImageSearch(); }
        });

        // Click overlay to close
        $('#aic-image-search-modal').on('click', function(e) {
            if (e.target === this) $(this).remove();
        });
    }

    window.submitImageSearch = function() {
        var query = $('#aic-image-search-input').val().trim();
        if (!query) return;
        $('#aic-image-search-modal').remove();
        $('#aic-input').val('Find an image of ' + query + ' and replace this image');
        aiSendMessage();
    };

    window.aiImageQuick = function(query) {
        $('#aic-image-search-input').val(query);
        submitImageSearch();
    };

    window.aiSearchMore = function(query) {
        $('#aic-input').val('Search for more images of ' + query);
        aiSendMessage();
    };

    function scrollToBottom() {
        var el = document.getElementById('aic-messages');
        if (el) el.scrollTop = el.scrollHeight;
    }

    /**
     * Refresh preview after AI makes changes - double refresh to ensure
     * Elementor regenerates CSS from the updated JSON data
     */
    function refreshPreviewAfterChange() {
        var iframe = document.getElementById('aic-preview');
        if (!iframe) return;
        // Save scroll position
        var scrollY = 0;
        try { scrollY = iframe.contentWindow.scrollY || 0; } catch(e) {}
        var baseUrl = iframe.src.split('?')[0];
        // Show updating overlay on preview
        if (!$('#aic-updating-overlay').length) {
            $('.aic-preview-panel').append(
                '<div id="aic-updating-overlay" style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.3);z-index:15;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(2px);animation:msgIn .2s ease;">' +
                '<div style="background:#111;color:#fff;padding:10px 24px;border-radius:12px;font-size:12px;font-weight:600;font-family:Poppins,sans-serif;display:flex;align-items:center;gap:8px;box-shadow:0 4px 20px rgba(0,0,0,0.3);">' +
                '<div style="width:14px;height:14px;border:2px solid rgba(255,255,255,0.2);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;"></div>' +
                'Updating preview...</div></div>'
            );
        }
        // Fast refresh - 200ms delay
        setTimeout(function() {
            iframe.src = baseUrl + '?nocache=' + Date.now();
        }, 200);
        // After load: restore scroll + remove overlay
        iframe.addEventListener('load', function restoreScroll() {
            iframe.removeEventListener('load', restoreScroll);
            // Remove overlay
            setTimeout(function() { $('#aic-updating-overlay').fadeOut(200, function() { $(this).remove(); }); }, 100);
            // Restore scroll
            try {
                setTimeout(function() { iframe.contentWindow.scrollTo(0, scrollY); }, 200);
            } catch(e) {}
            // Re-inject bridge script
            injectBridgeScript(iframe);
        });
    }

    window.refreshPreview = function() {
        var iframe = document.getElementById('aic-preview');
        if (iframe) {
            iframe.src = iframe.src.split('?')[0] + '?nocache=' + Date.now();
        }
    };

    // ─── Undo ───

    window.aiUndo = function() {
        var pageId = $('#aic-page-select').val() || 0;
        if (!pageId) return;

        $('#aic-undo-btn').prop('disabled', true).css('opacity', 0.5);

        $.post(aiCopilot.ajaxUrl, {
            action: 'ai_copilot_undo',
            nonce: aiCopilot.nonce,
            page_id: pageId
        }, function(res) {
            $('#aic-undo-btn').prop('disabled', false).css('opacity', 1);

            if (res.success && res.data) {
                addAction({ tool: 'undo', result: res.data });
                addMessage('ai', res.data.message || 'Undo done!');
                refreshPreviewAfterChange();
                // Hide undo button if no more steps
                if ((res.data.remaining || 0) <= 0) {
                    $('#aic-undo-btn').hide();
                }
            } else {
                addMessage('ai', '&#10060; ' + (res.data || 'Nothing to undo.'));
            }
            scrollToBottom();
        }).fail(function() {
            $('#aic-undo-btn').prop('disabled', false).css('opacity', 1);
            addMessage('ai', '&#10060; Undo failed.');
            scrollToBottom();
        });
    };

    /**
     * Show undo button when changes are made
     */
    function showUndoButton() {
        $('#aic-undo-btn').show();
    }

    // ─── Suggestion Chips ───

    function showSuggestionChips(actionType) {
        // Remove old chips
        $('.aic-smart-suggestions').remove();

        var suggestions = [];

        switch (actionType) {
            case 'edit_element_style':
                suggestions = ['Change the font', 'Make it bigger', 'Try another color', 'Undo'];
                break;
            case 'edit_element_text':
                suggestions = ['Rewrite this', 'Make it shorter', 'Change the tone', 'Undo'];
                break;
            case 'add_section':
                suggestions = ['Edit section text', 'Change background', 'Add another section', 'Remove it'];
                break;
            case 'remove_section':
                suggestions = ['Undo', 'Add a new section'];
                break;
            case 'search_images':
                suggestions = ['Search for different images', 'Change image style'];
                break;
            case 'undo':
                suggestions = ['Undo again', 'Try something different'];
                break;
            default:
                suggestions = ['Change colors', 'Edit text', 'Add section', 'Undo'];
        }

        var html = '<div class="aic-smart-suggestions">';
        suggestions.forEach(function(s) {
            html += '<button onclick="aiSend(\'' + s.replace(/'/g, "\\'") + '\')">' + s + '</button>';
        });
        html += '</div>';

        $('#aic-messages').append(html);
        scrollToBottom();
    }

    // ─── Per-Page Chat Sessions ───

    function getSessionKey() {
        var pageId = $('#aic-page-select').val() || 0;
        return 'aic_chat_' + pageId;
    }

    function saveChatSession() {
        try {
            var key = getSessionKey();
            var data = {
                history: history.slice(-30),
                html: $('#aic-messages').html()
            };
            localStorage.setItem(key, JSON.stringify(data));
        } catch (e) { /* localStorage full or unavailable */ }
    }

    function loadChatSession() {
        var key = getSessionKey();
        // Clear current chat
        history = [];
        $('#aic-messages').html('');

        try {
            var saved = localStorage.getItem(key);
            if (saved) {
                var data = JSON.parse(saved);
                history = data.history || [];
                if (data.html) {
                    $('#aic-messages').html(data.html);
                    // Remove old suggestion chips and loading states
                    $('.aic-smart-suggestions').remove();
                    $('.aic-msg-loading').remove();
                    scrollToBottom();
                    return;
                }
            }
        } catch (e) { /* parse error */ }

        // Show welcome if no session
        $('#aic-messages').html(
            '<div class="aic-welcome">' +
                '<div class="aic-welcome-icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.5" stroke-linecap="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>' +
                '<h3>What should we change?</h3>' +
                '<p>Edit text, swap images, update colors, add sections — just describe what you want.</p>' +
                '<div class="aic-suggestions">' +
                    '<button onclick="aiSend(\'Change the hero heading text\')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit text</button>' +
                    '<button onclick="aiSend(\'Update the color scheme\')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg> Change colors</button>' +
                    '<button onclick="aiSend(\'Add a testimonials section\')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Add section</button>' +
                    '<button onclick="aiSend(\'Find an image for the hero\')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg> Find images</button>' +
                '</div>' +
            '</div>'
        );
    }
    // ─── Section Picker Modal ───

    var pickedSectionType = '';

    window.openSectionPicker = function() {
        if (!$('#aic-page-select').val()) {
            alert('Please select a page first.');
            return;
        }
        $('#aic-section-modal').show();
        // Reset to step 1
        $('.aic-section-grid').show();
        $('#aic-section-step2').hide();
        $('#aic-section-prompt').val('');
        pickedSectionType = '';
    };

    window.closeSectionPicker = function() {
        $('#aic-section-modal').hide();
    };

    window.pickSection = function(type) {
        pickedSectionType = type;
        // Show step 2
        $('.aic-section-grid').hide();
        var icons = { hero:'🚀', features:'⭐', testimonials:'💬', pricing:'💰', cta:'📢', faq:'❓', team:'👥', contact:'📧', gallery:'🖼️', custom:'✏️' };
        $('#aic-section-chosen-icon').text(icons[type] || '✏️');
        $('#aic-section-chosen-name').text(type.charAt(0).toUpperCase() + type.slice(1) + ' Section');
        $('#aic-section-step2').show();
        $('#aic-section-prompt').focus();
    };

    window.backToSectionGrid = function() {
        $('#aic-section-step2').hide();
        $('.aic-section-grid').show();
    };

    window.addSectionFromPicker = function() {
        var prompt = $('#aic-section-prompt').val().trim();
        var type = pickedSectionType || 'custom';
        var cmd;
        if (type === 'fullpage') {
            cmd = 'Generate a complete landing page';
            if (prompt) cmd += ' for ' + prompt;
            cmd += ' with hero, features, testimonials, pricing, FAQ, CTA, and contact sections. Replace all existing content.';
        } else {
            cmd = 'Add a ' + type + ' section';
            if (prompt) cmd += ': ' + prompt;
        }
        closeSectionPicker();
        $('#aic-input').val(cmd);
        aiSendMessage();
    };

    // Close modal on overlay click
    $(document).on('click', '#aic-section-modal', function(e) {
        if (e.target === this) closeSectionPicker();
    });

    // Enter key in section prompt
    $(document).on('keydown', '#aic-section-prompt', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            addSectionFromPicker();
        }
    });

    // ─── Style Presets ───

    window.toggleStylePresets = function() {
        var dd = $('#aic-style-presets-dropdown');
        if (dd.is(':visible')) {
            dd.hide();
        } else {
            dd.show();
        }
    };

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.aic-style-presets-wrap').length) {
            $('#aic-style-presets-dropdown').hide();
        }
    });

    window.applyStylePreset = function(name, primary, accent, light, font) {
        var pageId = $('#aic-page-select').val() || 0;
        if (!pageId) {
            alert('Please select a page first.');
            return;
        }
        $('#aic-style-presets-dropdown').hide();

        // Remove welcome
        $('.aic-welcome').remove();

        // Show applying message
        addMessage('user', 'Apply style preset: ' + name);
        var loadingId = 'loading-' + Date.now();
        $('#aic-messages').append(
            '<div class="aic-msg aic-msg-loading" id="' + loadingId + '">' +
            '<div class="aic-spinner"></div><span>Applying ' + name + ' preset...</span></div>'
        );
        scrollToBottom();

        // Direct AJAX call - no AI needed
        $.post(aiCopilot.ajaxUrl, {
            action: 'ai_copilot_apply_preset',
            nonce: aiCopilot.nonce,
            page_id: pageId,
            bg_color: primary,
            accent_color: accent,
            text_color: light,
            font: font
        }, function(res) {
            $('#' + loadingId).remove();
            if (res.success && res.data) {
                addAction({ tool: 'apply_preset', result: res.data });
                addMessage('ai', 'Style preset "' + name + '" applied! ' + (res.data.message || ''));
                refreshPreviewAfterChange();
                showUndoButton();
            } else {
                addMessage('ai', '&#10060; ' + (res.data || 'Failed to apply preset.'));
            }
            scrollToBottom();
        }).fail(function() {
            $('#' + loadingId).remove();
            addMessage('ai', '&#10060; Failed to apply preset.');
            scrollToBottom();
        });
    };

    // ─── Code Editor ───

    window.toggleCodeEditor = function() {
        var panel = $('#aic-code-panel');
        if (panel.is(':visible')) {
            closeCodeEditor();
        } else {
            openCodeEditor();
        }
    };

    function openCodeEditor() {
        var pageId = $('#aic-page-select').val() || 0;
        if (!pageId) { alert('Please select a page first.'); return; }

        $('#aic-code-content').text('Loading...');
        $('#aic-code-panel').show();

        $.post(aiCopilot.ajaxUrl, {
            action: 'ai_copilot_get_code',
            nonce: aiCopilot.nonce,
            page_id: pageId
        }, function(res) {
            if (res.success && res.data) {
                $('#aic-code-content').text(res.data.code);
            } else {
                $('#aic-code-content').text('Error: ' + (res.data || 'Failed to load'));
            }
        });
    }

    // Load newly created page in copilot preview
    window.loadNewPage = function(pageId) {
        // Reload pages dropdown to include new page
        loadPages();
        // Wait for dropdown to update, then select the new page
        setTimeout(function() {
            var sel = $('#aic-page-select');
            if (sel.find('option[value="' + pageId + '"]').length) {
                sel.val(pageId).trigger('change');
            } else {
                // Page not in dropdown yet, try again
                loadPages();
                setTimeout(function() {
                    sel.val(pageId).trigger('change');
                }, 1000);
            }
        }, 800);
    };

    window.closeCodeEditor = function() {
        $('#aic-code-panel').hide();
    };

    window.copyCode = function() {
        var code = $('#aic-code-content').text();
        navigator.clipboard.writeText(code).then(function() {
            var btn = $('.aic-code-actions button:first');
            btn.text('Copied!');
            setTimeout(function() { btn.text('Copy'); }, 1500);
        });
    };

    // ─── Custom Page Dropdown ───
    window.togglePageDropdown = function() {
        $('#aic-page-dropdown').toggleClass('open');
    };

    window.selectPage = function(id, title) {
        $('#aic-page-select').val(id).trigger('change');
        $('#aic-page-label').text(title);
        // Update active state
        $('.aic-page-item').removeClass('active');
        $('.aic-page-item[data-id="' + id + '"]').addClass('active');
        $('#aic-page-dropdown').removeClass('open');
    };

    // Close dropdown on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.aic-page-dropdown').length) {
            $('#aic-page-dropdown').removeClass('open');
        }
    });

})(jQuery);
