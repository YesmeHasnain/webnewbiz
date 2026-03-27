import { useState } from 'react';

export default function EcomCustomers() {
  const [search, setSearch] = useState('');
  const customers = [
    { id: 1, name: 'John Doe', email: 'john@example.com', orders: 5, spent: 249.99, lastOrder: '2026-03-15' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', orders: 3, spent: 159.50, lastOrder: '2026-03-20' },
  ];

  return (
    <div className="p-6">
      <div className="flex items-center justify-between mb-6">
        <div><h1 className="text-xl font-bold text-white">Customers</h1><p className="text-sm text-gray-500 mt-1">WooCommerce customer management</p></div>
        <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search customers..." className="bg-[#0a0a12] border border-[#1e1e2e] rounded-xl px-4 py-2 text-sm text-white outline-none w-64" />
      </div>
      <div className="bg-[#12121a] border border-[#1e1e2e] rounded-2xl overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-[#1e1e2e]">
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Customer</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Email</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Orders</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Total Spent</th>
            <th className="text-left text-xs text-gray-500 font-medium px-5 py-3">Last Order</th>
          </tr></thead>
          <tbody>{customers.filter(c => c.name.toLowerCase().includes(search.toLowerCase())).map(c => (
            <tr key={c.id} className="border-b border-[#1e1e2e]/50 hover:bg-white/[0.02]">
              <td className="px-5 py-3 text-sm text-white">{c.name}</td>
              <td className="px-5 py-3 text-sm text-gray-400">{c.email}</td>
              <td className="px-5 py-3 text-sm text-gray-300">{c.orders}</td>
              <td className="px-5 py-3 text-sm text-emerald-400">${c.spent}</td>
              <td className="px-5 py-3 text-sm text-gray-500">{c.lastOrder}</td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}
