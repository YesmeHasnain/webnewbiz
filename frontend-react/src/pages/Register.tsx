import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { authService } from '../services/auth.service';

export default function Register() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { handleAuthResponse } = useAuth();
  const navigate = useNavigate();

  const onSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name || !email || !password) return;
    setLoading(true);
    setError('');
    try {
      const res = await authService.register(name, email, password);
      handleAuthResponse(res.data);
      const pendingPrompt = sessionStorage.getItem('pending_prompt');
      if (pendingPrompt) {
        sessionStorage.removeItem('pending_prompt');
        navigate('/builder?prompt=' + encodeURIComponent(pendingPrompt));
      } else {
        navigate('/dashboard');
      }
    } catch (err: any) {
      setLoading(false);
      setError(err.response?.data?.message || err.response?.data?.errors?.email?.[0] || 'Registration failed');
    }
  };

  return (
    <div className="auth-page">
      <div className="auth-container">
        {/* Left panel */}
        <div className="auth-left">
          <div className="auth-left-content">
            <Link to="/" className="auth-logo">
              <div className="auth-logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                </svg>
              </div>
              <span className="auth-logo-text">WebNewBiz</span>
            </Link>
            <h2 className="auth-left-title">Start building in seconds</h2>
            <p className="auth-left-desc">Create a professional AI-powered website. No coding required.</p>
          </div>
        </div>

        {/* Right panel */}
        <div className="auth-right">
          <div className="auth-form-wrap">
            <h1 className="auth-title">Create your account</h1>
            <p className="auth-subtitle">Get started for free</p>

            {error && <div className="auth-error">{error}</div>}

            <form onSubmit={onSubmit} className="auth-form">
              <div className="auth-field">
                <label>Full Name</label>
                <input type="text" value={name} onChange={e => setName(e.target.value)} required
                  placeholder="John Doe" />
              </div>
              <div className="auth-field">
                <label>Email</label>
                <input type="email" value={email} onChange={e => setEmail(e.target.value)} required
                  placeholder="you@example.com" />
              </div>
              <div className="auth-field">
                <label>Password</label>
                <input type="password" value={password} onChange={e => setPassword(e.target.value)} required minLength={8}
                  placeholder="Min. 8 characters" />
              </div>
              <button type="submit" disabled={loading} className="auth-submit">
                {loading ? (
                  <><span className="auth-spinner" />Creating account...</>
                ) : 'Create Account'}
              </button>
            </form>

            <p className="auth-switch">
              Already have an account? <Link to="/login">Sign in</Link>
            </p>
          </div>
        </div>
      </div>

      <style>{authCSS}</style>
    </div>
  );
}

const authCSS = `
  .auth-page {
    min-height: 100vh;
    background: #fff;
    font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
  }

  .auth-container {
    display: flex;
    min-height: 100vh;
  }

  .auth-left {
    width: 45%;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
  }

  .auth-left-content {
    max-width: 400px;
  }

  .auth-logo {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    margin-bottom: 48px;
  }

  .auth-logo-icon {
    width: 40px;
    height: 40px;
    background: #222;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #333;
  }

  .auth-logo-text {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.02em;
  }

  .auth-left-title {
    font-size: 36px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    letter-spacing: -0.03em;
    margin: 0 0 16px;
  }

  .auth-left-desc {
    font-size: 16px;
    color: #888;
    line-height: 1.6;
    margin: 0;
  }

  .auth-right {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
    background: #fff;
  }

  .auth-form-wrap {
    width: 100%;
    max-width: 380px;
  }

  .auth-title {
    font-size: 28px;
    font-weight: 700;
    color: #000;
    margin: 0 0 6px;
    letter-spacing: -0.02em;
  }

  .auth-subtitle {
    font-size: 14px;
    color: #888;
    margin: 0 0 32px;
  }

  .auth-error {
    padding: 12px 14px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 13px;
    color: #333;
    margin-bottom: 20px;
  }

  .auth-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .auth-field label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #000;
    margin-bottom: 6px;
  }

  .auth-field input {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    color: #000;
    background: #fff;
    font-family: inherit;
    transition: border-color 0.15s;
    outline: none;
    box-sizing: border-box;
  }

  .auth-field input:focus {
    border-color: #000;
  }

  .auth-field input::placeholder {
    color: #bbb;
  }

  .auth-submit {
    width: 100%;
    padding: 13px;
    background: #000;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.15s;
    margin-top: 4px;
  }

  .auth-submit:hover {
    background: #222;
  }

  .auth-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .auth-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: auth-spin 0.6s linear infinite;
  }

  @keyframes auth-spin {
    to { transform: rotate(360deg); }
  }

  .auth-switch {
    text-align: center;
    font-size: 13px;
    color: #888;
    margin-top: 28px;
  }

  .auth-switch a {
    color: #000;
    font-weight: 600;
    text-decoration: none;
  }

  .auth-switch a:hover {
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .auth-left {
      display: none;
    }
    .auth-right {
      padding: 32px 20px;
    }
  }
`;