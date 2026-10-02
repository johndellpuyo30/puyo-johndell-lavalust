# Laboratory Exercise No. 5 - CRUD with Authentication

This project now contains the required LavaLust Product CRUD application with session-based authentication.

## Included Lab 5 Files

- `app/controllers/AuthController.php`
- `app/controllers/ProductController.php`
- `app/models/AuthModel.php`
- `app/models/ProductModel.php`
- `app/middlewares/AuthMiddleware.php`
- `app/views/auth/login.php`
- `app/views/products/index.php`
- `app/views/products/form.php`
- `app/views/products/delete.php`
- `public/assets/css/products.css`
- `database/lab5_products_auth.sql`

## 1. Import the database

Open Aiven MySQL using Navicat, MySQL Workbench, or another MySQL client and run:

`database/lab5_products_auth.sql`

This creates the required `products` table plus an `auth_users` table used for login.

Demo login:

- Username: `admin`
- Password: `admin123`

## 2. Configure environment variables

Copy `.env.aiven.example` to `.env` for local testing and fill in the Aiven values.

Required variables:

- `APP_KEY`
- `APP_ENV`
- `DB_HOST`
- `DB_PORT`
- `DB_USER`
- `DB_PASSWORD`
- `DB_NAME`
- `DB_SSL`
- `DB_SSL_CA`

Do not commit `.env` to GitHub. It is already ignored by `.gitignore`.

## 3. Main routes

- `/login` - login page
- `/logout` - logs out the current user
- `/products` - product list
- `/products/create` - add product
- `/products/edit/{id}` - edit product
- `/products/delete/{id}` - delete confirmation

All `/products` routes are protected by `AuthMiddleware`.

## 4. Render

The existing `Dockerfile` is ready for deployment. Add the same Aiven credentials in Render under Environment Variables instead of placing them in GitHub.

## 5. Screenshots required by the activity

Capture these after deployment:

1. Login/authentication page
2. Product list
3. Add product
4. Edit product
5. Delete operation
6. Aiven `products` table showing stored records

Also submit the GitHub repository URL and Render application URL.
