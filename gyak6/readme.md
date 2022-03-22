# ⚡ 6. gyakorlat

[Resource controllers](https://laravel.com/docs/9.x/controllers#resource-controllers)

- könnyebbé teszi a controllerek használatát, érdemes egyből ezt használni, és nem a 2. (?) órán bemutatott módszert
- `php artisan make:controller SomethingController --model=Something --resource` - resource controller létrehozása
- `Route::resource('something', SomethingController::class)->only(['create', 'store', ...)` - web.phpba
- `php artisan route:list` - routeok listája

