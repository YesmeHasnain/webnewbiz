import { useState, useEffect } from 'react';
import { billingService, type BillingOverview, type Plan, type CreditPackage, type CreditTransaction, type Invoice } from '../services/billing.service';

type Tab = 'overview' | 'plans' | 'credits' | 'history' | 'invoices';

export default function Billing() {
  const [tab, setTab] = useState<Tab>('overview');
  const [overview, setOverview] = useState<BillingOverview | null>(null);
  const [plans, setPlans] = useState<Plan[]>([]);
  const [packages, setPackages] = useState<CreditPackage[]>([]);
  const [transactions, setTransactions] = useState<CreditTransaction[]>([]);
  const [invoices, setInvoices] = useState<Invoice[]>([]);
  const [billingCycle, setBillingCycle] = useState<'monthly' | 'yearly'>('monthly');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const [ov, pl, pk] = await Promise.all([
        billingService.getOverview(),
        billingService.getPlans(),
        billingService.getPackages(),
      ]);
      setOverview(ov.data);
      setPlans(pl.data);
      setPackages(pk.data);
    } catch (err) {
      console.error('Failed to load billing data:', err);
    } finally {
      setLoading(false);
    }
  };

  const loadTransactions = async () => {
    const res = await billingService.getTransactions();
    setTransactions(res.data);
  };

  const loadInvoices = async () => {
    const res = await billingService.getInvoices();
    setInvoices(res.data);
  };

  const handleTabChange = (t: Tab) => {
    setTab(t);
    if (t === 'history' && !transactions.length) loadTransactions();
    if (t === 'invoices' && !invoices.length) loadInvoices();
  };

  const handlePurchase = async (pkgId: number) => {
    try {
      await billingService.purchaseCredits(pkgId);
      const ov = await billingService.getOverview();
      setOverview(ov.data);
      alert('Credits added successfully!');
    } catch { alert('Purchase failed'); }
  };

  const handleSubscribe = async (planId: number) => {
    try {
      await billingService.subscribe(planId, billingCycle);
      const ov = await billingService.getOverview();
      setOverview(ov.data);
      alert('Subscription activated!');
    } catch { alert('Subscription failed'); }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-[#0a0a0f] flex items-center justify-center">
        <div className="w-10 h-10 border-2 border-blue-500/30 border-t-blue-500 rounded-full animate-spin" />
      </div>
    );
  }

  const tabs: { id: Tab; label: string }[] = [
    { id: 'overview', label: 'Overview' },
    { id: 'plans', label: 'Plans' },
    { id: 'credits', label: 'Buy Credits' },
    { id: 'history', label: 'History' },
    { id: 'invoices', label: 'Invoices' },
  ];

  return (
    <div className="min-h-screen bg-[#0a0a0f] text-white">
      {/* Header */}
      <div className="border-b border-[#1a1d27] bg-[#0d1017]">
        <div className="max-w-6xl mx-auto px-6 py-6">
          <h1 className="text-2xl font-bold">Billing & Credits</h1>
          <p className="text-sm text-gray-500 mt-1">Manage your subscription, credits, and payment history</p>
        </div>
        <div className="max-w-6xl mx-auto px-6 flex gap-1">
          {tabs.map(t => (
            <button
              key={t.id}
              onClick={() => handleTabChange(t.id)}
              className={`px-4 py-2.5 text-sm font-medium border-b-2 transition ${
                tab === t.id ? 'text-blue-400 border-blue-500' : 'text-gray-500 border-transparent hover:text-gray-300'
              }`}
            >
              {t.label}
            </button>
          ))}
        </div>
      </div>

      <div className="max-w-6xl mx-auto px-6 py-8">
        {/* OVERVIEW TAB */}
        {tab === 'overview' && overview && (
          <div className="space-y-6">
            {/* Credit Balance */}
            <div className="bg-gradient-to-br from-blue-600/20 to-purple-600/20 border border-blue-500/20 rounded-2xl p-6">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-400">Credit Balance</p>
                  <p className="text-4xl font-bold mt-1">{overview.credits.toLocaleString()}</p>
                  <p className="text-sm text-gray-500 mt-1">credits available</p>
                </div>
                <div className="text-right">
                  <p className="text-sm text-gray-400">Current Plan</p>
                  <p className="text-xl font-semibold mt-1 capitalize">{overview.subscription_tier}</p>
                  {overview.subscription_ends && (
                    <p className="text-xs text-gray-500 mt-1">Renews {new Date(overview.subscription_ends).toLocaleDateString()}</p>
                  )}
                </div>
              </div>
            </div>

            {/* Credit Costs */}
            <div>
              <h3 className="text-lg font-semibold mb-4">Credit Costs per Action</h3>
              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                {Object.entries(overview.costs).map(([action, cost]) => (
                  <div key={action} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl px-4 py-3">
                    <p className="text-xs text-gray-500 capitalize">{action.replace(/_/g, ' ')}</p>
                    <p className="text-lg font-semibold mt-0.5">{cost} <span className="text-xs text-gray-600">credits</span></p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        )}

        {/* PLANS TAB */}
        {tab === 'plans' && (
          <div className="space-y-6">
            {/* Billing toggle */}
            <div className="flex items-center justify-center gap-3">
              <span className={`text-sm ${billingCycle === 'monthly' ? 'text-white' : 'text-gray-500'}`}>Monthly</span>
              <button
                onClick={() => setBillingCycle(billingCycle === 'monthly' ? 'yearly' : 'monthly')}
                className={`w-12 h-6 rounded-full transition relative ${billingCycle === 'yearly' ? 'bg-blue-600' : 'bg-[#2a2a3a]'}`}
              >
                <div className={`w-5 h-5 bg-white rounded-full absolute top-0.5 transition-all ${billingCycle === 'yearly' ? 'left-6' : 'left-0.5'}`} />
              </button>
              <span className={`text-sm ${billingCycle === 'yearly' ? 'text-white' : 'text-gray-500'}`}>Yearly <span className="text-emerald-400 text-xs">Save 17%</span></span>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              {plans.filter(p => p.slug !== 'enterprise').map(plan => {
                const price = billingCycle === 'monthly' ? plan.price_monthly : Math.round(plan.price_yearly / 12);
                const isCurrentPlan = overview?.subscription_tier === plan.slug;

                return (
                  <div key={plan.id} className={`bg-[#12121a] border rounded-2xl p-6 flex flex-col ${
                    plan.slug === 'pro' ? 'border-blue-500/50 ring-1 ring-blue-500/20' : 'border-[#1e1e2e]'
                  }`}>
                    {plan.slug === 'pro' && (
                      <div className="text-xs text-blue-400 font-semibold mb-2 uppercase">Most Popular</div>
                    )}
                    <h3 className="text-lg font-bold">{plan.name}</h3>
                    <p className="text-xs text-gray-500 mt-1">{plan.description}</p>
                    <div className="mt-4">
                      {price > 0 ? (
                        <><span className="text-3xl font-bold">${(price / 100).toFixed(0)}</span><span className="text-gray-500 text-sm">/mo</span></>
                      ) : plan.slug === 'free' ? (
                        <span className="text-3xl font-bold">Free</span>
                      ) : (
                        <span className="text-lg font-bold text-gray-400">Contact Sales</span>
                      )}
                    </div>
                    <p className="text-sm text-blue-400 mt-2">{plan.credits_monthly > 0 ? `${plan.credits_monthly} credits/month` : plan.slug === 'free' ? '50 credits to start' : 'Unlimited credits'}</p>
                    <ul className="mt-4 space-y-2 flex-1">
                      {plan.features.map((f, i) => (
                        <li key={i} className="flex items-center gap-2 text-xs text-gray-400">
                          <svg className="w-3.5 h-3.5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                          </svg>
                          {f}
                        </li>
                      ))}
                    </ul>
                    <button
                      onClick={() => !isCurrentPlan && handleSubscribe(plan.id)}
                      disabled={isCurrentPlan}
                      className={`mt-6 w-full py-2.5 rounded-xl text-sm font-medium transition ${
                        isCurrentPlan ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 cursor-default' :
                        plan.slug === 'pro' ? 'bg-blue-600 text-white hover:bg-blue-500' :
                        'bg-white/5 text-white border border-[#2a2a3a] hover:bg-white/10'
                      }`}
                    >
                      {isCurrentPlan ? 'Current Plan' : price > 0 ? 'Subscribe' : plan.slug === 'free' ? 'Free' : 'Contact Sales'}
                    </button>
                  </div>
                );
              })}
            </div>
          </div>
        )}

        {/* BUY CREDITS TAB */}
        {tab === 'credits' && (
          <div className="space-y-6">
            <div className="text-center mb-8">
              <p className="text-gray-400">Your balance: <span className="text-white font-bold text-lg">{overview?.credits.toLocaleString()}</span> credits</p>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
              {packages.map(pkg => (
                <div key={pkg.id} className={`bg-[#12121a] border rounded-2xl p-5 flex flex-col ${
                  pkg.is_popular ? 'border-blue-500/50 ring-1 ring-blue-500/20' : 'border-[#1e1e2e]'
                }`}>
                  {pkg.is_popular && <div className="text-xs text-blue-400 font-semibold mb-2">BEST VALUE</div>}
                  <h3 className="text-base font-bold">{pkg.name}</h3>
                  <div className="mt-3">
                    <span className="text-2xl font-bold">${(pkg.price / 100).toFixed(2)}</span>
                  </div>
                  <p className="text-sm text-gray-300 mt-2">{pkg.credits} credits</p>
                  {pkg.bonus_credits > 0 && (
                    <p className="text-xs text-emerald-400 mt-1">+ {pkg.bonus_credits} bonus credits</p>
                  )}
                  <button
                    onClick={() => handlePurchase(pkg.id)}
                    className={`mt-4 w-full py-2 rounded-xl text-sm font-medium transition ${
                      pkg.is_popular ? 'bg-blue-600 text-white hover:bg-blue-500' : 'bg-white/5 text-white border border-[#2a2a3a] hover:bg-white/10'
                    }`}
                  >
                    Purchase
                  </button>
                </div>
              ))}
            </div>
          </div>
        )}

        {/* HISTORY TAB */}
        {tab === 'history' && (
          <div className="space-y-3">
            {transactions.length === 0 ? (
              <p className="text-center text-gray-600 py-12">No transactions yet</p>
            ) : (
              <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
                <table className="w-full text-sm">
                  <thead>
                    <tr className="border-b border-[#1e1e2e] text-gray-500 text-xs">
                      <th className="text-left px-4 py-3">Date</th>
                      <th className="text-left px-4 py-3">Description</th>
                      <th className="text-left px-4 py-3">Type</th>
                      <th className="text-right px-4 py-3">Amount</th>
                      <th className="text-right px-4 py-3">Balance</th>
                    </tr>
                  </thead>
                  <tbody>
                    {transactions.map(tx => (
                      <tr key={tx.id} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
                        <td className="px-4 py-3 text-gray-400">{new Date(tx.created_at).toLocaleDateString()}</td>
                        <td className="px-4 py-3 text-gray-300">{tx.description}</td>
                        <td className="px-4 py-3">
                          <span className={`px-2 py-0.5 rounded text-xs ${
                            tx.type === 'purchase' ? 'bg-emerald-500/10 text-emerald-400' :
                            tx.type === 'usage' ? 'bg-amber-500/10 text-amber-400' :
                            'bg-blue-500/10 text-blue-400'
                          }`}>{tx.type}</span>
                        </td>
                        <td className={`px-4 py-3 text-right font-medium ${tx.amount > 0 ? 'text-emerald-400' : 'text-red-400'}`}>
                          {tx.amount > 0 ? '+' : ''}{tx.amount}
                        </td>
                        <td className="px-4 py-3 text-right text-gray-400">{tx.balance_after}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}

        {/* INVOICES TAB */}
        {tab === 'invoices' && (
          <div className="space-y-3">
            {invoices.length === 0 ? (
              <p className="text-center text-gray-600 py-12">No invoices yet</p>
            ) : (
              <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
                <table className="w-full text-sm">
                  <thead>
                    <tr className="border-b border-[#1e1e2e] text-gray-500 text-xs">
                      <th className="text-left px-4 py-3">Date</th>
                      <th className="text-left px-4 py-3">Description</th>
                      <th className="text-left px-4 py-3">Status</th>
                      <th className="text-right px-4 py-3">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    {invoices.map(inv => (
                      <tr key={inv.id} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
                        <td className="px-4 py-3 text-gray-400">{new Date(inv.created_at).toLocaleDateString()}</td>
                        <td className="px-4 py-3 text-gray-300">{inv.description}</td>
                        <td className="px-4 py-3">
                          <span className={`px-2 py-0.5 rounded text-xs ${
                            inv.status === 'paid' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400'
                          }`}>{inv.status}</span>
                        </td>
                        <td className="px-4 py-3 text-right font-medium">${(inv.amount / 100).toFixed(2)}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
