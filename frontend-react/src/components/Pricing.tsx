import { useState } from 'react';
import { Link } from 'react-router-dom';

const plans = [
  {
    name: 'Starter',
    description: 'Perfect for personal projects and small businesses just getting started.',
    monthlyPrice: 10,
    annualPrice: 8,
    features: [
      '1 Website',
      'AI Content Generation',
      'Free SSL Certificate',
      '5 GB Storage',
      'Community Support',
      'Basic Analytics',
    ],
    cta: 'Get Started',
    popular: false,
  },
  {
    name: 'Professional',
    description: 'For growing businesses that need more power and flexibility.',
    monthlyPrice: 20,
    annualPrice: 15,
    features: [
      '5 Websites',
      'AI Content Generation',
      'Free SSL Certificate',
      '25 GB Storage',
      'Priority Support',
      'Advanced Analytics',
      'Custom Domain',
      'E-Commerce (WooCommerce)',
      'SEO Tools',
    ],
    cta: 'Get Started',
    popular: true,
  },
  {
    name: 'Agency',
    description: 'For agencies and teams managing multiple client websites.',
    monthlyPrice: 50,
    annualPrice: 40,
    features: [
      'Unlimited Websites',
      'AI Content Generation',
      'Free SSL Certificate',
      '100 GB Storage',
      'Dedicated Support',
      'Advanced Analytics',
      'Custom Domain',
      'E-Commerce (WooCommerce)',
      'SEO Tools',
      'White Label Branding',
      'Team Collaboration',
      'API Access',
    ],
    cta: 'Get Started',
    popular: false,
  },
];

export default function Pricing() {
  const [annual, setAnnual] = useState(false);

  return (
    <section id="pricing" className="py-32 px-6 relative">
      <div className="max-w-7xl mx-auto">
        {/* Section header */}
        <div className="text-center mb-16">
          <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-neutral-100 border border-neutral-200 mb-6">
            <span className="text-[11px] font-semibold text-neutral-500 tracking-widest uppercase">Pricing</span>
          </div>
          <h2 className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tighter mb-4">
            Simple, transparent pricing
          </h2>
          <p className="text-lg text-neutral-500 max-w-xl mx-auto leading-relaxed mb-10">
            Start for free, upgrade when you need to. No hidden fees,
            no surprises. Cancel anytime.
          </p>

          {/* Toggle */}
          <div className="inline-flex items-center gap-4 p-1.5 rounded-full bg-neutral-100 border border-neutral-200">
            <button
              className={`px-5 py-2 rounded-full text-sm font-semibold transition-all ${!annual ? 'bg-black text-white shadow-sm' : 'text-neutral-500 hover:text-black'}`}
              onClick={() => setAnnual(false)}
            >
              Monthly
            </button>
            <button
              className={`px-5 py-2 rounded-full text-sm font-semibold transition-all flex items-center gap-2 ${annual ? 'bg-black text-white shadow-sm' : 'text-neutral-500 hover:text-black'}`}
              onClick={() => setAnnual(true)}
            >
              Annual
              <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${annual ? 'bg-white/20 text-white' : 'bg-green-100 text-green-700'}`}>
                -20%
              </span>
            </button>
          </div>
        </div>

        {/* Plans grid */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
          {plans.map((plan) => (
            <div
              key={plan.name}
              className={`relative rounded-2xl p-8 transition-all duration-300 ${
                plan.popular
                  ? 'bg-black text-white border-2 border-black shadow-[0_20px_60px_rgba(0,0,0,0.15)] scale-[1.02] md:scale-105'
                  : 'bg-white border border-neutral-200 hover:border-neutral-300 hover:shadow-[0_8px_40px_rgba(0,0,0,0.06)]'
              }`}
            >
              {plan.popular && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-white text-black text-xs font-bold rounded-full shadow-sm">
                  Most Popular
                </div>
              )}

              <div className="mb-6">
                <h3 className={`text-lg font-bold mb-1 ${plan.popular ? 'text-white' : 'text-black'}`}>{plan.name}</h3>
                <p className={`text-sm leading-relaxed ${plan.popular ? 'text-neutral-400' : 'text-neutral-500'}`}>{plan.description}</p>
              </div>

              <div className="mb-8">
                <div className="flex items-baseline gap-1">
                  <span className={`text-5xl font-black tracking-tight ${plan.popular ? 'text-white' : 'text-black'}`}>
                    ${annual ? plan.annualPrice : plan.monthlyPrice}
                  </span>
                  <span className={`text-sm font-medium ${plan.popular ? 'text-neutral-400' : 'text-neutral-500'}`}>/mo</span>
                </div>
                {annual && (
                  <p className={`text-xs mt-1 ${plan.popular ? 'text-neutral-500' : 'text-neutral-400'}`}>
                    Billed annually (${(annual ? plan.annualPrice : plan.monthlyPrice) * 12}/year)
                  </p>
                )}
              </div>

              <Link
                to="/builder"
                className={`block w-full text-center py-3 px-6 rounded-xl text-sm font-semibold transition-all ${
                  plan.popular
                    ? 'bg-white text-black hover:bg-neutral-100'
                    : 'bg-black text-white hover:bg-neutral-800'
                }`}
              >
                {plan.cta}
              </Link>

              <div className="mt-8 space-y-3">
                {plan.features.map((feature) => (
                  <div key={feature} className="flex items-center gap-3">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke={plan.popular ? '#a3a3a3' : '#a3a3a3'}
                      strokeWidth="2.5"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    >
                      <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    <span className={`text-sm ${plan.popular ? 'text-neutral-300' : 'text-neutral-600'}`}>{feature}</span>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
