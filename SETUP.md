# Products CRUD — uzstādīšana

Faili ir Laravel 11/12 mapju struktūrā. Kopējiet tos savā Laravel projektā (ar to pašu mapju ceļu)
vai izveidojiet jaunu projektu un tad kopējiet.

## 1. Jauns projekts (ja vēl nav)

```bash
composer create-project laravel/laravel shop
cd shop
```

Nokopējiet šī arhīva failus projektā (pārrakstiet `routes/web.php`, `resources/css/app.css`, `resources/js/app.js`).

## 2. SQLite

`.env` failā:

```
DB_CONNECTION=sqlite
```

(rindas `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` var dzēst vai atstāt aizkomentētas.)

```bash
touch database/database.sqlite
php artisan migrate
```

## 3. Palaišana (divi termināļi)

```bash
php artisan serve     # http://127.0.0.1:8000
npm install
npm run dev           # Vite serveris CSS/JS servēšanai
```

Atveriet http://127.0.0.1:8000 — tas novirza uz `/products`.

## Maršruti

`php artisan route:list --name=products`

| Metode    | URI                      | Nosaukums         |
|-----------|--------------------------|-------------------|
| GET       | /products                | products.index    |
| GET       | /products/create         | products.create   |
| POST      | /products                | products.store    |
| GET       | /products/{product}      | products.show     |
| GET       | /products/{product}/edit | products.edit     |
| PUT/PATCH | /products/{product}      | products.update   |
| DELETE    | /products/{product}      | products.destroy  |
