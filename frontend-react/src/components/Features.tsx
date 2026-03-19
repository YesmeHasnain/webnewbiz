const features = [
  {
    icon: (
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M12 2a4 4 0 0 0-4 4c0 2 1.5 3.5 3 4.5V12h2v-1.5c1.5-1 3-2.5 3-4.5a4 4 0 0 0-4-4z"/>
        <path d="M9 18h6"/><path d="M10 22h4"/>
        <path d="M12 12v6"/>
      </svg>
    ),
    title: 'AI-Powered Generation',
    description: 'Describe your business in plain English and watch as our AI crafts a complete website with unique content, images, and design tailored to your brand.',
  },
  {
    icon: (
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <rect x="2" y="3" width="20" height="14" rx="2"/>
        <path d="M8 21h8"/><path d="M12 17v4"/>
        <path d="M7 8h4"/><path d="M7 12h2"/>
      </svg>
    ),
    title: 'WordPress + Elementor',
    description: 'Built on the world\'s most popular CMS with Elementor Pro. Full drag-and-drop editing, thousands of plugins, and complete ownership of your site.',
  },
  {
    icon: (
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
        <line x1="4" y1="22" x2="4" y2="15"/>
      </svg>
    ),
    title: 'Premium Hosting',
    description: 'Lightning-fast hosting with 99.9% uptime, free SSL, daily backups, and a global CDN. Your website stays online and loads instantly, everywhere.',
  },
  {
    icon: (
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
    ),
    title: '9 Premium Layouts',
    description: 'Choose from 9 professionally designed layouts spanning dark, light, modern, and elegant styles. Each one is fully responsive and conversion-optimized.',
  },
  {
    icon: (
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="11" cy="11" r="8"/>
        <path d="m21 21-4.35-4.35"/>
        <path d="m11 8v6"/><path d="m8 11h6"/>
      </svg>
    ),
    title: 'SEO Optimized',
    description: 'Every website is built with clean code, proper heading structure, meta tags, and schema markup. Start ranking on Google from day one.',
  },
  {
    icon: (
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
      </svg>
    ),
    title: 'E-Commerce Ready',
    description: 'WooCommerce integration out of the box. Product listings, shopping cart, checkout, and payment processing all set up and ready to sell.',
  },
];

export default function Features() {
  return (
    <section id="features" className="py-32 px-6 relative">
      <div className="max-w-7xl mx-auto">
        {/* Section header */}
        <div className="text-center mb-20">
          <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-neutral-100 border border-neutral-200 mb-6">
            <span className="text-[11px] font-semibold text-neutral-500 tracking-widest uppercase">Platform</span>
          </div>
          <h2 className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tighter mb-4">
            Everything you need
          </h2>
          <p className="text-lg text-neutral-500 max-w-xl mx-auto leading-relaxed">
            A complete platform to build, host, and manage your website.
            No compromises, no hidden fees.
          </p>
        </div>

        {/* Feature grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {features.map((feature, index) => (
            <div
              key={index}
              className="group p-8 rounded-2xl border border-neutral-200 bg-white/60 hover:bg-white hover:border-neutral-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.06)] transition-all duration-300"
              style={{ backdropFilter: 'blur(10px)' }}
            >
              <div className="w-12 h-12 rounded-xl bg-neutral-100 group-hover:bg-black group-hover:text-white flex items-center justify-center mb-6 transition-all duration-300 text-neutral-600">
                {feature.icon}
              </div>
              <h3 className="text-lg font-bold text-black mb-2 tracking-tight">{feature.title}</h3>
              <p className="text-sm text-neutral-500 leading-relaxed">{feature.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
