import { useState } from 'react';

export default function EcomSettings() {
  const [settings, setSettings] = useState({
    currency: 'USD', taxEnabled: true, taxRate: '10', shippingFlat: '5.99',
    freeShippingMin: '50', paymentGateway: 'stripe', inventoryTracking: true,
  });

  const update = (key: string, val: unknown) => setSettings(s => ({ ...s, [key]: val }));

  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Store Settings</h1>
      <p className="text-sm text-gray-500 mb-6">Configure your WooCommerce store</p>
      <div className="space-y-6 max-w-2xl">
        <Section title="General">
          <Field label="Currency"><select value={settings.currency} onChange={e => update('currency', e.target.value)} className="input-style">
            <option value="USD">USD ($)</option><option value="EUR">EUR</option><option value="GBP">GBP</option><option value="PKR">PKR</option>
          </select></Field>
        </Section>
        <Section title="Tax">
          <div className="flex items-center justify-between"><span className="text-sm text-gray-300">Enable Tax</span><Toggle checked={settings.taxEnabled} onChange={v => update('taxEnabled', v)} /></div>
          {settings.taxEnabled && <Field label="Tax Rate (%)"><input value={settings.taxRate} onChange={e => update('taxRate', e.target.value)} className="input-style" /></Field>}
        </Section>
        <Section title="Shipping">
          <Field label="Flat Rate ($)"><input value={settings.shippingFlat} onChange={e => update('shippingFlat', e.target.value)} className="input-style" /></Field>
          <Field label="Free Shipping Above ($)"><input value={settings.freeShippingMin} onChange={e => update('freeShippingMin', e.target.value)} className="input-style" /></Field>
        </Section>
        <Section title="Inventory">
          <div className="flex items-center justify-between"><span className="text-sm text-gray-300">Track Inventory</span><Toggle checked={settings.inventoryTracking} onChange={v => update('inventoryTracking', v)} /></div>
        </Section>
        <button className="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white">Save Settings</button>
      </div>
    </div>
  );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (<div className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 space-y-4"><h3 className="text-sm font-semibold text-gray-300 mb-2">{title}</h3>{children}</div>);
}
function Field({ label, children }: { label: string; children: React.ReactNode }) {
  return (<div><label className="text-xs text-gray-500 block mb-1.5">{label}</label>{children}</div>);
}
function Toggle({ checked, onChange }: { checked: boolean; onChange: (v: boolean) => void }) {
  return (<button onClick={() => onChange(!checked)} className={`w-10 h-5 rounded-full transition ${checked ? 'bg-blue-600' : 'bg-gray-700'}`}><div className={`w-4 h-4 rounded-full bg-white transition-transform ${checked ? 'translate-x-5' : 'translate-x-0.5'}`} /></button>);
}
