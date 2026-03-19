const steps = [
  {
    number: '01',
    title: 'Describe Your Business',
    description: 'Tell us about your business in a few sentences. Our AI understands context, industry, and tone to create the perfect website for you.',
    mockup: (
      <div className="bg-neutral-900 rounded-xl p-6 border border-neutral-800">
        <div className="flex items-center gap-2 mb-4">
          <div className="w-3 h-3 rounded-full bg-neutral-700" />
          <div className="w-3 h-3 rounded-full bg-neutral-700" />
          <div className="w-3 h-3 rounded-full bg-neutral-700" />
        </div>
        <div className="space-y-3">
          <div className="h-2.5 bg-neutral-800 rounded-full w-3/4" />
          <div className="h-2.5 bg-neutral-800 rounded-full w-full" />
          <div className="h-2.5 bg-neutral-800 rounded-full w-5/6" />
          <div className="mt-6 flex items-center gap-3">
            <div className="h-8 w-8 rounded-lg bg-white/10 flex items-center justify-center">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round">
                <path d="M12 2a4 4 0 0 0-4 4c0 2 1.5 3.5 3 4.5V12h2v-1.5c1.5-1 3-2.5 3-4.5a4 4 0 0 0-4-4z"/>
              </svg>
            </div>
            <div className="h-2.5 bg-white/20 rounded-full w-24" />
          </div>
        </div>
      </div>
    ),
  },
  {
    number: '02',
    title: 'AI Builds Your Site',
    description: 'Our AI selects the perfect layout, generates unique content, downloads professional images, and assembles everything into a polished WordPress website.',
    mockup: (
      <div className="bg-neutral-900 rounded-xl p-6 border border-neutral-800">
        <div className="flex items-center gap-2 mb-4">
          <div className="w-3 h-3 rounded-full bg-neutral-700" />
          <div className="w-3 h-3 rounded-full bg-neutral-700" />
          <div className="w-3 h-3 rounded-full bg-neutral-700" />
        </div>
        <div className="space-y-4">
          <div className="flex items-center gap-3">
            <div className="w-5 h-5 rounded-full border-2 border-green-500 flex items-center justify-center">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#22c55e" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
            </div>
            <div className="h-2.5 bg-green-500/20 rounded-full w-32" />
          </div>
          <div className="flex items-center gap-3">
            <div className="w-5 h-5 rounded-full border-2 border-green-500 flex items-center justify-center">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#22c55e" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
            </div>
            <div className="h-2.5 bg-green-500/20 rounded-full w-40" />
          </div>
          <div className="flex items-center gap-3">
            <div className="w-5 h-5 rounded-full border-2 border-blue-500 flex items-center justify-center animate-spin">
              <div className="w-2 h-2 border border-blue-500 border-t-transparent rounded-full" />
            </div>
            <div className="h-2.5 bg-blue-500/20 rounded-full w-36" />
          </div>
          <div className="flex items-center gap-3 opacity-40">
            <div className="w-5 h-5 rounded-full border-2 border-neutral-700" />
            <div className="h-2.5 bg-neutral-800 rounded-full w-28" />
          </div>
        </div>
      </div>
    ),
  },
  {
    number: '03',
    title: 'Launch & Customize',
    description: 'Your website is live instantly. Edit anything with Elementor\'s visual builder, add plugins, connect your domain, and start growing your business.',
    mockup: (
      <div className="bg-neutral-900 rounded-xl p-6 border border-neutral-800">
        <div className="flex items-center gap-2 mb-4">
          <div className="w-3 h-3 rounded-full bg-red-500/60" />
          <div className="w-3 h-3 rounded-full bg-yellow-500/60" />
          <div className="w-3 h-3 rounded-full bg-green-500/60" />
        </div>
        <div className="space-y-3">
          <div className="bg-neutral-800 rounded-lg p-3">
            <div className="h-16 bg-gradient-to-r from-neutral-700 to-neutral-800 rounded-md mb-3" />
            <div className="flex gap-2">
              <div className="h-2 bg-neutral-700 rounded-full w-16" />
              <div className="h-2 bg-neutral-700 rounded-full w-12" />
              <div className="h-2 bg-neutral-700 rounded-full w-20" />
            </div>
          </div>
          <div className="flex gap-2">
            <div className="flex-1 bg-neutral-800 rounded-lg p-2 h-12" />
            <div className="flex-1 bg-neutral-800 rounded-lg p-2 h-12" />
            <div className="flex-1 bg-neutral-800 rounded-lg p-2 h-12" />
          </div>
        </div>
      </div>
    ),
  },
];

export default function HowItWorks() {
  return (
    <section id="how-it-works" className="py-32 px-6 bg-black text-white relative overflow-hidden">
      {/* Subtle radial glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-neutral-900 rounded-full blur-3xl opacity-50" />

      <div className="relative z-10 max-w-7xl mx-auto">
        {/* Section header */}
        <div className="text-center mb-20">
          <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-6">
            <span className="text-[11px] font-semibold text-neutral-400 tracking-widest uppercase">Process</span>
          </div>
          <h2 className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tighter mb-4">
            How it works
          </h2>
          <p className="text-lg text-neutral-500 max-w-xl mx-auto leading-relaxed">
            Three simple steps from idea to a live, professional website.
            No technical skills required.
          </p>
        </div>

        {/* Steps */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-6">
          {steps.map((step, index) => (
            <div key={index} className="group">
              <div className="mb-8">
                {step.mockup}
              </div>
              <div className="flex items-start gap-4">
                <span className="text-5xl font-black text-neutral-800 group-hover:text-neutral-600 transition-colors leading-none">{step.number}</span>
                <div>
                  <h3 className="text-xl font-bold mb-2 tracking-tight">{step.title}</h3>
                  <p className="text-sm text-neutral-500 leading-relaxed">{step.description}</p>
                </div>
              </div>
              {index < steps.length - 1 && (
                <div className="hidden lg:flex items-center justify-end mt-6 pr-4">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#404040" strokeWidth="2" strokeLinecap="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                  </svg>
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
