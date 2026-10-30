# Products CRUD — uzstādīšana

Laravel projekts ar `products` resursu (SQLite, Blade komponentes, Vite).

## 1. Sagatavošana

```bash
composer install
npm install
cp .env.example .env        # ja .env vēl nav
php artisan key:generate
```

`.env` failā:

```
DB_CONNECTION=sqlite
```

## 2. Datubāze

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=ProductSeeder    # 34 testa produkti
```

Sākt no nulles: `php artisan migrate:fresh --seed`
(`--seed` palaiž `DatabaseSeeder`; pievieno tajā `$this->call(ProductSeeder::class);`)

## 3. Palaišana (divi termināļi)

```bash
php artisan serve     # http://127.0.0.1:8000  (/ pārvirza uz /products)
npm run dev           # Vite serveris CSS un JS servēšanai
```

## 4. Testi

```bash
php artisan test
```

## Struktūra

| Ceļš | Kas tas ir |
|---|---|
| `routes/web.php` | `Route::resource('products', ...)`, `/` → `products.index` |
| `app/Models/Product.php` | modelis: name, quantity, description, expiration_date, status |
| `app/Http/Controllers/ProductController.php` | CRUD, route model binding, validācija |
| `database/migrations/` | `create_products_table` + `update_products_table_attributes` |
| `database/factories/ProductFactory.php` | produktu katalogs testa datiem |
| `database/seeders/ProductSeeder.php` | ielādē visu katalogu |
| `resources/views/components/layout.blade.php` | `<x-layout>` — Holy grail izkārtojums |
| `resources/views/components/flash-*.blade.php` | paziņojumi: veiksme un validācijas kļūdas |
| `resources/views/products/` | index, create, edit, show, `_form` |
| `resources/css/app.css`, `resources/js/app.js` | ielādē Vite |
| `tests/Feature/ProductControllerTest.php` | kontroliera testi |

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

## Biežākās problēmas

| Kļūda | Risinājums |
|---|---|
| `Database file ... does not exist` | `touch database/database.sqlite` |
| `no such table: sessions` | `php artisan make:session-table && php artisan migrate`, vai `.env`: `SESSION_DRIVER=file` |
| `Nothing to migrate`, bet tabulu nav | migrācijām jābūt `database/migrations/`, nevis saknes `migrations/` |
| `Class ... Controller not found` | jābūt `app/Http/Controllers/Controller.php` |
| Vite: `Failed to resolve import "./bootstrap"` | no `resources/js/app.js` jādzēš `import './bootstrap';` |
| Milzīga bultiņa lappušu numerācijā | `nav[role="navigation"] svg { width: 1.25rem; height: 1.25rem; }` |
| `intl extension is required` | tikai `db:show --counts`; lietojiet bez `--counts` vai uzstādiet `php-intl` |

## Git

`database/database.sqlite` nedrīkst būt git ar vietējiem datiem:

```bash
git rm --cached database/database.sqlite
echo "*.sqlite" >> database/.gitignore
```
