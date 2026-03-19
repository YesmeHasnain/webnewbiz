import { createContext, useContext, useState, useCallback, type ReactNode } from 'react';

import type { User, AuthResponse } from '../models/types';

interface AuthContextType {
  user: User | null;
  isLoggedIn: boolean;
  loading: boolean;
  handleAuthResponse: (res: AuthResponse) => void;
  logout: () => void;
}

// Dev default user — auth bypassed for now
const devUser: User = { id: 6, name: 'Hasnain', email: 'zenesadigital7234@gmail.com' } as User;

const AuthContext = createContext<AuthContextType>({
  user: devUser,
  isLoggedIn: true,
  loading: false,
  handleAuthResponse: () => {},
  logout: () => {},
});

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(devUser);

  const handleAuthResponse = useCallback((res: AuthResponse) => {
    localStorage.setItem('auth_token', res.token);
    localStorage.setItem('auth_user', JSON.stringify(res.user));
    setUser(res.user);
  }, []);

  const logout = useCallback(() => {
    setUser(devUser); // just reset to dev user
  }, []);

  return (
    <AuthContext.Provider value={{ user, isLoggedIn: true, loading: false, handleAuthResponse, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}