// WebNewBiz Analytics Tracking Pixel
// Embed: <script src="https://api.webnewbiz.app/tracking.js" data-site-id="YOUR_ID"></script>
(function() {
  var script = document.currentScript;
  var siteId = script ? script.getAttribute('data-site-id') : null;
  if (!siteId) return;

  var API = 'https://api.webnewbiz.app/api/track';

  function send(event, meta) {
    var data = {
      site_id: siteId,
      event: event,
      url: window.location.href,
      referrer: document.referrer || null,
      title: document.title,
      screen: window.innerWidth + 'x' + window.innerHeight,
      language: navigator.language,
      timestamp: new Date().toISOString(),
      metadata: meta || {}
    };

    if (navigator.sendBeacon) {
      navigator.sendBeacon(API, JSON.stringify(data));
    } else {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', API, true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify(data));
    }
  }

  // Page view
  send('pageview');

  // Track time on page
  var startTime = Date.now();
  window.addEventListener('beforeunload', function() {
    send('session_end', { duration: Math.round((Date.now() - startTime) / 1000) });
  });

  // Track clicks on links
  document.addEventListener('click', function(e) {
    var link = e.target.closest('a');
    if (link && link.href) {
      send('click', { href: link.href, text: (link.textContent || '').substring(0, 100) });
    }
  });

  // Track form submissions
  document.addEventListener('submit', function(e) {
    var form = e.target;
    send('form_submit', { action: form.action || '', id: form.id || '' });
  });

  // SPA navigation support
  var pushState = history.pushState;
  history.pushState = function() {
    pushState.apply(history, arguments);
    setTimeout(function() { send('pageview'); }, 100);
  };
  window.addEventListener('popstate', function() { send('pageview'); });
})();
