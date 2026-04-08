<?php
/**
 * Admin UI - Chat panel in WordPress admin
 */
class AICopilot_Admin
{
    public static function init()
    {
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAssets']);
        add_action('elementor/editor/after_enqueue_scripts', [self::class, 'enqueueElementorAssets']);
    }

    public static function addMenu()
    {
        add_menu_page(
            'AI Copilot',
            'AI Copilot',
            'edit_pages',
            'ai-copilot',
            [self::class, 'renderPage'],
            'dashicons-format-chat',
            3
        );
    }

    public static function enqueueAssets($hook)
    {
        if ($hook !== 'toplevel_page_ai-copilot') return;

        wp_enqueue_style('ai-copilot-css', AICOPILOT_URL . 'assets/css/copilot.css', [], AICOPILOT_VERSION);
        wp_enqueue_script('ai-copilot-js', AICOPILOT_URL . 'assets/js/copilot.js', ['jquery'], AICOPILOT_VERSION, true);
        wp_localize_script('ai-copilot-js', 'aiCopilot', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ai_copilot_nonce'),
            'siteUrl' => home_url(),
            'bridgeUrl' => AICOPILOT_URL . 'assets/js/preview-bridge.js',
        ]);
    }

    /**
     * Enqueue AI Copilot scripts inside Elementor editor
     */
    public static function enqueueElementorAssets()
    {
        wp_enqueue_script(
            'ai-copilot-elementor',
            AICOPILOT_URL . 'assets/js/elementor-panel.js',
            ['jquery', 'elementor-editor'],
            AICOPILOT_VERSION,
            true
        );
        wp_localize_script('ai-copilot-elementor', 'aicElementor', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ai_copilot_nonce'),
        ]);
    }

    public static function renderPage()
    {
        $cliAvailable = AICopilot_Settings::isCliAvailable();
        ?>
        <div id="ai-copilot-app">
            <?php if (!$cliAvailable): ?>
                <div class="aic-no-key">
                    <div class="aic-no-key-icon">⚡</div>
                    <h2>Claude CLI Required</h2>
                    <p>Install Claude Code CLI to start using AI Copilot.</p>
                    <code style="display:block;margin:16px 0;padding:12px;background:#f5f5f5;border-radius:8px;">npm install -g @anthropic-ai/claude-code</code>
                    <a href="<?php echo admin_url('options-general.php?page=ai-copilot-settings'); ?>" class="button button-primary button-hero">Check Status</a>
                </div>
            <?php else: ?>
                <div class="aic-layout">
                    <!-- Left: Chat Panel -->
                    <div class="aic-chat-panel">
                        <div class="aic-chat-header">
                            <div class="aic-logo">
                                <svg width="20" height="20" viewBox="0 0 24 24"><path d="M12 2L22 12L12 22L2 12Z" fill="#6366f1"/></svg>
                                <span>AI Copilot</span>
                            </div>
                            <button class="aic-collapse-btn" onclick="toggleChatPanel()" title="Collapse chat panel">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
                            </button>
                            <div class="aic-header-actions">
                                <button id="aic-undo-btn" class="aic-undo-btn" onclick="aiUndo()" title="Undo last change" style="display:none;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                    Undo
                                </button>
                                <div class="aic-page-dropdown" id="aic-page-dropdown">
                                    <button class="aic-page-toggle" id="aic-page-toggle" onclick="togglePageDropdown()">
                                        <span id="aic-page-label">Select page</span>
                                        <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    </button>
                                    <div class="aic-page-menu" id="aic-page-menu"></div>
                                </div>
                                <select id="aic-page-select" style="display:none;">
                                    <option value="0">Select a page...</option>
                                </select>
                            </div>
                        </div>

                        <div class="aic-messages" id="aic-messages">
                            <div class="aic-welcome">
                                <div class="aic-welcome-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.5" stroke-linecap="round">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                    </svg>
                                </div>
                                <h3>What should we change?</h3>
                                <p>Edit text, swap images, update colors, add sections — just describe what you want.</p>
                                <div class="aic-suggestions">
                                    <button onclick="aiSend('Change the hero heading text')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit text
                                    </button>
                                    <button onclick="aiSend('Update the color scheme')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>
                                        Change colors
                                    </button>
                                    <button onclick="aiSend('Add a testimonials section')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                        Add section
                                    </button>
                                    <button onclick="aiSend('Find an image for the hero')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        Find images
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Options Bar: Tone, Language, SEO Keywords -->
                        <div class="aic-options-bar" id="aic-options-bar" style="display:none;">
                            <select id="aic-tone" title="Content Tone">
                                <option value="">Tone</option>
                                <option value="professional">Professional</option>
                                <option value="casual">Casual</option>
                                <option value="friendly">Friendly</option>
                                <option value="formal">Formal</option>
                                <option value="persuasive">Persuasive</option>
                                <option value="witty">Witty</option>
                            </select>
                            <select id="aic-language" title="Content Language">
                                <option value="">Language</option>
                                <option value="English">English</option>
                                <option value="Urdu">Urdu</option>
                                <option value="Hindi">Hindi</option>
                                <option value="Arabic">Arabic</option>
                                <option value="Spanish">Spanish</option>
                                <option value="French">French</option>
                                <option value="German">German</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Japanese">Japanese</option>
                            </select>
                            <input type="text" id="aic-seo-keywords" placeholder="SEO keywords (comma separated)" title="SEO Keywords for content generation">
                        </div>

                        <div class="aic-input-area">
                            <button class="aic-options-toggle" onclick="toggleOptions()" title="Options">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            </button>
                            <div class="aic-input-wrap">
                                <div class="aic-input-inner">
                                    <textarea id="aic-input" placeholder="Ask AI to edit your website..." rows="1"></textarea>
                                    <button id="aic-send" onclick="aiSendMessage()">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Preview -->
                    <div class="aic-preview-panel">
                        <div class="aic-preview-header">
                            <button onclick="refreshPreview()" title="Refresh">↻</button>
                            <button class="aic-add-section-btn" onclick="openSectionPicker()" title="Add a new section">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Section
                            </button>
                            <div class="aic-style-presets-wrap">
                                <button class="aic-style-presets-btn" onclick="toggleStylePresets()" title="Apply a style preset">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="21.17" y1="8" x2="12" y2="8"/><line x1="3.95" y1="6.06" x2="8.54" y2="14"/><line x1="10.88" y1="21.94" x2="15.46" y2="14"/></svg>
                                    Styles
                                </button>
                                <div id="aic-style-presets-dropdown" class="aic-style-dropdown" style="display:none;">
                                    <div class="aic-style-dropdown-title">Quick Style Presets</div>
                                    <div class="aic-preset" onclick="applyStylePreset('modern-dark','#0f172a','#6366f1','#f8fafc','Inter')">
                                        <div class="aic-preset-colors"><span style="background:#0f172a"></span><span style="background:#6366f1"></span><span style="background:#f8fafc"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Modern Dark</div><div class="aic-preset-font">Inter</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('ocean-blue','#0c4a6e','#0ea5e9','#f0f9ff','Poppins')">
                                        <div class="aic-preset-colors"><span style="background:#0c4a6e"></span><span style="background:#0ea5e9"></span><span style="background:#f0f9ff"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Ocean Blue</div><div class="aic-preset-font">Poppins</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('forest-green','#14532d','#22c55e','#f0fdf4','Montserrat')">
                                        <div class="aic-preset-colors"><span style="background:#14532d"></span><span style="background:#22c55e"></span><span style="background:#f0fdf4"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Forest Green</div><div class="aic-preset-font">Montserrat</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('warm-sunset','#7c2d12','#f97316','#fff7ed','Raleway')">
                                        <div class="aic-preset-colors"><span style="background:#7c2d12"></span><span style="background:#f97316"></span><span style="background:#fff7ed"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Warm Sunset</div><div class="aic-preset-font">Raleway</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('elegant-gold','#1c1917','#d4a574','#fafaf9','Playfair Display')">
                                        <div class="aic-preset-colors"><span style="background:#1c1917"></span><span style="background:#d4a574"></span><span style="background:#fafaf9"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Elegant Gold</div><div class="aic-preset-font">Playfair Display</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('minimal-clean','#ffffff','#111827','#f9fafb','DM Sans')">
                                        <div class="aic-preset-colors"><span style="background:#ffffff;border:1px solid #ddd"></span><span style="background:#111827"></span><span style="background:#f9fafb;border:1px solid #ddd"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Minimal Clean</div><div class="aic-preset-font">DM Sans</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('royal-purple','#2e1065','#a855f7','#faf5ff','Outfit')">
                                        <div class="aic-preset-colors"><span style="background:#2e1065"></span><span style="background:#a855f7"></span><span style="background:#faf5ff"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Royal Purple</div><div class="aic-preset-font">Outfit</div></div>
                                    </div>
                                    <div class="aic-preset" onclick="applyStylePreset('rose-blush','#881337','#e11d48','#fff1f2','Nunito')">
                                        <div class="aic-preset-colors"><span style="background:#881337"></span><span style="background:#e11d48"></span><span style="background:#fff1f2"></span></div>
                                        <div class="aic-preset-info"><div class="aic-preset-name">Rose Blush</div><div class="aic-preset-font">Nunito</div></div>
                                    </div>
                                </div>
                            </div>
                            <button class="aic-code-btn" onclick="toggleCodeEditor()" title="View page code">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                                Code
                            </button>
                            <span id="aic-preview-url"><?php echo home_url(); ?></span>
                            <a href="<?php echo home_url(); ?>" target="_blank" title="Open in new tab">↗</a>
                        </div>
                        <iframe id="aic-preview" src="<?php echo home_url(); ?>"></iframe>

                        <!-- Code Editor Panel (slides over preview) -->
                        <div id="aic-code-panel" class="aic-code-panel" style="display:none;">
                            <div class="aic-code-header">
                                <span class="aic-code-title">Elementor JSON</span>
                                <div class="aic-code-actions">
                                    <button onclick="copyCode()" title="Copy to clipboard">Copy</button>
                                    <button onclick="closeCodeEditor()">Close</button>
                                </div>
                            </div>
                            <pre id="aic-code-content" class="aic-code-content"></pre>
                        </div>

                        <!-- Section Picker Modal -->
                        <div id="aic-section-modal" class="aic-modal-overlay" style="display:none;">
                            <div class="aic-modal">
                                <div class="aic-modal-header">
                                    <h3>Add Section</h3>
                                    <button class="aic-modal-close" onclick="closeSectionPicker()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                </div>
                                <p class="aic-modal-subtitle">Choose a section type, then describe the content.</p>
                                <div class="aic-section-grid">
                                    <div class="aic-section-card" onclick="pickSection('hero')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></div>
                                        <div class="aic-section-card-title">Hero</div>
                                        <div class="aic-section-card-desc">Big headline + CTA button</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('features')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></div>
                                        <div class="aic-section-card-title">Features</div>
                                        <div class="aic-section-card-desc">Highlight key services</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('testimonials')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
                                        <div class="aic-section-card-title">Testimonials</div>
                                        <div class="aic-section-card-desc">Customer reviews</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('pricing')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                                        <div class="aic-section-card-title">Pricing</div>
                                        <div class="aic-section-card-desc">Price tables / menu</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('cta')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
                                        <div class="aic-section-card-title">Call to Action</div>
                                        <div class="aic-section-card-desc">Drive visitor action</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('faq')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                                        <div class="aic-section-card-title">FAQ</div>
                                        <div class="aic-section-card-desc">Common questions</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('team')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                                        <div class="aic-section-card-title">Team</div>
                                        <div class="aic-section-card-desc">Members & profiles</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('contact')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                                        <div class="aic-section-card-title">Contact</div>
                                        <div class="aic-section-card-desc">Info & details</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('gallery')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
                                        <div class="aic-section-card-title">Gallery</div>
                                        <div class="aic-section-card-desc">Image portfolio</div>
                                    </div>
                                    <div class="aic-section-card" onclick="pickSection('custom')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
                                        <div class="aic-section-card-title">Custom</div>
                                        <div class="aic-section-card-desc">Describe anything</div>
                                    </div>
                                    <div class="aic-section-card aic-section-card-full" onclick="pickSection('fullpage')">
                                        <div class="aic-section-card-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
                                        <div class="aic-section-card-title">Full Page</div>
                                        <div class="aic-section-card-desc">Generate complete page with 5-7 sections</div>
                                    </div>
                                </div>
                                <!-- Step 2: Content prompt (shown after picking type) -->
                                <div id="aic-section-step2" style="display:none;">
                                    <div class="aic-section-chosen">
                                        <span id="aic-section-chosen-icon"></span>
                                        <span id="aic-section-chosen-name"></span>
                                        <button onclick="backToSectionGrid()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#6366f1;font-size:12px;">Change</button>
                                    </div>
                                    <textarea id="aic-section-prompt" class="aic-section-prompt" placeholder="Describe the content you want... (e.g. '3 coffee drinks with prices' or 'team members: John CEO, Sarah CTO')" rows="3"></textarea>
                                    <div class="aic-modal-actions">
                                        <button class="aic-modal-btn-secondary" onclick="closeSectionPicker()">Cancel</button>
                                        <button class="aic-modal-btn-primary" onclick="addSectionFromPicker()">Add Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
