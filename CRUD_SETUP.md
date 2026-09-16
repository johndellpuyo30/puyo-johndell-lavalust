# Puyo LavaLust MVC CRUD

## Local setup
1. Extract the project into `C:\xampp\htdocs\` or `C:\laragon\www\`.
2. Start Apache and MySQL.
3. Import `database/puyo_users_crud.sql` using phpMyAdmin or Navicat.
4. The included `.env` uses database `mydb`, user `root`, and a blank local password.
5. Open the project and go to `/users`.

Typical URLs:
- XAMPP: `http://localhost/Puyo_Lavalust/users`
- Laragon virtual host: use your generated local host, then append `/users`

## CRUD MVC files
- Routes: `app/config/routes.php`
- Controller: `app/controllers/UsersController.php`
- Model: `app/models/UsersModel.php`
- Views: `app/views/users/`
- Design: `public/assets/css/users-crud.css`

## Aiven
1. Create/select a MySQL database named `mydb`, or change `DB_NAME` to the database you use.
2. Import `database/aiven_users_crud.sql` into that database.
3. On Render, add the values shown in `.env.aiven.example` as environment variables.
4. Keep `DB_SSL=true` and `DB_SSL_CA=ca.pem`.

Never commit a real `.env` containing Aiven credentials.

## GitHub
The package does not contain old `.git` history. Create a new repository from this folder:

```bash
git init
git add .
git commit -m "Add LavaLust MVC users CRUD"
git branch -M main
git remote add origin YOUR_REPOSITORY_URL
git push -u origin main
```

`.env` is ignored by `.gitignore`.

## Render
The included `Dockerfile` uses Apache, enables `mod_rewrite`, installs `pdo_mysql`, and serves `public/` as the document root.

Create a Render Web Service from your GitHub repository and use the Docker runtime. Add your Aiven variables in Render's Environment settings.

## Presentation flow
- `/users` = READ
- `/users/create` + POST `/users/store` = CREATE
- `/users/edit/{id}` + POST `/users/update/{id}` = UPDATE
- POST `/users/delete/{id}` = DELETE

This demonstrates Route → Controller → Model → Database → View using LavaLust MVC.
