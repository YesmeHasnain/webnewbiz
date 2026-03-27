// WebNewBiz Live Chat Widget
// Embed: <script src="https://api.webnewbiz.app/chat-widget.js" data-site-id="YOUR_ID"></script>
(function() {
  var script = document.currentScript;
  var siteId = script ? script.getAttribute('data-site-id') : null;
  var color = script ? script.getAttribute('data-color') || '#3b82f6' : '#3b82f6';

  // Create widget container
  var container = document.createElement('div');
  container.id = 'wnb-chat-widget';
  container.innerHTML = [
    '<style>',
    '#wnb-chat-btn{position:fixed;bottom:24px;right:24px;width:56px;height:56px;border-radius:50%;background:' + color + ';border:none;cursor:pointer;box-shadow:0 4px 20px rgba(0,0,0,0.3);z-index:99999;display:flex;align-items:center;justify-content:center;transition:transform 0.2s}',
    '#wnb-chat-btn:hover{transform:scale(1.1)}',
    '#wnb-chat-btn svg{width:24px;height:24px;fill:white}',
    '#wnb-chat-box{position:fixed;bottom:96px;right:24px;width:380px;height:520px;background:#0a0a0f;border:1px solid #1e1e2e;border-radius:16px;z-index:99999;display:none;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.5)}',
    '#wnb-chat-box.open{display:flex}',
    '#wnb-chat-header{padding:16px;background:#12121a;border-bottom:1px solid #1e1e2e;display:flex;align-items:center;gap:10px}',
    '#wnb-chat-header .dot{width:8px;height:8px;border-radius:50%;background:#22c55e}',
    '#wnb-chat-header h3{color:white;font-size:14px;font-weight:600;margin:0;font-family:system-ui}',
    '#wnb-chat-header p{color:#888;font-size:11px;margin:0;font-family:system-ui}',
    '#wnb-chat-header button{margin-left:auto;background:none;border:none;color:#666;cursor:pointer;font-size:18px}',
    '#wnb-chat-msgs{flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:8px}',
    '.wnb-msg{max-width:80%;padding:10px 14px;border-radius:12px;font-size:13px;line-height:1.5;font-family:system-ui}',
    '.wnb-msg.bot{background:#12121a;color:#ccc;border:1px solid #1e1e2e;align-self:flex-start}',
    '.wnb-msg.user{background:' + color + ';color:white;align-self:flex-end}',
    '#wnb-chat-input{padding:12px;border-top:1px solid #1e1e2e;display:flex;gap:8px}',
    '#wnb-chat-input input{flex:1;background:#12121a;border:1px solid #1e1e2e;border-radius:10px;padding:10px 14px;color:white;font-size:13px;outline:none;font-family:system-ui}',
    '#wnb-chat-input input:focus{border-color:' + color + '50}',
    '#wnb-chat-input button{background:' + color + ';border:none;border-radius:10px;padding:10px 16px;color:white;cursor:pointer;font-size:13px;font-weight:500;font-family:system-ui}',
    '</style>',
    '<button id="wnb-chat-btn"><svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg></button>',
    '<div id="wnb-chat-box">',
    '<div id="wnb-chat-header"><div class="dot"></div><div><h3>Chat with us</h3><p>We typically reply in minutes</p></div><button id="wnb-chat-close">&times;</button></div>',
    '<div id="wnb-chat-msgs"><div class="wnb-msg bot">Hi! How can I help you today? 👋</div></div>',
    '<div id="wnb-chat-input"><input placeholder="Type a message..." id="wnb-chat-text"><button id="wnb-chat-send">Send</button></div>',
    '</div>',
  ].join('\n');

  document.body.appendChild(container);

  var btn = document.getElementById('wnb-chat-btn');
  var box = document.getElementById('wnb-chat-box');
  var closeBtn = document.getElementById('wnb-chat-close');
  var input = document.getElementById('wnb-chat-text');
  var sendBtn = document.getElementById('wnb-chat-send');
  var msgs = document.getElementById('wnb-chat-msgs');

  btn.onclick = function() { box.classList.toggle('open'); if (box.classList.contains('open')) input.focus(); };
  closeBtn.onclick = function() { box.classList.remove('open'); };

  function addMsg(text, type) {
    var div = document.createElement('div');
    div.className = 'wnb-msg ' + type;
    div.textContent = text;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
  }

  function sendMessage() {
    var text = input.value.trim();
    if (!text) return;
    addMsg(text, 'user');
    input.value = '';
    setTimeout(function() { addMsg('Thanks for your message! Our team will get back to you shortly.', 'bot'); }, 1000);
  }

  sendBtn.onclick = sendMessage;
  input.onkeydown = function(e) { if (e.key === 'Enter') sendMessage(); };
})();
