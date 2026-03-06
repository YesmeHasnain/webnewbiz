<?php
/**
 * Build Coop B-Ball site — proper Elementor modules
 * Each section = separate Container, all styling via CSS classes
 */

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'wp_zaid';
$siteUrl = 'http://localhost/zaid';

function eid() { return substr(bin2hex(random_bytes(4)), 0, 7); }

function con($cssClass, $elements) {
    return [
        'id' => eid(), 'elType' => 'container',
        'settings' => ['content_width' => 'full-width', 'css_classes' => $cssClass],
        'elements' => $elements,
    ];
}

function hw($code) {
    return [
        'id' => eid(), 'elType' => 'widget', 'widgetType' => 'html',
        'settings' => ['html' => $code], 'elements' => [],
    ];
}

function heading($text, $tag, $cssClass = '') {
    return [
        'id' => eid(), 'elType' => 'widget', 'widgetType' => 'heading',
        'settings' => ['title' => $text, 'header_size' => $tag, 'css_classes' => $cssClass],
        'elements' => [],
    ];
}

function te($html, $cssClass = '') {
    return [
        'id' => eid(), 'elType' => 'widget', 'widgetType' => 'text-editor',
        'settings' => ['editor' => $html, 'css_classes' => $cssClass],
        'elements' => [],
    ];
}

$img = [
    'hero'   => 'http://coopbballtraining.com/wp-content/uploads/2026/02/IMG_7194_1.jpg',
    'coach'  => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7352.jpg',
    'video'  => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7578.jpg',
    'vidSrc' => 'http://coopbballtraining.com/wp-content/uploads/2026/02/video-output-C3E31B30-E0A4-40FA-B7B1-CB853BD9B261-1.mov',
    'how1'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7582.jpg',
    'how2'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7124.jpg',
    'how3'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7577.jpg',
    'pb1'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7575.jpg',
    'pb2'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7195.jpg',
    'pb3'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_1176.jpg',
    'cta'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7124.jpg',
    'bio'    => 'http://coopbballtraining.com/wp-content/uploads/2026/02/IMG_7482.jpg',
    'logo'   => 'http://coopbballtraining.com/wp-content/uploads/2026/02/image_-_2026-02-27T002144.133-removebg-preview.png',
];

// Read original HTML for reference
$origHtml = file_get_contents(__DIR__ . '/convert-elementor.html');
preg_match('/<style>(.*?)<\/style>/s', $origHtml, $cssMatch);
$originalCss = $cssMatch[1] ?? '';

// ============================================================
// GLOBAL CSS — original CSS + Elementor reset overrides
// ============================================================
$globalCss = '<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,300;0,400;0,600;0,700;0,900;1,700;1,900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* === ELEMENTOR RESET === */
body.elementor-template-canvas { margin:0; padding:0; background:#080808; color:#F8F8F6; font-family:"Barlow",sans-serif; overflow-x:hidden; }
.elementor *, .elementor *::before, .elementor *::after { box-sizing:border-box; }
.elementor .elementor-widget { margin-bottom:0 !important; }
.elementor .e-con { --padding-top:0px; --padding-right:0px; --padding-bottom:0px; --padding-left:0px; --margin-top:0px; --margin-bottom:0px; --gap:0px; padding:0 !important; margin:0 !important; gap:0 !important; }
.elementor .e-con > .e-con-inner { gap:0 !important; padding:0 !important; }
.elementor .elementor-widget-wrap { padding:0 !important; }
.elementor .elementor-element { font-family:"Barlow",sans-serif; }
.elementor .elementor-heading-title { padding:0; margin:0; }
.elementor .elementor-widget-text-editor { color:inherit; }
.elementor p:last-child { margin-bottom:0; }

/* === ORIGINAL CSS === */
' . $originalCss . '

/* === NAV FIX (original uses "nav", we use ".bc-nav") === */
nav.bc-nav { position:fixed;top:56px;left:0;right:0;z-index:1000;padding:0 64px;height:110px !important;display:flex;align-items:center;justify-content:space-between;transition:background .4s,height .3s,top .3s; }
nav.bc-nav.bg { background:rgba(8,8,8,.95);backdrop-filter:blur(20px);height:68px;border-bottom:1px solid rgba(255,255,255,0.08); }
nav.bc-nav .nav-logo img { height:60px;filter:drop-shadow(0 0 16px rgba(255,69,0,.4));transition:height .3s; }
nav.bc-nav.bg .nav-logo img { height:48px; }
@media(max-width:1100px){ nav.bc-nav{padding:0 28px;top:94px;} }
@media(max-width:700px){ nav.bc-nav{padding:0 18px;height:68px;top:104px;} }
</style>';

// ============================================================
// BUILD SECTIONS
// ============================================================

// SEC 0: CSS
$sec0 = hw($globalCss);

// SEC 1: Signup Bar + Nav + Cursor + Back-to-top
$sec1 = hw('<div class="signup-bar">
  <div class="sb-text"><div class="sb-dot"></div><span>🏀 <strong>Now Enrolling — Virtual Training Camps</strong> · Instructional videos, live group sessions, weekly workouts &amp; more for just <strong>$22 to join</strong>. Cancel anytime.</span></div>
  <a href="#pricing" class="sb-btn">⚡ Sign Up Now</a>
</div>
<nav class="bc-nav" id="mainNav">
  <a href="#hero" class="nav-logo"><img src="'.$img['logo'].'" alt="Coop B-Ball"><div class="nav-wordmark"><span class="nav-wm1">Coop B-Ball</span><span class="nav-wm2">Virtual Training</span></div></a>
  <ul class="nav-links"><li><a href="#about">About</a></li><li><a href="#benefits">What You Get</a></li><li><a href="#programs">Programs</a></li><li><a href="#testimonials">Results</a></li><li><a href="#pricing">Join — $40/mo</a></li></ul>
  <div class="nav-right"><a href="mailto:james@coopbballtraining.com" class="btn-ghost" style="padding:9px 20px;font-size:12px;letter-spacing:1px;">✉️ Email Us</a><a href="#pricing" class="nav-cta">Join Now</a></div>
</nav>
<div id="cr"></div><div id="cr2"></div><a href="#hero" id="btt">↑</a>');

// SEC 2: Hero
$sec2 = con('sec-hero', [
    hw('<section id="hero">
  <div class="hero-bg" style="background-image:url(\''.$img['hero'].'\');"></div>
  <div class="hero-content">
    <div class="hero-eyebrow"><div class="hero-dot"></div><span>Virtual Basketball Training Camps</span></div>'),
    heading('Train<br><span style="display:block;color:transparent;-webkit-text-stroke:2px #FF4500;">Elite.</span>Online.', 'h1', 'hero-heading'),
    te('<p class="hero-sub">Join Coach Coop\'s virtual training camps. Live group sessions, instructional videos, weekly workouts and pro tips — all for $40/month. Train from anywhere.</p>'),
    hw('<div class="hero-actions">
      <a href="#pricing" class="btn-primary">🏀 Join for $40/Month</a>
      <a href="#benefits" class="btn-ghost">See What You Get</a>
    </div>
  </div>
  <div class="hero-scroll"><span>Scroll</span><div class="scroll-line"></div></div>
  <div class="hero-stats">
    <div class="hstat"><div class="hstat-n" data-count="200">0<em>+</em></div><div class="hstat-l">Athletes<br>Trained</div></div>
    <div class="hstat"><div class="hstat-n" data-count="98">0<em>%</em></div><div class="hstat-l">Satisfaction<br>Rate</div></div>
    <div class="hstat"><div class="hstat-n" data-count="5">0<em>+</em></div><div class="hstat-l">Years<br>Coaching</div></div>
  </div>
</section>'),
]);

// SEC 3: Ticker
$sec3 = con('sec-ticker', [
    hw('<div class="ticker"><div class="ticker-inner"><span>Virtual Training Camps</span><span class="dot">✦</span><span>Group Sessions</span><span class="dot">✦</span><span>Instructional Videos</span><span class="dot">✦</span><span>Ball Handling</span><span class="dot">✦</span><span>Shooting Mechanics</span><span class="dot">✦</span><span>Weekly Workouts</span><span class="dot">✦</span><span>$40 / Month</span><span class="dot">✦</span><span>Train Anywhere</span><span class="dot">✦</span><span>Virtual Training Camps</span><span class="dot">✦</span><span>Group Sessions</span><span class="dot">✦</span><span>Instructional Videos</span><span class="dot">✦</span><span>Ball Handling</span><span class="dot">✦</span><span>Shooting Mechanics</span><span class="dot">✦</span><span>Weekly Workouts</span><span class="dot">✦</span><span>$40 / Month</span><span class="dot">✦</span><span>Train Anywhere</span><span class="dot">✦</span></div></div>'),
]);

// SEC 4: About
$sec4 = con('sec-about', [
    hw('<section id="about" style="min-height:auto;">
  <div class="about-left">
    <div class="about-photo sr">
      <img src="'.$img['coach'].'" alt="Coach Coop">
      <div class="about-photo-overlay"></div>
      <div class="about-photo-tag"><div class="apt-name">Coach Coop</div><div class="apt-title">Pro · Dortmund, Germany · Division III All-American</div></div>
    </div>
    <div class="about-video">
      <video id="coopVid" playsinline muted preload="metadata" poster="'.$img['video'].'">
        <source src="'.$img['vidSrc'].'" type="video/mp4">
      </video>
      <div class="av-overlay" id="vidOverlay"><div class="av-play">▶</div></div>
    </div>
  </div>
  <div class="about-text">
    <div class="eyebrow sr">About Coop B-Ball</div>'),
    heading('Coached By<br><em style="color:#FF4500;font-style:normal;">A Pro.</em><br><span style="font-size:.5em;color:rgba(255,255,255,.3);font-weight:400;letter-spacing:0;text-transform:none;font-family:Barlow,sans-serif;display:block;margin-top:10px;line-height:1.5;">Virtual camps. Real results.</span>', 'h2', 'about-headline sr d1'),
    te('<p class="sr d2" style="font-size:16px;color:rgba(255,255,255,.55);line-height:1.85;font-weight:300;">Coop B-Ball Training is a <strong style="color:#F8F8F6;font-weight:500;">100% virtual basketball training platform</strong> built for serious athletes at every level. Through live group training camps, instructional videos, and personalized workouts, you get elite-level development for just $40/month.</p>'),
    hw('<div class="cred-block sr d2">
      <h4>🏆 Coach Coop\'s Background</h4>
      <div class="cred-list">
        <div class="cred-item"><span class="cred-star">★</span><span>Played <strong>professionally in Dortmund, Germany</strong></span></div>
        <div class="cred-item"><span class="cred-star">★</span><span><strong>Division III All-American</strong> — one of the top college basketball players</span></div>
        <div class="cred-item"><span class="cred-star">★</span><span>Multiple <strong>All-Conference awards</strong> throughout his college career</span></div>
        <div class="cred-item"><span class="cred-star">★</span><span>Trained <strong>200+ athletes online</strong> since 2019</span></div>
      </div>
    </div>
    <div class="about-pillars sr d3">
      <div class="pillar"><div class="pillar-ico">🎥</div><div><h4>Virtual Group Training Camps</h4><p>Live group sessions with Coach Coop — train together, grow together.</p></div></div>
      <div class="pillar"><div class="pillar-ico">📱</div><div><h4>Train From Anywhere</h4><p>Your driveway, local court, gym — all you need is a phone and a basketball.</p></div></div>
      <div class="pillar"><div class="pillar-ico">🏆</div><div><h4>Pro-Level Knowledge</h4><p>Learn from a player who competed professionally in Germany.</p></div></div>
    </div>
  </div>
</section>'),
]);

// SEC 5: Camps
$sec5 = con('sec-camps', [
    hw('<section id="camps"><div class="eyebrow sr">Virtual Training Camps</div>'),
    heading('Join The<br><em style="color:#FF4500;font-style:normal;">Camp.</em>', 'h2', 'camps-headline sr d1'),
    te('<p class="sr d2" style="max-width:560px;font-size:15px;color:rgba(255,255,255,.4);line-height:1.8;">Our virtual training camps are online group sessions run live by Coach Coop. All skill levels welcome.</p>'),
    hw('<div class="camps-grid">
    <div class="camp-card sr"><span class="cc-ico">🎥</span><h3>Live Group Sessions</h3><p>Train live with a group of athletes. Coach Coop runs every session, keeps the energy high.</p><div class="cc-badge">📅 Scheduled Weekly</div></div>
    <div class="camp-card sr d1"><span class="cc-ico">📚</span><h3>Instructional Video Library</h3><p>Full access to coaching breakdown videos — ball handling, shooting, footwork, defense, and more.</p><div class="cc-badge">🎬 On-Demand Access</div></div>
    <div class="camp-card sr d2"><span class="cc-ico">💪</span><h3>Workouts &amp; Coaching Tips</h3><p>Weekly workout programs and pro coaching tips between sessions.</p><div class="cc-badge">📊 Updated Weekly</div></div>
  </div></section>'),
]);

// SEC 6: Benefits
$sec6 = con('sec-benefits', [
    hw('<section id="benefits"><div class="eyebrow sr">What You Get</div>'),
    heading('One Plan.<br><em style="color:#FF4500;font-style:normal;">Everything Included.</em>', 'h2', 'benefits-headline sr d1'),
    te('<p class="sr d2" style="max-width:480px;font-size:15px;color:rgba(255,255,255,.4);line-height:1.8;margin-top:16px;">Subscribers get access to the full training platform. All for $40/month.</p>'),
    hw('<div class="benefits-grid">
    <div class="bcard sr"><span class="bc-ico">🎥</span><h3>Instructional Videos</h3><p>Full library of coaching breakdowns. New videos added weekly.</p></div>
    <div class="bcard sr d1"><span class="bc-ico">💪</span><h3>Weekly Workouts</h3><p>Structured drill programs by Coach Coop for virtual training.</p></div>
    <div class="bcard sr d2"><span class="bc-ico">🏕️</span><h3>Virtual Training Camps</h3><p>Live group sessions — scheduled every week.</p></div>
    <div class="bcard sr d3"><span class="bc-ico">💡</span><h3>Pro Tips &amp; Advice</h3><p>Regular coaching tips from a pro who played in Germany.</p></div>
  </div></section>'),
]);

// SEC 7: How It Works
$sec7 = con('sec-how', [
    hw('<section id="how" class="sec"><div class="eyebrow sr">How It Works</div>'),
    heading('Simple.<br><em style="color:#FF4500;font-style:normal;">Effective. Online.</em>', 'h2', 'how-headline sr d1'),
    hw('<div class="how-grid">
    <div class="how-steps">
      <div class="how-step sr"><div class="hs-num">01</div><div class="hs-body"><h4>Subscribe for $40/Month</h4><p>Sign up online. Get instant access to the full platform.</p><div class="hs-tag">⚡ Instant Access</div></div></div>
      <div class="how-step sr d1"><div class="hs-num">02</div><div class="hs-body"><h4>Join Virtual Training Camps</h4><p>Attend scheduled live group sessions with Coach Coop online.</p><div class="hs-tag">🎥 Live Group Sessions</div></div></div>
      <div class="how-step sr d2"><div class="hs-num">03</div><div class="hs-body"><h4>Watch. Work. Improve.</h4><p>Access instructional videos anytime. Follow the weekly workout plan.</p><div class="hs-tag">📱 On Any Device</div></div></div>
      <div class="how-step sr d3"><div class="hs-num">04</div><div class="hs-body"><h4>Level Up Continuously</h4><p>New content, camps, and challenges every week.</p><div class="hs-tag">📈 Constant Growth</div></div></div>
    </div>
    <div class="how-photos sr d2">
      <div class="hp tall"><img src="'.$img['how1'].'" alt="Training"><div class="hp-overlay"></div><div class="hp-label">Group Camp</div></div>
      <div class="hp"><img src="'.$img['how2'].'" alt="Drills"><div class="hp-overlay"></div><div class="hp-label">Drill Work</div></div>
      <div class="hp"><img src="'.$img['how3'].'" alt="Skills"><div class="hp-overlay"></div><div class="hp-label">Skills</div></div>
    </div>
  </div></section>'),
]);

// SEC 8: Programs
$sec8 = con('sec-programs', [
    hw('<section id="programs" style="padding:0;">
  <div class="programs-top">
    <div class="sr"><div class="eyebrow">Skill Focus Areas</div>'),
    heading('What We<br><em style="color:#FF4500;font-style:normal;">Train.</em>', 'h2', 'programs-headline'),
    hw('</div><p class="programs-top-right sr d2">Every camp and video covers these skill areas. Subscribers get access to content and live group training across all categories.</p>
  </div>
  <div class="prog-list">
    <div class="prog-item sr"><div class="pi-num">01</div><div class="pi-body"><span class="pi-icon">🏀</span><h3>Ball Handling</h3><p>Crossovers, hesitations, behind-the-back, dribble combos.</p></div><div class="pi-tag"><div class="pi-pill">🎥 Video + Camp</div></div></div>
    <div class="prog-item sr d1"><div class="pi-num">02</div><div class="pi-body"><span class="pi-icon">🎯</span><h3>Shooting Mechanics</h3><p>Break down your form, footwork, and release.</p></div><div class="pi-tag"><div class="pi-pill">🎥 Video + Camp</div></div></div>
    <div class="prog-item sr d2"><div class="pi-num">03</div><div class="pi-body"><span class="pi-icon">⚡</span><h3>Footwork &amp; Speed</h3><p>Improve lateral quickness, first-step explosiveness.</p></div><div class="pi-tag"><div class="pi-pill">💪 Workout</div></div></div>
    <div class="prog-item sr"><div class="pi-num">04</div><div class="pi-body"><span class="pi-icon">🧠</span><h3>Basketball IQ</h3><p>Learn to read the game — spacing, rotations, decisions.</p></div><div class="pi-tag"><div class="pi-pill">🎥 Instructional</div></div></div>
    <div class="prog-item sr d1"><div class="pi-num">05</div><div class="pi-body"><span class="pi-icon">🛡️</span><h3>Defense &amp; Positioning</h3><p>On-ball defense, help-side positioning, closeouts.</p></div><div class="pi-tag"><div class="pi-pill">💪 Workout</div></div></div>
    <div class="prog-item sr d2"><div class="pi-num">06</div><div class="pi-body"><span class="pi-icon">🔥</span><h3>Finishing at the Rim</h3><p>Euro steps, floaters, contact finishes, reverse layups.</p></div><div class="pi-tag"><div class="pi-pill">🎥 Video + Camp</div></div></div>
  </div>
</section>'),
]);

// SEC 9: Photo Break
$sec9 = con('sec-photos', [
    hw('<div id="photo-break">
  <div class="pb-photo"><img src="'.$img['pb1'].'" alt="Training"><div class="pb-overlay"></div><div class="pb-text"><h3>Train Live.<br>Train Smart.</h3><p>Virtual group sessions with Coach Coop</p></div></div>
  <div class="pb-photo"><img src="'.$img['pb2'].'" alt="Drills"><div class="pb-overlay"></div><div class="pb-text"><h3>Elite<br>Drills</h3><p>Pro-designed workouts</p></div></div>
  <div class="pb-photo"><img src="'.$img['pb3'].'" alt="Skills"><div class="pb-overlay"></div><div class="pb-text"><h3>Real<br>Results</h3><p>200+ athletes improved</p></div></div>
</div>'),
]);

// SEC 10: Testimonials
$sec10 = con('sec-testimonials', [
    hw('<section id="testimonials" class="sec"><div class="eyebrow sr">Real Athletes</div>'),
    heading('They Joined.<br><em style="color:#FF4500;font-style:normal;">They Level\'d Up.</em>', 'h2', 'testi-headline sr d1'),
    hw('<div class="testi-grid" style="margin-top:64px;">
    <div class="tcard featured sr"><div><div class="tc-type">🏕️ Virtual Training Camp</div><div class="tc-stars">★★★★★</div><p class="tc-text">"I joined Coop\'s virtual training camp and it changed how I play. The group sessions are competitive and Coach Coop played professionally in Germany and it shows."</p></div><div class="tc-author"><div class="tc-av">MJ</div><div class="tc-info"><h5>Marcus J.</h5><span>High School PG · Virtual Camp Member</span></div></div></div>
    <div class="tside">
      <div class="tcard sr d1"><div class="tc-type">🎥 Instructional Videos</div><div class="tc-stars">★★★★★</div><p class="tc-text">The video library alone is worth $40/month. My shooting improved noticeably after following his form videos for just a few weeks.</p><div class="tc-author"><div class="tc-av">TK</div><div class="tc-info"><h5>Tyler K.</h5><span>College Player</span></div></div></div>
      <div class="tcard sr d2"><div class="tc-type">💪 Weekly Workouts</div><div class="tc-stars">★★★★★</div><p class="tc-text">My son follows the weekly workouts from our driveway. Coach Coop played pro basketball in Germany and is sharing that knowledge for $40/month. Highly recommend.</p><div class="tc-author"><div class="tc-av">RP</div><div class="tc-info"><h5>Rachel P.</h5><span>Parent · Camp Member</span></div></div></div>
      <div class="tcard sr d3"><div class="tc-type">🏕️ Group Sessions</div><div class="tc-stars">★★★★★</div><p class="tc-text">Training in the group camps keeps me locked in. Best $40 I spend every month.</p><div class="tc-author"><div class="tc-av">DW</div><div class="tc-info"><h5>DeShawn W.</h5><span>AAU Player</span></div></div></div>
    </div>
  </div></section>'),
]);

// SEC 11: Pricing
$sec11 = con('sec-pricing', [
    hw('<section id="pricing" class="sec"><div style="text-align:center;"><div class="eyebrow sr" style="justify-content:center;">Membership</div>'),
    heading('One Plan.<br><em style="color:#FF4500;font-style:normal;">Everything Included.</em>', 'h2', 'pricing-headline sr d1'),
    hw('<p class="sr d2" style="max-width:440px;margin:18px auto 0;font-size:15px;color:rgba(255,255,255,.4);line-height:1.8;text-align:center;">No tiers, no upsells. One subscription gets you the full platform.</p></div>
  <div class="single-plan-wrap sr d1" style="max-width:700px;margin:64px auto 0;">
    <div class="single-plan">
      <div class="sp-top">
        <div class="sp-label">Join Today</div><div class="sp-name">Coop B-Ball Camp</div>
        <div class="sp-price"><div class="sp-cur">$</div><div class="sp-num">40</div><div class="sp-mo">/ month</div></div>
        <div class="sp-tagline">Instant access · Virtual camps · Videos · Workouts</div>
      </div>
      <div class="sp-body">
        <ul class="sp-features">
          <li><div class="sp-chk">✓</div> Virtual Group Training Camps</li>
          <li><div class="sp-chk">✓</div> Full Instructional Video Library</li>
          <li><div class="sp-chk">✓</div> Weekly Workout Programs</li>
          <li><div class="sp-chk">✓</div> Pro Coaching Tips &amp; Advice</li>
          <li><div class="sp-chk">✓</div> Ball Handling Drills</li>
          <li><div class="sp-chk">✓</div> Shooting Mechanics Content</li>
          <li><div class="sp-chk">✓</div> Footwork &amp; Speed Workouts</li>
          <li><div class="sp-chk">✓</div> Basketball IQ Breakdowns</li>
          <li><div class="sp-chk">✓</div> New Content Added Every Week</li>
          <li><div class="sp-chk">✓</div> Email Access to Coach Coop</li>
        </ul>
        <a href="mailto:james@coopbballtraining.com" class="btn-sub">🏀 Join Now — $40/Month</a>
        <p class="sp-note">Email james@coopbballtraining.com to get started · Cancel anytime</p>
      </div>
    </div>
  </div>
</section>'),
]);

// SEC 12: CTA
$sec12 = con('sec-cta', [
    hw('<section id="cta">
  <div class="cta-bg" style="background-image:url(\''.$img['cta'].'\');"></div>
  <div class="cta-inner">
    <div class="eyebrow sr" style="justify-content:center;">Ready to Level Up?</div>'),
    heading('Your Court.<br><em style="color:#FF4500;font-style:normal;display:block;">Our Camp.</em>', 'h2', 'cta-headline sr d1'),
    te('<p class="cta-p sr d2">Join 200+ athletes training under a professional who played in Dortmund, Germany. $40/month — virtual camps, instructional videos, workouts and more.</p>'),
    hw('<div class="cta-btns sr d3">
      <a href="mailto:james@coopbballtraining.com" class="btn-primary" style="font-family:Barlow Condensed,sans-serif;font-size:14px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">🏀 Join for $40/Month</a>
      <a href="mailto:james@coopbballtraining.com" class="btn-ghost">✉️ Email Coach Coop</a>
    </div>
  </div>
</section>'),
]);

// SEC 13: Bio
$sec13 = con('sec-bio', [
    hw('<section id="bio"><div class="eyebrow sr">Coach Bio</div>
  <div class="bio-grid">
    <div class="bio-photo-col sr">
      <img src="'.$img['bio'].'" alt="Coach Coop">
      <div class="bio-badge"><div class="bio-badge-n">PRO</div><div class="bio-badge-l">Germany</div></div>
    </div>
    <div class="bio-text">'),
    heading('Meet<br><em style="color:#FF4500;font-style:normal;display:block;">Coach Coop.</em>', 'h3', 'bio-headline sr'),
    te('<p class="sr d1" style="font-size:16px;color:rgba(255,255,255,.55);line-height:1.85;font-weight:300;">As a player, Coop was known for his efficient shooting, long-range shooting ability, and one-on-one moves. He finished his college career as the school\'s only three-time All-American and was later inducted into the Hall of Fame.</p>
<p class="sr d2" style="font-size:16px;color:rgba(255,255,255,.55);line-height:1.85;font-weight:300;">Coop scored <strong style="color:#F8F8F6;font-weight:500;">2,037 points</strong> (2nd all-time) while shooting <strong style="color:#F8F8F6;font-weight:500;">54% from the field</strong> and <strong style="color:#F8F8F6;font-weight:500;">46% from the 3-point line</strong>.</p>
<p class="sr d2" style="font-size:16px;color:rgba(255,255,255,.55);line-height:1.85;font-weight:300;">After Wooster, Coop defied the odds as one of very few 6\'0" guards to play professionally in Dortmund, Germany.</p>'),
    hw('<div class="bio-stats sr d3">
        <div class="bs"><div class="bs-n">2,037</div><div class="bs-l">Career Points<br>2nd All-Time</div></div>
        <div class="bs"><div class="bs-n">46%</div><div class="bs-l">3-Point<br>Shooting</div></div>
        <div class="bs"><div class="bs-n">HOF</div><div class="bs-l">Hall of Fame<br>Inductee</div></div>
      </div>
      <div style="display:flex;flex-direction:column;gap:10px;margin-top:28px;" class="sr d3">
        <div class="ba"><div class="ba-ico">🎯</div><div class="ba-text">Known for <strong>efficient shooting</strong>, <strong>deep range</strong>, and elite <strong>one-on-one moves</strong></div></div>
        <div class="ba"><div class="ba-ico">🏆</div><div class="ba-text">The school\'s <strong>only three-time All-American</strong> and later <strong>Hall of Fame inductee</strong></div></div>
        <div class="ba"><div class="ba-ico">📊</div><div class="ba-text"><strong>2,037 career points</strong> (2nd all-time) with <strong>54% FG</strong> and <strong>46% from three</strong></div></div>
        <div class="ba"><div class="ba-ico">🌍</div><div class="ba-text">Defied the odds as a <strong>6\'0" guard</strong> and played professionally in <strong>Dortmund, Germany</strong></div></div>
      </div>
    </div>
  </div>
</section>'),
]);

// SEC 14: Footer
$sec14 = con('sec-footer', [
    hw('<footer>
  <div class="footer-top">
    <div class="fb">
      <img src="'.$img['logo'].'" alt="Coop B-Ball">
      <div class="fb-name">Coop B-Ball Training</div>
      <div class="fb-sub">Virtual Basketball Camps</div>
      <p>Pro-coached virtual training camps, instructional videos and weekly workouts for $40/month.</p>
      <div class="fb-socials"><a href="#" class="fb-s">📷</a><a href="#" class="fb-s">🎵</a><a href="#" class="fb-s">🐦</a><a href="#" class="fb-s">▶</a><a href="#" class="fb-s">💬</a></div>
    </div>
    <div class="fc">
      <h4>Navigate</h4>
      <ul><li><a href="#about">About Coop</a></li><li><a href="#benefits">What You Get</a></li><li><a href="#programs">Skill Areas</a></li><li><a href="#testimonials">Results</a></li><li><a href="#pricing">Join — $40/mo</a></li><li><a href="#bio">Coach Bio</a></li></ul>
    </div>
    <div class="fc">
      <h4>Contact</h4>
      <div class="fc-contacts">
        <div class="fcc"><div class="fcc-i">📧</div><div class="fcc-t"><small>Email</small><span>james@coopbballtraining.com</span></div></div>
        <div class="fcc"><div class="fcc-i">🌍</div><div class="fcc-t"><small>Platform</small><span>100% Online · Worldwide</span></div></div>
        <div class="fcc"><div class="fcc-i">🏕️</div><div class="fcc-t"><small>Format</small><span>Virtual Group Training Camps</span></div></div>
        <div class="fcc"><div class="fcc-i">💰</div><div class="fcc-t"><small>Price</small><span>$40 / month · Cancel Anytime</span></div></div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 <span>Coop B-Ball Training</span>. All rights reserved.</p>
    <div class="fbl"><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></div>
  </div>
</footer>'),
]);

// SEC 15: JavaScript
$sec15 = hw('<script>
var cr=document.getElementById("cr"),cr2=document.getElementById("cr2");
var mx=0,my=0,tx=0,ty=0;
document.addEventListener("mousemove",function(e){mx=e.clientX;my=e.clientY;cr.style.left=(mx-6)+"px";cr.style.top=(my-6)+"px";});
(function loop(){tx+=(mx-tx-20)*.13;ty+=(my-ty-20)*.13;cr2.style.left=tx+"px";cr2.style.top=ty+"px";requestAnimationFrame(loop);})();
document.querySelectorAll("a,button").forEach(function(el){el.addEventListener("mouseenter",function(){cr.style.transform="scale(2.5)";cr.style.opacity=".5";});el.addEventListener("mouseleave",function(){cr.style.transform="scale(1)";cr.style.opacity="1";});});
var vid=document.getElementById("coopVid"),ov=document.getElementById("vidOverlay");
if(vid){vid.muted=true;}
if(vid&&ov){ov.addEventListener("click",function(){if(vid.paused){vid.play();ov.style.opacity="0";ov.style.pointerEvents="none";}else{vid.pause();ov.style.opacity="1";ov.style.pointerEvents="all";}});vid.addEventListener("pause",function(){ov.style.opacity="1";ov.style.pointerEvents="all";});vid.addEventListener("ended",function(){ov.style.opacity="1";ov.style.pointerEvents="all";});}
window.addEventListener("scroll",function(){var n=document.getElementById("mainNav");var s=document.querySelector(".signup-bar");if(n)n.classList.toggle("bg",scrollY>50);var b=document.getElementById("btt");if(b)b.classList.toggle("show",scrollY>500);if(s&&n){var h=s.offsetHeight||56;n.style.top=scrollY>h?"0px":(h+"px");}});
var io=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting)e.target.classList.add("in");});},{threshold:0.08});
document.querySelectorAll(".sr").forEach(function(el){io.observe(el);});
function countUp(el){var t=parseInt(el.dataset.count);var em=el.querySelector("em").outerHTML;var n=0;var s=t/70;var tick=function(){n=Math.min(n+s,t);el.innerHTML=Math.floor(n)+em;if(n<t)requestAnimationFrame(tick);};tick();}
var cio=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){countUp(e.target);cio.unobserve(e.target);}});},{threshold:0.5});
document.querySelectorAll("[data-count]").forEach(function(el){cio.observe(el);});
</script>');

// ============================================================
// ASSEMBLE — each section is a top-level element
// ============================================================
$elementorData = [
    $sec0,  // CSS
    $sec1,  // Signup + Nav
    $sec2,  // Hero
    $sec3,  // Ticker
    $sec4,  // About
    $sec5,  // Camps
    $sec6,  // Benefits
    $sec7,  // How
    $sec8,  // Programs
    $sec9,  // Photos
    $sec10, // Testimonials
    $sec11, // Pricing
    $sec12, // CTA
    $sec13, // Bio
    $sec14, // Footer
    $sec15, // JS
];

// Wrap containers properly — each section needs to be inside a container
$rootElements = [];
foreach ($elementorData as $el) {
    if ($el['elType'] === 'widget') {
        // Widgets need a container wrapper
        $rootElements[] = [
            'id' => eid(), 'elType' => 'container',
            'settings' => ['content_width' => 'full-width', 'css_classes' => 'sec-widget-wrap'],
            'elements' => [$el],
        ];
    } else {
        $rootElements[] = $el;
    }
}

$json = json_encode($rootElements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// ============================================================
// UPDATE DB
// ============================================================
echo "Updating Elementor data...\n";
$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("SET SESSION sql_mode = ''");

$stmt = $db->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = 2 AND meta_key = '_elementor_data'");
$stmt->execute([$json]);
echo $stmt->rowCount() > 0 ? "✓ Updated (" . strlen($json) . " bytes)\n" : "✗ Failed\n";

// Clear CSS cache
$db->exec("UPDATE wp_postmeta SET meta_value = '' WHERE post_id = 2 AND meta_key = '_elementor_css'");
$cssDir = 'C:\\xampp\\htdocs\\zaid\\wp-content\\uploads\\elementor\\css';
if (is_dir($cssDir)) { array_map('unlink', glob("$cssDir/*.css")); }
echo "✓ CSS cache cleared\n\n";
echo "Visit: http://localhost/zaid\n";
echo "Admin: http://localhost/zaid/wp-admin\n";
