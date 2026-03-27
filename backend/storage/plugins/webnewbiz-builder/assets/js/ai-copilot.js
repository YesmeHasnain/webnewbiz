/**
 * WebNewBiz AI Copilot — Premium WordPress AI Assistant
 * Communicates with Laravel platform API for AI processing + action execution.
 */
(function() {
    'use strict';

    // Config from wp_localize_script
    const config = window.wnbCopilot || {};
    const PLATFORM_URL = config.platformUrl || 'http://localhost:8000';
    const PLATFORM_TOKEN = config.platformToken || '';
    const WEBSITE_ID = config.websiteId || 0;
    const SITE_NAME = config.siteName || 'Website';
    const CURRENT_PAGE_ID = config.currentPageId || 0;
    const IS_ELEMENTOR = config.isElementor === '1';

    if (!WEBSITE_ID || !PLATFORM_TOKEN) return;

    // ─── State ───
    let isOpen = false;
    let isLoading = false;
    let messages = [];
    let sessionId = null;
    let actionHistory = [];

    // ─── Create Widget ───
    function createWidget() {
        // Floating Action Button
        const fab = document.createElement('div');
        fab.id = 'wnb-copilot-fab';
        fab.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
            </svg>
        `;
        document.body.appendChild(fab);

        // Chat Panel
        const panel = document.createElement('div');
        panel.id = 'wnb-copilot-panel';
        panel.innerHTML = `
            <div class="wnb-cp-header">
                <div class="wnb-cp-header-left">
                    <div class="wnb-cp-logo">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/></svg>
                    </div>
                    <div>
                        <div class="wnb-cp-title">AI Copilot</div>
                        <div class="wnb-cp-subtitle">${SITE_NAME}</div>
                    </div>
                </div>
                <div class="wnb-cp-header-actions">
                    <button class="wnb-cp-btn-icon" id="wnb-cp-clear" title="Clear chat">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z"/></svg>
                    </button>
                    <button class="wnb-cp-btn-icon" id="wnb-cp-close" title="Close">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>
            <div class="wnb-cp-messages" id="wnb-cp-messages">
                <div class="wnb-cp-welcome">
                    <div class="wnb-cp-welcome-icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#7c5cfc"/></svg>
                    </div>
                    <h3>Hi! I'm your AI Copilot</h3>
                    <p>I can edit your website content, change styles, add sections, manage products, and more. Just tell me what you need!</p>
                    <div class="wnb-cp-suggestions" id="wnb-cp-suggestions"></div>
                </div>
            </div>
            <div class="wnb-cp-input-area">
                <div class="wnb-cp-context-bar" id="wnb-cp-context">
                    ${CURRENT_PAGE_ID ? `<span class="wnb-cp-context-badge">Page #${CURRENT_PAGE_ID}</span>` : ''}
                    ${IS_ELEMENTOR ? '<span class="wnb-cp-context-badge wnb-cp-context-elementor">Elementor Editor</span>' : ''}
                </div>
                <div class="wnb-cp-input-row">
                    <textarea id="wnb-cp-input" placeholder="Ask me to make changes..." rows="1"></textarea>
                    <button id="wnb-cp-send" class="wnb-cp-send-btn" disabled>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(panel);

        // Bind events
        fab.addEventListener('click', togglePanel);
        document.getElementById('wnb-cp-close').addEventListener('click', togglePanel);
        document.getElementById('wnb-cp-clear').addEventListener('click', clearChat);

        const input = document.getElementById('wnb-cp-input');
        const sendBtn = document.getElementById('wnb-cp-send');

        input.addEventListener('input', function() {
            sendBtn.disabled = !this.value.trim();
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) sendMessage();
            }
        });

        sendBtn.addEventListener('click', sendMessage);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) togglePanel();
        });

        // Load suggestions
        loadSuggestions();
    }

    // ─── Toggle Panel ───
    function togglePanel() {
        isOpen = !isOpen;
        const panel = document.getElementById('wnb-copilot-panel');
        const fab = document.getElementById('wnb-copilot-fab');

        if (isOpen) {
            panel.classList.add('wnb-cp-open');
            fab.classList.add('wnb-cp-fab-active');
            document.getElementById('wnb-cp-input').focus();
        } else {
            panel.classList.remove('wnb-cp-open');
            fab.classList.remove('wnb-cp-fab-active');
        }
    }

    // ─── Send Message ───
    async function sendMessage(promptText) {
        const input = document.getElementById('wnb-cp-input');
        const text = promptText || input.value.trim();
        if (!text || isLoading) return;

        input.value = '';
        input.style.height = 'auto';
        document.getElementById('wnb-cp-send').disabled = true;

        // Hide welcome
        const welcome = document.querySelector('.wnb-cp-welcome');
        if (welcome) welcome.style.display = 'none';

        // Add user message
        addMessage('user', text);
        messages.push({ role: 'user', content: text });

        // Show loading
        isLoading = true;
        const loadingEl = addMessage('assistant', '', true);

        // Detect current page ID dynamically
        let pageId = CURRENT_PAGE_ID;
        if (IS_ELEMENTOR && window.elementor && window.elementor.config) {
            pageId = window.elementor.config.document.id || pageId;
        }

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 120000); // 2 min timeout

            const response = await fetch(`${PLATFORM_URL}/api/websites/${WEBSITE_ID}/copilot/chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${PLATFORM_TOKEN}`,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: text,
                    history: messages.slice(-20),
                    page_id: pageId || null,
                    session_id: sessionId,
                }),
                signal: controller.signal,
            });

            clearTimeout(timeoutId);

            const data = await response.json();

            // Remove loading
            loadingEl.remove();
            isLoading = false;

            if (data.success) {
                sessionId = data.session_id;

                // Add AI response
                addMessage('assistant', data.reply);
                messages.push({ role: 'assistant', content: data.reply });

                // Separate read vs write actions
                if (data.actions && data.actions.length > 0) {
                    const READ_TOOLS = ['get_page_editables', 'get_global_colors', 'get_page_seo', 'list_products', 'get_menus'];
                    const writeActions = data.actions.filter(a => !READ_TOOLS.includes(a.tool));

                    if (writeActions.length > 0) {
                        addActionCards(writeActions);
                        // Auto-reload after 2s so user sees changes
                        setTimeout(() => {
                            if (IS_ELEMENTOR) {
                                // In Elementor: reload the editor
                                location.reload();
                            } else {
                                // In WP admin: show subtle notification, no forced reload
                                showAutoRefreshNotice();
                            }
                        }, 2500);
                    }
                }
            } else {
                addMessage('assistant', data.reply || 'Sorry, something went wrong. Please try again.');
            }
        } catch (err) {
            loadingEl.remove();
            isLoading = false;
            addMessage('assistant', 'Connection error. Please check your internet and try again.');
            console.error('Copilot error:', err);
        }
    }

    // ─── Add Message to UI ───
    function addMessage(role, text, isLoading = false) {
        const container = document.getElementById('wnb-cp-messages');
        const msgEl = document.createElement('div');
        msgEl.className = `wnb-cp-msg wnb-cp-msg-${role}`;

        if (isLoading) {
            msgEl.innerHTML = `
                <div class="wnb-cp-msg-bubble wnb-cp-msg-loading">
                    <div class="wnb-cp-typing">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;
        } else {
            const rendered = renderMarkdown(text);
            msgEl.innerHTML = `<div class="wnb-cp-msg-bubble">${rendered}</div>`;
        }

        container.appendChild(msgEl);
        container.scrollTop = container.scrollHeight;
        return msgEl;
    }

    // ─── Action Cards ───
    function addActionCards(actions) {
        const container = document.getElementById('wnb-cp-messages');
        const cardGroup = document.createElement('div');
        cardGroup.className = 'wnb-cp-action-group';

        let html = '<div class="wnb-cp-action-header"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Changes Applied</div>';

        actions.forEach((action, idx) => {
            const icon = getActionIcon(action.tool);
            const label = getActionLabel(action);
            const success = action.result && action.result.success;

            html += `
                <div class="wnb-cp-action-card ${success ? '' : 'wnb-cp-action-failed'}">
                    <div class="wnb-cp-action-icon">${icon}</div>
                    <div class="wnb-cp-action-text">${label}</div>
                    ${success ? `<button class="wnb-cp-undo-btn" data-action-id="${action.result.action_id || ''}" title="Undo">↩</button>` : '<span class="wnb-cp-action-fail-badge">Failed</span>'}
                </div>
            `;
        });

        cardGroup.innerHTML = html;
        container.appendChild(cardGroup);
        container.scrollTop = container.scrollHeight;

        // Bind undo buttons
        cardGroup.querySelectorAll('.wnb-cp-undo-btn').forEach(btn => {
            btn.addEventListener('click', () => undoAction(btn.dataset.actionId));
        });
    }

    // ─── Auto-Refresh Notice ───
    function showAutoRefreshNotice() {
        const container = document.getElementById('wnb-cp-messages');
        const noticeEl = document.createElement('div');
        noticeEl.className = 'wnb-cp-refresh-prompt';
        noticeEl.innerHTML = `
            <div class="wnb-cp-refresh-notice wnb-cp-auto-refresh">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Changes saved! View your site to see them live.
            </div>
        `;
        container.appendChild(noticeEl);
        container.scrollTop = container.scrollHeight;
    }

    // ─── Undo Action ───
    async function undoAction(actionId) {
        if (!actionId) return;

        try {
            const response = await fetch(`${PLATFORM_URL}/api/websites/${WEBSITE_ID}/copilot/undo/${actionId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${PLATFORM_TOKEN}`,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                addMessage('assistant', '↩ Change undone successfully. Refresh the page to see the result.');
            } else {
                addMessage('assistant', 'Could not undo this change: ' + (data.error || 'Unknown error'));
            }
        } catch (err) {
            addMessage('assistant', 'Failed to undo. Please try again.');
        }
    }

    // ─── Load Suggestions ───
    async function loadSuggestions() {
        try {
            const response = await fetch(`${PLATFORM_URL}/api/websites/${WEBSITE_ID}/copilot/suggestions?page_id=${CURRENT_PAGE_ID}`, {
                headers: {
                    'Authorization': `Bearer ${PLATFORM_TOKEN}`,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success && data.suggestions) {
                const container = document.getElementById('wnb-cp-suggestions');
                container.innerHTML = data.suggestions.map(s => `
                    <button class="wnb-cp-suggestion" data-prompt="${escapeAttr(s.prompt)}">
                        <span class="wnb-cp-suggestion-icon">${getSuggestionIcon(s.icon)}</span>
                        ${s.text}
                    </button>
                `).join('');

                container.querySelectorAll('.wnb-cp-suggestion').forEach(btn => {
                    btn.addEventListener('click', () => sendMessage(btn.dataset.prompt));
                });
            }
        } catch (err) {
            // Suggestions are optional
        }
    }

    // ─── Clear Chat ───
    function clearChat() {
        messages = [];
        sessionId = null;
        const container = document.getElementById('wnb-cp-messages');
        container.innerHTML = `
            <div class="wnb-cp-welcome">
                <div class="wnb-cp-welcome-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#7c5cfc"/></svg>
                </div>
                <h3>Hi! I'm your AI Copilot</h3>
                <p>I can edit your website content, change styles, add sections, manage products, and more. Just tell me what you need!</p>
                <div class="wnb-cp-suggestions" id="wnb-cp-suggestions"></div>
            </div>
        `;
        loadSuggestions();
    }

    // ─── Helpers ───

    function renderMarkdown(text) {
        if (!text) return '';
        return text
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
            .replace(/^### (.*?)$/gm, '<h4>$1</h4>')
            .replace(/^## (.*?)$/gm, '<h3>$1</h3>')
            .replace(/^# (.*?)$/gm, '<h2>$1</h2>')
            .replace(/^- (.*?)$/gm, '<li>$1</li>')
            .replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>')
            .replace(/^/, '<p>').replace(/$/, '</p>');
    }

    function escapeAttr(str) {
        return str.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function getActionIcon(tool) {
        const icons = {
            'edit_element_text': '✏️',
            'edit_element_style': '🎨',
            'edit_element_image': '🖼️',
            'add_section': '➕',
            'remove_section': '🗑️',
            'reorder_sections': '↕️',
            'create_page': '📄',
            'update_page_title': '📝',
            'delete_page': '🗑️',
            'set_global_colors': '🎨',
            'set_global_fonts': '🔤',
            'update_page_seo': '🔍',
            'create_product': '🛍️',
            'update_product': '🛍️',
            'install_plugin': '🔌',
            'upload_image': '🖼️',
            'update_menu': '📋',
            'update_site_settings': '⚙️',
        };
        return icons[tool] || '⚡';
    }

    function getActionLabel(action) {
        const tool = action.tool;
        const input = action.input || {};
        const result = action.result || {};

        switch (tool) {
            case 'edit_element_text':
                return `Updated text "${(input.field || '').substring(0, 20)}" on element`;
            case 'edit_element_style':
                return `Changed ${input.property || 'style'} to ${input.value || ''}`;
            case 'edit_element_image':
                return 'Updated image';
            case 'add_section':
                return `Added ${input.section_type || 'new'} section`;
            case 'remove_section':
                return 'Removed section';
            case 'create_page':
                return `Created page "${input.title || ''}"`;
            case 'update_page_seo':
                return `Updated SEO meta for page`;
            case 'create_product':
                return `Created product "${input.name || ''}"`;
            case 'set_global_colors':
                return 'Updated global brand colors';
            case 'set_global_fonts':
                return 'Updated global fonts';
            case 'install_plugin':
                return `Installed plugin "${input.slug || ''}"`;
            case 'upload_image':
                return 'Uploaded image to media library';
            case 'update_menu':
                return 'Updated navigation menu';
            default:
                return `Executed: ${tool}`;
        }
    }

    function getSuggestionIcon(icon) {
        const svgIcons = {
            'edit': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
            'palette': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="13.5" cy="6.5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="12" r="2.5"/><circle cx="13.5" cy="17.5" r="2.5"/></svg>',
            'search': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
            'layout': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>',
            'type': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>',
            'image': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
            'eye': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
            'shopping-bag': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>',
            'tag': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
        };
        return svgIcons[icon] || svgIcons['edit'];
    }

    // ─── Init ───
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createWidget);
    } else {
        createWidget();
    }

})();
