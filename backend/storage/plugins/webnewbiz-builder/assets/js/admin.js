/**
 * WebNewBiz Builder — Admin JavaScript
 * Vanilla JS, no jQuery dependency
 */
(function () {
    'use strict';

    /* ─── Globals from wp_localize_script ─── */
    var ajaxUrl = (window.wnbAdmin && wnbAdmin.ajaxUrl) || '/wp-admin/admin-ajax.php';
    var nonce   = (window.wnbAdmin && wnbAdmin.nonce) || '';

    /* ═══════════════════════════════════════════
     *  Toast Notification System
     * ═══════════════════════════════════════════ */
    var toastContainer = null;

    function ensureToastContainer() {
        if (toastContainer) return;
        toastContainer = document.createElement('div');
        toastContainer.className = 'wnb-toast-container';
        document.body.appendChild(toastContainer);
    }

    function showToast(message, type) {
        type = type || 'success';
        ensureToastContainer();

        var toast = document.createElement('div');
        toast.className = 'wnb-toast wnb-toast-' + type;

        var icon = type === 'success' ? '&#10003;' : type === 'error' ? '&#10007;' : '&#9432;';
        toast.innerHTML =
            '<span class="wnb-toast-icon">' + icon + '</span>' +
            '<span class="wnb-toast-msg">' + escapeHtml(message) + '</span>' +
            '<button class="wnb-toast-close" onclick="this.parentNode.remove()">&times;</button>';

        toastContainer.appendChild(toast);

        // Animate in
        requestAnimationFrame(function () {
            toast.classList.add('wnb-toast-visible');
        });

        // Auto-remove after 5s
        setTimeout(function () {
            toast.classList.remove('wnb-toast-visible');
            setTimeout(function () { toast.remove(); }, 300);
        }, 5000);
    }

    /* ═══════════════════════════════════════════
     *  AJAX Helper
     * ═══════════════════════════════════════════ */
    function wnbAjax(action, data, callback) {
        var formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', nonce);

        if (data) {
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    formData.append(key, data[key]);
                }
            }
        }

        return fetch(ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(function (res) { return res.json(); })
        .then(function (json) {
            if (callback) callback(json);
            return json;
        })
        .catch(function (err) {
            showToast('Network error: ' + err.message, 'error');
            if (callback) callback({ success: false, data: err.message });
        });
    }

    /* ═══════════════════════════════════════════
     *  Loading State Helpers
     * ═══════════════════════════════════════════ */
    function setLoading(btn, loading) {
        if (!btn) return;
        if (loading) {
            btn.dataset.originalText = btn.innerHTML;
            btn.innerHTML = '<span class="wnb-spinner"></span> Working...';
            btn.disabled = true;
            btn.classList.add('wnb-btn-loading');
        } else {
            btn.innerHTML = btn.dataset.originalText || btn.innerHTML;
            btn.disabled = false;
            btn.classList.remove('wnb-btn-loading');
        }
    }

    /* ═══════════════════════════════════════════
     *  Confirmation Dialog
     * ═══════════════════════════════════════════ */
    function showConfirm(title, message, onConfirm) {
        var overlay = document.createElement('div');
        overlay.className = 'wnb-modal-overlay';

        overlay.innerHTML =
            '<div class="wnb-modal">' +
                '<div class="wnb-modal-header">' +
                    '<h3>' + escapeHtml(title) + '</h3>' +
                    '<button class="wnb-modal-close" data-action="cancel">&times;</button>' +
                '</div>' +
                '<div class="wnb-modal-body">' +
                    '<p>' + escapeHtml(message) + '</p>' +
                '</div>' +
                '<div class="wnb-modal-footer">' +
                    '<button class="wnb-btn wnb-btn-secondary" data-action="cancel">Cancel</button>' +
                    '<button class="wnb-btn wnb-btn-danger" data-action="confirm">Confirm</button>' +
                '</div>' +
            '</div>';

        document.body.appendChild(overlay);
        requestAnimationFrame(function () { overlay.classList.add('wnb-modal-visible'); });

        overlay.addEventListener('click', function (e) {
            var action = e.target.dataset.action;
            if (action === 'cancel') {
                overlay.classList.remove('wnb-modal-visible');
                setTimeout(function () { overlay.remove(); }, 200);
            } else if (action === 'confirm') {
                overlay.classList.remove('wnb-modal-visible');
                setTimeout(function () { overlay.remove(); }, 200);
                if (onConfirm) onConfirm();
            }
        });
    }

    /* ═══════════════════════════════════════════
     *  Utility
     * ═══════════════════════════════════════════ */
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function on(selector, event, handler) {
        var els = document.querySelectorAll(selector);
        for (var i = 0; i < els.length; i++) {
            els[i].addEventListener(event, handler);
        }
    }

    /* ═══════════════════════════════════════════
     *  Cache Purge Buttons
     * ═══════════════════════════════════════════ */
    on('[data-purge-cache]', 'click', function (e) {
        var btn = e.currentTarget;
        var type = btn.dataset.purgeCache;
        setLoading(btn, true);

        wnbAjax('wnb_purge_cache', { cache_type: type }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast(res.data.message, 'success');
                // Update last purge time display if present
                var timeEl = document.getElementById('wnb-last-purge');
                if (timeEl && res.data.time) timeEl.textContent = res.data.time;
            } else {
                showToast(res.data || 'Failed to purge cache', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Toggle Switches (save setting)
     * ═══════════════════════════════════════════ */
    on('.wnb-toggle input[type="checkbox"]', 'change', function (e) {
        var input = e.currentTarget;
        var key = input.dataset.key;
        var value = input.checked ? '1' : '0';
        var label = input.closest('.wnb-toggle');

        if (label) label.classList.add('wnb-toggle-saving');

        wnbAjax('wnb_save_setting', { key: key, value: value }, function (res) {
            if (label) label.classList.remove('wnb-toggle-saving');
            if (res.success) {
                showToast('Setting saved', 'success');
            } else {
                showToast(res.data || 'Failed to save', 'error');
                // Revert toggle
                input.checked = !input.checked;
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Database Cleanup Buttons
     * ═══════════════════════════════════════════ */
    on('[data-db-cleanup]', 'click', function (e) {
        var btn = e.currentTarget;
        var type = btn.dataset.dbCleanup;
        var label = type === 'all' ? 'all database items' : type.replace(/_/g, ' ');

        showConfirm(
            'Database Cleanup',
            'Are you sure you want to clean ' + label + '? This cannot be undone.',
            function () {
                setLoading(btn, true);
                wnbAjax('wnb_db_cleanup', { cleanup_type: type }, function (res) {
                    setLoading(btn, false);
                    if (res.success) {
                        showToast(res.data.message, 'success');
                        // Refresh counters if page has them
                        setTimeout(function () { location.reload(); }, 1500);
                    } else {
                        showToast(res.data || 'Cleanup failed', 'error');
                    }
                });
            }
        );
    });

    /* ═══════════════════════════════════════════
     *  Optimize Tables Button
     * ═══════════════════════════════════════════ */
    on('[data-db-optimize]', 'click', function (e) {
        var btn = e.currentTarget;
        setLoading(btn, true);
        wnbAjax('wnb_db_cleanup', { cleanup_type: 'optimize' }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast('Tables optimized', 'success');
            } else {
                showToast(res.data || 'Optimization failed', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Backup Creation
     * ═══════════════════════════════════════════ */
    on('[data-create-backup]', 'click', function (e) {
        var btn = e.currentTarget;
        var typeSelect = document.getElementById('wnb-backup-type');
        var type = typeSelect ? typeSelect.value : 'full';

        setLoading(btn, true);
        wnbAjax('wnb_create_backup', { backup_type: type }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast(res.data.message + ' (' + res.data.size_formatted + ')', 'success');
                setTimeout(function () { location.reload(); }, 1500);
            } else {
                showToast(res.data || 'Backup failed', 'error');
            }
        });
    });

    /* ─── Backup Delete ─── */
    on('[data-delete-backup]', 'click', function (e) {
        var btn = e.currentTarget;
        var id = btn.dataset.deleteBackup;

        showConfirm('Delete Backup', 'Are you sure? The backup files will be permanently deleted.', function () {
            setLoading(btn, true);
            wnbAjax('wnb_delete_backup', { backup_id: id }, function (res) {
                setLoading(btn, false);
                if (res.success) {
                    showToast('Backup deleted', 'success');
                    var row = btn.closest('tr');
                    if (row) row.remove();
                } else {
                    showToast(res.data || 'Delete failed', 'error');
                }
            });
        });
    });

    /* ─── Backup Restore ─── */
    on('[data-restore-backup]', 'click', function (e) {
        var btn = e.currentTarget;
        var id = btn.dataset.restoreBackup;

        showConfirm(
            'Restore Backup',
            'WARNING: This will overwrite your current database. Are you absolutely sure?',
            function () {
                setLoading(btn, true);
                wnbAjax('wnb_restore_backup', { backup_id: id }, function (res) {
                    setLoading(btn, false);
                    if (res.success) {
                        showToast(res.data.message, 'success');
                        setTimeout(function () { location.reload(); }, 2000);
                    } else {
                        showToast(res.data || 'Restore failed', 'error');
                    }
                });
            }
        );
    });

    /* ═══════════════════════════════════════════
     *  Image Optimizer
     * ═══════════════════════════════════════════ */
    on('[data-optimize-images]', 'click', function (e) {
        var btn = e.currentTarget;
        setLoading(btn, true);

        wnbAjax('wnb_optimize_images', {}, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast(res.data.message, 'success');
                // Update stats on page
                var statOptimized = document.getElementById('wnb-img-optimized');
                var statRemaining = document.getElementById('wnb-img-remaining');
                if (statOptimized) statOptimized.textContent = res.data.total_optimized;
                if (statRemaining) statRemaining.textContent = res.data.remaining;

                // If remaining, allow running again
                if (res.data.remaining > 0) {
                    showToast(res.data.remaining + ' images remaining. Click again to continue.', 'info');
                }
            } else {
                showToast(res.data || 'Optimization failed', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  AI Content Generator
     * ═══════════════════════════════════════════ */
    on('[data-ai-generate]', 'click', function (e) {
        var btn = e.currentTarget;
        var prompt = document.getElementById('wnb-ai-prompt');
        var contentType = document.getElementById('wnb-ai-type');
        var tone = document.getElementById('wnb-ai-tone');
        var length = document.getElementById('wnb-ai-length');
        var output = document.getElementById('wnb-ai-output');

        if (!prompt || !prompt.value.trim()) {
            showToast('Please enter a prompt', 'error');
            return;
        }

        setLoading(btn, true);
        if (output) output.innerHTML = '<div class="wnb-ai-loading"><span class="wnb-spinner"></span> Generating content with AI...</div>';

        wnbAjax('wnb_ai_generate', {
            prompt: prompt.value,
            content_type: contentType ? contentType.value : 'blog_post',
            tone: tone ? tone.value : 'professional',
            length: length ? length.value : 'medium'
        }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                if (output) {
                    output.innerHTML = res.data.content;
                }
                showToast('Content generated (' + res.data.word_count + ' words)', 'success');
            } else {
                if (output) output.innerHTML = '';
                showToast(res.data || 'Generation failed', 'error');
            }
        });
    });

    /* ─── AI Copy to Clipboard ─── */
    on('[data-ai-copy]', 'click', function () {
        var output = document.getElementById('wnb-ai-output');
        if (!output || !output.textContent.trim()) {
            showToast('No content to copy', 'error');
            return;
        }

        if (navigator.clipboard) {
            navigator.clipboard.writeText(output.innerHTML).then(function () {
                showToast('Copied to clipboard', 'success');
            });
        } else {
            // Fallback
            var range = document.createRange();
            range.selectNodeContents(output);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            document.execCommand('copy');
            sel.removeAllRanges();
            showToast('Copied to clipboard', 'success');
        }
    });

    /* ═══════════════════════════════════════════
     *  Maintenance Mode Toggle
     * ═══════════════════════════════════════════ */
    on('[data-maintenance-toggle]', 'change', function (e) {
        var input = e.currentTarget;
        var enabled = input.checked ? '1' : '0';

        wnbAjax('wnb_toggle_maintenance', { enabled: enabled }, function (res) {
            if (res.success) {
                showToast(res.data.message, 'success');
                // Update status indicator
                var status = document.getElementById('wnb-maintenance-status');
                if (status) {
                    status.textContent = enabled === '1' ? 'Active' : 'Inactive';
                    status.className = 'wnb-badge ' + (enabled === '1' ? 'wnb-badge-red' : 'wnb-badge-green');
                }
            } else {
                showToast(res.data || 'Failed', 'error');
                input.checked = !input.checked;
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  SEO Redirect Management
     * ═══════════════════════════════════════════ */
    on('[data-add-redirect]', 'click', function (e) {
        var btn = e.currentTarget;
        var fromInput = document.getElementById('wnb-redirect-from');
        var toInput = document.getElementById('wnb-redirect-to');

        if (!fromInput || !toInput || !fromInput.value.trim() || !toInput.value.trim()) {
            showToast('Both "From" and "To" fields are required', 'error');
            return;
        }

        setLoading(btn, true);
        wnbAjax('wnb_save_redirect', { from: fromInput.value, to: toInput.value }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast('Redirect saved', 'success');
                fromInput.value = '';
                toInput.value = '';
                setTimeout(function () { location.reload(); }, 1000);
            } else {
                showToast(res.data || 'Failed to save redirect', 'error');
            }
        });
    });

    on('[data-delete-redirect]', 'click', function (e) {
        var btn = e.currentTarget;
        var id = btn.dataset.deleteRedirect;

        setLoading(btn, true);
        wnbAjax('wnb_delete_redirect', { redirect_id: id }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast('Redirect deleted', 'success');
                var row = btn.closest('tr');
                if (row) row.remove();
            } else {
                showToast(res.data || 'Delete failed', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Settings Save Forms
     * ═══════════════════════════════════════════ */
    on('[data-save-setting]', 'click', function (e) {
        var btn = e.currentTarget;
        var key = btn.dataset.saveSetting;
        var input = document.getElementById('wnb-setting-' + key);

        if (!input) return;

        setLoading(btn, true);
        wnbAjax('wnb_save_setting', { key: key, value: input.value }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast('Setting saved', 'success');
            } else {
                showToast(res.data || 'Failed to save', 'error');
            }
        });
    });

    /* ─── Bulk Settings Save ─── */
    on('[data-save-settings-form]', 'submit', function (e) {
        e.preventDefault();
        var form = e.currentTarget;
        var inputs = form.querySelectorAll('[data-setting-key]');
        var btn = form.querySelector('button[type="submit"]');
        var settings = {};

        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            var key = input.dataset.settingKey;
            if (input.type === 'checkbox') {
                settings[key] = input.checked ? '1' : '0';
            } else {
                settings[key] = input.value;
            }
        }

        if (btn) setLoading(btn, true);

        wnbAjax('wnb_save_settings', { settings: settings }, function (res) {
            if (btn) setLoading(btn, false);
            if (res.success) {
                showToast('Settings saved', 'success');
            } else {
                showToast(res.data || 'Failed', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Slider (compression quality etc)
     * ═══════════════════════════════════════════ */
    on('.wnb-range-slider input[type="range"]', 'input', function (e) {
        var input = e.currentTarget;
        var display = input.parentNode.querySelector('.wnb-range-value');
        if (display) display.textContent = input.value;
    });

    on('.wnb-range-slider input[type="range"]', 'change', function (e) {
        var input = e.currentTarget;
        var key = input.dataset.key;
        if (!key) return;

        wnbAjax('wnb_save_setting', { key: key, value: input.value }, function (res) {
            if (res.success) {
                showToast('Saved', 'success');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Tab Navigation
     * ═══════════════════════════════════════════ */
    on('.wnb-tab-btn', 'click', function (e) {
        var btn = e.currentTarget;
        var tabGroup = btn.closest('.wnb-tabs');
        var target = btn.dataset.tab;

        if (!tabGroup || !target) return;

        // Deactivate all tabs
        var btns = tabGroup.querySelectorAll('.wnb-tab-btn');
        for (var i = 0; i < btns.length; i++) btns[i].classList.remove('active');
        btn.classList.add('active');

        // Show/hide panels
        var panels = tabGroup.parentNode.querySelectorAll('.wnb-tab-panel');
        for (var j = 0; j < panels.length; j++) {
            panels[j].style.display = panels[j].dataset.panel === target ? 'block' : 'none';
        }
    });

    /* ═══════════════════════════════════════════
     *  SEO Robots.txt Save
     * ═══════════════════════════════════════════ */
    on('[data-save-robots]', 'click', function (e) {
        var btn = e.currentTarget;
        var textarea = document.getElementById('wnb-robots-txt');
        if (!textarea) return;

        setLoading(btn, true);
        wnbAjax('wnb_save_setting', { key: 'wnb_robots_txt', value: textarea.value }, function (res) {
            setLoading(btn, false);
            if (res.success) {
                showToast('Robots.txt saved', 'success');
            } else {
                showToast(res.data || 'Failed', 'error');
            }
        });
    });

    /* ═══════════════════════════════════════════
     *  Maintenance Custom Fields Save
     * ═══════════════════════════════════════════ */
    on('[data-save-maintenance]', 'click', function (e) {
        var btn = e.currentTarget;
        var fields = {
            wnb_maintenance_message: document.getElementById('wnb-maint-message'),
            wnb_maintenance_bg_color: document.getElementById('wnb-maint-bg'),
            wnb_maintenance_back_date: document.getElementById('wnb-maint-date'),
            wnb_maintenance_custom_css: document.getElementById('wnb-maint-css'),
        };

        setLoading(btn, true);

        var promises = [];
        for (var key in fields) {
            if (fields[key]) {
                promises.push(wnbAjax('wnb_save_setting', { key: key, value: fields[key].value }));
            }
        }

        Promise.all(promises).then(function () {
            setLoading(btn, false);
            showToast('Maintenance settings saved', 'success');
        });
    });

    /* ═══════════════════════════════════════════
     *  White Label Fields Save
     * ═══════════════════════════════════════════ */
    on('[data-save-whitelabel]', 'click', function (e) {
        var btn = e.currentTarget;
        var fields = {
            wnb_whitelabel_login_logo: document.getElementById('wnb-wl-login-logo'),
            wnb_whitelabel_login_bg: document.getElementById('wnb-wl-login-bg'),
            wnb_whitelabel_footer_text: document.getElementById('wnb-wl-footer'),
            wnb_whitelabel_widget_title: document.getElementById('wnb-wl-widget-title'),
            wnb_whitelabel_widget_content: document.getElementById('wnb-wl-widget-content'),
        };

        setLoading(btn, true);

        var promises = [];
        for (var key in fields) {
            if (fields[key]) {
                promises.push(wnbAjax('wnb_save_setting', { key: key, value: fields[key].value }));
            }
        }

        Promise.all(promises).then(function () {
            setLoading(btn, false);
            showToast('White label settings saved', 'success');
        });
    });

    /* ═══════════════════════════════════════════
     *  Schema Settings Save
     * ═══════════════════════════════════════════ */
    on('[data-save-schema]', 'click', function (e) {
        var btn = e.currentTarget;
        var fields = {
            wnb_schema_org_name: document.getElementById('wnb-schema-name'),
            wnb_schema_org_logo: document.getElementById('wnb-schema-logo'),
            wnb_schema_org_phone: document.getElementById('wnb-schema-phone'),
        };

        setLoading(btn, true);

        var promises = [];
        for (var key in fields) {
            if (fields[key]) {
                promises.push(wnbAjax('wnb_save_setting', { key: key, value: fields[key].value }));
            }
        }

        Promise.all(promises).then(function () {
            setLoading(btn, false);
            showToast('Schema settings saved', 'success');
        });
    });

})();
