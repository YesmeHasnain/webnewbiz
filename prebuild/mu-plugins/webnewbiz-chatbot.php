<?php
/**
 * Plugin Name: Webnewbiz AI Chatbot
 * Description: Floating AI chatbot widget powered by Claude. Lets site owners edit their website via chat.
 * Version: 1.0
 * Author: Webnewbiz
 */

if (!defined('ABSPATH')) exit;

/**
 * Output the floating chatbot widget on every frontend page.
 */
add_action('wp_footer', function () {
    if (is_admin()) return;

    $siteId = get_option('webnewbiz_site_id', '');
    $apiBase = get_option('webnewbiz_api_url', 'http://127.0.0.1:8000');
    $colors = get_option('webnewbiz_colors', '');

    if (!$siteId) return;

    $colorData = $colors ? json_decode($colors, true) : [];
    $primary = $colorData['primary'] ?? '#6366F1';
    $secondary = $colorData['secondary'] ?? '#4F46E5';

    ?>
<div id="wnb-chatbot-app" style="position:fixed;bottom:24px;right:24px;z-index:99999;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">

    <!-- Chat Toggle Button -->
    <button id="wnb-chat-toggle" onclick="wnbChat.toggle()" style="width:60px;height:60px;border-radius:50%;border:none;cursor:pointer;background:linear-gradient(135deg,<?php echo esc_attr($primary); ?>,<?php echo esc_attr($secondary); ?>);color:#fff;box-shadow:0 8px 32px rgba(99,102,241,0.4);display:flex;align-items:center;justify-content:center;transition:all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <svg id="wnb-chat-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <svg id="wnb-close-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <!-- Chat Panel -->
    <div id="wnb-chat-panel" style="display:none;position:absolute;bottom:72px;right:0;width:380px;max-width:calc(100vw - 32px);height:520px;max-height:calc(100vh - 120px);background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;flex-direction:column;">

        <!-- Header -->
        <div style="background:linear-gradient(135deg,<?php echo esc_attr($primary); ?>,<?php echo esc_attr($secondary); ?>);padding:18px 20px;color:#fff;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm0 14.2a7.2 7.2 0 0 1-6-3.2c.03-2 4-3.1 6-3.1s5.97 1.1 6 3.1a7.2 7.2 0 0 1-6 3.2z" fill="currentColor"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:15px;">AI Website Assistant</div>
                    <div style="font-size:12px;opacity:0.85;">Powered by Claude</div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div id="wnb-chat-messages" style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:12px;">
            <!-- Welcome message -->
            <div class="wnb-msg wnb-msg-bot">
                <div style="background:#F1F5F9;border-radius:16px 16px 16px 4px;padding:12px 16px;max-width:85%;font-size:14px;line-height:1.6;color:#334155;">
                    Hi! I'm your AI assistant. I can help you edit this website. Try:
                    <ul style="margin:8px 0 0;padding-left:18px;color:#64748B;font-size:13px;">
                        <li>Change the hero title</li>
                        <li>Update the site colors</li>
                        <li>Edit page content</li>
                        <li>Add a new page</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div style="padding:12px 16px;border-top:1px solid #E2E8F0;background:#FAFBFC;">
            <form id="wnb-chat-form" onsubmit="return wnbChat.send(event)" style="display:flex;gap:8px;">
                <input id="wnb-chat-input" type="text" placeholder="Ask me anything about your site..." maxlength="2000" style="flex:1;padding:10px 14px;border:1px solid #E2E8F0;border-radius:12px;font-size:14px;outline:none;background:#fff;transition:border-color 0.2s;" onfocus="this.style.borderColor='<?php echo esc_attr($primary); ?>'" onblur="this.style.borderColor='#E2E8F0'">
                <button type="submit" id="wnb-send-btn" style="width:40px;height:40px;border-radius:12px;border:none;cursor:pointer;background:<?php echo esc_attr($primary); ?>;color:#fff;display:flex;align-items:center;justify-content:center;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
var wnbChat = {
    siteId: <?php echo json_encode($siteId); ?>,
    apiBase: <?php echo json_encode(rtrim($apiBase, '/')); ?>,
    isOpen: false,
    loading: false,

    toggle: function() {
        this.isOpen = !this.isOpen;
        var panel = document.getElementById('wnb-chat-panel');
        var chatIcon = document.getElementById('wnb-chat-icon');
        var closeIcon = document.getElementById('wnb-close-icon');
        if (this.isOpen) {
            panel.style.display = 'flex';
            chatIcon.style.display = 'none';
            closeIcon.style.display = 'block';
            document.getElementById('wnb-chat-input').focus();
            this.scrollDown();
        } else {
            panel.style.display = 'none';
            chatIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }
    },

    send: function(e) {
        e.preventDefault();
        var input = document.getElementById('wnb-chat-input');
        var msg = input.value.trim();
        if (!msg || this.loading) return false;

        this.addMessage(msg, 'user');
        input.value = '';
        this.loading = true;
        document.getElementById('wnb-send-btn').style.opacity = '0.5';

        // Show typing indicator
        var typingId = 'wnb-typing-' + Date.now();
        this.addTyping(typingId);

        var self = this;
        fetch(this.apiBase + '/websites/' + this.siteId + '/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: msg })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            self.removeTyping(typingId);
            if (data.success && data.reply) {
                self.addMessage(data.reply, 'bot', data.actions_taken);
            } else {
                self.addMessage(data.reply || 'Sorry, something went wrong.', 'bot');
            }
        })
        .catch(function(err) {
            self.removeTyping(typingId);
            self.addMessage('Connection error. Make sure the server is running.', 'bot');
        })
        .finally(function() {
            self.loading = false;
            document.getElementById('wnb-send-btn').style.opacity = '1';
        });

        return false;
    },

    addMessage: function(text, role, actions) {
        var container = document.getElementById('wnb-chat-messages');
        var div = document.createElement('div');
        div.className = 'wnb-msg wnb-msg-' + role;
        div.style.display = 'flex';
        div.style.justifyContent = role === 'user' ? 'flex-end' : 'flex-start';

        var bubble = document.createElement('div');
        if (role === 'user') {
            bubble.style.cssText = 'background:<?php echo esc_attr($primary); ?>;color:#fff;border-radius:16px 16px 4px 16px;padding:10px 16px;max-width:80%;font-size:14px;line-height:1.5;';
        } else {
            bubble.style.cssText = 'background:#F1F5F9;color:#334155;border-radius:16px 16px 16px 4px;padding:12px 16px;max-width:85%;font-size:14px;line-height:1.6;';
        }
        bubble.textContent = text;

        // Show actions taken
        if (actions && actions.length > 0) {
            var actionsDiv = document.createElement('div');
            actionsDiv.style.cssText = 'margin-top:8px;padding-top:8px;border-top:1px solid rgba(0,0,0,0.08);font-size:12px;';
            actions.forEach(function(a) {
                var badge = document.createElement('div');
                badge.style.cssText = 'display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:8px;margin:2px;font-size:11px;' +
                    (a.success ? 'background:#D1FAE5;color:#065F46;' : 'background:#FEE2E2;color:#991B1B;');
                badge.textContent = (a.success ? '\u2713 ' : '\u2717 ') + (a.type || 'action').replace(/_/g, ' ');
                actionsDiv.appendChild(badge);
            });
            bubble.appendChild(actionsDiv);
        }

        div.appendChild(bubble);
        container.appendChild(div);
        this.scrollDown();
    },

    addTyping: function(id) {
        var container = document.getElementById('wnb-chat-messages');
        var div = document.createElement('div');
        div.id = id;
        div.className = 'wnb-msg wnb-msg-bot';
        div.innerHTML = '<div style="background:#F1F5F9;border-radius:16px 16px 16px 4px;padding:12px 16px;display:inline-flex;gap:4px;">' +
            '<span class="wnb-dot" style="width:8px;height:8px;border-radius:50%;background:#94A3B8;animation:wnbBounce 1.4s infinite both;animation-delay:0s;"></span>' +
            '<span class="wnb-dot" style="width:8px;height:8px;border-radius:50%;background:#94A3B8;animation:wnbBounce 1.4s infinite both;animation-delay:0.2s;"></span>' +
            '<span class="wnb-dot" style="width:8px;height:8px;border-radius:50%;background:#94A3B8;animation:wnbBounce 1.4s infinite both;animation-delay:0.4s;"></span>' +
            '</div>';
        container.appendChild(div);
        this.scrollDown();
    },

    removeTyping: function(id) {
        var el = document.getElementById(id);
        if (el) el.remove();
    },

    scrollDown: function() {
        var c = document.getElementById('wnb-chat-messages');
        setTimeout(function() { c.scrollTop = c.scrollHeight; }, 50);
    }
};
</script>
<style>
@keyframes wnbBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}
#wnb-chat-panel { animation: wnbSlideUp 0.3s ease; }
@keyframes wnbSlideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
#wnb-chat-messages::-webkit-scrollbar { width: 4px; }
#wnb-chat-messages::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
@media (max-width: 480px) {
    #wnb-chat-panel { width: calc(100vw - 16px) !important; right: -16px !important; height: calc(100vh - 100px) !important; }
}
</style>
<?php
}, 50);
