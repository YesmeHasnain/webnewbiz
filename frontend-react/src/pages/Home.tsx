import { useState, useEffect, useCallback } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { builderService } from '../services/builder.service';
import { websiteService } from '../services/website.service';
import Navbar from '../components/Navbar';
import type { AIQuestion, BuildSummary } from '../models/types';

type ModalPhase = 'loading' | 'questions' | 'summary' | 'generating';

/* ───── Data ───── */
const features = [
  {
    icon: 'M13 2L3 14h9l-1 8 10-12h-9l1-8z',
    title: 'AI-Powered Generation',
    desc: 'Describe your business in plain English. Our AI builds a complete, professional website with content, images, and design in seconds.',
    img: '/assets/images/services/AI-Built-Website-.jpg',
  },
  {
    icon: 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5',
    title: 'WordPress + Elementor',
    desc: "Built on the world's most popular CMS. Full Elementor drag-and-drop editing. You own your site completely.",
    img: '/assets/images/services/widgets.jpg',
  },
  {
    icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
    title: 'SSL & Security',
    desc: 'Lightning-fast servers with SSL, daily backups, CDN, and 99.9% uptime. Your site is always secure and blazing fast.',
    img: '/assets/images/services/security.jpg',
  },
  {
    icon: 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z',
    title: '9 Premium Layouts',
    desc: 'AI selects the perfect design from 9 hand-crafted layouts. Dark, light, minimal, elegant — matched to your industry.',
    img: '/assets/images/services/co-pilot-white.jpg',
  },
  {
    icon: 'M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z',
    title: 'E-Commerce Ready',
    desc: 'Sell products from day one. WooCommerce pre-configured with product management, inventory, and checkout.',
    img: '/assets/images/homepage/ecommerce-ready_1x.jpg',
  },
  {
    icon: 'M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2zM22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z',
    title: 'Custom Domains',
    desc: 'Connect your own domain or get a free one. Full DNS management, email setup, and professional branding.',
    img: '/assets/images/homepage/free-custom-domain_1x-1.jpg',
  },
];

const howItWorksSteps = [
  {
    num: '01',
    title: 'Describe Your Business',
    desc: 'Tell us about your business in a few sentences. What you do, who you serve, and what makes you unique.',
    img: '/assets/images/homepage/describe_1x.jpg',
  },
  {
    num: '02',
    title: 'AI Builds Your Site',
    desc: 'Our AI selects the perfect layout, generates professional content, sources relevant images, and builds your complete website.',
    img: '/assets/images/homepage/review_1x-1.jpg',
  },
  {
    num: '03',
    title: 'Customize & Launch',
    desc: 'Fine-tune your site with Elementor drag-and-drop editor. Change anything you want, then publish with one click.',
    img: '/assets/images/homepage/customize_1x-1.jpg',
  },
];

const plans = [
  {
    name: 'Starter',
    desc: 'Perfect for small businesses just getting online.',
    monthlyPrice: 10,
    annualPrice: 8,
    features: ['1 AI-Generated Website', '10 GB Storage', 'Free SSL Certificate', '10K Monthly Visitors', 'Basic SEO Tools', 'Email Support'],
    popular: false,
    cta: 'Start Free Trial',
  },
  {
    name: 'Professional',
    desc: 'For growing businesses that need more power.',
    monthlyPrice: 20,
    annualPrice: 15,
    features: ['3 AI-Generated Websites', '25 GB Storage', 'Free SSL + CDN', '50K Monthly Visitors', 'Advanced SEO + Analytics', 'E-Commerce Ready', 'Priority Support', 'Custom Domain'],
    popular: true,
    cta: 'Start Free Trial',
  },
  {
    name: 'Agency',
    desc: 'For agencies managing multiple client sites.',
    monthlyPrice: 50,
    annualPrice: 40,
    features: ['10 AI-Generated Websites', '100 GB Storage', 'Free SSL + CDN + Staging', '200K Monthly Visitors', 'White Label Branding', 'Full E-Commerce Suite', 'Dedicated Support', 'API Access'],
    popular: false,
    cta: 'Contact Sales',
  },
];

const trustedLogos = [
  { name: 'WordPress', icon: 'M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm-1.974 14.6l-2.965-8.126h1.29l1.79 5.39.915-3.16-.64-1.77h1.28l1.79 5.39 1.74-5.39h1.23L12.5 16.6h-1.22l-.92-2.82-.92 2.82h-1.22z' },
  { name: 'Elementor', icon: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM9 16V8h2v8H9zm4 0V8h2v8h-2z' },
  { name: 'WooCommerce', icon: 'M2 5a2 2 0 012-2h16a2 2 0 012 2v11a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm4 1a1 1 0 00-1 1v6a1 1 0 001 1h2l1.5-3L12 13l1.5-3L15 13h2a1 1 0 001-1V7a1 1 0 00-1-1H6z' },
];

/* ───── CSS-in-JS Styles for Modal ───── */
const modalStyles = {
  overlay: {
    position: 'fixed' as const,
    inset: 0,
    background: 'rgba(0, 0, 0, 0.7)',
    backdropFilter: 'blur(12px)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000,
    padding: '20px',
    animation: 'fadeIn 0.2s ease',
  },
  content: {
    background: '#0a0a0a',
    border: '1px solid rgba(255,255,255,0.08)',
    borderRadius: '24px',
    width: '100%',
    maxWidth: '480px',
    position: 'relative' as const,
    overflow: 'hidden',
    boxShadow: '0 25px 60px rgba(0, 0, 0, 0.5)',
    animation: 'slideUp 0.3s ease',
  },
  close: {
    position: 'absolute' as const,
    top: '16px',
    right: '16px',
    width: '36px',
    height: '36px',
    borderRadius: '50%',
    background: 'rgba(255,255,255,0.06)',
    border: 'none',
    cursor: 'pointer',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    color: 'rgba(255,255,255,0.5)',
    zIndex: 10,
    transition: 'all 0.2s',
  },
  phase: {
    padding: '48px 36px',
    textAlign: 'center' as const,
  },
  phaseTitle: {
    fontSize: '22px',
    fontWeight: 700,
    color: '#fff',
    margin: '0 0 8px',
    letterSpacing: '-0.02em',
  },
  phaseSubtitle: {
    fontSize: '14px',
    color: 'rgba(255,255,255,0.4)',
    margin: 0,
    lineHeight: 1.5,
  },
};

/* ───── Component ───── */
export default function Home() {
  const navigate = useNavigate();
  const { isLoggedIn } = useAuth();

  // Modal state
  const [showModal, setShowModal] = useState(false);
  const [modalPhase, setModalPhase] = useState<ModalPhase>('loading');
  const [prompt, setPrompt] = useState('');

  // Analysis data
  const [businessName, setBusinessName] = useState('');
  const [businessType, setBusinessType] = useState('');
  const [questions, setQuestions] = useState<AIQuestion[]>([]);
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [answers, setAnswers] = useState<Record<string, boolean | string>>({});
  const [textAnswer, setTextAnswer] = useState('');
  const [suggestedStyle, setSuggestedStyle] = useState('');

  // Summary data
  const [summary, setSummary] = useState<BuildSummary | null>(null);

  // Error
  const [error, setError] = useState('');

  // Animation
  const [questionAnimating, setQuestionAnimating] = useState(false);

  // Hero prompt
  const [heroPrompt, setHeroPrompt] = useState('');
  const [enhancing, setEnhancing] = useState(false);

  // Pricing toggle
  const [annual, setAnnual] = useState(true);

  // Restore state from sessionStorage on mount
  useEffect(() => {
    const savedState = sessionStorage.getItem('builder_state');
    if (savedState && isLoggedIn) {
      try {
        const state = JSON.parse(savedState);
        sessionStorage.removeItem('builder_state');
        setPrompt(state.prompt);
        setBusinessName(state.businessName);
        setBusinessType(state.businessType);
        setSummary(state.summary);
        setShowModal(true);
        setModalPhase('summary');
      } catch {
        sessionStorage.removeItem('builder_state');
      }
    }
  }, [isLoggedIn]);

  const onEnhance = useCallback(() => {
    if (heroPrompt.trim().length < 3 || enhancing) return;
    setEnhancing(true);
    builderService.enhancePrompt(heroPrompt.trim()).then(res => {
      if (res.data.success && res.data.enhanced) {
        setHeroPrompt(res.data.enhanced);
      }
    }).catch(() => {}).finally(() => setEnhancing(false));
  }, [heroPrompt, enhancing]);

  const onGenerate = useCallback((inputPrompt: string) => {
    setPrompt(inputPrompt);
    setError('');
    setShowModal(true);
    setModalPhase('loading');
    setCurrentQuestionIndex(0);
    setAnswers({});
    setTextAnswer('');

    builderService.analyzeWithQuestions(inputPrompt).then(res => {
      const result = res.data;
      setBusinessName(result.business_name);
      setBusinessType(result.business_type);
      setQuestions(result.questions.map(q => ({ ...q, type: q.type || 'yesno' })));
      setSuggestedStyle(result.suggested_style);
      setModalPhase('questions');
    }).catch(() => {
      setError('Analysis failed. Please try again.');
      setShowModal(false);
    });
  }, []);

  const advanceQuestion = useCallback((newAnswers: Record<string, boolean | string>, newIndex: number, totalQuestions: number, currentPrompt: string, currentBusinessName: string, currentBusinessType: string, currentSuggestedStyle: string) => {
    setQuestionAnimating(true);
    setTimeout(() => {
      setQuestionAnimating(false);
      if (newIndex < totalQuestions - 1) {
        setCurrentQuestionIndex(newIndex + 1);
      } else {
        setModalPhase('loading');
        builderService.summarize({
          prompt: currentPrompt,
          business_name: currentBusinessName,
          business_type: currentBusinessType,
          answers: newAnswers,
        }).then(res => {
          setSummary(res.data);
          setModalPhase('summary');
        }).catch(() => {
          setSummary({
            business_name: currentBusinessName,
            business_type: currentBusinessType,
            summary: `A professional website for ${currentBusinessName}`,
            features: ['Professional Design', 'Mobile Responsive', 'Contact Form'],
            pages: ['home', 'about', 'services', 'contact'],
            theme: currentSuggestedStyle || 'azure',
          });
          setModalPhase('summary');
        });
      }
    }, 300);
  }, []);

  const answerQuestion = (answer: boolean) => {
    if (questionAnimating) return;
    const q = questions[currentQuestionIndex];
    const newAnswers = { ...answers, [q.id]: answer };
    setAnswers(newAnswers);
    advanceQuestion(newAnswers, currentQuestionIndex, questions.length, prompt, businessName, businessType, suggestedStyle);
  };

  const submitTextAnswer = () => {
    if (questionAnimating) return;
    const val = textAnswer.trim();
    if (!val) return;
    const q = questions[currentQuestionIndex];
    const newAnswers = { ...answers, [q.id]: val };
    setAnswers(newAnswers);

    let currentBN = businessName;
    if (q.id === 'q_name') {
      setBusinessName(val);
      currentBN = val;
    }

    setTextAnswer('');
    advanceQuestion(newAnswers, currentQuestionIndex, questions.length, prompt, currentBN, businessType, suggestedStyle);
  };

  const buildWebsite = async () => {
    if (!isLoggedIn) {
      sessionStorage.setItem(
        'builder_state',
        JSON.stringify({ prompt, businessName, businessType, summary })
      );
      setShowModal(false);
      navigate('/register');
      return;
    }

    setModalPhase('generating');
    setError('');

    try {
      const res = await websiteService.generate({
        business_name: summary?.business_name || businessName,
        business_type: summary?.business_type || businessType,
        prompt,
        layout: summary?.theme || suggestedStyle || 'azure',
        pages: summary?.pages || ['home', 'about', 'services', 'contact'],
      });
      setShowModal(false);
      navigate(`/builder/progress/${res.data.id}`);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Build failed. Please try again.');
      setModalPhase('summary');
    }
  };

  const closeModal = () => {
    if (modalPhase !== 'generating') {
      setShowModal(false);
    }
  };

  const progressPercent = questions.length === 0 ? 0 : ((currentQuestionIndex + 1) / questions.length) * 100;
  const currentQuestion = questions[currentQuestionIndex] || null;
  const year = new Date().getFullYear();

  return (
    <div style={{ background: '#000', color: '#fff', minHeight: '100vh' }}>
      <Navbar />

      {/* ─── Hero ─── */}
      <section id="hero" className="relative min-h-screen flex flex-col items-center justify-center overflow-hidden pt-20 pb-12">
        {/* Background effects */}
        <div className="absolute inset-0" style={{
          backgroundImage: 'radial-gradient(ellipse 80% 50% at 50% -20%, rgba(255, 255, 255, 0.15), transparent)',
        }} />
        <div className="absolute inset-0" style={{
          backgroundImage: 'linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px)',
          backgroundSize: '60px 60px',
        }} />

        {/* Floating orbs */}
        <div className="absolute top-32 left-10 w-72 h-72 bg-white/5 rounded-full blur-3xl" style={{ animation: 'float 6s ease-in-out infinite' }} />
        <div className="absolute bottom-20 right-10 w-96 h-96 bg-white/3 rounded-full blur-3xl" style={{ animation: 'float 8s ease-in-out 2s infinite' }} />

        <div className="relative z-10 max-w-5xl mx-auto px-6 text-center">
          {/* Badge */}
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 mb-8 animate-fade-in-up">
            <img src="/assets/icons/sparkles.gif" alt="" className="w-4 h-4" />
            <span className="text-xs font-semibold text-white/70 tracking-wide">More than a website builder</span>
          </div>

          {/* Headline */}
          <h1 className="text-4xl md:text-6xl lg:text-7xl font-black tracking-tight leading-[1.05] mb-6 animate-fade-in-up" style={{ animationDelay: '0.1s' }}>
            Your site should do more<br/>
            <span className="gradient-text">than look good</span>
          </h1>

          {/* Subtitle */}
          <p className="text-base md:text-lg text-white/50 max-w-2xl mx-auto mb-10 leading-relaxed animate-fade-in-up" style={{ animationDelay: '0.2s' }}>
            WebNewBiz unites AI, WordPress, and Elementor to create, manage, and
            optimize impactful web experiences — in seconds.
          </p>

          {/* CTA Buttons */}
          <div className="flex flex-wrap items-center justify-center gap-4 mb-16 animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
            <a href="#generate" className="btn-primary !px-8 !py-4 !text-base !rounded-2xl flex items-center gap-2">
              Start Building Now
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
              </svg>
            </a>
            <a href="#how-it-works" className="btn-secondary !px-8 !py-4 !text-base !rounded-2xl flex items-center gap-2">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="5,3 19,12 5,21" />
              </svg>
              Watch video
            </a>
          </div>

          {/* Hero Image — Dashboard screenshot */}
          <div className="relative max-w-4xl mx-auto animate-fade-in-up" style={{ animationDelay: '0.5s' }}>
            <div className="relative rounded-2xl overflow-hidden border border-white/10 glow-white">
              <img
                src="/assets/images/homepage/hero-bg.jpg"
                alt="WebNewBiz Dashboard"
                className="w-full h-auto"
              />
              {/* Gradient overlay at bottom */}
              <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#000000] to-transparent" />
            </div>
          </div>
        </div>

        {/* Trusted by logos */}
        <div className="relative z-10 mt-16 animate-fade-in-up" style={{ animationDelay: '0.7s' }}>
          <p className="text-xs font-semibold text-white/30 uppercase tracking-widest mb-6 text-center">Powered by industry leaders</p>
          <div className="flex items-center justify-center gap-10">
            {trustedLogos.map(logo => (
              <div key={logo.name} className="flex items-center gap-2 text-white/20 hover:text-white/40 transition-colors">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d={logo.icon}/></svg>
                <span className="text-sm font-semibold">{logo.name}</span>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ─── Generate Section (Prompt Box) ─── */}
      <section id="generate" className="py-20 md:py-28 relative">
        <div className="absolute inset-0" style={{
          backgroundImage: 'radial-gradient(ellipse 60% 40% at 50% 0%, rgba(255, 255, 255, 0.08), transparent)',
        }} />
        <div className="relative z-10 max-w-3xl mx-auto px-6 text-center">
          <div className="section-label justify-center">Create Your Website</div>
          <h2 className="section-title mb-4">Describe it. We build it.</h2>
          <p className="section-subtitle mx-auto mb-10">Tell us about your business and watch AI create a professional website in under 60 seconds.</p>

          <div style={{
            background: 'rgba(255,255,255,0.03)',
            border: '1px solid rgba(255,255,255,0.08)',
            borderRadius: '24px',
            padding: '20px 24px',
            boxShadow: '0 4px 40px rgba(0,0,0,0.3)',
            transition: 'all 0.3s ease',
          }}>
            <div className="flex items-start gap-3">
              <div className="mt-3 ml-1">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
              </div>
              <textarea
                value={heroPrompt}
                onChange={e => setHeroPrompt(e.target.value)}
                placeholder="Describe your business... e.g., 'A modern dental clinic in Dubai offering teeth whitening and cosmetic dentistry'"
                rows={3}
                className="flex-1 bg-transparent border-none outline-none resize-none text-base text-white placeholder-white/30 py-3"
                style={{ fontFamily: 'inherit', lineHeight: 1.6 }}
                onKeyDown={e => {
                  if (e.key === 'Enter') {
                    e.preventDefault();
                    if (heroPrompt.trim().length >= 10) onGenerate(heroPrompt.trim());
                  }
                }}
              />
            </div>
            <div className="flex items-center justify-between pt-3 border-t border-white/5">
              <span className="text-xs text-white/30">
                {heroPrompt.length > 0 ? `${heroPrompt.length} characters` : 'Minimum 10 characters'}
              </span>
              <div className="flex items-center gap-2">
                <button
                  className={`flex items-center gap-1.5 py-2.5 px-4 text-sm rounded-xl border transition-all duration-200 ${
                    heroPrompt.trim().length < 3 || enhancing
                      ? 'opacity-30 cursor-not-allowed border-white/10 text-white/30'
                      : 'border-white/20 text-white/60 hover:bg-white/5 hover:border-white/30'
                  }`}
                  disabled={heroPrompt.trim().length < 3 || enhancing}
                  onClick={onEnhance}
                >
                  {enhancing ? (
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" className="animate-spin">
                      <path d="M21 12a9 9 0 11-6.219-8.56"/>
                    </svg>
                  ) : (
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
                      <path d="M20 3v4"/><path d="M22 5h-4"/>
                    </svg>
                  )}
                  {enhancing ? 'Enhancing...' : 'Enhance'}
                </button>
                <button
                  className={`btn-primary !py-2.5 !px-6 !text-sm !rounded-xl flex items-center gap-2 ${heroPrompt.trim().length < 10 ? 'opacity-40' : ''}`}
                  disabled={heroPrompt.trim().length < 10}
                  onClick={() => onGenerate(heroPrompt.trim())}
                >
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                  </svg>
                  Generate Website
                </button>
              </div>
            </div>
          </div>

          {/* AI-Built Website Preview */}
          <div className="mt-12 relative rounded-2xl overflow-hidden border border-white/8">
            <img
              src="/assets/images/services/AI-Built-Website-.jpg"
              alt="AI generates a website from a prompt"
              className="w-full h-auto"
            />
            <div className="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#000000] to-transparent" />
          </div>
        </div>
      </section>

      {/* ─── Features ─── */}
      <section id="features" className="py-20 md:py-28 relative">
        <div className="max-w-7xl mx-auto px-6 lg:px-8">
          <div className="text-center mb-16">
            <div className="section-label justify-center">Features</div>
            <h2 className="section-title mb-5">Everything you need to<br/><span className="text-white/20">launch & grow</span></h2>
            <p className="section-subtitle mx-auto">One platform to build, host, and manage your website. No plugins to install, no servers to configure.</p>
          </div>
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {features.map(feature => (
              <div key={feature.title} className="glass-card overflow-hidden group">
                <div className="h-48 overflow-hidden">
                  <img
                    src={feature.img}
                    alt={feature.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                </div>
                <div className="p-7">
                  <div className="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center mb-4">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                      <path d={feature.icon}/>
                    </svg>
                  </div>
                  <h3 className="text-lg font-bold text-white mb-2 tracking-tight">{feature.title}</h3>
                  <p className="text-sm text-white/40 leading-relaxed">{feature.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ─── Stats Banner ─── */}
      <section className="py-16 relative border-y border-white/5">
        <div className="absolute inset-0" style={{
          backgroundImage: 'radial-gradient(ellipse 80% 60% at 50% 50%, rgba(255, 255, 255, 0.06), transparent)',
        }} />
        <div className="relative z-10 max-w-5xl mx-auto px-6">
          <div className="flex flex-wrap items-center justify-center gap-12 md:gap-20">
            <div className="text-center">
              <div className="text-4xl md:text-5xl font-black gradient-text">10K+</div>
              <div className="text-xs text-white/40 mt-2 font-medium uppercase tracking-wider">Websites Created</div>
            </div>
            <div className="w-px h-12 bg-white/10 hidden md:block" />
            <div className="text-center">
              <div className="text-4xl md:text-5xl font-black gradient-text">30s</div>
              <div className="text-xs text-white/40 mt-2 font-medium uppercase tracking-wider">Average Build Time</div>
            </div>
            <div className="w-px h-12 bg-white/10 hidden md:block" />
            <div className="text-center">
              <div className="text-4xl md:text-5xl font-black gradient-text">99.9%</div>
              <div className="text-xs text-white/40 mt-2 font-medium uppercase tracking-wider">Uptime Guaranteed</div>
            </div>
            <div className="w-px h-12 bg-white/10 hidden md:block" />
            <div className="text-center">
              <div className="text-4xl md:text-5xl font-black gradient-text">9</div>
              <div className="text-xs text-white/40 mt-2 font-medium uppercase tracking-wider">Premium Layouts</div>
            </div>
          </div>
        </div>
      </section>

      {/* ─── How It Works ─── */}
      <section id="how-it-works" className="py-20 md:py-28 relative">
        <div className="max-w-7xl mx-auto px-6 lg:px-8">
          <div className="text-center mb-16">
            <div className="section-label justify-center">How It Works</div>
            <h2 className="section-title mb-5">Three steps to your<br/><span className="text-white/20">dream website</span></h2>
            <p className="section-subtitle mx-auto">No learning curve. No design skills needed. Just describe and launch.</p>
          </div>
          <div className="grid md:grid-cols-3 gap-8">
            {howItWorksSteps.map((step, index) => (
              <div key={step.num} className="relative group">
                <div className="glass-card overflow-hidden h-full">
                  <div className="h-52 overflow-hidden relative">
                    <img
                      src={step.img}
                      alt={step.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                    <div className="absolute top-4 left-4 w-10 h-10 rounded-full bg-white flex items-center justify-center text-black text-sm font-bold">
                      {step.num}
                    </div>
                  </div>
                  <div className="p-7">
                    <h3 className="text-xl font-bold text-white mb-3 tracking-tight">{step.title}</h3>
                    <p className="text-sm text-white/40 leading-relaxed">{step.desc}</p>
                  </div>
                </div>
                {index < 2 && (
                  <div className="hidden md:flex absolute top-1/2 -right-4 z-10 w-8 items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" strokeWidth="2" strokeLinecap="round">
                      <polyline points="9 18 15 12 9 6"/>
                    </svg>
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ─── AI Chatbot Showcase ─── */}
      <section className="py-20 md:py-28 relative">
        <div className="absolute inset-0" style={{
          backgroundImage: 'radial-gradient(ellipse 70% 50% at 50% 50%, rgba(255, 255, 255, 0.06), transparent)',
        }} />
        <div className="relative z-10 max-w-6xl mx-auto px-6 lg:px-8">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <div>
              <div className="section-label">AI-Powered Support</div>
              <h2 className="section-title mb-5">Built-in AI Chatbot<br/><span className="text-white/20">for your visitors</span></h2>
              <p className="text-white/50 leading-relaxed mb-8">Every website comes with an intelligent AI chatbot that answers visitor questions, captures leads, and provides 24/7 support — no extra plugins required.</p>
              <div className="space-y-4">
                {['Answers customer questions instantly', 'Captures leads automatically', 'Available 24/7, never misses a query'].map(item => (
                  <div key={item} className="flex items-center gap-3">
                    <div className="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </div>
                    <span className="text-sm text-white/60">{item}</span>
                  </div>
                ))}
              </div>
            </div>
            <div className="relative rounded-2xl overflow-hidden border border-white/8 glow-white">
              <img
                src="/assets/images/homepage/Ai-Chatbot-1.jpg"
                alt="AI Chatbot"
                className="w-full h-auto"
              />
            </div>
          </div>
        </div>
      </section>

      {/* ─── Pricing ─── */}
      <section id="pricing" className="py-20 md:py-28 relative">
        <div className="max-w-7xl mx-auto px-6 lg:px-8">
          <div className="text-center mb-16">
            <div className="section-label justify-center">Pricing</div>
            <h2 className="section-title mb-5">Simple, transparent<br/><span className="text-white/20">pricing</span></h2>
            <p className="section-subtitle mx-auto mb-10">Start free. Upgrade when you're ready. No hidden fees.</p>
            <div className="inline-flex items-center gap-1 bg-white/5 rounded-full p-1.5 border border-white/8">
              <button
                onClick={() => setAnnual(false)}
                className={`inline-flex items-center px-5 py-2 rounded-full text-sm font-semibold transition-all border-none cursor-pointer ${
                  !annual ? 'bg-white text-black' : 'bg-transparent text-white/40'
                }`}
              >
                Monthly
              </button>
              <button
                onClick={() => setAnnual(true)}
                className={`inline-flex items-center px-5 py-2 rounded-full text-sm font-semibold transition-all border-none cursor-pointer ${
                  annual ? 'bg-white text-black' : 'bg-transparent text-white/40'
                }`}
              >
                Annual
                <span className="ml-1.5 text-xs bg-white/10 text-white/70 px-2 py-0.5 rounded-full font-semibold">-20%</span>
              </button>
            </div>
          </div>
          <div className="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            {plans.map(plan => (
              <div
                key={plan.name}
                className={`relative transition-all duration-300 hover:-translate-y-1 rounded-3xl ${plan.popular ? 'glow-white' : ''}`}
                style={{
                  background: plan.popular ? 'rgba(255, 255, 255, 0.08)' : 'rgba(255,255,255,0.02)',
                  border: plan.popular ? '1px solid rgba(255, 255, 255, 0.3)' : '1px solid rgba(255,255,255,0.06)',
                }}
              >
                {plan.popular && (
                  <div style={{
                    position: 'absolute',
                    top: '-12px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    background: 'linear-gradient(135deg, #ffffff, #e5e5e5)',
                    color: '#000',
                    fontSize: '12px',
                    fontWeight: 700,
                    padding: '4px 16px',
                    borderRadius: '9999px',
                    whiteSpace: 'nowrap',
                  }}>
                    Most Popular
                  </div>
                )}
                <div className="p-8">
                  <h3 className="text-lg font-bold text-white mb-1">{plan.name}</h3>
                  <p className="text-sm text-white/40 mb-6">{plan.desc}</p>
                  <div className="flex items-baseline gap-1 mb-8">
                    <span className="text-5xl font-black text-white">${annual ? plan.annualPrice : plan.monthlyPrice}</span>
                    <span className="text-white/30 text-sm">/month</span>
                  </div>
                  <button className={`w-full mb-8 ${plan.popular ? 'btn-primary' : 'btn-secondary'}`}>
                    {plan.cta}
                  </button>
                  <ul className="space-y-3">
                    {plan.features.map(feature => (
                      <li key={feature} className="flex items-start gap-3 text-sm text-white/50">
                        <svg className="w-4 h-4 mt-0.5 flex-shrink-0 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                          <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        {feature}
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            ))}
          </div>
          <p className="text-center text-sm text-white/30 mt-12">
            All plans include a 14-day free trial. No credit card required.
          </p>
        </div>
      </section>

      {/* ─── CTA Section ─── */}
      <section className="py-20 md:py-28 relative">
        <div className="absolute inset-0" style={{
          backgroundImage: 'radial-gradient(ellipse 80% 60% at 50% 50%, rgba(255, 255, 255, 0.12), transparent)',
        }} />
        <div className="relative z-10 max-w-3xl mx-auto px-6 text-center">
          <h2 className="text-3xl md:text-5xl font-black tracking-tight mb-6">
            Ready to build your<br/><span className="gradient-text">dream website?</span>
          </h2>
          <p className="text-white/50 text-lg mb-10 max-w-xl mx-auto">
            Join thousands of businesses who launched their professional website in under 60 seconds.
          </p>
          <a href="#generate" className="btn-primary !px-10 !py-4 !text-base !rounded-2xl inline-flex items-center gap-2">
            Start Building — It's Free
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
          </a>
        </div>
      </section>

      {/* ─── Footer ─── */}
      <footer className="border-t border-white/5 pt-20 pb-8">
        <div className="max-w-7xl mx-auto px-6 lg:px-8">
          <div className="grid md:grid-cols-4 gap-12 mb-16">
            <div className="md:col-span-1">
              <Link to="/" className="flex items-center gap-2.5 mb-5">
                <img src="/assets/logo/Web-New-Biz-logo.png" alt="WebNewBiz" className="h-8 w-auto" />
              </Link>
              <p className="text-sm text-white/40 leading-relaxed">
                AI-powered website builder. Create stunning WordPress sites in seconds.
              </p>
            </div>
            <div>
              <h4 className="text-sm font-bold uppercase tracking-wider text-white/30 mb-5">Product</h4>
              <ul className="space-y-3">
                <li><a href="#features" className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Features</a></li>
                <li><a href="#pricing" className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Pricing</a></li>
                <li><a href="#how-it-works" className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">How It Works</a></li>
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Templates</a></li>
              </ul>
            </div>
            <div>
              <h4 className="text-sm font-bold uppercase tracking-wider text-white/30 mb-5">Company</h4>
              <ul className="space-y-3">
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">About</a></li>
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Blog</a></li>
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Careers</a></li>
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Contact</a></li>
              </ul>
            </div>
            <div>
              <h4 className="text-sm font-bold uppercase tracking-wider text-white/30 mb-5">Legal</h4>
              <ul className="space-y-3">
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Privacy Policy</a></li>
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Terms of Service</a></li>
                <li><a className="text-sm text-white/40 hover:text-white transition-colors cursor-pointer">Cookie Policy</a></li>
              </ul>
            </div>
          </div>
          <div className="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p className="text-sm text-white/30">&copy; {year} WebNewBiz. All rights reserved.</p>
            <div className="flex items-center gap-5">
              <a className="text-white/20 hover:text-white transition-colors cursor-pointer" aria-label="Twitter">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
              </a>
              <a className="text-white/20 hover:text-white transition-colors cursor-pointer" aria-label="LinkedIn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
              <a className="text-white/20 hover:text-white transition-colors cursor-pointer" aria-label="GitHub">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </footer>

      {/* ─── AI Builder Modal ─── */}
      {showModal && (
        <div style={modalStyles.overlay} onClick={closeModal}>
          <div style={modalStyles.content} onClick={e => e.stopPropagation()}>

            {/* Close button */}
            {modalPhase !== 'generating' && (
              <button style={modalStyles.close} onClick={closeModal}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
              </button>
            )}

            {/* Loading Phase */}
            {modalPhase === 'loading' && (
              <div style={modalStyles.phase}>
                <div style={{ position: 'relative', width: '64px', height: '64px', margin: '0 auto 24px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <div style={{
                    position: 'absolute', inset: 0,
                    border: '3px solid rgba(255,255,255,0.1)', borderTopColor: '#ffffff',
                    borderRadius: '50%', animation: 'spin 1s linear infinite',
                  }} />
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/>
                    <path d="M10 22h4"/>
                  </svg>
                </div>
                <h2 style={modalStyles.phaseTitle}>Analyzing your business...</h2>
                <p style={modalStyles.phaseSubtitle}>Our AI is understanding your needs</p>
              </div>
            )}

            {/* Questions Phase */}
            {modalPhase === 'questions' && currentQuestion && (
              <div style={{ ...modalStyles.phase, paddingTop: '32px' }}>
                {/* Progress bar */}
                <div style={{ height: '4px', background: 'rgba(255,255,255,0.06)', borderRadius: '2px', marginBottom: '8px', overflow: 'hidden' }}>
                  <div style={{ height: '100%', background: 'linear-gradient(90deg, #ffffff, #e5e5e5)', borderRadius: '2px', transition: 'width 0.4s ease', width: `${progressPercent}%` }} />
                </div>
                <div style={{ fontSize: '11px', color: 'rgba(255,255,255,0.3)', fontWeight: 600, textTransform: 'uppercase' as const, letterSpacing: '0.05em', marginBottom: '24px' }}>
                  Question {currentQuestionIndex + 1} of {questions.length}
                </div>

                {/* Business name badge */}
                <div style={{
                  display: 'inline-flex', alignItems: 'center', gap: '6px',
                  padding: '6px 14px', background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.2)', borderRadius: '20px',
                  fontSize: '13px', fontWeight: 600, color: '#ffffff', marginBottom: '28px',
                }}>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                  </svg>
                  {businessName}
                </div>

                {/* Question card */}
                <div style={{ animation: questionAnimating ? 'slideOutLeft 0.3s ease forwards' : 'slideInRight 0.3s ease' }}>
                  <h2 style={{ ...modalStyles.phaseTitle, fontSize: '20px', lineHeight: 1.3, marginBottom: '8px' }}>{currentQuestion.question}</h2>
                  <p style={{ color: 'rgba(255,255,255,0.35)', fontSize: '13px', margin: '0 0 32px', lineHeight: 1.5 }}>{currentQuestion.context}</p>

                  {currentQuestion.type === 'text' ? (
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '12px', alignItems: 'center' }}>
                      <input
                        type="text"
                        value={textAnswer}
                        onChange={e => setTextAnswer(e.target.value)}
                        placeholder={currentQuestion.placeholder || 'Type your answer...'}
                        onKeyDown={e => { if (e.key === 'Enter') submitTextAnswer(); }}
                        autoFocus
                        style={{
                          width: '100%', maxWidth: '340px', padding: '14px 18px',
                          border: '1px solid rgba(255,255,255,0.1)', borderRadius: '14px',
                          fontSize: '15px', fontWeight: 500, color: '#fff', background: 'rgba(255,255,255,0.05)',
                          outline: 'none', transition: 'border-color 0.2s', textAlign: 'center',
                        }}
                      />
                      <button
                        onClick={submitTextAnswer}
                        style={{
                          display: 'inline-flex', alignItems: 'center', gap: '6px',
                          padding: '10px 28px', background: '#ffffff', color: '#000',
                          border: 'none', borderRadius: '12px', fontSize: '14px',
                          fontWeight: 600, cursor: 'pointer', transition: 'all 0.2s',
                          opacity: textAnswer.trim() ? 1 : 0.4,
                          pointerEvents: textAnswer.trim() ? 'auto' : 'none',
                        }}
                      >
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                          <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                        Continue
                      </button>
                    </div>
                  ) : (
                    <div style={{ display: 'flex', gap: '12px', justifyContent: 'center' }}>
                      <button
                        onClick={() => answerQuestion(true)}
                        style={{
                          display: 'flex', alignItems: 'center', gap: '8px',
                          padding: '12px 32px', borderRadius: '14px', fontSize: '15px',
                          fontWeight: 600, border: 'none',
                          cursor: 'pointer', transition: 'all 0.2s',
                          background: '#ffffff', color: '#000',
                        }}
                      >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                          <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Yes
                      </button>
                      <button
                        onClick={() => answerQuestion(false)}
                        style={{
                          display: 'flex', alignItems: 'center', gap: '8px',
                          padding: '12px 32px', borderRadius: '14px', fontSize: '15px',
                          fontWeight: 600, border: '1px solid rgba(255,255,255,0.1)',
                          cursor: 'pointer', transition: 'all 0.2s',
                          background: 'rgba(255,255,255,0.05)', color: 'rgba(255,255,255,0.6)',
                        }}
                      >
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
                          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        No
                      </button>
                    </div>
                  )}
                </div>
              </div>
            )}

            {/* Summary Phase */}
            {modalPhase === 'summary' && summary && (
              <div style={{ ...modalStyles.phase, padding: '40px 36px' }}>
                <div style={{
                  width: '56px', height: '56px', background: 'rgba(255,255,255,0.1)',
                  border: '1px solid rgba(255,255,255,0.2)',
                  borderRadius: '16px', display: 'flex', alignItems: 'center',
                  justifyContent: 'center', margin: '0 auto 20px', color: '#ffffff',
                }}>
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                  </svg>
                </div>

                <h2 style={modalStyles.phaseTitle}>{summary.business_name}</h2>
                <p style={{ textTransform: 'capitalize', fontSize: '13px', color: 'rgba(255,255,255,0.3)', marginBottom: '12px' }}>{summary.business_type}</p>
                <p style={{ fontSize: '14px', color: 'rgba(255,255,255,0.5)', maxWidth: '380px', margin: '0 auto 24px', lineHeight: 1.5 }}>{summary.summary}</p>

                {summary.features.length > 0 && (
                  <div style={{ marginBottom: '20px' }}>
                    <h3 style={{ fontSize: '11px', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.08em', color: 'rgba(255,255,255,0.3)', margin: '0 0 10px' }}>Features</h3>
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: '8px', justifyContent: 'center' }}>
                      {summary.features.map(feature => (
                        <span key={feature} style={{ padding: '5px 14px', background: 'rgba(255,255,255,0.05)', border: '1px solid rgba(255,255,255,0.08)', borderRadius: '20px', fontSize: '12px', fontWeight: 600, color: 'rgba(255,255,255,0.6)' }}>
                          {feature}
                        </span>
                      ))}
                    </div>
                  </div>
                )}

                <div style={{ marginBottom: '20px' }}>
                  <h3 style={{ fontSize: '11px', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.08em', color: 'rgba(255,255,255,0.3)', margin: '0 0 10px' }}>Pages</h3>
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '8px', justifyContent: 'center' }}>
                    {summary.pages.map(page => (
                      <span key={page} style={{ padding: '5px 14px', background: 'rgba(255,255,255,0.08)', color: 'rgba(255,255,255,0.7)', border: '1px solid rgba(255,255,255,0.1)', borderRadius: '8px', fontSize: '12px', fontWeight: 600, textTransform: 'capitalize' }}>
                        {page}
                      </span>
                    ))}
                  </div>
                </div>

                {error && (
                  <div style={{ background: 'rgba(220,38,38,0.1)', border: '1px solid rgba(220,38,38,0.2)', color: '#f87171', padding: '10px 16px', borderRadius: '10px', fontSize: '13px', marginBottom: '16px' }}>
                    {error}
                  </div>
                )}

                <button
                  onClick={buildWebsite}
                  style={{
                    display: 'inline-flex', alignItems: 'center', gap: '8px',
                    padding: '14px 36px', background: '#ffffff', color: '#000',
                    border: 'none', borderRadius: '14px', fontSize: '15px',
                    fontWeight: 700, cursor: 'pointer', transition: 'all 0.2s',
                    marginTop: '8px',
                  }}
                >
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                  </svg>
                  Build My Website
                </button>
              </div>
            )}

            {/* Generating Phase */}
            {modalPhase === 'generating' && (
              <div style={modalStyles.phase}>
                <div style={{ position: 'relative', width: '64px', height: '64px', margin: '0 auto 24px' }}>
                  <div style={{
                    position: 'absolute', inset: 0,
                    border: '3px solid rgba(255,255,255,0.1)', borderTopColor: '#ffffff',
                    borderRadius: '50%', animation: 'spin 1s linear infinite',
                    width: '64px', height: '64px',
                  }} />
                  <div style={{ position: 'absolute', inset: '8px',
                    border: '3px solid rgba(255,255,255,0.05)', borderTopColor: '#e5e5e5',
                    borderRadius: '50%', animation: 'spin 0.8s linear infinite reverse',
                  }} />
                </div>
                <h2 style={modalStyles.phaseTitle}>Building your website...</h2>
                <p style={modalStyles.phaseSubtitle}>This usually takes about 30 seconds</p>
                <div style={{ marginTop: '28px' }}>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '12px', textAlign: 'left', maxWidth: '280px', margin: '0 auto' }}>
                    {['Generating content', 'Downloading images', 'Building pages', 'Configuring site'].map((step, i) => (
                      <div key={step} style={{ display: 'flex', alignItems: 'center', gap: '10px', animation: `fadeIn 0.4s ease ${i * 0.8}s both` }}>
                        <div style={{
                          width: '20px', height: '20px', borderRadius: '50%',
                          border: '2px solid rgba(255,255,255,0.1)', borderTopColor: '#ffffff',
                          animation: 'spin 1s linear infinite',
                        }} />
                        <span style={{ fontSize: '13px', color: 'rgba(255,255,255,0.4)' }}>{step}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
}
