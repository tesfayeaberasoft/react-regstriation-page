
import { useState } from "react";

// Local PHP API URL
const API_URL =
  "http://localhost/react-php-registration/backend/register.php";

export default function App() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");

  const [message, setMessage] = useState("");
  const [isError, setIsError] = useState(false);
  const [loading, setLoading] = useState(false);

  async function handleSubmit(event) {
    event.preventDefault();

    setMessage("");

    if (password.length < 8) {
      setIsError(true);
      setMessage("Password must be at least 8 characters.");
      return;
    }

    if (password !== confirmPassword) {
      setIsError(true);
      setMessage("Passwords do not match.");
      return;
    }

    setLoading(true);

    try {
      const response = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          email: email,
          password: password
        })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(
          data.message || "Registration failed."
        );
      }

      setIsError(false);
      setMessage(data.message);

      setEmail("");
      setPassword("");
      setConfirmPassword("");

    } catch (error) {
      setIsError(true);
      setMessage(
        error.message || "Could not connect to server."
      );
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className="page">
      <section className="card">

        <div className="brand-mark">+</div>

        <p className="eyebrow">WELCOME</p>

        <h1>Create your account</h1>

        <p className="subtitle">
          Register with your email and a secure password.
        </p>

        <form onSubmit={handleSubmit}>

          <label htmlFor="email">
            Email address
          </label>

          <input
            id="email"
            type="email"
            autoComplete="email"
            placeholder="you@example.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />

          <label htmlFor="password">
            Password
          </label>

          <input
            id="password"
            type="password"
            autoComplete="new-password"
            placeholder="At least 8 characters"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            minLength={8}
            required
          />

          <label htmlFor="confirmPassword">
            Confirm password
          </label>

          <input
            id="confirmPassword"
            type="password"
            autoComplete="new-password"
            placeholder="Enter password again"
            value={confirmPassword}
            onChange={(e) =>
              setConfirmPassword(e.target.value)
            }
            required
          />

          <button type="submit" disabled={loading}>
            {loading
              ? "Creating account..."
              : "Create account"}
          </button>

        </form>

        {message && (
          <p
            className={
              isError
                ? "message error"
                : "message success"
            }
            role="status"
          >
            {message}
          </p>
        )}

        <p className="footnote">
          Your password is securely hashed by the PHP server.
        </p>

      </section>
    </main>
  );
}
