import api from './api';

export interface BillingOverview {
  credits: number;
  subscription_tier: string;
  subscription_ends: string | null;
  costs: Record<string, number>;
}

export interface Plan {
  id: number;
  slug: string;
  name: string;
  description: string;
  price_monthly: number;
  price_yearly: number;
  credits_monthly: number;
  features: string[];
  is_active: boolean;
}

export interface CreditPackage {
  id: number;
  name: string;
  credits: number;
  price: number;
  bonus_credits: number;
  is_popular: boolean;
}

export interface CreditTransaction {
  id: number;
  amount: number;
  balance_after: number;
  type: string;
  action: string | null;
  description: string;
  created_at: string;
}

export interface Invoice {
  id: number;
  type: string;
  amount: number;
  currency: string;
  status: string;
  description: string;
  paid_at: string | null;
  created_at: string;
}

export const billingService = {
  getOverview: () => api.get<BillingOverview>('/billing/overview'),
  getPlans: () => api.get<Plan[]>('/billing/plans'),
  getPackages: () => api.get<CreditPackage[]>('/billing/packages'),
  getTransactions: () => api.get<CreditTransaction[]>('/billing/transactions'),
  getInvoices: () => api.get<Invoice[]>('/billing/invoices'),
  purchaseCredits: (packageId: number) => api.post('/billing/purchase-credits', { package_id: packageId }),
  subscribe: (planId: number, billingCycle: 'monthly' | 'yearly' = 'monthly') =>
    api.post('/billing/subscribe', { plan_id: planId, billing_cycle: billingCycle }),
};
