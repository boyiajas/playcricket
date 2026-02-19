# PlayCricket – Vue + Laravel

The React/Vite prototype has been migrated to a Vue 3 front-end with a Laravel-ready API skeleton.

## Front-end (Vue 3 + Vite)
1. Install dependencies: `npm install`
2. Add a `.env` file with your Gemini key: `VITE_GEMINI_API_KEY=your_key`
3. Run the dev server: `npm run dev`

## Back-end (Laravel API skeleton)
- The `backend/` folder contains controllers, routes, and config data mirroring the Vue mock data.
- Create a fresh Laravel app (Laravel 10/11) and copy the contents of `backend/` into the matching paths of that app:
  - `config/appdata.php`
  - `routes/api.php` (merge with your routes)
  - `app/Http/Controllers/*.php`
- Suggested quick start inside a new `laravel-app` directory:
  ```
  composer create-project laravel/laravel laravel-app
  cd laravel-app
  cp -R ../backend/config/appdata.php config/
  cp -R ../backend/app/Http/Controllers app/Http/
  cp ../backend/routes/api.php routes/api.php
  php artisan serve
  ```
- Replace the in-memory arrays with migrations/models when ready, then point the Vue app to `http://127.0.0.1:8000/api`.

## Notes
- UI lives under `src/` with Tailwind via CDN (no build-time Tailwind config needed).
- Data is currently mock-driven; switch the Vue services to call the Laravel endpoints once the API is running.
