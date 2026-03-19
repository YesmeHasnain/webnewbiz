import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

/* ─── Mega Menu Data ─── */
const megaMenus: Record<string, MegaMenuData> = {
  Features: {
    columns: [
      {
        heading: 'Build',
        items: [
          { icon: 'M13 2L3 14h9l-1 8 10-12h-9l1-8z', title: 'AI Website Generator', desc: 'Describe your business, get a complete site', href: '#generate' },
          { icon: 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5', title: 'WordPress + Elementor', desc: 'Drag-and-drop editing, full ownership', href: '#features' },
          { icon: 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z', title: '9 Premium Layouts', desc: 'AI-matched designs for every industry', href: '#features' },
        ],
      },
      {
        heading: 'Grow',
        items: [
          { icon: 'M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2zM22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z', title: 'E-Commerce', desc: 'WooCommerce with products & checkout', href: '#features' },
          { icon: 'M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z', title: 'AI Chatbot', desc: '24/7 visitor support, lead capture', href: '#features' },
          { icon: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z', title: 'SEO & Security', desc: 'SSL, meta tags, schema markup built-in', href: '#features' },
        ],
      },
    ],
    featured: {
      img: '/assets/images/services/AI-Built-Website-.jpg',
      title: 'See AI in action',
      desc: 'Watch how a prompt becomes a full website',
      href: '#generate',
    },
  },
  Solutions: {
    columns: [
      {
        heading: 'By Industry',
        items: [
          { icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', title: 'Restaurants & Cafes', desc: 'Menus, reservations, delivery', href: '#generate' },
          { icon: 'M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z', title: 'Health & Wellness', desc: 'Clinics, spas, fitness studios', href: '#generate' },
          { icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', title: 'Tech & SaaS', desc: 'Landing pages, demos, signups', href: '#generate' },
        ],
      },
      {
        heading: 'By Business Size',
        items: [
          { icon: 'M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2', title: 'Freelancers', desc: 'Portfolio, booking, services', href: '#generate' },
          { icon: 'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M23 21v-2a4 4 0 00-3-3.87', title: 'Small Business', desc: 'Professional web presence fast', href: '#generate' },
          { icon: 'M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4z', title: 'Enterprise', desc: 'Multi-site, white-label, API access', href: '#pricing' },
        ],
      },
    ],
    featured: {
      img: '/assets/images/services/co-pilot-white.jpg',
      title: 'AI Co-Pilot',
      desc: 'Let AI suggest edits and improvements to your site',
      href: '#features',
    },
  },
  Resources: {
    columns: [
      {
        heading: 'Learn',
        items: [
          { icon: 'M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2zM22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z', title: 'Documentation', desc: 'Guides, tutorials, API reference', href: '#' },
          { icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2', title: 'Blog', desc: 'Tips, updates, and case studies', href: '#' },
          { icon: 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z', title: 'Video Tutorials', desc: 'Step-by-step visual guides', href: '#' },
        ],
      },
      {
        heading: 'Support',
        items: [
          { icon: 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', title: 'Help Center', desc: 'FAQs and troubleshooting', href: '#' },
          { icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', title: 'Contact Us', desc: 'Get in touch with our team', href: '#' },
          { icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0', title: 'Community', desc: 'Join our growing community', href: '#' },
        ],
      },
    ],
  },
};

interface MegaMenuItem {
  icon: string;
  title: string;
  desc: string;
  href: string;
}

interface MegaMenuColumn {
  heading: string;
  items: MegaMenuItem[];
}

interface MegaMenuData {
  columns: MegaMenuColumn[];
  featured?: {
    img: string;
    title: string;
    desc: string;
    href: string;
  };
}

const navItems = ['Features', 'Solutions', 'Resources', 'Pricing'];

export default function Navbar({ forceDark = false }: { forceDark?: boolean }) {
  const [isScrolled, setIsScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [activeMenu, setActiveMenu] = useState<string | null>(null);
  const [mobileExpanded, setMobileExpanded] = useState<string | null>(null);
  const { isLoggedIn, logout } = useAuth();
  const closeTimer = useRef<ReturnType<typeof setTimeout> | null>(null);

  useEffect(() => {
    const onScroll = () => setIsScrolled(window.scrollY > 20);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  const handleMouseEnter = (item: string) => {
    if (closeTimer.current) clearTimeout(closeTimer.current);
    if (megaMenus[item]) {
      setActiveMenu(item);
    }
  };

  const handleMouseLeave = () => {
    closeTimer.current = setTimeout(() => setActiveMenu(null), 150);
  };

  const handleMenuEnter = () => {
    if (closeTimer.current) clearTimeout(closeTimer.current);
  };

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled || activeMenu || forceDark
          ? 'bg-black/90 shadow-[0_1px_0_rgba(255,255,255,0.05)]'
          : 'bg-transparent'
      }`}
      style={{ backdropFilter: isScrolled || activeMenu || forceDark ? 'blur(20px)' : undefined }}
    >
      <div className="max-w-7xl mx-auto px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo only — no text */}
          <Link to="/" className="group">
            <img
              src="/assets/logo/Web-New-Biz-logo.png"
              alt="WebNewBiz"
              className="h-10 w-auto group-hover:scale-105 transition-transform"
            />
          </Link>

          {/* Desktop Nav */}
          <div className="hidden lg:flex items-center gap-1">
            {navItems.map(item => {
              const hasMega = !!megaMenus[item];
              return (
                <div
                  key={item}
                  className="relative"
                  onMouseEnter={() => handleMouseEnter(item)}
                  onMouseLeave={handleMouseLeave}
                >
                  <a
                    href={item === 'Pricing' ? '#pricing' : undefined}
                    className={`flex items-center gap-1 px-4 py-2 text-sm font-medium transition-colors rounded-lg cursor-pointer ${
                      activeMenu === item ? 'text-white bg-white/5' : 'text-white/60 hover:text-white'
                    }`}
                  >
                    {item}
                    {hasMega && (
                      <svg
                        width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
                        className={`transition-transform duration-200 ${activeMenu === item ? 'rotate-180' : ''}`}
                      >
                        <polyline points="6 9 12 15 18 9" />
                      </svg>
                    )}
                  </a>
                </div>
              );
            })}
          </div>

          {/* Right side */}
          <div className="hidden lg:flex items-center gap-3">
            {isLoggedIn ? (
              <>
                <Link to="/dashboard" className="text-sm font-semibold text-white/70 hover:text-white transition-colors px-4 py-2">
                  Dashboard
                </Link>
                <button onClick={logout} className="text-sm font-semibold text-white/70 hover:text-white transition-colors px-4 py-2">
                  Logout
                </button>
              </>
            ) : (
              <>
                <Link to="/login" className="text-sm font-semibold text-white/70 hover:text-white transition-colors px-4 py-2">
                  Login
                </Link>
                <Link to="/register" className="btn-primary !py-2.5 !px-5 !text-sm !rounded-xl">
                  Get started
                </Link>
              </>
            )}
          </div>

          {/* Mobile hamburger */}
          <button className="lg:hidden p-2 text-white" onClick={() => setMobileOpen(!mobileOpen)}>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
              {!mobileOpen ? (
                <><line x1="3" y1="6" x2="21" y2="6" /><line x1="3" y1="12" x2="21" y2="12" /><line x1="3" y1="18" x2="21" y2="18" /></>
              ) : (
                <><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></>
              )}
            </svg>
          </button>
        </div>
      </div>

      {/* ─── Desktop Mega Menu Dropdown ─── */}
      {activeMenu && megaMenus[activeMenu] && (
        <div
          className="hidden lg:block absolute left-0 right-0 top-20"
          onMouseEnter={handleMenuEnter}
          onMouseLeave={handleMouseLeave}
          style={{ animation: 'fadeIn 0.15s ease, slideUp 0.2s ease' }}
        >
          <div className="max-w-7xl mx-auto px-6 lg:px-8">
            <div
              className="rounded-2xl overflow-hidden border border-white/8"
              style={{
                background: 'linear-gradient(180deg, rgba(10,10,10,0.98) 0%, rgba(5,5,5,0.98) 100%)',
                backdropFilter: 'blur(40px)',
                boxShadow: '0 25px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05) inset',
              }}
            >
              <div className="flex">
                {/* Columns */}
                <div className="flex-1 p-8">
                  <div className="grid grid-cols-2 gap-8">
                    {megaMenus[activeMenu].columns.map(col => (
                      <div key={col.heading}>
                        <h4 className="text-[11px] font-bold uppercase tracking-[0.12em] text-white/25 mb-4 px-3">{col.heading}</h4>
                        <div className="space-y-1">
                          {col.items.map(item => (
                            <a
                              key={item.title}
                              href={item.href}
                              onClick={() => setActiveMenu(null)}
                              className="flex items-start gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 transition-colors group/item"
                            >
                              <div className="w-9 h-9 rounded-lg bg-white/5 border border-white/8 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover/item:bg-white/10 group-hover/item:border-white/15 transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.6)" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                                  <path d={item.icon} />
                                </svg>
                              </div>
                              <div>
                                <div className="text-sm font-semibold text-white/80 group-hover/item:text-white transition-colors">{item.title}</div>
                                <div className="text-xs text-white/30 mt-0.5 leading-relaxed">{item.desc}</div>
                              </div>
                            </a>
                          ))}
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Featured card */}
                {megaMenus[activeMenu].featured && (
                  <div className="w-72 border-l border-white/5 p-6 flex flex-col">
                    <a
                      href={megaMenus[activeMenu].featured!.href}
                      onClick={() => setActiveMenu(null)}
                      className="group/feat flex-1 flex flex-col"
                    >
                      <div className="rounded-xl overflow-hidden mb-4 border border-white/8">
                        <img
                          src={megaMenus[activeMenu].featured!.img}
                          alt={megaMenus[activeMenu].featured!.title}
                          className="w-full h-36 object-cover group-hover/feat:scale-105 transition-transform duration-500"
                        />
                      </div>
                      <h4 className="text-sm font-bold text-white/80 group-hover/feat:text-white transition-colors mb-1">
                        {megaMenus[activeMenu].featured!.title}
                      </h4>
                      <p className="text-xs text-white/30 leading-relaxed mb-3">
                        {megaMenus[activeMenu].featured!.desc}
                      </p>
                      <span className="text-xs font-semibold text-white/50 flex items-center gap-1 mt-auto group-hover/feat:text-white group-hover/feat:gap-2 transition-all">
                        Learn more
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                          <line x1="5" y1="12" x2="19" y2="12" /><polyline points="12 5 19 12 12 19" />
                        </svg>
                      </span>
                    </a>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* ─── Mobile Menu ─── */}
      {mobileOpen && (
        <div
          className="lg:hidden border-t border-white/5 overflow-y-auto"
          style={{
            animation: 'fadeIn 0.2s ease',
            background: 'linear-gradient(180deg, rgba(5,5,5,0.99) 0%, rgba(0,0,0,0.99) 100%)',
            backdropFilter: 'blur(40px)',
            maxHeight: 'calc(100vh - 80px)',
          }}
        >
          <div className="px-6 py-4">
            {navItems.map(item => {
              const hasMega = !!megaMenus[item];
              const isExpanded = mobileExpanded === item;

              return (
                <div key={item} className="border-b border-white/5 last:border-b-0">
                  {hasMega ? (
                    <>
                      <button
                        onClick={() => setMobileExpanded(isExpanded ? null : item)}
                        className="flex items-center justify-between w-full py-3.5 text-sm font-medium text-white/70"
                      >
                        {item}
                        <svg
                          width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
                          className={`transition-transform duration-200 ${isExpanded ? 'rotate-180' : ''}`}
                        >
                          <polyline points="6 9 12 15 18 9" />
                        </svg>
                      </button>
                      {isExpanded && (
                        <div className="pb-4 pl-2" style={{ animation: 'fadeIn 0.2s ease' }}>
                          {megaMenus[item].columns.map(col => (
                            <div key={col.heading} className="mb-4">
                              <h4 className="text-[10px] font-bold uppercase tracking-[0.12em] text-white/20 mb-2 px-2">{col.heading}</h4>
                              <div className="space-y-0.5">
                                {col.items.map(sub => (
                                  <a
                                    key={sub.title}
                                    href={sub.href}
                                    onClick={() => { setMobileOpen(false); setMobileExpanded(null); }}
                                    className="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-white/5 transition-colors"
                                  >
                                    <div className="w-7 h-7 rounded-md bg-white/5 border border-white/8 flex items-center justify-center flex-shrink-0">
                                      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                                        <path d={sub.icon} />
                                      </svg>
                                    </div>
                                    <div>
                                      <div className="text-sm font-medium text-white/70">{sub.title}</div>
                                      <div className="text-[11px] text-white/25">{sub.desc}</div>
                                    </div>
                                  </a>
                                ))}
                              </div>
                            </div>
                          ))}
                        </div>
                      )}
                    </>
                  ) : (
                    <a
                      href={`#${item.toLowerCase()}`}
                      className="block py-3.5 text-sm font-medium text-white/70"
                      onClick={() => setMobileOpen(false)}
                    >
                      {item}
                    </a>
                  )}
                </div>
              );
            })}

            {/* Auth buttons */}
            <div className="pt-4 mt-2 border-t border-white/5 flex flex-col gap-2">
              {isLoggedIn ? (
                <>
                  <Link to="/dashboard" className="text-sm font-semibold text-center py-2.5 text-white" onClick={() => setMobileOpen(false)}>
                    Dashboard
                  </Link>
                  <button onClick={() => { logout(); setMobileOpen(false); }} className="text-sm font-semibold text-center py-2.5 text-white">
                    Logout
                  </button>
                </>
              ) : (
                <>
                  <Link to="/login" className="text-sm font-semibold text-center py-2.5 text-white" onClick={() => setMobileOpen(false)}>
                    Login
                  </Link>
                  <Link to="/register" className="btn-primary !text-sm text-center" onClick={() => setMobileOpen(false)}>
                    Get started
                  </Link>
                </>
              )}
            </div>
          </div>
        </div>
      )}
    </nav>
  );
}
