// Auth bypassed for development — will re-enable later
export default function AuthGuard({ children }: { children: React.ReactNode }) {
  return <>{children}</>;
}