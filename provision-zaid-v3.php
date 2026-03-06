<?php
/**
 * Zaid V3 — Each section = 1 container + 1 complete HTML widget
 * No split DOM, no native heading/text widgets. Original CSS used directly.
 */

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'wp_zaid';
$wpPath = 'C:\\xampp\\htdocs\\zaid';

function eid() { return substr(bin2hex(random_bytes(4)), 0, 7); }

function con($cls, $html) {
    return [
        'id' => eid(), 'elType' => 'container',
        'settings' => ['content_width' => 'full', 'css_classes' => $cls . ' e-lazyloaded'],
        'elements' => [[
            'id' => eid(), 'elType' => 'widget', 'widgetType' => 'html',
            'settings' => ['html' => $html], 'elements' => [],
        ]],
    ];
}

$img = [
    'coach'  => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7352.jpg',
    'video'  => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7578.jpg',
    'vidSrc' => 'http://coopbballtraining.com/wp-content/uploads/2026/02/video-output-C3E31B30-E0A4-40FA-B7B1-CB853BD9B261-1.mov',
    'how1'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7582.jpg',
    'how2'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7124.jpg',
    'how3'   => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7577.jpg',
    'pb1'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7575.jpg',
    'pb2'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_7195.jpg',
    'pb3'    => 'http://coopbballtraining.com/wp-content/uploads/2026/03/IMG_1176.jpg',
    'bio'    => 'http://coopbballtraining.com/wp-content/uploads/2026/02/IMG_7482.jpg',
    'logo'   => 'http://coopbballtraining.com/wp-content/uploads/2026/02/image_-_2026-02-27T002144.133-removebg-preview.png',
];

// Extract original CSS from source HTML
$origHtml = file_get_contents(__DIR__ . '/convert-elementor.html');
preg_match('/<style>(.*?)<\/style>/s', $origHtml, $m);
$originalCss = $m[1] ?? '';

// Extract original JS from source HTML
preg_match('/<script>(.*?)<\/script>/s', $origHtml, $jsm);
$originalJs = $jsm[1] ?? '';

// ============================================================
// GLOBAL CSS
// ============================================================
$globalCss = <<<GCSS
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,300;0,400;0,600;0,700;0,900;1,700;1,900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* === ELEMENTOR RESET === */
body.elementor-template-canvas{margin:0!important;padding:0!important;background:#080808!important;color:#F8F8F6!important;font-family:'Barlow',sans-serif!important;overflow-x:hidden;cursor:none;}
.elementor,.elementor .e-con,.elementor .elementor-widget,.elementor .elementor-widget-html{font-family:'Barlow',sans-serif!important;color:inherit!important;}
.elementor .elementor-widget{margin-bottom:0!important;width:100%!important;}
.elementor .e-con{--padding-top:0px;--padding-right:0px;--padding-bottom:0px;--padding-left:0px;--margin-top:0px;--margin-bottom:0px;--gap:0px;--container-default-padding-top:0px;--container-default-padding-right:0px;--container-default-padding-bottom:0px;--container-default-padding-left:0px;--widgets-spacing:0px;--content-width:100%;padding:0!important;margin:0!important;gap:0!important;}
.elementor .e-con>.e-con-inner{gap:0!important;padding:0!important;}
.elementor .e-con-full{padding-block-start:0!important;padding-block-end:0!important;}
.elementor .elementor-widget-wrap{padding:0!important;}
.elementor .elementor-widget-container{padding:0;margin:0;}
.elementor p:last-child{margin-bottom:0;}
.elementor a{color:inherit;text-decoration:none;}
.elementor h1,.elementor h2,.elementor h3,.elementor h4,.elementor h5,.elementor h6{font-family:inherit;color:inherit;margin:0;padding:0;}
.elementor ul,.elementor ol{list-style:none;padding:0;margin:0;}
.elementor img{max-width:100%;height:auto;border:0;}
/* === ORIGINAL CSS === */
{$originalCss}
</style>
GCSS;

// ============================================================
// SECTION HTML — each is COMPLETE, no splitting
// ============================================================

$overlayHtml = <<<HTML
<div id="cr"></div><div id="cr2"></div><a href="#hero" id="btt">&uarr;</a>
<div class="signup-bar">
  <div class="sb-text"><div class="sb-dot"></div><span>&#127936; <strong>Now Enrolling &mdash; Virtual Training Camps</strong> &middot; Instructional videos, live group sessions, weekly workouts &amp; more for just <strong>\$22 to join</strong>. Cancel anytime.</span></div>
  <a href="#pricing" class="sb-btn">&#9889; Sign Up Now</a>
</div>
<nav id="mainNav">
  <a href="#hero" class="nav-logo"><img src="{$img['logo']}" alt="Coop B-Ball"><div class="nav-wordmark"><span class="nav-wm1">Coop B-Ball</span><span class="nav-wm2">Virtual Training</span></div></a>
  <ul class="nav-links"><li><a href="#about">About</a></li><li><a href="#benefits">What You Get</a></li><li><a href="#programs">Programs</a></li><li><a href="#testimonials">Results</a></li><li><a href="#pricing">Join &mdash; \$40/mo</a></li></ul>
  <div class="nav-right"><a href="mailto:james@coopbballtraining.com" class="btn-ghost" style="padding:9px 20px;font-size:12px;letter-spacing:1px;">&#9993;&#65039; Email Us</a><a href="#pricing" class="nav-cta">Join Now</a></div>
</nav>
HTML;

$heroHtml = <<<HTML
<section id="hero">
  <div class="hero-bg"></div>
  <div class="hero-content">
    <div class="hero-eyebrow"><div class="hero-dot"></div><span>Virtual Basketball Training Camps</span></div>
    <h1 class="hero-h1">Train<br><span>Elite.</span><br>Online.</h1>
    <p class="hero-sub">Join Coach Coop's virtual training camps. Live group sessions, instructional videos, weekly workouts and pro tips &mdash; all for \$40/month. Train from anywhere.</p>
    <div class="hero-actions">
      <a href="#pricing" class="btn-primary">&#127936; Join for \$40/Month</a>
      <a href="#benefits" class="btn-ghost">See What You Get</a>
    </div>
  </div>
  <div class="hero-scroll"><span>Scroll</span><div class="scroll-line"></div></div>
  <div class="hero-stats">
    <div class="hstat"><div class="hstat-n" data-count="200">0<em>+</em></div><div class="hstat-l">Athletes<br>Trained</div></div>
    <div class="hstat"><div class="hstat-n" data-count="98">0<em>%</em></div><div class="hstat-l">Satisfaction<br>Rate</div></div>
    <div class="hstat"><div class="hstat-n" data-count="5">0<em>+</em></div><div class="hstat-l">Years<br>Coaching</div></div>
  </div>
</section>
HTML;

$tickerHtml = <<<'HTML'
<div class="ticker"><div class="ticker-inner"><span>Virtual Training Camps</span><span class="dot">&#10022;</span><span>Group Sessions</span><span class="dot">&#10022;</span><span>Instructional Videos</span><span class="dot">&#10022;</span><span>Ball Handling</span><span class="dot">&#10022;</span><span>Shooting Mechanics</span><span class="dot">&#10022;</span><span>Weekly Workouts</span><span class="dot">&#10022;</span><span>$40 / Month</span><span class="dot">&#10022;</span><span>Train Anywhere</span><span class="dot">&#10022;</span><span>Virtual Training Camps</span><span class="dot">&#10022;</span><span>Group Sessions</span><span class="dot">&#10022;</span><span>Instructional Videos</span><span class="dot">&#10022;</span><span>Ball Handling</span><span class="dot">&#10022;</span><span>Shooting Mechanics</span><span class="dot">&#10022;</span><span>Weekly Workouts</span><span class="dot">&#10022;</span><span>$40 / Month</span><span class="dot">&#10022;</span><span>Train Anywhere</span><span class="dot">&#10022;</span></div></div>
HTML;

$aboutHtml = <<<HTML
<section id="about" style="min-height:auto;">
  <div class="about-left">
    <div class="about-photo sr">
      <img src="{$img['coach']}" alt="Coach Coop">
      <div class="about-photo-overlay"></div>
      <div class="about-photo-tag"><div class="apt-name">Coach Coop</div><div class="apt-title">Pro &middot; Dortmund, Germany &middot; Division III All-American</div></div>
    </div>
    <div class="about-video">
      <video id="coopVid" playsinline muted preload="metadata" poster="{$img['video']}">
        <source src="{$img['vidSrc']}" type="video/mp4">
        <source src="{$img['vidSrc']}" type="video/quicktime">
      </video>
      <div class="av-overlay" id="vidOverlay"><div class="av-play">&#9654;</div></div>
    </div>
  </div>
  <div class="about-text">
    <div class="eyebrow sr">About Coop B-Ball</div>
    <h2 class="headline sr d1" style="margin-bottom:28px;">Coached By<br><em>A Pro.</em><br><span style="font-size:.5em;color:rgba(255,255,255,.3);font-weight:400;letter-spacing:0;text-transform:none;font-family:'Barlow',sans-serif;display:block;margin-top:10px;line-height:1.5;">Virtual camps. Real results.</span></h2>
    <p class="sr d2">Coop B-Ball Training is a <strong>100% virtual basketball training platform</strong> built for serious athletes at every level. Through live group training camps, instructional videos, and personalized workouts, you get elite-level development for just \$40/month.</p>
    <div class="cred-block sr d2">
      <h4>&#127942; Coach Coop's Background</h4>
      <div class="cred-list">
        <div class="cred-item"><span class="cred-star">&#9733;</span><span>Played <strong>professionally in Dortmund, Germany</strong> &mdash; competed at the highest level of European professional basketball</span></div>
        <div class="cred-item"><span class="cred-star">&#9733;</span><span><strong>Division III All-American</strong> &mdash; recognized as one of the top college basketball players in the nation</span></div>
        <div class="cred-item"><span class="cred-star">&#9733;</span><span>Multiple <strong>All-Conference awards</strong> throughout his decorated college career</span></div>
        <div class="cred-item"><span class="cred-star">&#9733;</span><span>Trained <strong>200+ athletes online</strong> since 2019 through virtual camps and programs</span></div>
      </div>
    </div>
    <div class="about-pillars sr d3">
      <div class="pillar"><div class="pillar-ico">&#127909;</div><div><h4>Virtual Group Training Camps</h4><p>Live group sessions with Coach Coop &mdash; train together, get coached together, grow together.</p></div></div>
      <div class="pillar"><div class="pillar-ico">&#128241;</div><div><h4>Train From Anywhere</h4><p>Your driveway, local court, gym &mdash; all you need is a phone and a basketball.</p></div></div>
      <div class="pillar"><div class="pillar-ico">&#127942;</div><div><h4>Pro-Level Knowledge</h4><p>Learn from a player who competed professionally in Germany and was an NAIA All-American.</p></div></div>
    </div>
  </div>
</section>
HTML;

$campsHtml = <<<'HTML'
<section id="camps">
  <div class="eyebrow sr">Virtual Training Camps</div>
  <h2 class="headline sr d1" style="margin-bottom:16px;">Join The<br><em>Camp.</em></h2>
  <p class="sr d2" style="max-width:560px;font-size:15px;color:rgba(255,255,255,.4);line-height:1.8;margin-top:0;">Our virtual training camps are online group sessions run live by Coach Coop. All skill levels welcome &mdash; all you need is a subscription.</p>
  <div class="camps-grid">
    <div class="camp-card sr"><span class="cc-ico">&#127909;</span><h3>Live Group Sessions</h3><p>Train live with a group of athletes in a virtual camp setting. Coach Coop runs every session, keeps the energy high, and pushes everyone to their limit.</p><div class="cc-badge">&#128197; Scheduled Weekly</div></div>
    <div class="camp-card sr d1"><span class="cc-ico">&#128218;</span><h3>Instructional Video Library</h3><p>Full access to a growing library of coaching breakdown videos &mdash; ball handling, shooting form, footwork, defense, and more. Watch anytime, anywhere.</p><div class="cc-badge">&#127916; On-Demand Access</div></div>
    <div class="camp-card sr d2"><span class="cc-ico">&#128170;</span><h3>Workouts &amp; Coaching Tips</h3><p>Receive weekly workout programs and pro coaching tips from Coach Coop between sessions. Every drill is designed to create real, measurable improvement on the court.</p><div class="cc-badge">&#128202; Updated Weekly</div></div>
  </div>
</section>
HTML;

$benefitsHtml = <<<'HTML'
<section id="benefits">
  <div class="eyebrow sr">What You Get</div>
  <h2 class="headline sr d1">One Plan.<br><em>Everything Included.</em></h2>
  <p class="sr d2" style="max-width:480px;font-size:15px;color:rgba(255,255,255,.4);line-height:1.8;margin-top:16px;">Subscribers get access to the full training platform &mdash; instructional videos, workouts, live camps, tips and more. All for $40/month.</p>
  <div class="benefits-grid">
    <div class="bcard sr"><span class="bc-ico">&#127909;</span><h3>Instructional Videos</h3><p>Full library of coaching breakdowns covering every skill &mdash; ball handling, shooting, footwork, defense, and basketball IQ. New videos added weekly.</p></div>
    <div class="bcard sr d1"><span class="bc-ico">&#128170;</span><h3>Weekly Workouts</h3><p>Structured drill programs built by Coach Coop for virtual training. Know exactly what to work on every day to develop your game consistently.</p></div>
    <div class="bcard sr d2"><span class="bc-ico">&#9978;&#65039;</span><h3>Virtual Training Camps</h3><p>Live group sessions with Coach Coop online. Train alongside other athletes in a high-energy virtual camp environment &mdash; scheduled every week.</p></div>
    <div class="bcard sr d3"><span class="bc-ico">&#128161;</span><h3>Pro Tips &amp; Advice</h3><p>Regular coaching tips from a pro who played in Germany and was an NAIA All-American &mdash; mental game, skill development, reads, positioning and more.</p></div>
  </div>
</section>
HTML;

$howHtml = <<<HTML
<section id="how" class="sec">
  <div class="eyebrow sr">How It Works</div>
  <h2 class="headline sr d1">Simple.<br><em>Effective. Online.</em></h2>
  <div class="how-grid">
    <div class="how-steps">
      <div class="how-step sr"><div class="hs-num">01</div><div class="hs-body"><h4>Subscribe for \$40/Month</h4><p>Sign up online in seconds. Get instant access to the full platform &mdash; instructional videos, weekly workouts, live camp schedule, and coaching tips.</p><div class="hs-tag">&#9889; Instant Access</div></div></div>
      <div class="how-step sr d1"><div class="hs-num">02</div><div class="hs-body"><h4>Join Virtual Training Camps</h4><p>Attend scheduled live group sessions with Coach Coop online. Train with other motivated athletes, get coaching in real time, and push each other every session.</p><div class="hs-tag">&#127909; Live Group Sessions</div></div></div>
      <div class="how-step sr d2"><div class="hs-num">03</div><div class="hs-body"><h4>Watch. Work. Improve.</h4><p>Access instructional videos anytime between sessions. Follow the weekly workout plan and apply Coach Coop's pro tips directly to your game &mdash; on any court, anywhere.</p><div class="hs-tag">&#128241; On Any Device</div></div></div>
      <div class="how-step sr d3"><div class="hs-num">04</div><div class="hs-body"><h4>Level Up Continuously</h4><p>New content, new camps, and new challenges every week. Stay subscribed, put in the work, and let a professional European player coach you to the next level.</p><div class="hs-tag">&#128200; Constant Growth</div></div></div>
    </div>
    <div class="how-photos sr d2">
      <div class="hp tall"><img src="{$img['how1']}" alt="Training session"><div class="hp-overlay"></div><div class="hp-label">Group Camp</div></div>
      <div class="hp"><img src="{$img['how2']}" alt="Drill training"><div class="hp-overlay"></div><div class="hp-label">Drill Work</div></div>
      <div class="hp"><img src="{$img['how3']}" alt="Skills training"><div class="hp-overlay"></div><div class="hp-label">Skills</div></div>
    </div>
  </div>
</section>
HTML;

$programsHtml = <<<'HTML'
<section id="programs" style="padding:0;">
  <div class="programs-top">
    <div class="sr"><div class="eyebrow">Skill Focus Areas</div><h2 class="headline">What We<br><em>Train.</em></h2></div>
    <p class="programs-top-right sr d2">Every camp and video covers these skill areas. Subscribers get access to content and live group training across all categories.</p>
  </div>
  <div class="prog-list">
    <div class="prog-item sr"><div class="pi-num">01</div><div class="pi-body"><span class="pi-icon">&#127936;</span><h3>Ball Handling</h3><p>Crossovers, hesitations, behind-the-back, dribble combos &mdash; build a handle that breaks defenders at every level of play.</p></div><div class="pi-tag"><div class="pi-pill">&#127909; Video + Camp</div></div></div>
    <div class="prog-item sr d1"><div class="pi-num">02</div><div class="pi-body"><span class="pi-icon">&#127919;</span><h3>Shooting Mechanics</h3><p>Break down your form, footwork, and release from the ground up. Build a consistent, repeatable jumper you can trust under pressure.</p></div><div class="pi-tag"><div class="pi-pill">&#127909; Video + Camp</div></div></div>
    <div class="prog-item sr d2"><div class="pi-num">03</div><div class="pi-body"><span class="pi-icon">&#9889;</span><h3>Footwork &amp; Speed</h3><p>Get to your spots faster. Improve lateral quickness, first-step explosiveness, and movement efficiency on both ends of the floor.</p></div><div class="pi-tag"><div class="pi-pill">&#128170; Workout</div></div></div>
    <div class="prog-item sr"><div class="pi-num">04</div><div class="pi-body"><span class="pi-icon">&#129504;</span><h3>Basketball IQ</h3><p>Learn to read the game &mdash; spacing, defensive rotations, pick-and-roll coverage, and how pros make decisions in real time.</p></div><div class="pi-tag"><div class="pi-pill">&#127909; Instructional</div></div></div>
    <div class="prog-item sr d1"><div class="pi-num">05</div><div class="pi-body"><span class="pi-icon">&#128737;&#65039;</span><h3>Defense &amp; Positioning</h3><p>Become a stopper. On-ball defense, help-side positioning, closeouts &mdash; drills designed to make you a two-way threat.</p></div><div class="pi-tag"><div class="pi-pill">&#128170; Workout</div></div></div>
    <div class="prog-item sr d2"><div class="pi-num">06</div><div class="pi-body"><span class="pi-icon">&#128293;</span><h3>Finishing at the Rim</h3><p>Euro steps, floaters, contact finishes, reverse layups &mdash; master the art of getting buckets in traffic at any level of play.</p></div><div class="pi-tag"><div class="pi-pill">&#127909; Video + Camp</div></div></div>
  </div>
</section>
HTML;

$photosHtml = <<<HTML
<div id="photo-break">
  <div class="pb-photo"><img src="{$img['pb1']}" alt="Training"><div class="pb-overlay"></div><div class="pb-text"><h3>Train Live.<br>Train Smart.</h3><p>Virtual group sessions with Coach Coop</p></div></div>
  <div class="pb-photo"><img src="{$img['pb2']}" alt="Drills"><div class="pb-overlay"></div><div class="pb-text"><h3>Elite<br>Drills</h3><p>Pro-designed workouts</p></div></div>
  <div class="pb-photo"><img src="{$img['pb3']}" alt="Skills"><div class="pb-overlay"></div><div class="pb-text"><h3>Real<br>Results</h3><p>200+ athletes improved</p></div></div>
</div>
HTML;

$testimonialsHtml = <<<'HTML'
<section id="testimonials" class="sec">
  <div class="eyebrow sr">Real Athletes</div>
  <h2 class="headline sr d1" style="margin-bottom:50px;">They Joined.<br><em>They Level'd Up.</em></h2>
  <div class="testi-grid">
    <div class="tcard featured sr"><div><div class="tc-type">&#9978;&#65039; Virtual Training Camp</div><div class="tc-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p class="tc-text">"I joined Coop's virtual training camp and it changed how I play. The group sessions are competitive and push you hard &mdash; the instructional videos are something I go back to constantly. Coach Coop played professionally in Germany and it shows. His knowledge of the game is on a completely different level."</p></div><div class="tc-author"><div class="tc-av">MJ</div><div class="tc-info"><h5>Marcus J.</h5><span>High School PG &middot; Virtual Camp Member</span></div></div></div>
    <div class="tside">
      <div class="tcard sr d1"><div class="tc-type">&#127909; Instructional Videos</div><div class="tc-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p class="tc-text">The video library alone is worth $40/month. Coop breaks down every skill in a way that makes sense. My shooting improved noticeably after following his form videos for just a few weeks.</p><div class="tc-author"><div class="tc-av">TK</div><div class="tc-info"><h5>Tyler K.</h5><span>College Player</span></div></div></div>
      <div class="tcard sr d2"><div class="tc-type">&#128170; Weekly Workouts</div><div class="tc-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p class="tc-text">My son follows the weekly workouts from our driveway. The fact that Coach Coop played pro basketball in Germany and is sharing that knowledge for $40/month is unreal value. Highly recommend.</p><div class="tc-author"><div class="tc-av">RP</div><div class="tc-info"><h5>Rachel P.</h5><span>Parent &middot; Camp Member</span></div></div></div>
      <div class="tcard sr d3"><div class="tc-type">&#9978;&#65039; Group Sessions</div><div class="tc-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><p class="tc-text">Training in the group camps keeps me locked in. The energy is real and Coop's coaching background makes every session feel like prep for the next level. Best $40 I spend every month.</p><div class="tc-author"><div class="tc-av">DW</div><div class="tc-info"><h5>DeShawn W.</h5><span>AAU Player</span></div></div></div>
    </div>
  </div>
</section>
HTML;

$pricingHtml = <<<'HTML'
<section id="pricing" class="sec">
  <div style="text-align:center;margin-bottom:0;">
    <div class="eyebrow sr" style="justify-content:center;">Membership</div>
    <h2 class="headline sr d1">One Plan.<br><em>Everything Included.</em></h2>
    <p class="sr d2" style="max-width:440px;margin:18px auto 0;font-size:15px;color:rgba(255,255,255,.4);line-height:1.8;">No tiers, no upsells. One subscription gets you the full platform &mdash; virtual camps, videos, workouts, and tips.</p>
  </div>
  <div class="single-plan-wrap sr d1">
    <div class="single-plan">
      <div class="sp-top">
        <div class="sp-label">Join Today</div><div class="sp-name">Coop B-Ball Camp</div>
        <div class="sp-price"><div class="sp-cur">$</div><div class="sp-num">40</div><div class="sp-mo">/ month</div></div>
        <div class="sp-tagline">Instant access &middot; Virtual camps &middot; Videos &middot; Workouts</div>
      </div>
      <div class="sp-body">
        <ul class="sp-features">
          <li><div class="sp-chk">&#10003;</div> Virtual Group Training Camps</li>
          <li><div class="sp-chk">&#10003;</div> Full Instructional Video Library</li>
          <li><div class="sp-chk">&#10003;</div> Weekly Workout Programs</li>
          <li><div class="sp-chk">&#10003;</div> Pro Coaching Tips &amp; Advice</li>
          <li><div class="sp-chk">&#10003;</div> Ball Handling Drills</li>
          <li><div class="sp-chk">&#10003;</div> Shooting Mechanics Content</li>
          <li><div class="sp-chk">&#10003;</div> Footwork &amp; Speed Workouts</li>
          <li><div class="sp-chk">&#10003;</div> Basketball IQ Breakdowns</li>
          <li><div class="sp-chk">&#10003;</div> New Content Added Every Week</li>
          <li><div class="sp-chk">&#10003;</div> Email Access to Coach Coop</li>
        </ul>
        <a href="mailto:james@coopbballtraining.com" class="btn-sub">&#127936; Join Now &mdash; $40/Month</a>
        <p class="sp-note">Email james@coopbballtraining.com to get started &middot; Cancel anytime</p>
      </div>
    </div>
  </div>
</section>
HTML;

$ctaHtml = <<<'HTML'
<section id="cta">
  <div class="cta-bg"></div>
  <div class="cta-inner">
    <div class="eyebrow sr" style="justify-content:center;">Ready to Level Up?</div>
    <h2 class="cta-h sr d1">Your Court.<em>Our Camp.</em></h2>
    <p class="cta-p sr d2">Join 200+ athletes training under a professional who played in Dortmund, Germany and was a Division III All-American. $40/month &mdash; virtual camps, instructional videos, workouts and more.</p>
    <div class="cta-btns sr d3">
      <a href="mailto:james@coopbballtraining.com" class="btn-primary" style="font-family:'Barlow Condensed',sans-serif;font-size:14px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">&#127936; Join for $40/Month</a>
      <a href="mailto:james@coopbballtraining.com" class="btn-ghost">&#9993;&#65039; Email Coach Coop</a>
    </div>
  </div>
</section>
HTML;

$bioHtml = <<<HTML
<section id="bio">
  <div class="eyebrow sr">Coach Bio</div>
  <div class="bio-grid">
    <div class="bio-photo-col sr">
      <img src="{$img['bio']}" alt="Coach Coop">
      <div class="bio-badge"><div class="bio-badge-n">PRO</div><div class="bio-badge-l">Germany</div></div>
    </div>
    <div class="bio-text">
      <h3 class="sr">Meet<br><em>Coach Coop.</em></h3>
      <p class="sr d1">As a player, Coop was known for his efficient shooting, long-range shooting ability, and one-on-one moves. He finished his college career as the school's only three-time All-American and was later inducted into the Hall of Fame.</p>
      <p class="sr d2">Coop also scored <strong>2,037 points</strong> (2nd all-time in school history) while shooting <strong>54% from the field</strong> and <strong>46% from the 3-point line</strong>.</p>
      <p class="sr d2">After finishing his career at Wooster, Coop defied the odds as one of very few 6'0" guards to play professionally in Dortmund, Germany. Today, he is dedicated to helping athletes take their game to the next level.</p>
      <div class="bio-stats sr d3">
        <div class="bs"><div class="bs-n">2,037</div><div class="bs-l">Career Points<br>2nd All-Time</div></div>
        <div class="bs"><div class="bs-n">46%</div><div class="bs-l">3-Point<br>Shooting</div></div>
        <div class="bs"><div class="bs-n">HOF</div><div class="bs-l">Hall of Fame<br>Inductee</div></div>
      </div>
      <div class="bio-accolades sr d3">
        <div class="ba"><div class="ba-ico">&#127919;</div><div class="ba-text">Known for <strong>efficient shooting</strong>, <strong>deep range</strong>, and elite <strong>one-on-one moves</strong></div></div>
        <div class="ba"><div class="ba-ico">&#127942;</div><div class="ba-text">The school's <strong>only three-time All-American</strong> and later <strong>Hall of Fame inductee</strong></div></div>
        <div class="ba"><div class="ba-ico">&#128202;</div><div class="ba-text"><strong>2,037 career points</strong> (2nd all-time) with <strong>54% FG</strong> and <strong>46% from three</strong></div></div>
        <div class="ba"><div class="ba-ico">&#127758;</div><div class="ba-text">Defied the odds as a <strong>6'0" guard</strong> and played professionally in <strong>Dortmund, Germany</strong></div></div>
      </div>
    </div>
  </div>
</section>
HTML;

$footerHtml = <<<HTML
<footer>
  <div class="footer-top">
    <div class="fb">
      <img src="{$img['logo']}" alt="Coop B-Ball">
      <div class="fb-name">Coop B-Ball Training</div>
      <div class="fb-sub">Virtual Basketball Camps</div>
      <p>Pro-coached virtual training camps, instructional videos and weekly workouts for \$40/month. Train anywhere, improve everywhere.</p>
      <div class="fb-socials"><a href="#" class="fb-s">&#128247;</a><a href="#" class="fb-s">&#127925;</a><a href="#" class="fb-s">&#128038;</a><a href="#" class="fb-s">&#9654;</a><a href="#" class="fb-s">&#128172;</a></div>
    </div>
    <div class="fc">
      <h4>Navigate</h4>
      <ul><li><a href="#about">About Coop</a></li><li><a href="#benefits">What You Get</a></li><li><a href="#programs">Skill Areas</a></li><li><a href="#testimonials">Results</a></li><li><a href="#pricing">Join &mdash; \$40/mo</a></li><li><a href="#bio">Coach Bio</a></li></ul>
    </div>
    <div class="fc">
      <h4>Contact</h4>
      <div class="fc-contacts">
        <div class="fcc"><div class="fcc-i">&#128231;</div><div class="fcc-t"><small>Email</small><span>james@coopbballtraining.com</span></div></div>
        <div class="fcc"><div class="fcc-i">&#127758;</div><div class="fcc-t"><small>Platform</small><span>100% Online &middot; Worldwide</span></div></div>
        <div class="fcc"><div class="fcc-i">&#9978;&#65039;</div><div class="fcc-t"><small>Format</small><span>Virtual Group Training Camps</span></div></div>
        <div class="fcc"><div class="fcc-i">&#128176;</div><div class="fcc-t"><small>Price</small><span>\$40 / month &middot; Cancel Anytime</span></div></div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2026 <span>Coop B-Ball Training</span>. All rights reserved.</p>
    <div class="fbl"><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></div>
  </div>
</footer>
HTML;

$jsHtml = '<script>' . $originalJs . '</script>';

// ============================================================
// BUILD ELEMENTOR DATA
// ============================================================
$elementorData = [
    con('zaid-css', $globalCss),
    con('zaid-overlay', $overlayHtml),
    con('zaid-hero', $heroHtml),
    con('zaid-ticker', $tickerHtml),
    con('zaid-about', $aboutHtml),
    con('zaid-camps', $campsHtml),
    con('zaid-benefits', $benefitsHtml),
    con('zaid-how', $howHtml),
    con('zaid-programs', $programsHtml),
    con('zaid-photos', $photosHtml),
    con('zaid-testimonials', $testimonialsHtml),
    con('zaid-pricing', $pricingHtml),
    con('zaid-cta', $ctaHtml),
    con('zaid-bio', $bioHtml),
    con('zaid-footer', $footerHtml),
    con('zaid-js', $jsHtml),
];

$json = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// ============================================================
// UPDATE DATABASE
// ============================================================
echo "Connecting to database...\n";
$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("SET SESSION sql_mode = ''");

// Update Elementor data
$stmt = $db->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = 2 AND meta_key = '_elementor_data'");
$stmt->execute([$json]);
echo $stmt->rowCount() > 0 ? "OK Updated _elementor_data (" . strlen($json) . " bytes)\n" : "FAIL: no rows updated\n";

// Clear Elementor CSS cache
$db->exec("UPDATE wp_postmeta SET meta_value = '' WHERE post_id = 2 AND meta_key = '_elementor_css'");
$cssDir = $wpPath . '\\wp-content\\uploads\\elementor\\css';
if (is_dir($cssDir)) {
    foreach (glob("$cssDir/*.css") as $f) @unlink($f);
}
echo "OK Cleared CSS cache\n";

// Disable Elementor lazy-loading experiment
$db->exec("DELETE FROM wp_options WHERE option_name = 'elementor_experiment-e_lazyload'");
$db->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_experiment-e_lazyload', 'inactive', 'yes')");
echo "OK Disabled lazy-loading\n";

// Create mu-plugin to dequeue theme styles on Canvas pages
$muDir = $wpPath . '\\wp-content\\mu-plugins';
if (!is_dir($muDir)) mkdir($muDir, 0777, true);
$muCode = <<<'MU'
<?php
// Dequeue theme styles on Elementor Canvas pages
add_action('wp_enqueue_scripts', function() {
    if (!is_singular()) return;
    $tpl = get_page_template_slug();
    if ($tpl !== 'elementor_canvas') return;
    global $wp_styles;
    if (!$wp_styles) return;
    foreach ($wp_styles->registered as $handle => $style) {
        if (isset($style->src) && strpos($style->src, '/themes/') !== false) {
            wp_dequeue_style($handle);
        }
    }
}, 999);
MU;
file_put_contents("$muDir/canvas-clean.php", $muCode);
echo "OK Created mu-plugin canvas-clean.php\n";

echo "\nDone! Visit: http://localhost/zaid\n";
echo "Admin: http://localhost/zaid/wp-admin\n";
