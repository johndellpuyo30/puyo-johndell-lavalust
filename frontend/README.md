# Puyo Lab 6 React frontend

This Vite and React application calls the LavaLust API; it does not connect to MySQL. From this folder, install dependencies and run `npm run dev`. Set `VITE_API_URL` in a local `.env` file (see `.env.example`), for example `http://127.0.0.1:3000/api`. Build the deployable static application with `npm ci && npm run build`; publish `dist/` and set `VITE_API_URL` to the deployed API URL ending in `/api`.

Configure the API's `FRONTEND_ORIGIN` with the exact frontend origin to allow browser requests. See the root `LAB6_SETUP.md` for database setup, account creation, API routes, and deployment steps.