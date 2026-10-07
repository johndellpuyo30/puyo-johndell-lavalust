import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { api, saveSession, clearSession, hasSession } from './api';
import './style.css';

const blank = {
  product_name: '',
  description: '',
  price: '',
  quantity: ''
};

const money = (n) =>
  new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP'
  }).format(Number(n));

function App() {
  const [user, setUser] = useState(null);
  const [checking, setChecking] = useState(hasSession());
  const [creds, setCreds] = useState({ username: '', password: '' });
  const [items, setItems] = useState([]);
  const [search, setSearch] = useState('');
  const [edit, setEdit] = useState(null);
  const [form, setForm] = useState(blank);
  const [del, setDel] = useState(null);
  const [busy, setBusy] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [notice, setNotice] = useState('');
  const [fields, setFields] = useState({});

  function fail(e) {
    setError(e.message);
    setFields(e.fields || {});
    if (!hasSession()) {
      setUser(null);
      setEdit(null);
      setDel(null);
    }
  }

  async function load() {
    setLoading(true);
    try {
      const data = await api('/products');
      setItems(data.products);
    } catch (e) {
      fail(e);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    if (hasSession()) {
      api('/me')
        .then((x) => setUser(x.user))
        .catch((e) => {
          clearSession();
          setError(e.message);
        })
        .finally(() => setChecking(false));
    }
  }, []);

  useEffect(() => {
    if (user) {
      load();
    }
  }, [user]);

  async function login(e) {
    e.preventDefault();
    setBusy(true);
    setError('');

    try {
      const x = await api('/login', 'POST', creds);
      saveSession(x.tokens);
      setUser(x.user);
      setCreds({ username: '', password: '' });
    } catch (e) {
      fail(e);
    } finally {
      setBusy(false);
    }
  }

  async function logout() {
    setBusy(true);
    try {
      await api('/logout', 'POST', {});
      clearSession();
      setUser(null);
      setItems([]);
      setNotice('Signed out.');
    } catch (e) {
      fail(e);
    } finally {
      setBusy(false);
    }
  }

  function editor(p) {
    setError('');
    setFields({});
    setNotice('');
    setEdit(p || { id: null });
    setForm(
      p
        ? {
            product_name: p.product_name,
            description: p.description,
            price: String(p.price),
            quantity: String(p.quantity)
          }
        : { ...blank }
    );
  }

  async function save(e) {
    e.preventDefault();
    setBusy(true);
    setError('');
    setFields({});

    try {
      const x = await api(
        edit.id ? `/products/${edit.id}` : '/products',
        edit.id ? 'PUT' : 'POST',
        form
      );
      setItems(
        edit.id
          ? items.map((p) => (p.id === x.product.id ? x.product : p))
          : [x.product, ...items]
      );
      setNotice(x.message);
      setEdit(null);
    } catch (e) {
      fail(e);
    } finally {
      setBusy(false);
    }
  }

  async function remove() {
    setBusy(true);
    try {
      const x = await api(`/products/${del.id}`, 'DELETE');
      setItems(items.filter((p) => p.id !== del.id));
      setDel(null);
      setNotice(x.message);
    } catch (e) {
      fail(e);
    } finally {
      setBusy(false);
    }
  }

  const shown = items.filter((p) =>
    (p.product_name + ' ' + p.description).toLowerCase().includes(search.toLowerCase())
  );

  const alert = (
    <>
      {error && (
        <div className="alert error" role="alert">
          {error}
        </div>
      )}
      {notice && (
        <div className="alert success" role="status">
          {notice}
        </div>
      )}
    </>
  );

  if (checking) {
    return <div className="loading">Restoring your session...</div>;
  }

  if (!user) {
    return (
      <main className="login">
        <section className="story">
          <div className="brand">
            <b>P</b> PUYO / INVENTORY
          </div>
          <div>
            <small>PRODUCT MANAGEMENT</small>
            <h1>
              A clear view of<br />every product.
            </h1>
            <p>Keep your catalog organized, your stock accurate, and your next update simple.</p>
          </div>
          <small>LABORATORY EXERCISE 06 · REACT + LAVALUST</small>
        </section>

        <section className="signin">
          <form onSubmit={login}>
            <small>WELCOME BACK</small>
            <h2>Sign in to your workspace</h2>
            <p>Enter your account details to manage products.</p>
            {alert}
            <label>
              Username
              <input
                autoFocus
                autoComplete="username"
                maxLength="100"
                required
                value={creds.username}
                onChange={(e) => setCreds({ ...creds, username: e.target.value })}
              />
            </label>
            <label>
              Password
              <input
                type="password"
                autoComplete="current-password"
                required
                value={creds.password}
                onChange={(e) => setCreds({ ...creds, password: e.target.value })}
              />
            </label>
            <button className="primary wide" disabled={busy}>
              {busy ? 'Signing in...' : 'Sign in →'}
            </button>
            <p className="hint">Use the account created during database setup.</p>
          </form>
        </section>
      </main>
    );
  }

  return (
    <>
      <header>
        <div className="brand">
          <b>P</b> PUYO <span>/ INVENTORY</span>
        </div>
        <div>
          {user.username}{' '}
          <button className="soft" onClick={logout} disabled={busy}>
            Log out
          </button>
        </div>
      </header>

      <main className="content">
        <div className="heading">
          <div>
            <small>YOUR WORKSPACE</small>
            <h1>
              Product catalog<span>.</span>
            </h1>
            <p>Everything you sell, in one place.</p>
          </div>
          <button className="primary" onClick={() => editor(null)}>
            ＋ Add product
          </button>
        </div>

        {alert}

        <section className="stats">
          <div>
            <small>Total products</small>
            <strong>{items.length}</strong>
          </div>
          <div>
            <small>Units in stock</small>
            <strong>
              {items.reduce((a, p) => a + Number(p.quantity), 0).toLocaleString()}
            </strong>
          </div>
          <div>
            <small>Inventory value</small>
            <strong>
              {money(items.reduce((a, p) => a + Number(p.quantity) * Number(p.price), 0))}
            </strong>
          </div>
        </section>

        <section className="catalog">
          <div className="toolbar">
            <h2>
              All products <small>{items.length}</small>
            </h2>
            <div className="tools">
              <input
                placeholder="Search products..."
                aria-label="Search products"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
              <button className="soft" onClick={load} disabled={loading}>
                {loading ? 'Loading...' : 'Refresh'}
              </button>
            </div>
          </div>

          <div className="scroll">
            <table>
              <thead>
                <tr>
                  <th>PRODUCT</th>
                  <th>PRICE</th>
                  <th>STOCK</th>
                  <th>ADDED</th>
                  <th className="right">ACTIONS</th>
                </tr>
              </thead>
              <tbody>
                {shown.map((p) => (
                  <tr key={p.id}>
                    <td>
                      <b>{p.product_name}</b>
                      <p>{p.description || 'No description'}</p>
                    </td>
                    <td>{money(p.price)}</td>
                    <td>
                      <i className={Number(p.quantity) === 0 ? 'out' : ''}>
                        {p.quantity} units
                      </i>
                    </td>
                    <td>{String(p.created_at).slice(0, 10)}</td>
                    <td className="right">
                      <button className="soft" onClick={() => editor(p)}>
                        Edit
                      </button>
                      <button className="delink" onClick={() => setDel(p)}>
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {!shown.length && (
            <div className="empty">
              <h3>
                {loading
                  ? 'Loading...'
                  : search
                  ? 'No matching products'
                  : 'Your catalog starts here'}
              </h3>
              <p>
                {search
                  ? 'Try another product name.'
                  : 'Add your first product to track inventory.'}
              </p>
            </div>
          )}
        </section>

        <footer>
          LABORATORY EXERCISE 06 <span>REACT FRONTEND · LAVALUST API</span>
        </footer>
      </main>

      {/* Edit / Add Modal */}
      {edit && (
        <div className="overlay">
          <section className="modal" role="dialog" aria-modal="true">
            <small>CATALOG DETAILS</small>
            <h2>{edit.id ? 'Edit product' : 'Add product'}</h2>
            {error && <div className="alert error">{error}</div>}
            <form onSubmit={save}>
              <label>
                Product name
                <input
                  autoFocus
                  required
                  maxLength="100"
                  value={form.product_name}
                  onChange={(e) => setForm({ ...form, product_name: e.target.value })}
                />
                {fields.product_name && <em>{fields.product_name}</em>}
              </label>
              <label>
                Description
                <textarea
                  rows="3"
                  value={form.description}
                  onChange={(e) => setForm({ ...form, description: e.target.value })}
                />
                {fields.description && <em>{fields.description}</em>}
              </label>
              <div className="row">
                <label>
                  Price (PHP)
                  <input
                    type="number"
                    min="0"
                    max="99999999.99"
                    step="0.01"
                    required
                    value={form.price}
                    onChange={(e) => setForm({ ...form, price: e.target.value })}
                  />
                  {fields.price && <em>{fields.price}</em>}
                </label>
                <label>
                  Quantity
                  <input
                    type="number"
                    min="0"
                    step="1"
                    required
                    value={form.quantity}
                    onChange={(e) => setForm({ ...form, quantity: e.target.value })}
                  />
                  {fields.quantity && <em>{fields.quantity}</em>}
                </label>
              </div>
              <div className="actions">
                <button type="button" className="soft" onClick={() => setEdit(null)}>
                  Cancel
                </button>
                <button className="primary" disabled={busy}>
                  {busy ? 'Saving...' : edit.id ? 'Save changes' : 'Add product'}
                </button>
              </div>
            </form>
          </section>
        </div>
      )}

      {/* Delete Confirmation Modal */}
      {del && (
        <div className="overlay">
          <section className="modal">
            <small>REMOVE FROM CATALOG</small>
            <h2>Delete this product?</h2>
            <p>
              <b>{del.product_name}</b> will be permanently removed.
            </p>
            {error && <div className="alert error">{error}</div>}
            <div className="actions">
              <button className="soft" onClick={() => setDel(null)}>
                Cancel
              </button>
              <button className="danger" onClick={remove} disabled={busy}>
                Delete product
              </button>
            </div>
          </section>
        </div>
      )}
    </>
  );
}

createRoot(document.getElementById('root')).render(<App />);
