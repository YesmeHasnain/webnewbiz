import { Link, useParams } from 'react-router-dom';

export default function BrandingOverview() {
  const { id } = useParams();
  const tools = [
    { name: 'Logo & Assets', desc: 'Upload and generate logos with AI', to: `/websites/${id}/branding/logo-assets`, icon: '🎨', color: 'blue' },
    { name: 'Business Cards', desc: 'Design professional business cards', to: `/websites/${id}/branding/business-card`, icon: '💼', color: 'purple' },
    { name: 'Social Media', desc: 'Create social media graphics', to: `/websites/${id}/branding/social-media`, icon: '📱', color: 'pink' },
    { name: 'Social Publishing', desc: 'Schedule and publish posts', to: `/websites/${id}/branding/social-publishing`, icon: '📢', color: 'cyan' },
    { name: 'Ad Campaigns', desc: 'Design ad creatives for campaigns', to: `/websites/${id}/branding/ad-campaigns`, icon: '📣', color: 'amber' },
    { name: 'Link in Bio', desc: 'Create a link-in-bio page', to: `/websites/${id}/branding/link-in-bio`, icon: '🔗', color: 'emerald' },
    { name: 'Email Signature', desc: 'Professional email signatures', to: `/websites/${id}/branding/email-signature`, icon: '✉️', color: 'red' },
    { name: 'Invoices', desc: 'Branded invoice templates', to: `/websites/${id}/branding/invoices`, icon: '📄', color: 'teal' },
  ];

  return (
    <div className="p-6">
      <h1 className="text-xl font-bold text-white mb-1">Brand Studio</h1>
      <p className="text-sm text-gray-500 mb-6">Create consistent branding across all touchpoints</p>
      <div className="grid grid-cols-2 gap-4">
        {tools.map(t => (
          <Link key={t.name} to={t.to} className="bg-[#12121a] border border-[#1e1e2e] rounded-xl p-5 hover:border-blue-500/30 transition group">
            <span className="text-2xl">{t.icon}</span>
            <h3 className="text-sm font-medium text-white mt-3 group-hover:text-blue-400 transition">{t.name}</h3>
            <p className="text-xs text-gray-600 mt-1">{t.desc}</p>
          </Link>
        ))}
      </div>
    </div>
  );
}
