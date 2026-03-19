import { Link, useLocation } from 'react-router-dom';

export default function ComingSoon() {
  const location = useLocation();

  // Derive page title from the URL path
  const getPageTitle = (): string => {
    const segments = location.pathname.split('/').filter(Boolean);
    const lastSegment = segments[segments.length - 1] || '';
    // Convert slug to title: "abandoned-carts" -> "Abandoned Carts"
    return lastSegment
      .split('-')
      .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
      .join(' ') || 'Coming Soon';
  };

  // Build relative path to manage (go up to parent, then to manage)
  const getManageLink = (): string => {
    const segments = location.pathname.split('/').filter(Boolean);
    // Pattern: /websites/:id/... -> /websites/:id/manage
    if (segments.length >= 2 && segments[0] === 'websites') {
      return `/websites/${segments[1]}/manage`;
    }
    return '../manage';
  };

  return (
    <div style={styles.container}>
      <div style={styles.iconWrap}>
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="1.5"
            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
          />
        </svg>
      </div>
      <h2 style={styles.title}>{getPageTitle()}</h2>
      <p style={styles.description}>
        This feature is coming soon. We're working hard to bring you the best experience.
      </p>
      <Link to={getManageLink()} style={styles.backBtn} className="cs-back-btn">
        Back to Main
      </Link>

      <style>{`
        .cs-back-btn:hover {
          background: #1F2937 !important;
        }
      `}</style>
    </div>
  );
}

const styles: Record<string, React.CSSProperties> = {
  container: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    minHeight: 400,
    textAlign: 'center',
    padding: 40,
  },
  iconWrap: {
    width: 80,
    height: 80,
    borderRadius: 20,
    background: '#F3F4F6',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 20,
    color: '#9CA3AF',
  },
  title: {
    fontSize: 20,
    fontWeight: 600,
    color: '#111827',
    margin: '0 0 8px',
  },
  description: {
    fontSize: 14,
    color: '#6B7280',
    maxWidth: 400,
    margin: '0 0 24px',
    lineHeight: 1.5,
  },
  backBtn: {
    padding: '8px 20px',
    background: '#111827',
    color: '#fff',
    borderRadius: 8,
    fontSize: 13,
    fontWeight: 500,
    textDecoration: 'none',
  },
};
