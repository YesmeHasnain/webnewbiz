import { useState } from 'react';

interface Props { title: string; description: string; icon: string; fields: Array<{ name: string; placeholder: string; type?: string }> }

export default function BrandingTool({ title, description, icon, fields }: Props) {
  const [values, setValues] = useState<Record<string, string>>({});
  const [generated, setGenerated] = useState(false);

  const update = (name: string, val: string) => setValues(v => ({ ...v, [name]: val }));

  return (
    <div className="p-6">
      <div className="flex items-center gap-3 mb-6">
        <span className="text-2xl">{icon}</span>
        <div><h1 className="text-xl font-bold text-white">{title}</h1><p className="text-sm text-gray-500">{description}</p></div>
      </div>

      <div className="grid grid-cols-2 gap-6">
        <div className="space-y-4">
          {fields.map(f => (
            <div key={f.name}>
              <label className="text-xs text-gray-500 block mb-1.5">{f.name}</label>
              {f.type === 'textarea' ? (
                <textarea value={values[f.name] || ''} onChange={e => update(f.name, e.target.value)} placeholder={f.placeholder} rows={3} className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50 resize-none" />
              ) : (
                <input value={values[f.name] || ''} onChange={e => update(f.name, e.target.value)} placeholder={f.placeholder} className="w-full bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500/50" />
              )}
            </div>
          ))}
          <button onClick={() => setGenerated(true)} className="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-xl text-sm font-medium text-white">Generate with AI</button>
        </div>

        <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl p-8 flex items-center justify-center min-h-[300px]">
          {generated ? (
            <div className="text-center">
              <div className="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-2xl">{icon}</div>
              <p className="text-sm text-white font-medium">Preview Generated!</p>
              <p className="text-xs text-gray-500 mt-1">Your {title.toLowerCase()} is ready</p>
              <div className="flex gap-2 mt-4 justify-center">
                <button className="px-4 py-2 bg-blue-600 rounded-lg text-xs text-white">Download</button>
                <button className="px-4 py-2 bg-white/5 rounded-lg text-xs text-gray-300">Edit</button>
              </div>
            </div>
          ) : (
            <div className="text-center">
              <p className="text-sm text-gray-600">Fill in the details and click generate</p>
              <p className="text-xs text-gray-700 mt-1">AI will create a professional design</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

// Individual branding pages
export function BusinessCard() { return <BrandingTool title="Business Card" description="AI-generated professional business cards" icon="💼" fields={[{ name: 'Name', placeholder: 'John Doe' }, { name: 'Title', placeholder: 'CEO' }, { name: 'Company', placeholder: 'Acme Inc' }, { name: 'Email', placeholder: 'john@acme.com' }, { name: 'Phone', placeholder: '+1 555 0123' }]} />; }
export function SocialMedia() { return <BrandingTool title="Social Media Kit" description="Create branded social media graphics" icon="📱" fields={[{ name: 'Brand Name', placeholder: 'Your Brand' }, { name: 'Tagline', placeholder: 'Your tagline here' }, { name: 'Style', placeholder: 'Modern, Minimal, Bold...' }, { name: 'Colors', placeholder: '#3b82f6, #8b5cf6' }]} />; }
export function SocialPublishing() { return <BrandingTool title="Social Publishing" description="Schedule and publish social media posts" icon="📢" fields={[{ name: 'Post Text', placeholder: 'Your post content...', type: 'textarea' }, { name: 'Platform', placeholder: 'Instagram, Twitter, LinkedIn...' }, { name: 'Schedule', placeholder: '2026-04-01 10:00 AM' }]} />; }
export function AdCampaigns() { return <BrandingTool title="Ad Campaigns" description="Design ad creatives with AI" icon="📣" fields={[{ name: 'Campaign Name', placeholder: 'Summer Sale' }, { name: 'Headline', placeholder: 'Up to 50% off everything' }, { name: 'Description', placeholder: 'Shop now...', type: 'textarea' }, { name: 'Platform', placeholder: 'Google, Facebook, Instagram' }]} />; }
export function LinkInBio() { return <BrandingTool title="Link in Bio" description="Create a branded link-in-bio page" icon="🔗" fields={[{ name: 'Display Name', placeholder: 'Your Name' }, { name: 'Bio', placeholder: 'A short bio...', type: 'textarea' }, { name: 'Link 1', placeholder: 'https://yoursite.com' }, { name: 'Link 2', placeholder: 'https://shop.com' }]} />; }
export function EmailSignature() { return <BrandingTool title="Email Signature" description="Professional HTML email signatures" icon="✉️" fields={[{ name: 'Full Name', placeholder: 'John Doe' }, { name: 'Title', placeholder: 'CEO' }, { name: 'Company', placeholder: 'Acme Inc' }, { name: 'Phone', placeholder: '+1 555 0123' }, { name: 'Website', placeholder: 'https://acme.com' }]} />; }
export function BrandedInvoices() { return <BrandingTool title="Branded Invoices" description="Custom invoice templates for your business" icon="📄" fields={[{ name: 'Business Name', placeholder: 'Acme Inc' }, { name: 'Address', placeholder: '123 Main St, City' }, { name: 'Tax ID', placeholder: 'XX-XXXXXXX' }, { name: 'Payment Terms', placeholder: 'Net 30' }]} />; }
