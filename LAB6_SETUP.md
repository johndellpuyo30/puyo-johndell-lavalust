# Laboratory Exercise No. 6

This project now includes a React product-management frontend, a LavaLust REST API, bearer-token authentication, and CLI database migrations. The React app sends every database operation to the API; it never connects to MySQL directly.

## Requirements

- PHP 8.1 or newer with PDO MySQL enabled
- Node.js and npm
- An Aiven MySQL database and its CA certificate when TLS is enabled

The PHP API runs from the existing LavaLust project. The separately deployable React application is in `frontend/`.

## Configure Aiven and secrets

1. Create a private `.env` based on `.env.aiven.example` and enter your Aiven host, port, username, password, database name, and CA certificate path. Do not commit `.env` or database credentials.
2. Generate different API signing keys from the project root:

   ```sh
   php lava jwt:generate
   ```

   This writes `JWT_SECRET` and `REFRESH_TOKEN_KEY` to `.env`. Keep them private and stable; replacing them invalidates existing login sessions.
3. Set `FRONTEND_ORIGIN` to the exact browser origin that will host the React app. During local development, use `http://localhost:5173`.
4. Set `LAB6_ADMIN_USERNAME` and `LAB6_ADMIN_PASSWORD` in `.env`. Use a password with at least 12 characters. The seed command hashes the password and refuses to replace an existing account.

## Create the database schema and account

The project has existing Lab 5 data, so the Lab 6 product migration uses `CREATE TABLE IF NOT EXISTS`, and its rollback intentionally preserves product data. Migrations are enabled only in CLI requests.

```sh
php lava migration status
php lava migration run
php lava migration seed
php lava migration status
```

This creates the migration tracker, `users` and `refresh_tokens` tables, adds password and role fields to the existing users table where needed, and creates `products` with the required Lab 6 fields. The admin account uses your private seed environment values.

To change a password for an account that already exists, use the local CLI command:

```sh
php lava user:password lab6admin
```

Enter and confirm a memorable passphrase of 12 to 72 characters when prompted. The command updates the password hash in `users`; it does not change your Aiven database login password. Password input is typed in the local terminal, so avoid running it while screen sharing.

To create a future migration:

```sh
php lava migration create-migration create_categories_table
```

Use `php lava migration rollback` only after reviewing the migration's `down()` method. `rollback-all` and `refresh` can remove existing schema and data; use them only on a disposable development database. The included seed and migrations are for initial setup, not for erasing the existing Lab 5 database.

## Run locally

In terminal 1, from the project root:

```sh
php lava serve 3000
```

In terminal 2:

```sh
cd frontend
npm install
```

Copy `frontend/.env.example` to `frontend/.env` and set `VITE_API_URL=http://127.0.0.1:3000/api`, then run:

```sh
npm run dev
```

Open the Vite address, sign in with the account you seeded, and try adding, editing, and deleting products. You can check `http://127.0.0.1:3000/api/health` for API availability; product routes require a bearer token.

## API routes

| Method | Path | Access |
| --- | --- | --- |
| GET | `/api/health` | Public health check |
| POST | `/api/login` | Public; returns access and refresh tokens |
| POST | `/api/refresh` | Public; rotates the refresh token |
| POST | `/api/logout` | Authenticated; revokes the session |
| GET | `/api/me` | Authenticated |
| GET | `/api/products` | Authenticated |
| GET | `/api/products/{id}` | Authenticated |
| POST | `/api/products` | Authenticated |
| PUT or PATCH | `/api/products/{id}` | Authenticated |
| DELETE | `/api/products/{id}` | Authenticated |

Product JSON fields are `product_name`, `description`, `price`, and `quantity`. API responses use the LavaLust `Api` library and JSON. Access tokens expire after 15 minutes; the frontend renews them with its refresh token. Logging out revokes the refresh-token session and makes its access token unusable immediately.

Migration operations are intentionally CLI-only. Although LavaLust routes are registered so its CLI can dispatch them, the migration controller rejects HTTP requests before loading the migration library.

## Build and deploy

The repository includes a Render Blueprint for the PHP API. Push this project to a Git provider connected to Render, then create a Render Blueprint from the repository and apply `render.yaml`. The Blueprint asks for private environment values; copy the current Aiven and signing-key values from your ignored local `.env` file. If `APP_KEY`, `JWT_SECRET`, or `REFRESH_TOKEN_KEY` is missing, generate it locally with `php lava key:generate` or `php lava jwt:generate` and enter the generated value in Render. Leave `DB_SSL=true` and `DB_SSL_CA=ca.pem` so the API verifies Aiven's TLS certificate. The API container listens on Render's assigned port and checks `/api/health`.

After the API deploys, create a Render Static Site from the same repository with root directory `frontend`, build command `npm ci && npm run build`, and publish directory `dist`. Add the build environment variable `VITE_API_URL=https://YOUR-API.onrender.com/api`, replacing the example with your deployed API URL. Copy the static site's exact `https://...onrender.com` origin into the API's `FRONTEND_ORIGIN` environment variable, replacing the initial local-development value, then redeploy the API. This is required for browser CORS requests.

The Aiven schema and admin account were already created during local setup. Do not run migrations or the seed again during deployment; the Render free service has no interactive shell for that. Keep all database passwords and signing keys in Render's private environment settings, never in Git or frontend build variables. The frontend build variable contains only the public API URL.

Render needs this workspace in a Git repository before it can deploy. The current folder does not have a Git remote or a connected Git publishing account, so publishing must wait until the repository is pushed and linked to Render. Use the deployed API and frontend URLs, plus your repository URL(s), for the lab submission.

## Submission screenshots

Capture the login page, product list, add form, edit form, delete confirmation/result, the Aiven `products` table, and a complete working CRUD demonstration. Submit the LavaLust repository, React repository, Render API URL, and frontend URL.
