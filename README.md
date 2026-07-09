# Library Consumer

A Laravel app that consumes a separate "Library Owner" catalog API (built in Yii) and republishes it as a public, searchable book catalog, with an admin dashboard to trigger sync.

- Public catalog with search/filter (`/catalogs`) and detail pages (`/catalogs/{id}`)
- Admin login (`/admin/login`) + dashboard (`/admin`) to manually trigger a sync
- `php artisan api:fetch` — pulls the catalog from the upstream API and upserts it into the local `catalog_inventories` table (also runnable via the dashboard's "sync" button)

## Tech Stack

- PHP 8.3+ (8.4 in Docker) · Laravel 13
- SQLite (default local) or MySQL
- Docker + Docker Compose

---

## Local Development

### Docker

```bash
cp .env.example .env
```

Add these to `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=library_consumer
DB_USERNAME=library
DB_PASSWORD=secret
DB_ROOT_PASSWORD=secret

YII_BASE_URL=http://library-owner.test
YII_API_KEY=your-api-key
```

`docker-compose.override.yaml` is picked up automatically by `docker compose` whenever it's present alongside `docker-compose.yaml` — no extra flags needed. It bind-mounts your source into the `app` container (so code edits apply immediately, no rebuild), disables Opcache's file-timestamp caching, and publishes MySQL on `3307` so you can connect a local GUI client:

```bash
docker compose up -d --build

docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=UserSeeder   # optional: admin user
docker compose exec app php artisan api:fetch                     # populate the catalog
```

Visit `http://localhost:8000`. If the upstream API runs on your host machine (not in Docker), it's reachable from the container at `http://library-owner.test` (see `extra_hosts` in `docker-compose.yaml`).

**Images / `storage/app/public`:** in dev, `docker-compose.override.yaml` binds `./storage/app/public` on your host directly into both `app` and `web` — so dropping a file into `storage/app/public/images/` locally shows up immediately, no rebuild or restart needed, same as working without Docker at all.

To run without the dev overrides (e.g. to sanity-check the production image locally), temporarily rename or remove `docker-compose.override.yaml`, or run `docker compose -f docker-compose.yaml up -d --build` explicitly.

### Manual (no Docker)

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Pick a database in `.env`:

```env
# SQLite (default) — just: touch database/database.sqlite
DB_CONNECTION=sqlite

# — or — MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_consumer
DB_USERNAME=root
DB_PASSWORD=
```

```env
YII_BASE_URL=http://library-owner.test
YII_API_KEY=your-api-key
```

```bash
php artisan migrate
php artisan db:seed --class=UserSeeder   # optional: admin user
php artisan api:fetch                     # populate the catalog
php artisan storage:link                  # exposes storage/app/public at public/storage
php artisan serve
```

---

## Production Deployment

### Docker

`docker-compose.yaml` (without the override file) is already production-leaning: PHP-FPM behind Nginx (not `artisan serve`), a non-root container user, no source bind-mount (the image is self-contained), and no database port published to the host.

`app` and `web` are separate containers, so a `php artisan storage:link` symlink made inside one wouldn't be visible to the other. Instead, both share a named volume (`storage-app-public`) mounted at the equivalent path in each — `app`'s `storage/app/public` and `web`'s `public/storage` are the same underlying files, no symlink needed. The first time that volume is created, Docker seeds it from whatever's already at that path in the freshly-built image (e.g. images already committed to the repo).

> **Using Apache on the host?** That's fine — the Nginx here runs *inside* the `web` container and is completely independent of whatever the host machine runs. Docker just needs a free port to publish. If Apache on that host already owns port 80/443 for other sites, point it at the container instead of trying to replace it — add a `VirtualHost` that reverse-proxies to wherever you published the `web` service:
>
> ```apache
> <VirtualHost *:80>
>     ServerName library.example.com
>     ProxyPreserveHost On
>     ProxyPass / http://127.0.0.1:8000/
>     ProxyPassReverse / http://127.0.0.1:8000/
> </VirtualHost>
> ```
>
> (requires `mod_proxy` and `mod_proxy_http` enabled: `a2enmod proxy proxy_http`)

Deploy steps:

```bash
git pull                      # or however you get code onto the server
cp .env.example .env          # fill in real production values, keep this file out of version control
docker compose -f docker-compose.yaml up -d --build

docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

**Adding new images after deployment:** the `storage-app-public` volume is only auto-seeded from the image the *first* time it's created — it does not get refreshed on later `up --build` runs, since by then it already exists and Docker treats it as the persistent source of truth (same idea as the database volume). So committing a new image to `storage/app/public/images/` and redeploying will **not** make it appear. Push new files onto the live volume directly instead:

```bash
docker compose cp ./storage/app/public/images/new-cover.jpg app:/var/www/storage/app/public/images/
```

### Manual (deploying directly to Apache, no Docker)

If you're not containerizing production at all and deploying straight onto an Apache server:

1. Point Apache's `DocumentRoot` at the project's `public/` directory (never the project root) and enable `mod_rewrite` — Laravel ships a `public/.htaccess` that needs it for pretty URLs:

   ```apache
   <VirtualHost *:80>
       ServerName library.example.com
       DocumentRoot /var/www/library-consumer/public

       <Directory /var/www/library-consumer/public>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   ```bash
   a2enmod rewrite
   ```

2. Ship the code and install production dependencies:

   ```bash
   composer install --no-dev --optimize-autoloader
   cp .env.example .env    # fill in real production values
   php artisan key:generate
   ```

3. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.

4. Fix ownership/permissions so Apache's user can write to `storage/` and `bootstrap/cache/`:

   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

5. Link storage and cache config/routes/views:

   ```bash
   php artisan storage:link
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

6. `systemctl restart apache2`

---

## Routes

| Method | URI | Purpose |
|---|---|---|
| GET | `/` | Home |
| GET | `/catalogs` | Catalog listing — supports `criteria`+`search`, `author`, `publisher`, `isbn`, `year` query params |
| GET | `/catalogs/{id}` | Catalog detail |
| GET/POST | `/admin/login` | Admin login |
| GET | `/admin` | Admin dashboard |
| POST | `/admin` | Trigger catalog sync |

## Testing

```bash
php artisan test
```

## Notes

- Change the default seeded admin (`root@root.com` / `rootroot`) before deploying anywhere real.
- Set a real `YII_API_KEY` — the shipped `.env` uses a placeholder.
- `/admin` currently has no `auth` middleware guarding it — anyone can reach the dashboard and trigger a sync. Worth adding before this goes anywhere public.

## License

No license file is currently included. Add one (e.g. MIT) if you intend to distribute this project.
