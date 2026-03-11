First time setup (run once):


cd HouseOfParfum
composer update
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh
Update .env with your DB credentials:


DB_DATABASE=house_of_parfum
DB_USERNAME=root
DB_PASSWORD=root
Every time you want to run the project (3 terminals):

Terminal 1 — Laravel:


cd HouseOfParfum
php artisan serve
Terminal 2 — Vite assets:


cd HouseOfParfum
npm install
npm run dev
Terminal 3 — phpMyAdmin (optional):


cd /opt/homebrew/share/phpmyadmin
php -S localhost:8888
URLs:

Service	URL
App	http://localhost:8000
phpMyAdmin	http://localhost:8888
