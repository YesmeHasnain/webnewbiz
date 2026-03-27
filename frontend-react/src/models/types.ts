export interface User {
  id: number;
  name: string;
  email: string;
  created_at: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}

export interface Website {
  id: number;
  name: string;
  slug: string;
  business_type: string;
  ai_prompt: string;
  ai_theme: string;
  pages?: string[];
  status: 'pending' | 'building' | 'active' | 'failed';
  build_step: string | null;
  build_log: string[];
  url: string | null;
  home_page_id?: number;
  wp_admin_url?: string;
  elementor_url?: string;
  auto_login_url?: string;
  created_at: string;
}

export interface GenerateParams {
  business_name: string;
  business_type: string;
  prompt: string;
  layout: string;
  pages: string[];
}

export interface AnalysisResult {
  business_name: string;
  business_type: string;
  features: string[];
  pages: string[];
  recommended_layout: string;
  reasoning: string;
}

export interface LayoutOption {
  slug: string;
  name: string;
  description: string;
  style: string;
  primary_color: string;
  accent: string;
  preview_bg: string;
  is_dark: boolean;
  keywords: string[];
  best_for: string[];
}

export interface AIQuestion {
  id: string;
  type: 'text' | 'yesno';
  question: string;
  context: string;
  placeholder?: string;
}

export interface QuestionAnalysis {
  business_name: string;
  business_type: string;
  questions: AIQuestion[];
  suggested_style: string;
}

export interface BuildSummary {
  business_name: string;
  business_type: string;
  summary: string;
  features: string[];
  pages: string[];
  theme: string;
}

export interface StatusResponse {
  id: number;
  status: string;
  build_step: string;
  build_log: string[];
  url: string;
  name: string;
  wp_admin_url?: string;
}

// ─── Code Builder Project Types ───

export interface Project {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  framework: 'html' | 'react' | 'nextjs' | 'vue' | 'angular' | 'svelte';
  status: 'draft' | 'generating' | 'ready' | 'deployed';
  ai_prompt: string | null;
  file_tree: FileTreeNode[];
  created_at: string;
  updated_at: string;
}

export interface FileTreeNode {
  name: string;
  path: string;
  type: 'file' | 'directory';
  children?: FileTreeNode[];
}

export interface ProjectMessage {
  id: number;
  role: 'user' | 'assistant';
  content: string;
  files_changed: string[] | null;
  created_at: string;
}

// ─── WP Management Types ───

export interface WpPlugin {
  file: string;
  name: string;
  slug: string;
  version: string;
  is_active: boolean;
  description: string;
  author: string;
  url: string;
  update_available: string | null;
}

export interface WpTheme {
  slug: string;
  name: string;
  version: string;
  is_active: boolean;
  screenshot: string;
  description: string;
  author: string;
  update_available: string | null;
}

export interface SiteOverview {
  wp_version: string;
  php_version: string;
  server_software: string;
  disk_usage_mb: number;
  db_size_mb: number;
  active_plugins: number;
  total_plugins: number;
  active_theme: string;
  total_pages: number;
  total_posts: number;
  total_users: number;
  site_url: string;
  site_title: string;
  admin_email: string;
  multisite: boolean;
  woocommerce_active: boolean;
}

export interface UpdatesInfo {
  core: { current: string; new_version: string | null };
  plugins: Array<{ file: string; name: string; current: string; new_version: string }>;
  themes: Array<{ slug: string; name: string; current: string; new_version: string }>;
}

export interface WpPage {
  id: number;
  title: string;
  slug: string;
  status: string;
  url: string;
  template: string;
  modified: string;
}

export interface Backup {
  id: number;
  filename: string;
  size_bytes: number;
  type: 'manual' | 'auto';
  notes: string | null;
  status: string;
  created_at: string;
}

export interface CustomDomain {
  id: number;
  domain: string;
  type: 'primary' | 'alias' | 'redirect';
  status: 'pending' | 'active' | 'failed';
  verified_at: string | null;
  created_at: string;
}

// ─── WooCommerce Types ───

export interface WooProduct {
  id: number;
  name: string;
  slug: string;
  status: 'publish' | 'draft' | 'pending' | 'private' | 'trash';
  type: string;
  description: string;
  short_description: string;
  sku: string;
  regular_price: string;
  sale_price: string;
  price: string;
  stock_status: 'instock' | 'outofstock' | 'onbackorder';
  stock_quantity: number | null;
  weight: string;
  virtual: boolean;
  featured_image: string;
  gallery_images: string[];
  categories: { id: number; name: string; slug: string }[];
  date_created: string;
  date_modified: string;
  permalink: string;
}

export interface WooOrder {
  id: number;
  status: string;
  total: string;
  currency: string;
  customer: string;
  email: string;
  items_count: number;
  date_created: string;
  payment_method: string;
}

export interface WooCategory {
  id: number;
  name: string;
  slug: string;
  count: number;
}
