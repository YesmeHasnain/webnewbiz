<?php

namespace App\Services\Layouts;

/**
 * Royal — Luxurious dark purple layout with glass effects.
 * Deep purple backgrounds, glass cards with backdrop-filter blur,
 * purple/violet gradient accents, custom cursor, scroll-reveal,
 * gradient text on hero, premium hotel/luxury aesthetic.
 */
class LayoutRoyal extends AbstractLayout
{
    public function slug(): string { return 'royal'; }
    public function name(): string { return 'Royal'; }
    public function description(): string { return 'Luxurious dark purple theme with glass effects for premium brands'; }
    public function bestFor(): array { return ['Hospitality', 'Hotel', 'Luxury Retail', 'Jewelry']; }
    public function isDark(): bool { return true; }

    public function colors(): array
    {
        return [
            'primary'   => '#7C3AED',
            'secondary' => '#6D28D9',
            'accent'    => '#A78BFA',
            'bg'        => '#0C0A1A',
            'surface'   => '#16132A',
            'surface2'  => '#1E1B36',
            'text'      => '#F5F3FF',
            'muted'     => 'rgba(245,243,255,0.5)',
            'border'    => 'rgba(245,243,255,0.08)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'DM Sans', 'body' => 'Inter'];
    }

    // ═══════════════════════════════════════════════════════════
    // GLOBAL CSS
    // ═══════════════════════════════════════════════════════════

    public function buildGlobalCss(): string
    {
        $c = $this->colors();
        $f = $this->fonts();
        $hf = str_replace(' ', '+', $f['heading']);
        $bf = str_replace(' ', '+', $f['body']);

        return <<<CSS
<link href="https://fonts.googleapis.com/css2?family={$hf}:wght@300;400;500;600;700;900&family={$bf}:wght@300;400;500;600;700&family=Cinzel:wght@400;600;700;900&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
<style>
:root{--royal-bg:{$c['bg']};--royal-surface:{$c['surface']};--royal-surface2:{$c['surface2']};--royal-text:{$c['text']};--royal-muted:{$c['muted']};--royal-border:{$c['border']};--royal-primary:{$c['primary']};--royal-secondary:{$c['secondary']};--royal-accent:{$c['accent']};}
body,body.elementor-template-canvas{background:var(--royal-bg);color:var(--royal-text);font-family:'{$f['body']}',sans-serif;overflow-x:hidden;margin:0;padding:0;}
.elementor-element,.elementor.elementor-2{font-family:'{$f['body']}',sans-serif;}
.elementor-widget{margin-bottom:0 !important;}
.e-con{--gap:0px;}
.e-con:not(.e-con--row)>.elementor-widget{width:100%;}
.e-con--row>.elementor-widget{width:auto;}

/* Custom Cursor (purple dot) */
#royal-cr{width:12px;height:12px;background:var(--royal-primary);border-radius:50%;position:fixed;top:0;left:0;z-index:99999;pointer-events:none;mix-blend-mode:screen;transition:transform .15s;box-shadow:0 0 20px rgba(124,58,237,.6);}
#royal-cr2{width:40px;height:40px;border:1px solid rgba(124,58,237,.4);border-radius:50%;position:fixed;top:0;left:0;z-index:99998;pointer-events:none;}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes roll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
@keyframes shimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes pulseGlow{0%,100%{box-shadow:0 0 20px rgba(124,58,237,.2)}50%{box-shadow:0 0 40px rgba(124,58,237,.5)}}
.sr{opacity:0;transform:translateY(36px);transition:opacity .85s ease,transform .85s ease;}
.sr.d1{transition-delay:.1s}.sr.d2{transition-delay:.2s}.sr.d3{transition-delay:.3s}.sr.d4{transition-delay:.45s}
.sr.in{opacity:1;transform:none;}

/* Gradient Text */
.royal-gradient-text{background:linear-gradient(135deg,#7C3AED 0%,#A78BFA 40%,#EC4899 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}

/* Eyebrow */
.eyebrow{display:inline-flex;align-items:center;gap:12px;font-size:11px;font-weight:600;letter-spacing:4px;text-transform:uppercase;color:var(--royal-accent);margin-bottom:20px;}
.eyebrow::before{content:'';width:28px;height:1px;background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent));}

/* Ticker */
.royal-ticker{overflow:hidden;white-space:nowrap;padding:14px 0;background:linear-gradient(90deg,var(--royal-primary),var(--royal-secondary),#EC4899);}
.royal-ticker-inner{display:inline-flex;animation:roll 28s linear infinite;}
.royal-ticker-inner span{font-family:'{$f['heading']}',sans-serif;font-size:14px;font-weight:700;letter-spacing:4px;text-transform:uppercase;color:rgba(255,255,255,.9);padding:0 40px;}
.royal-ticker-inner .dot{color:rgba(255,255,255,.3)!important;padding:0 4px!important;}

/* Gold Frame Hero */
.royal-gold-frame{position:absolute;top:30px;left:30px;right:30px;bottom:30px;border:1px solid rgba(212,175,55,.35);pointer-events:none;z-index:3;}
.royal-gold-frame::before,.royal-gold-frame::after{content:'';position:absolute;width:20px;height:20px;border-color:rgba(212,175,55,.6);border-style:solid;}
.royal-gold-frame::before{top:-1px;left:-1px;border-width:2px 0 0 2px;}
.royal-gold-frame::after{bottom:-1px;right:-1px;border-width:0 2px 2px 0;}
.royal-gold-corner-tr,.royal-gold-corner-bl{position:absolute;width:20px;height:20px;border-color:rgba(212,175,55,.6);border-style:solid;pointer-events:none;z-index:3;}
.royal-gold-corner-tr{top:29px;right:29px;border-width:2px 2px 0 0;}
.royal-gold-corner-bl{bottom:29px;left:29px;border-width:0 0 2px 2px;}

/* Awards Bar */
.royal-awards{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:0;padding:28px 20px;}
.royal-awards-item{display:flex;align-items:center;gap:10px;padding:0 30px;border-right:1px solid rgba(212,175,55,.25);white-space:nowrap;}
.royal-awards-item:last-child{border-right:none;}
.royal-awards-item span{font-family:'Cinzel',serif;font-size:13px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:rgba(212,175,55,.85);}
.royal-awards-item .award-star{color:rgba(212,175,55,.9);font-size:11px;letter-spacing:1px;}
@media(max-width:767px){.royal-awards{flex-direction:column;gap:16px;}.royal-awards-item{border-right:none;border-bottom:1px solid rgba(212,175,55,.15);padding:10px 0;}.royal-awards-item:last-child{border-bottom:none;}}

/* Amenity Card */
.royal-amenity{position:relative;overflow:hidden;border-radius:4px;min-height:420px;display:flex;flex-direction:column;justify-content:flex-end;}
.royal-amenity img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform 1.2s ease;}
.royal-amenity:hover img{transform:scale(1.06);}
.royal-amenity-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,15,.88) 0%,rgba(10,10,15,.3) 40%,transparent 100%);z-index:1;}
.royal-amenity-text{position:relative;z-index:2;padding:40px 36px;}
.royal-amenity-text h3{font-family:'Cinzel',serif;font-size:24px;font-weight:700;color:#F5F3FF;letter-spacing:1px;margin:0 0 10px 0;}
.royal-amenity-text p{font-family:'Lora',serif;font-size:14px;color:rgba(245,243,255,.55);line-height:1.7;margin:0;}
.royal-amenity-text .gold-line{width:40px;height:2px;background:rgba(212,175,55,.7);margin-bottom:16px;}

/* About Overlay Box */
.royal-about-overlay{position:relative;min-height:500px;display:flex;align-items:center;justify-content:center;}
.royal-about-overlay-bg{position:absolute;inset:0;z-index:0;}
.royal-about-overlay-bg img{width:100%;height:100%;object-fit:cover;}
.royal-about-overlay-box{position:relative;z-index:2;width:60%;max-width:720px;background:rgba(245,243,255,.92);padding:60px 56px;text-align:center;}
.royal-about-overlay-box h2{font-family:'Cinzel',serif;font-size:32px;font-weight:700;color:#0A0A0F;letter-spacing:1px;margin:0 0 8px 0;}
.royal-about-overlay-box .gold-accent{width:50px;height:2px;background:rgba(212,175,55,.8);margin:16px auto 24px auto;}
.royal-about-overlay-box p{font-family:'Lora',serif;font-size:15px;color:rgba(10,10,15,.65);line-height:1.85;margin:0;}
@media(max-width:767px){.royal-about-overlay-box{width:90%;padding:40px 28px;}.royal-about-overlay-box h2{font-size:24px;}}

/* Testimonial Single Quote */
.royal-single-quote{text-align:center;max-width:760px;margin:0 auto;}
.royal-quote-mark{font-family:'Cinzel',serif;font-size:72px;line-height:1;color:rgba(212,175,55,.6);margin-bottom:0;}
.royal-single-quote blockquote{font-family:'Lora',serif;font-size:22px;font-style:italic;line-height:1.9;color:rgba(245,243,255,.75);margin:0 0 32px 0;padding:0;}
.royal-single-quote .quote-author{font-family:'Cinzel',serif;font-size:15px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(212,175,55,.85);}
.royal-single-quote .quote-role{font-family:'Lora',serif;font-size:12px;color:rgba(245,243,255,.35);letter-spacing:1px;text-transform:uppercase;margin-top:6px;}
@media(max-width:767px){.royal-single-quote blockquote{font-size:17px;}}

/* Concierge CTA */
.royal-concierge{text-align:center;}
.royal-concierge h2{font-family:'Cinzel',serif;font-size:42px;font-weight:700;color:#F5F3FF;letter-spacing:2px;margin:0 0 16px 0;}
.royal-concierge p{font-family:'Lora',serif;font-size:16px;color:rgba(245,243,255,.45);line-height:1.8;max-width:520px;margin:0 auto 40px auto;}
.royal-concierge .gold-ornament{width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(212,175,55,.6),transparent);margin:0 auto 32px auto;}
@media(max-width:767px){.royal-concierge h2{font-size:28px;}}

/* Glass Card */
.royal-glass{background:rgba(22,19,42,.6);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(124,58,237,.12);border-radius:16px;transition:all .4s ease;}
.royal-glass:hover{background:rgba(30,27,54,.8);border-color:rgba(124,58,237,.25);transform:translateY(-6px);box-shadow:0 20px 60px rgba(124,58,237,.15);}

/* Service Card */
.royal-card{position:relative;overflow:hidden;background:rgba(22,19,42,.5);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(124,58,237,.1);border-radius:16px;transition:all .4s ease;}
.royal-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--royal-primary),var(--royal-accent),#EC4899);transform:scaleX(0);transform-origin:left;transition:transform .5s;}
.royal-card:hover{background:rgba(30,27,54,.7);border-color:rgba(124,58,237,.25);transform:translateY(-4px);box-shadow:0 24px 60px rgba(124,58,237,.2);}
.royal-card:hover::before{transform:scaleX(1);}

/* Benefit Card */
.royal-bcard{text-align:center;background:rgba(22,19,42,.4);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(124,58,237,.08);border-radius:16px;transition:all .4s ease;position:relative;overflow:hidden;}
.royal-bcard::after{content:'';position:absolute;inset:0;background:radial-gradient(circle at 50% 0%,rgba(124,58,237,.15),transparent 70%);opacity:0;transition:opacity .4s;}
.royal-bcard:hover{background:rgba(30,27,54,.6);border-color:rgba(167,139,250,.2);transform:translateY(-6px);}
.royal-bcard:hover::after{opacity:1;}

/* Testimonial Card */
.royal-tcard{border:1px solid var(--royal-border);border-radius:16px;background:rgba(22,19,42,.4);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);transition:all .4s ease;}
.royal-tcard:hover{border-color:rgba(124,58,237,.3);transform:translateY(-5px);box-shadow:0 20px 50px rgba(124,58,237,.15);}
.royal-tcard-feat{border-color:var(--royal-primary)!important;background:linear-gradient(135deg,rgba(124,58,237,.2),rgba(167,139,250,.1))!important;}
.royal-tcard-feat:hover{transform:translateY(-8px)!important;box-shadow:0 28px 70px rgba(124,58,237,.4);}

/* Stats */
.royal-stat{text-align:center;}
.royal-stat-n{font-family:'{$f['heading']}',sans-serif;font-size:56px;font-weight:900;background:linear-gradient(135deg,var(--royal-primary),var(--royal-accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;text-shadow:none;}
.royal-stat-l{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(245,243,255,.3);margin-top:5px;}

/* CTA pill badge */
.royal-badge{display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:5px 12px;border-radius:50px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);color:var(--royal-accent);}

/* Stars */
.royal-stars{color:var(--royal-accent);letter-spacing:2px;font-size:14px;margin-bottom:16px;}

/* Credential block */
.royal-cred{background:rgba(124,58,237,.06);border:1px solid rgba(124,58,237,.18);border-radius:12px;padding:24px 22px;margin:28px 0;}
.royal-cred h4{font-family:'{$f['heading']}',sans-serif;font-size:13px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--royal-accent);margin-bottom:14px;}
.royal-cred-item{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:rgba(245,243,255,.6);line-height:1.5;margin-bottom:9px;}
.royal-cred-item .star{color:var(--royal-accent);flex-shrink:0;font-size:12px;margin-top:2px;}

/* Pillar rows */
.royal-pillar{display:flex;align-items:center;gap:20px;padding:22px 24px;border-bottom:1px solid var(--royal-border);transition:all .3s;cursor:pointer;}
.royal-pillar:last-child{border-bottom:none;}
.royal-pillar:hover{background:rgba(124,58,237,.06);padding-left:32px;}
.royal-pillar-ico{width:44px;height:44px;min-width:44px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.royal-pillar h4{font-family:'{$f['heading']}',sans-serif;font-size:17px;font-weight:700;letter-spacing:.5px;color:var(--royal-text);margin-bottom:3px;}
.royal-pillar p{font-size:13px;color:rgba(245,243,255,.4);line-height:1.5;margin:0;}

/* Gallery photo hover */
.royal-photo{overflow:hidden;position:relative;border-radius:12px;}
.royal-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .7s ease;}
.royal-photo:hover img{transform:scale(1.06);}
.royal-pho{position:absolute;inset:0;background:linear-gradient(to top,rgba(12,10,26,.7) 0%,transparent 50%);opacity:0;transition:opacity .4s;}
.royal-photo:hover .royal-pho{opacity:1;}

/* Contact info block */
.royal-ci{display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;}
.royal-ci-i{width:34px;height:34px;min-width:34px;background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:14px;}
.royal-ci small{display:block;font-size:9px;color:rgba(245,243,255,.25);letter-spacing:2px;text-transform:uppercase;margin-bottom:2px;}
.royal-ci span{font-size:13px;color:rgba(245,243,255,.45);}

/* Back to top */
#royal-btt{position:fixed;bottom:28px;right:28px;width:46px;height:46px;background:linear-gradient(135deg,var(--royal-primary),var(--royal-secondary));color:#FFF;border-radius:12px;font-size:20px;display:flex;align-items:center;justify-content:center;text-decoration:none;z-index:500;opacity:0;transform:translateY(12px);pointer-events:none;transition:all .4s;box-shadow:0 8px 24px rgba(124,58,237,.4);}
#royal-btt.show{opacity:1;transform:translateY(0);pointer-events:all;}
#royal-btt:hover{transform:translateY(-4px)!important;box-shadow:0 12px 32px rgba(124,58,237,.6);}

/* Step style */
.royal-step{border-bottom:1px solid var(--royal-border);transition:all .3s;}
.royal-step:first-child{border-top:1px solid var(--royal-border);}

/* Pricing */
.royal-plan{border-radius:16px;overflow:hidden;box-shadow:0 24px 64px rgba(124,58,237,.25);background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(167,139,250,.1));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(124,58,237,.2);}
.sp-price{display:flex;align-items:flex-start;justify-content:center;gap:4px;margin-bottom:10px;}
.sp-cur{font-size:26px;font-weight:700;color:var(--royal-accent);margin-top:14px;}
.sp-num{font-family:'{$f['heading']}',sans-serif;font-size:108px;font-weight:900;line-height:1;background:linear-gradient(135deg,var(--royal-primary),var(--royal-accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;letter-spacing:-4px;}
.sp-mo{font-size:16px;color:rgba(245,243,255,.4);align-self:flex-end;padding-bottom:14px;}
.sp-features{list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:14px 40px;margin:0 0 40px 0;padding:0;}
.sp-features li{display:flex;align-items:center;gap:10px;font-size:15px;color:rgba(245,243,255,.65);}
.sp-chk{width:22px;height:22px;min-width:22px;border-radius:50%;background:rgba(124,58,237,.15);display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--royal-accent);}

/* Responsive */
@media(max-width:1100px){
  .royal-photo{min-height:200px;}.royal-ticker-inner span{font-size:12px;padding:0 24px;}
  .royal-card.e-con,.royal-bcard.e-con,.royal-photo.e-con{--width:48% !important;width:48% !important;}
}
@media(max-width:767px){
  .royal-card.e-con,.royal-bcard.e-con,.royal-stat.e-con,.royal-photo.e-con{--width:100% !important;width:100% !important;}
  .royal-tcard.e-con{--width:100% !important;width:100% !important;}
  .sp-features{grid-template-columns:1fr;}
}
{$this->woocommerceCss()}
</style>
CSS;
    }

    public function buildGlobalJs(): string
    {
        return <<<'JS'
<script>
(function(){
const cr=document.createElement('div'),cr2=document.createElement('div');
cr.id='royal-cr';cr2.id='royal-cr2';document.body.appendChild(cr);document.body.appendChild(cr2);
let mx=0,my=0,tx=0,ty=0;
document.addEventListener("mousemove",e=>{mx=e.clientX;my=e.clientY;cr.style.left=(mx-6)+"px";cr.style.top=(my-6)+"px";});
(function loop(){tx+=(mx-tx-20)*.13;ty+=(my-ty-20)*.13;cr2.style.left=tx+"px";cr2.style.top=ty+"px";requestAnimationFrame(loop);})();
document.querySelectorAll("a,button").forEach(el=>{el.addEventListener("mouseenter",()=>{cr.style.transform="scale(2.5)";cr.style.opacity=".5";});el.addEventListener("mouseleave",()=>{cr.style.transform="scale(1)";cr.style.opacity="1";});});
const btt=document.getElementById('royal-btt');
window.addEventListener("scroll",()=>{if(btt)btt.classList.toggle("show",scrollY>500);});
const o=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add("in");}),{threshold:.08});
document.querySelectorAll(".sr").forEach(el=>o.observe(el));
function countUp(el){const t=parseInt(el.dataset.count);let n=0;const s=t/60;const tick=()=>{n=Math.min(n+s,t);el.textContent=Math.floor(n)+(el.dataset.suffix||'');if(n<t)requestAnimationFrame(tick);};tick();}
const co=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting){countUp(e.target);co.unobserve(e.target);}}),{threshold:.5});
document.querySelectorAll("[data-count]").forEach(el=>co.observe(el));
})();
</script>
JS;
    }

    // ═══════════════════════════════════════════════════════════
    // HOME PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildHomePage(array $c, array $img): array
    {
        $siteName = $c['site_name'] ?? 'Business Name';
        $heroTitle = $c['hero_title'] ?? 'Exquisite Luxury. Redefined.';
        $heroSub = $c['hero_subtitle'] ?? 'Experience unparalleled elegance and sophistication with our premium services tailored for discerning clientele.';
        $heroCta = $c['hero_cta'] ?? 'Book Your Stay';
        $heroCtaUrl = $c['hero_cta_url'] ?? '#contact';

        $aboutTitle = $c['about_title'] ?? 'Our Heritage';
        $aboutText = $c['about_text'] ?? 'A legacy of excellence built on decades of unwavering commitment to luxury and refinement. Every detail is meticulously crafted to exceed the expectations of our distinguished guests.';

        $services = $c['services'] ?? [
            ['icon' => "\u{2728}", 'title' => 'Luxury Suites', 'desc' => 'Opulent accommodations with panoramic views, bespoke furnishings, and round-the-clock concierge service.'],
            ['icon' => "\u{1F451}", 'title' => 'Fine Dining', 'desc' => 'World-class gastronomy from Michelin-starred chefs using the finest seasonal ingredients.'],
            ['icon' => "\u{1F48E}", 'title' => 'Spa & Wellness', 'desc' => 'Rejuvenating treatments in a serene sanctuary designed for ultimate relaxation and renewal.'],
            ['icon' => "\u{1F31F}", 'title' => 'Private Events', 'desc' => 'Bespoke celebrations and gatherings in breathtaking venues with impeccable attention to detail.'],
        ];

        $testimonials = $c['testimonials'] ?? [
            ['quote' => 'An extraordinary experience from start to finish. The attention to detail and level of service was truly exceptional in every regard.', 'name' => 'Victoria Harrington', 'role' => 'VIP Guest', 'initials' => 'VH'],
        ];

        $heroImg = $img['hero'] ?? '';
        $aboutImg = $img['about'] ?? '';
        $galleryImgs = [
            $img['gallery1'] ?? '',
            $img['gallery2'] ?? '',
            $img['gallery3'] ?? ($img['services'] ?? ''),
            $img['gallery4'] ?? ($img['feature'] ?? ''),
        ];

        $sections = [];

        // ═══════════════════════════════════════════════════════════
        // 1. HERO — Full-screen cinematic with gold picture frame
        // ═══════════════════════════════════════════════════════════
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'flex_align_items' => 'center',
            'padding' => self::pad(0),
            'min_height' => ['size' => 100, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_image' => ['url' => $heroImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'hero',
            'custom_css' => "selector{position:relative;overflow:hidden;}
selector::before{content:'';position:absolute;inset:0;background:rgba(10,10,15,.65);z-index:0;}
selector>.e-con-inner,selector>.elementor-widget{position:relative;z-index:2;}",
        ], [
            // Gold picture frame (inset border 30px from edges)
            self::html('<div class="royal-gold-frame"></div><div class="royal-gold-corner-tr"></div><div class="royal-gold-corner-bl"></div>'),

            // Centered hero content
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'flex_justify_content' => 'center',
                'padding' => self::pad(60, 40),
                'padding_mobile' => self::pad(40, 20),
            ], [
                // Eyebrow with gold accent
                self::html('<div style="text-align:center;opacity:0;animation:fadeUp .7s ease .2s forwards;"><span style="font-family:\'Cinzel\',serif;font-size:11px;font-weight:600;letter-spacing:6px;text-transform:uppercase;color:rgba(212,175,55,.8);">' . e($c['hero_eyebrow'] ?? 'Welcome to ' . $siteName) . '</span></div>'),

                // Cinematic heading — centered serif
                self::html('<h1 style="font-family:\'Cinzel\',serif;font-size:clamp(36px,6vw,80px);font-weight:700;line-height:1.1;text-transform:uppercase;letter-spacing:4px;color:#F5F3FF;text-align:center;margin:28px 0 24px 0;max-width:800px;opacity:0;animation:fadeUp .9s ease .35s forwards;">' . e($heroTitle) . '</h1>'),

                // Gold ornamental line
                self::html('<div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(212,175,55,.7),transparent);margin:0 auto 24px auto;opacity:0;animation:fadeIn 1s ease .5s forwards;"></div>'),

                // Subtitle — centered Lora italic
                self::html('<p style="font-family:\'Lora\',serif;font-size:17px;font-style:italic;font-weight:400;line-height:1.8;color:rgba(245,243,255,.55);text-align:center;max-width:520px;margin:0 auto 40px auto;opacity:0;animation:fadeUp .8s ease .55s forwards;">' . e($heroSub) . '</p>'),

                // Single luxurious gold CTA
                self::button($heroCta, $heroCtaUrl, [
                    'button_type' => 'default',
                    'background_color' => 'rgba(212,175,55,.9)',
                    'button_text_color' => '#0A0A0F',
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Cinzel',
                    'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_letter_spacing' => ['size' => 4, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_radius' => self::radius(0),
                    'button_padding' => self::pad(18, 52),
                    'button_background_hover_color' => 'rgba(212,175,55,1)',
                    'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .7s forwards;} selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 12px 40px rgba(212,175,55,.35);}',
                ]),
            ]),
        ]);

        // ═══════════════════════════════════════════════════════════
        // 2. AWARDS BAR — Gold accolades on dark surface
        // ═══════════════════════════════════════════════════════════
        $awards = $c['awards'] ?? [
            ['star' => true, 'text' => '5-Star Hotel'],
            ['star' => false, 'text' => 'Forbes Travel Guide'],
            ['star' => false, 'text' => 'Best Luxury ' . date('Y')],
            ['star' => false, 'text' => 'TripAdvisor Excellence'],
            ['star' => false, 'text' => 'Cond&eacute; Nast Selection'],
        ];

        $awardsHtml = '';
        foreach ($awards as $award) {
            $starHtml = ($award['star'] ?? false) ? '<span class="award-star">&starf;&starf;&starf;&starf;&starf;</span>' : '';
            $awardsHtml .= '<div class="royal-awards-item">' . $starHtml . '<span>' . ($award['text'] ?? '') . '</span></div>';
        }

        $sections[] = self::container([
            'content_width' => 'full',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
            'custom_css' => 'selector{border-top:1px solid rgba(212,175,55,.12);border-bottom:1px solid rgba(212,175,55,.12);}',
        ], [
            self::html('<div class="royal-awards sr">' . $awardsHtml . '</div>'),
        ]);

        // ═══════════════════════════════════════════════════════════
        // 3. AMENITIES / SERVICES — Large 2-column image cards
        // ═══════════════════════════════════════════════════════════
        $amenityCards = [];
        $galleryIdx = 0;
        foreach (array_slice($services, 0, 4) as $i => $svc) {
            $cardImg = $galleryImgs[$galleryIdx] ?? $heroImg;
            $galleryIdx = ($galleryIdx + 1) % max(count(array_filter($galleryImgs)), 1);

            $amenityCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'width' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(0),
                'css_classes' => 'royal-amenity sr d' . ($i + 1),
            ], [
                self::html('<img src="' . e($cardImg) . '" alt="' . e($svc['title']) . '" loading="lazy"><div class="royal-amenity-overlay"></div><div class="royal-amenity-text"><div class="gold-line"></div><h3>' . e($svc['title']) . '</h3><p>' . e($svc['desc']) . '</p></div>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                '_margin' => self::margin(0, 0, 56, 0),
            ], [
                self::html('<span style="font-family:\'Cinzel\',serif;font-size:11px;font-weight:600;letter-spacing:6px;text-transform:uppercase;color:rgba(212,175,55,.7);display:block;text-align:center;margin-bottom:16px;">' . e($c['services_eyebrow'] ?? 'Amenities & Services') . '</span>'),
                self::heading($c['services_title'] ?? 'Curated Experiences', 'h2', [
                    'align' => 'center',
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Cinzel',
                    'typography_font_weight' => '700',
                    'typography_letter_spacing' => ['size' => 2, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
                ] + self::responsiveSize(42, 34, 28)),
                self::html('<div style="width:50px;height:1px;background:linear-gradient(90deg,transparent,rgba(212,175,55,.6),transparent);margin:16px auto 0 auto;"></div>'),
            ]),
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
                'flex_justify_content' => 'center',
            ], $amenityCards),
        ], 'services');

        // ═══════════════════════════════════════════════════════════
        // 4. ABOUT — Full-width bg image with overlaid text box
        // ═══════════════════════════════════════════════════════════
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'flex_justify_content' => 'center',
            'min_height' => ['size' => 500, 'unit' => 'px', 'sizes' => []],
            'padding' => self::pad(80, 40),
            'padding_mobile' => self::pad(40, 15),
            'background_background' => 'classic',
            'background_image' => ['url' => $aboutImg, 'id' => ''],
            'background_position' => 'center center',
            'background_size' => 'cover',
            '_element_id' => 'about',
            'custom_css' => 'selector{position:relative;}',
        ], [
            self::html('<div class="royal-about-overlay-box sr">'
                . '<h2>' . e($aboutTitle) . '</h2>'
                . '<div class="gold-accent"></div>'
                . '<p>' . e($aboutText) . '</p>'
                . '</div>'),
        ]);

        // ═══════════════════════════════════════════════════════════
        // 5. TESTIMONIAL — Single large centered quote
        // ═══════════════════════════════════════════════════════════
        $featTest = $testimonials[0] ?? $testimonials[array_key_first($testimonials)];

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
            'padding' => self::pad(120, 40),
            'padding_mobile' => self::pad(80, 20),
        ], [
            self::html('<div class="royal-single-quote sr">'
                . '<div class="royal-quote-mark">&ldquo;</div>'
                . '<blockquote>' . e($featTest['quote']) . '</blockquote>'
                . '<div class="quote-author">' . e($featTest['name']) . '</div>'
                . '<div class="quote-role">' . e($featTest['role']) . '</div>'
                . '</div>'),
        ], 'testimonials');

        // ═══════════════════════════════════════════════════════════
        // 6. CONCIERGE CTA — Elegant dark section with gold
        // ═══════════════════════════════════════════════════════════
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
            'padding' => self::pad(120, 40),
            'padding_mobile' => self::pad(80, 20),
            '_element_id' => 'cta',
            'custom_css' => 'selector{border-top:1px solid rgba(212,175,55,.1);}',
        ], [
            self::html('<div class="royal-concierge sr">'
                . '<div class="gold-ornament"></div>'
                . '<h2>' . e($c['cta_title'] ?? 'Experience the Extraordinary') . '</h2>'
                . '<p>' . e($c['cta_text'] ?? 'Allow our dedicated team to craft an unforgettable experience tailored exclusively for you.') . '</p>'
                . '</div>'),
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'content_width' => 'full',
                'css_classes' => 'sr d2',
            ], [
                self::button($c['cta_button'] ?? 'Reserve Your Experience', '#contact', [
                    'button_type' => 'default',
                    'background_color' => 'rgba(212,175,55,.9)',
                    'button_text_color' => '#0A0A0F',
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Cinzel',
                    'typography_font_size' => ['size' => 12, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    'typography_letter_spacing' => ['size' => 4, 'unit' => 'px'],
                    'typography_text_transform' => 'uppercase',
                    'border_radius' => self::radius(0),
                    'button_padding' => self::pad(18, 52),
                    'button_background_hover_color' => 'rgba(212,175,55,1)',
                    'custom_css' => 'selector .elementor-button:hover{transform:translateY(-3px);box-shadow:0 12px 40px rgba(212,175,55,.35);}',
                ]),
            ]),
        ]);

        // Back to top
        $sections[] = self::html('<a href="#hero" id="royal-btt">&uarr;</a>');

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // ABOUT PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildAboutPage(array $c, array $img): array
    {
        $sections = [];

        // Hero banner
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('About Us'),
            $this->headline($c['about_title'] ?? 'Our Legacy.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['about_text'] ?? 'A tradition of excellence and luxury since our founding.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Two-col about
        $sections[] = self::twoCol(
            [self::image($img['about'] ?? '', [
                'custom_css' => 'selector img{width:100%;min-height:400px;object-fit:cover;border-radius:16px;}',
            ])],
            [
                $this->eyebrow('Who We Are'),
                $this->headline($c['about_subtitle'] ?? 'Heritage Meets Innovation.', 'h2', [
                    'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                ]),
                $this->bodyText($c['about_text'] ?? 'We are custodians of a rich legacy, dedicated to providing unparalleled luxury.'),
                $this->bodyText($c['about_text2'] ?? 'With decades of expertise, we craft experiences that resonate with elegance and refinement.'),
                $this->ctaButton($c['about_cta'] ?? 'Our Services', '#services', [
                    '_margin' => self::margin(20, 0, 0, 0),
                    'background_color' => 'transparent',
                    'custom_css' => 'selector .elementor-button{background:linear-gradient(135deg,#7C3AED,#6D28D9)!important;border-radius:12px!important;}',
                ]),
            ],
            50,
            ['padding' => self::pad(100, 64)],
            ['css_classes' => 'sr'],
            ['css_classes' => 'sr d2', 'flex_justify_content' => 'center']
        );

        // Stats
        $stats = $c['stats'] ?? [
            ['number' => '500', 'suffix' => '+', 'label' => 'Guests Served'],
            ['number' => '98', 'suffix' => '%', 'label' => 'Satisfaction'],
            ['number' => '25', 'suffix' => '+', 'label' => 'Years'],
        ];
        $statEls = [];
        foreach ($stats as $i => $s) {
            $statEls[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => round(100 / count($stats), 2), 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(50, 20),
                'background_background' => 'classic',
                'background_color' => $this->colors()['surface'],
                'css_classes' => 'royal-stat sr d' . ($i + 1),
            ], [
                self::html('<div class="royal-stat-n" data-count="' . $s['number'] . '" data-suffix="' . ($s['suffix'] ?? '') . '">' . $s['number'] . ($s['suffix'] ?? '') . '</div>'),
                self::html('<div class="royal-stat-l">' . e($s['label']) . '</div>'),
            ]);
        }
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'row',
            'flex_direction_mobile' => 'column',
            'flex_wrap' => 'wrap',
            'flex_gap' => ['size' => 2, 'unit' => 'px', 'column' => '2', 'row' => '2'],
            'padding' => self::pad(0),
        ], $statEls);

        // Values section
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
        ], [
            $this->eyebrow('Our Values'),
            $this->headline($c['values_title'] ?? 'What Defines Us.', 'h2'),
            $this->bodyText($c['values_text'] ?? 'The guiding principles behind every luxury experience we create.', [
                '_margin' => self::margin(0, 0, 50, 0),
                'custom_css' => 'selector{max-width:500px;}',
            ]),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // SERVICES PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildServicesPage(array $c, array $img): array
    {
        $sections = [];

        // Hero banner
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Our Services'),
            $this->headline($c['services_title'] ?? 'What We Offer.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['services_subtitle'] ?? 'Comprehensive luxury solutions for the most discerning clientele.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:560px;}',
            ]),
        ]);

        // Service cards
        $services = $c['services'] ?? [
            ['icon' => "\u{2728}", 'title' => 'Premium Experience', 'desc' => 'Immersive luxury experiences designed to delight.'],
            ['icon' => "\u{1F451}", 'title' => 'Bespoke Services', 'desc' => 'Tailored exclusively for your preferences.'],
            ['icon' => "\u{1F48E}", 'title' => 'Elite Collection', 'desc' => 'Curated selections from around the world.'],
        ];

        $cards = [];
        foreach ($services as $i => $svc) {
            $cards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => self::pad(40, 32),
                'css_classes' => 'royal-card sr d' . min($i + 1, 4),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:18px;">' . ($svc['icon'] ?? "\u{2728}") . '</span>'),
                self::heading($svc['title'], 'h3', [
                    'title_color' => $this->colors()['text'],
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 26, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 12, 0),
                ]),
                $this->bodyText($svc['desc']),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::cardGrid($cards, min(count($cards), 3), [
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
            ]),
        ], 'services-grid');

        // CTA
        $sections[] = $this->section([
            'flex_align_items' => 'center',
            'custom_css' => 'selector{background:linear-gradient(135deg,#7C3AED,#6D28D9,#EC4899)!important;}',
        ], [
            $this->headline($c['cta_title'] ?? 'Ready to Begin?', 'h2', [
                'title_color' => '#FFFFFF',
                'align' => 'center',
            ]),
            $this->bodyText($c['cta_text'] ?? 'Contact us today for an exclusive consultation.', [
                'align' => 'center',
                'text_color' => 'rgba(255,255,255,0.7)',
                '_margin' => self::margin(0, 0, 30, 0),
            ]),
            self::button($c['cta_button'] ?? 'Contact Us', '#contact', [
                'background_color' => '#FFFFFF',
                'button_text_color' => '#7C3AED',
                'typography_typography' => 'custom',
                'typography_font_family' => $this->fonts()['heading'],
                'typography_font_size' => ['size' => 14, 'unit' => 'px'],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
                'border_radius' => self::radius(12),
                'button_padding' => self::pad(16, 44),
            ]),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // PORTFOLIO PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildPortfolioPage(array $c, array $img): array
    {
        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Portfolio'),
            $this->headline($c['portfolio_title'] ?? 'Our Collection.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
        ]);

        // Gallery grid
        $galleryImgs = array_filter([
            $img['hero'] ?? '', $img['about'] ?? '',
            $img['gallery1'] ?? '', $img['gallery2'] ?? '',
            $img['services'] ?? '', $img['team'] ?? '',
        ]);

        $photoEls = [];
        foreach (array_slice($galleryImgs, 0, 6) as $i => $url) {
            if (!$url) continue;
            $photoEls[] = self::container([
                'content_width' => 'full',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'css_classes' => 'royal-photo sr d' . min($i + 1, 4),
                'custom_css' => 'selector{min-height:300px;}',
            ], [
                self::image($url, [
                    'custom_css' => 'selector img{width:100%;height:300px;object-fit:cover;border-radius:12px;}',
                ]),
            ]);
        }

        if ($photoEls) {
            $sections[] = $this->section([
                'background_background' => 'classic',
                'background_color' => $this->colors()['bg'],
            ], [
                self::container([
                    'flex_direction' => 'row',
                    'flex_direction_mobile' => 'column',
                    'flex_wrap' => 'wrap',
                    'content_width' => 'full',
                    'flex_gap' => ['size' => 16, 'unit' => 'px', 'column' => '16', 'row' => '16'],
                ], $photoEls),
            ]);
        }

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // CONTACT PAGE
    // ═══════════════════════════════════════════════════════════

    public function buildContactPage(array $c, array $img): array
    {
        $sections = [];

        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'padding' => self::pad(140, 64, 100, 64),
            'padding_mobile' => self::pad(100, 20, 60, 20),
            'padding_tablet' => self::pad(120, 40, 80, 40),
            'min_height' => ['size' => 50, 'unit' => 'vh', 'sizes' => []],
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface'],
        ], [
            $this->eyebrow('Contact'),
            $this->headline($c['contact_title'] ?? 'Get In Touch.', 'h1', array_merge(
                self::responsiveSize(100, 64, 42),
                ['custom_css' => 'selector{opacity:0;animation:fadeUp .9s ease .2s forwards;}']
            )),
            $this->bodyText($c['contact_subtitle'] ?? 'We would be delighted to hear from you.', [
                'custom_css' => 'selector{opacity:0;animation:fadeUp .8s ease .4s forwards;max-width:500px;}',
            ]),
        ]);

        // Contact info cards (glass style)
        $contactInfo = $c['contact_info'] ?? [
            ['icon' => "\u{1F4E7}", 'label' => 'Email', 'value' => $c['email'] ?? 'concierge@example.com'],
            ['icon' => "\u{1F4DE}", 'label' => 'Phone', 'value' => $c['phone'] ?? '(555) 123-4567'],
            ['icon' => "\u{1F4CD}", 'label' => 'Location', 'value' => $c['address'] ?? '123 Luxury Ave, City, State'],
        ];

        $infoCards = [];
        foreach ($contactInfo as $i => $info) {
            $infoCards[] = self::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'width' => ['size' => 31, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
                'padding' => self::pad(40, 28),
                'css_classes' => 'royal-bcard sr d' . ($i + 1),
            ], [
                self::textEditor('<span style="font-size:36px;display:block;margin-bottom:14px;">' . $info['icon'] . '</span>'),
                self::heading($info['label'], 'h4', [
                    'title_color' => $this->colors()['text'],
                    'align' => 'center',
                    'typography_typography' => 'custom',
                    'typography_font_family' => $this->fonts()['heading'],
                    'typography_font_size' => ['size' => 18, 'unit' => 'px'],
                    'typography_font_weight' => '700',
                    '_margin' => self::margin(0, 0, 8, 0),
                ]),
                self::textEditor('<p style="text-align:center;color:' . $this->colors()['muted'] . ';font-size:14px;">' . e($info['value']) . '</p>'),
            ]);
        }

        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['bg'],
        ], [
            self::container([
                'flex_direction' => 'row',
                'flex_direction_mobile' => 'column',
                'flex_wrap' => 'wrap',
                'content_width' => 'full',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
            ], $infoCards),
        ]);

        // Contact form
        $sections[] = $this->section([
            'background_background' => 'classic',
            'background_color' => $this->colors()['surface2'],
            'flex_align_items' => 'center',
        ], [
            $this->eyebrow('Send a Message'),
            $this->headline('We Await Your Inquiry.', 'h2', [
                'align' => 'center',
                'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                '_margin' => self::margin(0, 0, 40, 0),
            ]),
            self::html('<form style="max-width:600px;width:100%;margin:0 auto;display:flex;flex-direction:column;gap:16px;">
<input type="text" placeholder="Your Name" style="padding:14px 20px;background:rgba(22,19,42,.6);backdrop-filter:blur(16px);border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(124,58,237,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<input type="email" placeholder="Your Email" style="padding:14px 20px;background:rgba(22,19,42,.6);backdrop-filter:blur(16px);border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(124,58,237,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'">
<textarea rows="5" placeholder="Your Message" style="padding:14px 20px;background:rgba(22,19,42,.6);backdrop-filter:blur(16px);border:1px solid ' . $this->colors()['border'] . ';color:' . $this->colors()['text'] . ';font-family:\'' . $this->fonts()['body'] . '\',sans-serif;font-size:14px;border-radius:12px;outline:none;resize:vertical;transition:border-color .3s;" onfocus="this.style.borderColor=\'rgba(124,58,237,.4)\'" onblur="this.style.borderColor=\'' . $this->colors()['border'] . '\'"></textarea>
<button type="submit" style="padding:16px 44px;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#FFF;font-family:\'' . $this->fonts()['heading'] . '\',sans-serif;font-size:14px;font-weight:700;letter-spacing:3px;text-transform:uppercase;border:none;border-radius:12px;cursor:pointer;transition:all .3s;box-shadow:0 8px 24px rgba(124,58,237,.3);" onmouseover="this.style.boxShadow=\'0 12px 32px rgba(124,58,237,.5)\'" onmouseout="this.style.boxShadow=\'0 8px 24px rgba(124,58,237,.3)\'">Send Message</button>
</form>'),
        ]);

        return $sections;
    }

    // ═══════════════════════════════════════════════════════════
    // HEADER (HFE) — Luxury centered-logo split-nav
    // ═══════════════════════════════════════════════════════════

    public function buildHeader(string $siteName, array $pages): array
    {
        $c = $this->colors();
        $f = $this->fonts();

        // Split pages into left group and right group around centered logo
        $pageList = [];
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $pageList[] = ['url' => $url, 'label' => e($label)];
        }
        $mid = (int) floor(count($pageList) / 2);
        $leftPages = array_slice($pageList, 0, $mid);
        $rightPages = array_slice($pageList, $mid);

        $leftNav = '';
        foreach ($leftPages as $p) {
            $leftNav .= '<li><a href="' . $p['url'] . '">' . $p['label'] . '</a></li>';
        }
        $rightNav = '';
        foreach ($rightPages as $p) {
            $rightNav .= '<li><a href="' . $p['url'] . '">' . $p['label'] . '</a></li>';
        }
        $allNav = '';
        foreach ($pageList as $p) {
            $allNav .= '<li><a href="' . $p['url'] . '">' . $p['label'] . '</a></li>';
        }

        $sn = e($siteName);
        $hFont = $f['heading'];

        return [self::html('
<header class="royal-hdr" id="royalHeader">
  <!-- Expanded state: two rows (logo centered top, split nav below) -->
  <div class="royal-hdr-expanded">
    <div class="royal-hdr-logo-row">
      <a href="/" class="royal-hdr-logo">' . $sn . '</a>
    </div>
    <div class="royal-hdr-nav-row">
      <ul class="royal-hdr-nav-left">' . $leftNav . '</ul>
      <div class="royal-hdr-nav-diamond"></div>
      <ul class="royal-hdr-nav-right">' . $rightNav . '</ul>
    </div>
    <div class="royal-hdr-gradient-line"></div>
  </div>

  <!-- Collapsed state: single row (logo left, nav right) -->
  <div class="royal-hdr-collapsed">
    <a href="/" class="royal-hdr-logo royal-hdr-logo-sm">' . $sn . '</a>
    <ul class="royal-hdr-nav-inline">' . $allNav . '</ul>
    <a href="/contact/" class="royal-hdr-cta">Book Now</a>
  </div>

  <!-- Mobile hamburger -->
  <div class="royal-hdr-mobile-bar">
    <a href="/" class="royal-hdr-logo royal-hdr-logo-sm">' . $sn . '</a>
    <button class="royal-hdr-hamburger" id="royalHamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <!-- Mobile overlay -->
  <div class="royal-hdr-overlay" id="royalOverlay">
    <button class="royal-hdr-overlay-close" id="royalOverlayClose" aria-label="Close">&times;</button>
    <a href="/" class="royal-hdr-logo royal-hdr-logo-overlay">' . $sn . '</a>
    <nav class="royal-hdr-overlay-nav">
      <ul>' . $allNav . '</ul>
    </nav>
    <a href="/contact/" class="royal-hdr-cta royal-hdr-cta-overlay">Book Now</a>
    <div class="royal-hdr-overlay-accent"></div>
  </div>

  <!-- CTA for expanded desktop -->
  <a href="/contact/" class="royal-hdr-cta royal-hdr-cta-desktop">Book Now</a>
</header>

<style>
/* === ROYAL HEADER BASE === */
.royal-hdr{position:fixed;top:0;left:0;right:0;z-index:1000;transition:all .4s ease;}
.royal-hdr .royal-hdr-logo{font-family:\'' . $hFont . '\',sans-serif;font-size:24px;font-weight:900;letter-spacing:2px;color:' . $c['text'] . ';text-decoration:none;text-transform:uppercase;transition:font-size .3s;}
.royal-hdr .royal-hdr-logo-sm{font-size:18px;letter-spacing:1.5px;}

/* === EXPANDED STATE (default, before scroll) === */
.royal-hdr-expanded{display:flex;flex-direction:column;align-items:center;padding:24px 64px 0;transition:opacity .3s,transform .3s;}
.royal-hdr-logo-row{margin-bottom:14px;}
.royal-hdr-nav-row{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:12px;}
.royal-hdr-nav-left,.royal-hdr-nav-right{display:flex;list-style:none;padding:0;margin:0;gap:0;}
.royal-hdr-nav-diamond{width:6px;height:6px;background:linear-gradient(135deg,' . $c['primary'] . ',' . $c['accent'] . ');transform:rotate(45deg);margin:0 28px;flex-shrink:0;}
.royal-hdr-gradient-line{width:100%;height:1px;background:linear-gradient(90deg,transparent 10%,' . $c['primary'] . ' 40%,' . $c['accent'] . ' 60%,transparent 90%);opacity:.4;}

/* Nav link styles */
.royal-hdr-nav-left a,.royal-hdr-nav-right a,.royal-hdr-nav-inline a{
  display:block;padding:8px 22px;font-family:\'' . $hFont . '\',sans-serif;font-size:11px;font-weight:600;
  letter-spacing:2.5px;text-transform:uppercase;color:' . $c['muted'] . ';text-decoration:none;
  transition:color .3s;position:relative;
}
.royal-hdr-nav-left a::after,.royal-hdr-nav-right a::after,.royal-hdr-nav-inline a::after{
  content:\'\';position:absolute;bottom:2px;left:22px;right:22px;height:1px;
  background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');
  transform:scaleX(0);transform-origin:center;transition:transform .3s;
}
.royal-hdr-nav-left a:hover,.royal-hdr-nav-right a:hover,.royal-hdr-nav-inline a:hover{color:' . $c['text'] . ';}
.royal-hdr-nav-left a:hover::after,.royal-hdr-nav-right a:hover::after,.royal-hdr-nav-inline a:hover::after{transform:scaleX(1);}

/* CTA button */
.royal-hdr-cta{
  padding:12px 32px;background:linear-gradient(135deg,' . $c['primary'] . ',' . $c['secondary'] . ');
  color:#FFF;font-family:\'' . $hFont . '\',sans-serif;font-size:11px;font-weight:700;
  letter-spacing:2px;text-transform:uppercase;border-radius:10px;text-decoration:none;
  transition:all .3s;box-shadow:0 4px 20px rgba(124,58,237,.35);border:none;cursor:pointer;
}
.royal-hdr-cta:hover{box-shadow:0 6px 28px rgba(124,58,237,.55);transform:translateY(-1px);}
.royal-hdr-cta-desktop{position:absolute;top:28px;right:64px;}

/* === COLLAPSED STATE (on scroll) === */
.royal-hdr-collapsed{
  display:none;align-items:center;justify-content:space-between;padding:0 64px;height:68px;
  background:rgba(12,10,26,.95);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
  border-bottom:1px solid ' . $c['border'] . ';
}
.royal-hdr-nav-inline{display:flex;list-style:none;padding:0;margin:0;gap:0;}
.royal-hdr.scrolled .royal-hdr-expanded{display:none!important;}
.royal-hdr.scrolled .royal-hdr-collapsed{display:flex!important;}
.royal-hdr.scrolled .royal-hdr-cta-desktop{display:none!important;}

/* === MOBILE BAR === */
.royal-hdr-mobile-bar{display:none;align-items:center;justify-content:space-between;padding:0 24px;height:64px;
  background:rgba(12,10,26,.95);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);}
.royal-hdr-hamburger{background:none;border:none;cursor:pointer;padding:8px;display:flex;flex-direction:column;gap:5px;}
.royal-hdr-hamburger span{display:block;width:24px;height:2px;background:' . $c['text'] . ';border-radius:1px;transition:all .3s;}

/* === MOBILE OVERLAY === */
.royal-hdr-overlay{
  position:fixed;top:0;left:0;right:0;bottom:0;z-index:2000;
  background:rgba(12,10,26,.98);backdrop-filter:blur(30px);-webkit-backdrop-filter:blur(30px);
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:28px;
  opacity:0;pointer-events:none;transition:opacity .35s;
}
.royal-hdr-overlay.open{opacity:1;pointer-events:all;}
.royal-hdr-overlay-close{
  position:absolute;top:20px;right:24px;background:none;border:none;
  color:' . $c['text'] . ';font-size:32px;cursor:pointer;opacity:.6;transition:opacity .3s;
}
.royal-hdr-overlay-close:hover{opacity:1;}
.royal-hdr-logo-overlay{font-size:22px!important;margin-bottom:8px;}
.royal-hdr-overlay-nav ul{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;align-items:center;gap:6px;}
.royal-hdr-overlay-nav a{
  display:block;padding:12px 20px;font-family:\'' . $hFont . '\',sans-serif;font-size:13px;font-weight:600;
  letter-spacing:3px;text-transform:uppercase;color:' . $c['muted'] . ';text-decoration:none;transition:color .3s;
}
.royal-hdr-overlay-nav a:hover{color:' . $c['text'] . ';}
.royal-hdr-cta-overlay{margin-top:12px;}
.royal-hdr-overlay-accent{
  position:absolute;bottom:40px;left:50%;transform:translateX(-50%);
  width:60px;height:2px;background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');
  border-radius:1px;opacity:.5;
}

/* === RESPONSIVE === */
@media(max-width:1100px){
  .royal-hdr-expanded,.royal-hdr-collapsed,.royal-hdr-cta-desktop{display:none!important;}
  .royal-hdr-mobile-bar{display:flex!important;}
  .royal-hdr.scrolled .royal-hdr-collapsed{display:none!important;}
  .royal-hdr.scrolled .royal-hdr-mobile-bar{display:flex!important;}
}
</style>

<script>
(function(){
  var hdr=document.getElementById("royalHeader");
  var hamburger=document.getElementById("royalHamburger");
  var overlay=document.getElementById("royalOverlay");
  var closeBtn=document.getElementById("royalOverlayClose");
  window.addEventListener("scroll",function(){hdr.classList.toggle("scrolled",window.scrollY>60);});
  hamburger.addEventListener("click",function(){overlay.classList.add("open");document.body.style.overflow="hidden";});
  closeBtn.addEventListener("click",function(){overlay.classList.remove("open");document.body.style.overflow="";});
  overlay.addEventListener("click",function(e){if(e.target===overlay){overlay.classList.remove("open");document.body.style.overflow="";}});
})();
</script>')];
    }

    // ═══════════════════════════════════════════════════════════
    // FOOTER (HFE) — Luxurious concierge-style
    // ═══════════════════════════════════════════════════════════

    public function buildFooter(string $siteName, array $pages, array $contact): array
    {
        $c = $this->colors();
        $f = $this->fonts();
        $hFont = $f['heading'];
        $bFont = $f['body'];
        $sn = e($siteName);

        $navLinks = '';
        foreach ($pages as $slug => $label) {
            $url = ($slug === 'home') ? '/' : '/' . $slug . '/';
            $navLinks .= '<li><a href="' . $url . '">' . e($label) . '</a></li>';
        }

        $email = $contact['email'] ?? 'concierge@example.com';
        $phone = $contact['phone'] ?? '';
        $address = $contact['address'] ?? '';
        $tagline = e($contact['tagline'] ?? 'Luxury Redefined');
        $footerText = e($contact['footer_text'] ?? 'Crafting extraordinary experiences with timeless elegance and unparalleled attention to detail.');
        $year = date('Y');

        $sections = [];

        // ── TOP: Centered brand + gradient tagline + thin divider ──
        $sections[] = self::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'flex_align_items' => 'center',
            'padding' => self::pad(0),
            'background_background' => 'classic',
            'background_color' => $c['surface2'],
        ], [
            // Brand header section
            self::container([
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(70, 64, 40, 64),
                'padding_mobile' => self::pad(50, 24, 30, 24),
            ], [
                self::html('<div style="text-align:center;">
<div style="font-family:\'' . $hFont . '\',sans-serif;font-size:28px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:' . $c['text'] . ';margin-bottom:10px;">' . $sn . '</div>
<div style="font-size:10px;font-weight:600;letter-spacing:10px;text-transform:uppercase;background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:24px;">' . $tagline . '</div>
<div style="width:100px;height:1px;background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');margin:0 auto;opacity:.6;"></div>
</div>'),
            ]),

            // ── MIDDLE: 4-column grid ──
            self::container([
                'flex_direction' => 'row',
                'content_width' => 'full',
                'flex_gap' => ['size' => 40, 'unit' => 'px', 'column' => '40', 'row' => '40'],
                'padding' => self::pad(40, 64, 50, 64),
                'padding_mobile' => self::pad(30, 24),
                'flex_direction_mobile' => 'column',
                'border_border' => 'solid',
                'border_width' => self::pad(0, 0, 1, 0),
                'border_color' => $c['border'],
                'custom_css' => 'selector{display:grid;grid-template-columns:1.2fr 1fr 1fr 1.2fr;}@media(max-width:767px){selector{grid-template-columns:1fr!important;}}@media(min-width:768px) and (max-width:1024px){selector{grid-template-columns:1fr 1fr!important;}}',
            ], [
                // Col 1: About
                self::html('<div class="royal-ft-col">
<h4 class="royal-ft-heading">About</h4>
<p style="font-family:\'' . $bFont . '\',sans-serif;font-size:13.5px;color:' . $c['muted'] . ';line-height:1.9;margin:0;">' . $footerText . '</p>
</div>'),

                // Col 2: Experience (page links)
                self::html('<div class="royal-ft-col">
<h4 class="royal-ft-heading">Experience</h4>
<ul class="royal-ft-links">' . $navLinks . '</ul>
</div>'),

                // Col 3: Concierge
                self::html('<div class="royal-ft-col">
<h4 class="royal-ft-heading">Concierge</h4>
<div class="royal-ft-contact-item">
  <span class="royal-ft-contact-label">Email</span>
  <a href="mailto:' . e($email) . '" class="royal-ft-contact-val">' . e($email) . '</a>
</div>'
. ($phone ? '<div class="royal-ft-contact-item">
  <span class="royal-ft-contact-label">Phone</span>
  <a href="tel:' . e($phone) . '" class="royal-ft-contact-val">' . e($phone) . '</a>
</div>' : '')
. '<div class="royal-ft-contact-item">
  <span class="royal-ft-contact-available">Available 24/7</span>
</div>
</div>'),

                // Col 4: Visit Us
                self::html('<div class="royal-ft-col">
<h4 class="royal-ft-heading">Visit Us</h4>'
. ($address ? '<p style="font-family:\'' . $bFont . '\',sans-serif;font-size:13.5px;color:' . $c['muted'] . ';line-height:1.8;margin:0 0 10px;">' . e($address) . '</p>' : '')
. '<p style="font-family:\'' . $bFont . '\',sans-serif;font-size:12px;color:' . $c['muted'] . ';margin:0 0 12px;"><span style="background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:600;">Valet Parking Available</span></p>
<a href="#" class="royal-ft-map-link">View on Map &rarr;</a>
</div>'),
            ]),

            // ── AWARDS STRIP ──
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(20, 64),
                'padding_mobile' => self::pad(16, 24),
                'background_background' => 'classic',
                'background_color' => $c['surface'],
            ], [
                self::html('<div class="royal-ft-awards">
<span class="royal-ft-award-item">Forbes Travel Guide</span>
<span class="royal-ft-award-sep"></span>
<span class="royal-ft-award-item">Best Luxury Hotel ' . $year . '</span>
<span class="royal-ft-award-sep"></span>
<span class="royal-ft-award-item">Michelin Recommended</span>
</div>'),
            ]),

            // ── BOTTOM: Copyright + Privacy/Terms ──
            self::container([
                'flex_direction' => 'row',
                'flex_justify_content' => 'space-between',
                'flex_align_items' => 'center',
                'content_width' => 'full',
                'padding' => self::pad(20, 64),
                'padding_mobile' => self::pad(16, 24),
                'flex_direction_mobile' => 'column',
            ], [
                self::textEditor('<p style="font-size:12px;color:rgba(245,243,255,.2);margin:0;">&copy; ' . $year . ' <span style="background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:600;">' . $sn . '</span>. All rights reserved.</p>'),
                self::textEditor('<span style="display:flex;gap:20px;"><a href="/privacy-policy/" style="font-size:11.5px;color:rgba(245,243,255,.2);text-decoration:none;transition:color .3s;">Privacy Policy</a><a href="/terms/" style="font-size:11.5px;color:rgba(245,243,255,.2);text-decoration:none;transition:color .3s;">Terms of Service</a></span>'),
            ]),
        ]);

        // Footer styles injected via HTML widget
        array_unshift($sections, self::html('<style>
/* === ROYAL FOOTER STYLES === */
.royal-ft-heading{
  font-family:\'' . $hFont . '\',sans-serif;font-size:11px;font-weight:700;
  letter-spacing:3px;text-transform:uppercase;color:' . $c['text'] . ';
  margin:0 0 18px;padding-bottom:12px;position:relative;
}
.royal-ft-heading::after{
  content:\'\';position:absolute;bottom:0;left:0;width:30px;height:1px;
  background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');
}
.royal-ft-links{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;}
.royal-ft-links a{
  font-family:\'' . $bFont . '\',sans-serif;font-size:13px;color:' . $c['muted'] . ';
  text-decoration:none;transition:color .3s,padding-left .3s;display:block;
}
.royal-ft-links a:hover{color:' . $c['text'] . ';padding-left:6px;}
.royal-ft-contact-item{margin-bottom:12px;}
.royal-ft-contact-label{
  display:block;font-family:\'' . $hFont . '\',sans-serif;font-size:10px;font-weight:600;
  letter-spacing:2px;text-transform:uppercase;color:rgba(245,243,255,.25);margin-bottom:3px;
}
.royal-ft-contact-val{
  font-family:\'' . $bFont . '\',sans-serif;font-size:13.5px;color:' . $c['text'] . ';
  text-decoration:none;transition:color .3s;
}
.royal-ft-contact-val:hover{color:' . $c['accent'] . ';}
.royal-ft-contact-available{
  display:inline-block;font-family:\'' . $hFont . '\',sans-serif;font-size:10px;font-weight:700;
  letter-spacing:2px;text-transform:uppercase;
  background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.royal-ft-map-link{
  font-family:\'' . $hFont . '\',sans-serif;font-size:11px;font-weight:600;
  letter-spacing:1.5px;text-transform:uppercase;
  background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  text-decoration:none;transition:opacity .3s;
}
.royal-ft-map-link:hover{opacity:.7;}

/* Awards strip */
.royal-ft-awards{
  display:flex;align-items:center;justify-content:center;gap:24px;flex-wrap:wrap;
}
.royal-ft-award-item{
  font-family:\'' . $hFont . '\',sans-serif;font-size:10px;font-weight:600;
  letter-spacing:2.5px;text-transform:uppercase;
  background:linear-gradient(90deg,' . $c['primary'] . ',' . $c['accent'] . ');
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.royal-ft-award-sep{
  width:4px;height:4px;border-radius:50%;
  background:linear-gradient(135deg,' . $c['primary'] . ',' . $c['accent'] . ');opacity:.5;
}
@media(max-width:767px){
  .royal-ft-awards{gap:14px;}
}
</style>'));

        return $sections;
    }
}
