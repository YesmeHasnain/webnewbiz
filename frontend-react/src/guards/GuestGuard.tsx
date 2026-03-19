import { Navigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function GuestGuard({ children }: { children: React.ReactNode }) {
  const { isLoggedIn, loading } = useAuth();

  if (loading) {
    return (
      <div className="flex items-center justify-center h-screen">
        <div className="w-8 h-8 border-3 border-neutral-200 border-t-black rounded-full" style={{ animation: 'spin 0.8s linear infinite' }} />
      </div>
    );
  }

  if (isLoggedIn) return <Navigate to="/dashboard" replace />;
  return <>{children}</>;
}
