(function () {
    'use strict';

    if (typeof elementor === 'undefined') return;

    var ACTIONS = [
        { key: 'rewrite',     label: 'Rewrite',      icon: '&#x1f504;' },
        { key: 'simplify',    label: 'Simplify',      icon: '&#x2728;' },
        { key: 'expand',      label: 'Make Longer',   icon: '&#x1f4c4;' },
        { key: 'shorten',     label: 'Make Shorter',  icon: '&#x2702;' },
        { key: 'fix_grammar', label: 'Fix Grammar',   icon: '&#x2705;' },
        { key: 'change_tone', label: 'Change Tone',   icon: '&#x1f3ad;' },
        { key: 'translate',   label: 'Translate',     icon: '&#x1f310;' }
    ];

    var TONES = ['Professional', 'Friendly', 'Casual', 'Formal', 'Persuasive', 'Humorous'];
    var LANGUAGES = ['English', 'Spanish', 'French', 'German', 'Arabic', 'Urdu', 'Chinese', 'Japanese', 'Hindi', 'Portuguese'];

    // Inject AI buttons when widget panel opens
    elementor.hooks.addAction('panel/open_editor/widget', function (panel, model, view) {
        setTimeout(function () { injectAIButtons(panel, model); }, 300);
    });

    function injectAIButtons(panel, model) {
        var panelEl = panel.$el || panel.el;
        if (!panelEl) return;

        var $panel = jQuery(panelEl);
        // Find text/textarea controls
        var $controls = $panel.find('.elementor-control-type-text .elementor-control-input-wrapper, .elementor-control-type-textarea .elementor-control-input-wrapper, .elementor-control-type-wysiwyg .elementor-control-input-wrapper');

        $controls.each(function () {
            var $wrapper = jQuery(this);
            if ($wrapper.find('.wnb-ai-btn').length) return; // already injected

            var $input = $wrapper.find('input[type="text"], textarea').first();
            if (!$input.length) return;

            var controlName = $wrapper.closest('.elementor-control').data('setting') ||
                              $input.data('setting') ||
                              $wrapper.closest('.elementor-control').attr('class').match(/elementor-control-(\w+)/)?.[1];

            var $btn = jQuery('<button type="button" class="wnb-ai-btn" title="AI Assist">AI</button>');
            $btn.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var currentText = $input.val() || '';
                openAIModal(currentText, controlName, model, $input);
            });

            $wrapper.css('position', 'relative');
            $wrapper.append($btn);
        });
    }

    function openAIModal(currentText, controlName, model, $input) {
        // Remove existing modal
        jQuery('.wnb-ai-modal-overlay').remove();

        var html = '<div class="wnb-ai-modal-overlay">' +
            '<div class="wnb-ai-modal">' +
                '<div class="wnb-ai-modal-header">' +
                    '<h3>AI Content Assistant</h3>' +
                    '<button type="button" class="wnb-ai-modal-close">&times;</button>' +
                '</div>' +
                '<div class="wnb-ai-modal-tabs">' +
                    '<button type="button" class="wnb-ai-tab active" data-tab="actions">Quick Actions</button>' +
                    '<button type="button" class="wnb-ai-tab" data-tab="write">Write with AI</button>' +
                '</div>' +
                '<div class="wnb-ai-modal-body">' +
                    // Quick Actions tab
                    '<div class="wnb-ai-tab-content active" data-tab="actions">' +
                        '<div class="wnb-ai-current-text">' +
                            '<label>Current Text:</label>' +
                            '<div class="wnb-ai-text-preview">' + escapeHtml(currentText || '(empty)') + '</div>' +
                        '</div>' +
                        '<div class="wnb-ai-actions-grid">' +
                            buildActionButtons() +
                        '</div>' +
                        '<div class="wnb-ai-extra-options" style="display:none;">' +
                            '<div class="wnb-ai-tone-select" style="display:none;">' +
                                '<label>Tone:</label>' +
                                '<select class="wnb-ai-tone">' + buildOptions(TONES) + '</select>' +
                            '</div>' +
                            '<div class="wnb-ai-language-select" style="display:none;">' +
                                '<label>Language:</label>' +
                                '<select class="wnb-ai-language">' + buildOptions(LANGUAGES) + '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    // Write tab
                    '<div class="wnb-ai-tab-content" data-tab="write">' +
                        '<textarea class="wnb-ai-prompt" rows="4" placeholder="Describe what you want to write... e.g. \'Write a compelling hero headline for a tech startup\'"></textarea>' +
                        '<button type="button" class="button button-primary wnb-ai-generate-btn">Generate</button>' +
                    '</div>' +
                    // Result area
                    '<div class="wnb-ai-result" style="display:none;">' +
                        '<label>Result:</label>' +
                        '<div class="wnb-ai-result-text"></div>' +
                        '<div class="wnb-ai-result-actions">' +
                            '<button type="button" class="button button-primary wnb-ai-apply">Apply</button>' +
                            '<button type="button" class="button wnb-ai-regenerate">Regenerate</button>' +
                        '</div>' +
                    '</div>' +
                    // Loading
                    '<div class="wnb-ai-loading" style="display:none;">' +
                        '<div class="wnb-ai-spinner"></div>' +
                        '<p>Generating with AI...</p>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        var $modal = jQuery(html).appendTo('body');
        var lastAction = null;
        var lastExtra = {};

        // Close modal
        $modal.find('.wnb-ai-modal-close').on('click', function () { $modal.remove(); });
        $modal.on('click', function (e) { if (e.target === $modal[0]) $modal.remove(); });

        // Tab switching
        $modal.find('.wnb-ai-tab').on('click', function () {
            var tab = jQuery(this).data('tab');
            $modal.find('.wnb-ai-tab').removeClass('active');
            jQuery(this).addClass('active');
            $modal.find('.wnb-ai-tab-content').removeClass('active');
            $modal.find('.wnb-ai-tab-content[data-tab="' + tab + '"]').addClass('active');
        });

        // Action buttons
        $modal.find('.wnb-ai-action-btn').on('click', function () {
            var action = jQuery(this).data('action');
            lastAction = action;
            lastExtra = {};

            // Show/hide extra options
            if (action === 'change_tone') {
                $modal.find('.wnb-ai-extra-options, .wnb-ai-tone-select').show();
                $modal.find('.wnb-ai-language-select').hide();
                return; // Wait for tone selection — we'll trigger via the select
            } else if (action === 'translate') {
                $modal.find('.wnb-ai-extra-options, .wnb-ai-language-select').show();
                $modal.find('.wnb-ai-tone-select').hide();
                return;
            }

            $modal.find('.wnb-ai-extra-options').hide();
            doTextAction($modal, currentText, action, {});
        });

        // Tone/Language select change triggers the action
        $modal.find('.wnb-ai-tone').on('change', function () {
            lastExtra = { tone: jQuery(this).val() };
            doTextAction($modal, currentText, 'change_tone', lastExtra);
        });

        $modal.find('.wnb-ai-language').on('change', function () {
            lastExtra = { language: jQuery(this).val() };
            doTextAction($modal, currentText, 'translate', lastExtra);
        });

        // Free-form generate
        $modal.find('.wnb-ai-generate-btn').on('click', function () {
            var prompt = $modal.find('.wnb-ai-prompt').val();
            if (!prompt) return;
            lastAction = 'generate';
            doFreeGenerate($modal, prompt);
        });

        // Apply result
        $modal.find('.wnb-ai-apply').on('click', function () {
            var result = $modal.find('.wnb-ai-result-text').text();
            $input.val(result).trigger('input').trigger('change');
            // Also try setting via Elementor model
            if (model && controlName) {
                model.setSetting(controlName, result);
            }
            $modal.remove();
        });

        // Regenerate
        $modal.find('.wnb-ai-regenerate').on('click', function () {
            if (lastAction === 'generate') {
                doFreeGenerate($modal, $modal.find('.wnb-ai-prompt').val());
            } else if (lastAction) {
                doTextAction($modal, currentText, lastAction, lastExtra);
            }
        });
    }

    function doTextAction($modal, text, action, extra) {
        if (!text) {
            alert('No text to process. Please enter some text first.');
            return;
        }

        $modal.find('.wnb-ai-loading').show();
        $modal.find('.wnb-ai-result').hide();

        var data = {
            text: text,
            action: action,
            widget_type: 'text'
        };
        if (extra.tone) data.tone = extra.tone;
        if (extra.language) data.language = extra.language;

        wp.apiFetch({
            path: '/webnewbiz-builder/v1/ai/text-action',
            method: 'POST',
            data: data
        }).then(function (res) {
            $modal.find('.wnb-ai-loading').hide();
            if (res.success && res.data) {
                $modal.find('.wnb-ai-result-text').text(res.data);
                $modal.find('.wnb-ai-result').show();
            } else {
                alert('AI generation failed: ' + (res.message || 'Unknown error'));
            }
        }).catch(function (err) {
            $modal.find('.wnb-ai-loading').hide();
            alert('AI request failed: ' + (err.message || 'Network error'));
        });
    }

    function doFreeGenerate($modal, prompt) {
        $modal.find('.wnb-ai-loading').show();
        $modal.find('.wnb-ai-result').hide();

        wp.apiFetch({
            path: '/webnewbiz-builder/v1/ai/generate-text',
            method: 'POST',
            data: { prompt: prompt }
        }).then(function (res) {
            $modal.find('.wnb-ai-loading').hide();
            if (res.success && res.data) {
                $modal.find('.wnb-ai-result-text').text(res.data);
                $modal.find('.wnb-ai-result').show();
            } else {
                alert('AI generation failed: ' + (res.message || 'Unknown error'));
            }
        }).catch(function (err) {
            $modal.find('.wnb-ai-loading').hide();
            alert('AI request failed: ' + (err.message || 'Network error'));
        });
    }

    function buildActionButtons() {
        return ACTIONS.map(function (a) {
            return '<button type="button" class="wnb-ai-action-btn" data-action="' + a.key + '">' +
                '<span class="wnb-ai-action-icon">' + a.icon + '</span>' +
                '<span>' + a.label + '</span>' +
            '</button>';
        }).join('');
    }

    function buildOptions(arr) {
        return arr.map(function (v) { return '<option value="' + v + '">' + v + '</option>'; }).join('');
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
})();
