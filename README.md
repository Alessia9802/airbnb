# Airbnb

Clone didattico di Airbnb, realizzato con **Laravel 12**, **Vue 3** e **Inertia.js** (starter kit ufficiale Laravel + Vue, TypeScript e Tailwind).

## Funzionalità

- **Area pubblica**: home con griglia degli alloggi, ricerca per città o titolo, paginazione e pagina di dettaglio con servizi e prezzo.
- **Area host** (`/host/alloggi`, serve il login): elenco dei propri alloggi, creazione, modifica ed eliminazione, con upload della copertina, scelta dei servizi e visibilità pubblico/nascosto.
- Registrazione, login, reset della password e impostazioni del profilo (dallo starter kit).

## Installazione

Requisiti: PHP 8.2+, Composer, Node 20+.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
composer run dev
```

Il sito è su http://localhost:8000.

Utente demo creato dal seeder: `test@example.com` / `password`.

## Struttura

| Cosa | Dove |
| --- | --- |
| Modelli | `app/Models/Apartment.php`, `app/Models/Service.php` |
| Controller pubblico | `app/Http/Controllers/ApartmentController.php` |
| Controller host (CRUD) | `app/Http/Controllers/Host/ApartmentController.php` |
| Validazione | `app/Http/Requests/ApartmentRequest.php` |
| Pagine Vue | `resources/js/pages/Home.vue`, `pages/Apartments/Show.vue`, `pages/Host/Apartments/*` |
| Seeder | `database/seeders/` (10 servizi e 24 alloggi di esempio) |

## Test

```bash
php artisan test
```
