import React, { useState } from 'react';

export default function App() {
  const [from, setFrom] = useState('');
  const [to, setTo] = useState('');
  const [stats, setStats] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchStats = async () => {
    if (!from || !to) {
      alert('Please select both From and To dates');
      return;
    }

    setLoading(true);
    setError(null);

    try {
      const res = await fetch(`/api/stats?from=${from}&to=${to}`);
      if (!res.ok) {
        throw new Error(`Error ${res.status}`);
      }
      const data = await res.json();
      setStats(data);
    } catch (e) {
      setError(e.message);
      setStats([]);
    } finally {
      setLoading(false);
    }
  };

  return (
      <div style={{ maxWidth: 600, margin: 'auto', padding: 20, fontFamily: 'Arial' }}>
        <h1>Page Visit Stats</h1>

        <label>
          From:{' '}
          <input
              type="date"
              value={from}
              onChange={(e) => setFrom(e.target.value)}
          />
        </label>

        <label style={{ marginLeft: 20 }}>
          To:{' '}
          <input
              type="date"
              value={to}
              onChange={(e) => setTo(e.target.value)}
          />
        </label>

        <button
            style={{ marginLeft: 20, padding: '4px 12px' }}
            onClick={fetchStats}
            disabled={loading}
        >
          {loading ? 'Loading...' : 'Get Stats'}
        </button>

        {error && (
            <div style={{ marginTop: 20, color: 'red' }}>
              Error: {error}
            </div>
        )}

        {!error && stats.length > 0 && (
            <table style={{ marginTop: 20, width: '100%', borderCollapse: 'collapse' }}>
              <thead>
              <tr>
                <th style={{ borderBottom: '1px solid #ccc', textAlign: 'left', padding: '8px' }}>URL</th>
                <th style={{ borderBottom: '1px solid #ccc', textAlign: 'right', padding: '8px' }}>Unique Visits</th>
              </tr>
              </thead>
              <tbody>
              {stats.map(({ url, unique_visits }) => (
                  <tr key={url}>
                    <td style={{ borderBottom: '1px solid #eee', padding: '8px' }}>{url}</td>
                    <td style={{ borderBottom: '1px solid #eee', padding: '8px', textAlign: 'right' }}>{unique_visits}</td>
                  </tr>
              ))}
              </tbody>
            </table>
        )}

        {!error && stats.length === 0 && !loading && (
            <p style={{ marginTop: 20 }}>No stats to display.</p>
        )}
      </div>
  );
}
