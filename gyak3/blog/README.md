# projekt letöltése és beüzemelése
1. letöltés githubról
2. composer install
3. .env.example -> .env, környezeti változók megadása, ha van ilyenünk (pl. adatbázis későbbiekben)
4. php artisan key:generate -> titkosítási kulcs generálása
5. php artisan serve -> php szerver elindítása

# konzolra kiírás
$console = new \Symfony\Component\Console\Output\ConsoleOutput();

$console->writeln("Something");

# kontrollerek létrehozása
php artisan make:controller SomethingController

# kép elérési útvonala
blog\storage\app\public\covers

# tinker
php artisan tinker

# adatbázishoz kötés
config/database.php

	DB_CONNECTION=sqlite
	DB_DATABASE=database/database.sqlite

# migration
php artisan migrate:fresh
