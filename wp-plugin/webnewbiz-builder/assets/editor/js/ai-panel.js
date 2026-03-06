(function () {
    'use strict';

    if (typeof elementor === 'undefined') return;

    var panelOpen = false;
    var messages = [];
    var isLoading = false;

    // ─── Quick Prompts ───
    var QUICK_PROMPTS = [
        { label: 'Hero Section', prompt: 'Create a stunning hero section with a headline, subtitle, and CTA button' },
        { label: 'Features Grid', prompt: 'Create a 3-column features section with icons, titles, and descriptions' },
        { label: 'Testimonials', prompt: 'Create a testimonials section with 3 customer reviews' },
        { label: 'Pricing Table', prompt: 'Create a 3-tier pricing table section' },
        { label: 'CTA Banner', prompt: 'Create a call-to-action banner with heading and button' },
        { label: 'About Section', prompt: 'Create an about us section with image and text side by side' },
        { label: 'FAQ Section', prompt: 'Create an FAQ section with 5 questions and answers' },
        { label: 'Contact Info', prompt: 'Create a contact section with address, phone, email and a message' },
        { label: 'Team Section', prompt: 'Create a team members section with 4 people' },
        { label: 'Stats Counter', prompt: 'Create a statistics section with 4 number counters' },
    ];

    // ─── Wait for Elementor editor to be ready ───
    elementor.on('preview:loaded', function () {
        setTimeout(injectPanelButton, 1000);
    });

    // Also try after a delay (for cases where preview:loaded already fired)
    setTimeout(function () {
        if (document.getElementById('elementor-panel')) {
            injectPanelButton();
        }
    }, 3000);

    // ─── Inject the AI button in Elementor's header ───
    function injectPanelButton() {
        if (document.getElementById('wnb-ai-panel-toggle')) return;

        // Find Elementor's panel header buttons area
        var headerBtns = document.querySelector('#elementor-panel-header-menu-button')?.parentElement;
        if (!headerBtns) {
            // Fallback: insert in panel header
            headerBtns = document.querySelector('#elementor-panel-header');
        }
        if (!headerBtns) return;

        var btn = document.createElement('button');
        btn.id = 'wnb-ai-panel-toggle';
        btn.className = 'wnb-panel-toggle-btn';
        btn.innerHTML = '<span class="wnb-ai-sparkle">&#x2728;</span> AI Builder';
        btn.title = 'Open AI Builder Panel';
        btn.addEventListener('click', togglePanel);

        headerBtns.appendChild(btn);

        // Create the panel
        createPanel();
    }

    // ─── Create the floating AI Panel ───
    function createPanel() {
        if (document.getElementById('wnb-ai-chat-panel')) return;

        var panel = document.createElement('div');
        panel.id = 'wnb-ai-chat-panel';
        panel.className = 'wnb-chat-panel';
        panel.innerHTML = buildPanelHTML();
        document.body.appendChild(panel);

        // Bind events
        bindPanelEvents(panel);

        // Start with welcome message
        addMessage('ai', 'Assalam o Alaikum! Main aapka AI Builder hoon. Batayein kya banana hai — sections, content, ya koi bhi cheez. Neeche quick prompts bhi hain!');
    }

    function buildPanelHTML() {
        var quickBtns = QUICK_PROMPTS.map(function (q) {
            return '<button class="wnb-quick-btn" data-prompt="' + escapeAttr(q.prompt) + '">' + q.label + '</button>';
        }).join('');

        return '' +
            '<div class="wnb-panel-header">' +
                '<div class="wnb-panel-title">' +
                    '<span class="wnb-ai-sparkle">&#x2728;</span>' +
                    '<span>AI Builder</span>' +
                '</div>' +
                '<div class="wnb-panel-actions">' +
                    '<button class="wnb-panel-clear" title="Clear chat">&#x1F5D1;</button>' +
                    '<button class="wnb-panel-close" title="Close">&times;</button>' +
                '</div>' +
            '</div>' +

            '<div class="wnb-quick-prompts">' +
                '<div class="wnb-quick-label">Quick Actions</div>' +
                '<div class="wnb-quick-grid">' + quickBtns + '</div>' +
            '</div>' +

            '<div class="wnb-chat-messages" id="wnb-chat-messages">' +
                '<!-- Messages will be inserted here -->' +
            '</div>' +

            '<div class="wnb-chat-input-area">' +
                '<div class="wnb-input-row">' +
                    '<textarea id="wnb-chat-input" class="wnb-chat-input" rows="2" placeholder="Describe what you want to build..."></textarea>' +
                    '<button id="wnb-chat-send" class="wnb-chat-send" title="Send">' +
                        '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>' +
                    '</button>' +
                '</div>' +
                '<div class="wnb-input-hint">Enter to send &bull; Shift+Enter for new line</div>' +
            '</div>';
    }

    // ─── Panel Events ───
    function bindPanelEvents(panel) {
        // Close
        panel.querySelector('.wnb-panel-close').addEventListener('click', togglePanel);

        // Clear
        panel.querySelector('.wnb-panel-clear').addEventListener('click', function () {
            messages = [];
            var msgContainer = document.getElementById('wnb-chat-messages');
            msgContainer.innerHTML = '';
            addMessage('ai', 'Chat cleared! Batayein kya banana hai.');
        });

        // Quick prompts
        panel.querySelectorAll('.wnb-quick-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var prompt = this.getAttribute('data-prompt');
                document.getElementById('wnb-chat-input').value = prompt;
                sendMessage();
            });
        });

        // Send button
        document.getElementById('wnb-chat-send').addEventListener('click', sendMessage);

        // Enter to send
        document.getElementById('wnb-chat-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // ─── Toggle Panel ───
    function togglePanel() {
        panelOpen = !panelOpen;
        var panel = document.getElementById('wnb-ai-chat-panel');
        if (panel) {
            panel.classList.toggle('open', panelOpen);
        }
        var btn = document.getElementById('wnb-ai-panel-toggle');
        if (btn) {
            btn.classList.toggle('active', panelOpen);
        }
    }

    // ─── Add Message to Chat ───
    function addMessage(role, text, elementorData) {
        messages.push({ role: role, text: text });

        var msgContainer = document.getElementById('wnb-chat-messages');
        if (!msgContainer) return;

        var msgDiv = document.createElement('div');
        msgDiv.className = 'wnb-msg wnb-msg-' + role;

        var content = '<div class="wnb-msg-bubble">';
        content += '<div class="wnb-msg-text">' + formatMessage(text) + '</div>';

        // If AI returned Elementor data, show insert button
        if (elementorData && role === 'ai') {
            content += '<div class="wnb-msg-actions">' +
                '<button class="wnb-insert-btn" data-elementor=\'' + escapeAttr(JSON.stringify(elementorData)) + '\'>' +
                    '&#x2795; Insert into Page' +
                '</button>' +
                '<button class="wnb-preview-btn" data-elementor=\'' + escapeAttr(JSON.stringify(elementorData)) + '\'>' +
                    '&#x1F441; Preview JSON' +
                '</button>' +
            '</div>';
        }

        content += '</div>';
        msgDiv.innerHTML = content;
        msgContainer.appendChild(msgDiv);

        // Bind insert buttons
        var insertBtn = msgDiv.querySelector('.wnb-insert-btn');
        if (insertBtn) {
            insertBtn.addEventListener('click', function () {
                var data = JSON.parse(this.getAttribute('data-elementor'));
                insertIntoElementor(data);
                this.textContent = '\u2705 Inserted!';
                this.disabled = true;
                this.classList.add('wnb-inserted');
            });
        }

        var previewBtn = msgDiv.querySelector('.wnb-preview-btn');
        if (previewBtn) {
            previewBtn.addEventListener('click', function () {
                var data = JSON.parse(this.getAttribute('data-elementor'));
                console.log('WebnewBiz AI - Elementor Data:', data);
                alert('Elementor JSON logged to console. ' + (Array.isArray(data) ? data.length + ' element(s)' : '1 element'));
            });
        }

        // Scroll to bottom
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    // ─── Add Loading Indicator ───
    function addLoading() {
        var msgContainer = document.getElementById('wnb-chat-messages');
        if (!msgContainer) return;

        var loader = document.createElement('div');
        loader.className = 'wnb-msg wnb-msg-ai wnb-msg-loading';
        loader.id = 'wnb-loading-msg';
        loader.innerHTML = '<div class="wnb-msg-bubble"><div class="wnb-typing"><span></span><span></span><span></span></div></div>';
        msgContainer.appendChild(loader);
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    function removeLoading() {
        var loader = document.getElementById('wnb-loading-msg');
        if (loader) loader.remove();
    }

    // ─── Send Message ───
    function sendMessage() {
        if (isLoading) return;

        var input = document.getElementById('wnb-chat-input');
        var text = input.value.trim();
        if (!text) return;

        input.value = '';
        addMessage('user', text);

        isLoading = true;
        addLoading();

        // Get current page context
        var pageContent = getPageContext();

        wp.apiFetch({
            path: '/webnewbiz-builder/v1/ai/chat',
            method: 'POST',
            data: {
                message: text,
                page_context: pageContent,
                history: messages.slice(-10).map(function (m) {
                    return { role: m.role === 'user' ? 'user' : 'assistant', content: m.text };
                })
            }
        }).then(function (res) {
            removeLoading();
            isLoading = false;

            if (res.success) {
                addMessage('ai', res.message || 'Here\'s what I created:', res.elementor_data || null);

                // Auto-insert if user seems to want it
                if (res.auto_insert && res.elementor_data) {
                    insertIntoElementor(res.elementor_data);
                    addMessage('ai', '\u2705 Section automatically inserted into your page!');
                }
            } else {
                addMessage('ai', 'Sorry, there was an error: ' + (res.message || 'Unknown error'));
            }
        }).catch(function (err) {
            removeLoading();
            isLoading = false;
            addMessage('ai', 'Request failed: ' + (err.message || 'Network error. Check your API key in WebnewBiz Settings.'));
        });
    }

    // ─── Get Current Page Context ───
    function getPageContext() {
        try {
            var doc = elementor.documents.getCurrent();
            if (!doc) return '';

            var container = doc.container;
            if (!container) return '';

            // Get a summary of existing sections
            var children = container.children;
            if (!children || !children.length) return 'Empty page - no sections yet.';

            var summary = [];
            children.forEach(function (child, i) {
                var type = child.model?.get('elType') || 'unknown';
                var widgetType = child.model?.get('widgetType') || '';
                var settings = child.model?.get('settings');
                var title = '';

                if (widgetType === 'heading') {
                    title = settings?.get('title') || '';
                }

                // Check children for headings
                if (!title && child.children) {
                    child.children.forEach(function (col) {
                        if (col.children) {
                            col.children.forEach(function (widget) {
                                if (!title && widget.model?.get('widgetType') === 'heading') {
                                    title = widget.model?.get('settings')?.get('title') || '';
                                }
                            });
                        }
                    });
                }

                summary.push('Section ' + (i + 1) + ': ' + type + (widgetType ? '/' + widgetType : '') + (title ? ' - "' + title + '"' : ''));
            });

            return 'Current page has ' + children.length + ' sections:\n' + summary.join('\n');
        } catch (e) {
            return '';
        }
    }

    // ─── Insert Elementor Data into Page ───
    function insertIntoElementor(data) {
        try {
            if (!data) return;

            var elements = Array.isArray(data) ? data : [data];

            elements.forEach(function (elementData) {
                // Ensure IDs are regenerated
                regenerateIds(elementData);

                // Use Elementor's API to create element
                try {
                    var doc = elementor.documents.getCurrent();
                    var docContainer = doc.container;

                    $e.run('document/elements/create', {
                        container: docContainer,
                        model: elementData,
                        options: {
                            at: docContainer.children.length, // Insert at end
                            edit: false
                        }
                    });
                } catch (apiErr) {
                    console.warn('WebnewBiz: $e.run failed, trying fallback', apiErr);
                    // Fallback: try via Elementor internal
                    try {
                        elementor.getPreviewView().addChildModel(elementData);
                    } catch (fallbackErr) {
                        console.error('WebnewBiz: Both insert methods failed', fallbackErr);
                        alert('Could not insert element. Please try refreshing the Elementor editor.');
                    }
                }
            });

        } catch (err) {
            console.error('WebnewBiz: Insert error', err);
            alert('Error inserting element: ' + err.message);
        }
    }

    // ─── Regenerate Element IDs ───
    function regenerateIds(element) {
        element.id = randomId();
        if (element.elements && element.elements.length) {
            element.elements.forEach(function (child) {
                regenerateIds(child);
            });
        }
    }

    function randomId() {
        return Math.random().toString(16).substring(2, 9);
    }

    // ─── Helpers ───
    function formatMessage(text) {
        // Basic markdown-like formatting
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
    }

    function escapeAttr(str) {
        return str.replace(/&/g, '&amp;').replace(/'/g, '&#39;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

})();
