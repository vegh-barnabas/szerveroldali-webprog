#parancsok
php artisan migrate:fresh

- friss migráció

php artisan migrate:rollback x

- visszamegy x verziót

php artisan migrate:status

- meg lehet nézni, mely táblák lettek bemigrateolva

php artisan make:migration x

- x nevű migrációt elkészíti

# modell

php artisan make:model Something

hasFactory

- legyen factoryja

Category::all()

Category::count()

Category::find(1)

- 1-es ID-t adja ki, ha nem létezik, nullt ad

Category::first()

- első elem

Category::findOrFail(11)

- elfailel ha nem találja meg

Category:find([2, 3])

Category::where('style', '=', 'info')

- buildert ad vissza

Category::where('style', '=', 'info')->get()

- eredményt ad vissza

## szerkesztés 1. változat

$firstCategory = Category::firstOrFail()

$firstCategory->style = 'primary'

$firstCategory

- style primaryt ad vissza, de databaseben még más

$firstCategory->save()

- style info lett, updated_at frissül

## szerkesztés 2. változat

Category::firstOrFail()->update(['style' => 'info'])

Category::fristOrFail()

- style info lett, updated_at frissül

# törlés 1

$firstCategory = Category::firstOrFail()

$firstCategory-delete()

- style info lett, updated_at frissül

# törlés 2

Category::firstOrFail()->delete()

Category::destroy(2)

- visszatérési értéke hogy hány kategóriát törölt ki

# factory

php artisan make:factory SomethingFactory

Something::factory()->make()

- csak generál egyet

Something::factory()->create()

- bekerül az adatbázisba

# seeder

php artisan make:seeder CategorySeeder

php artisan db:seed --class CategorySeeder

sok seederhez 1 parancs: DatabaseSeeder.php

- php artisan db:seed

# többi ilyen kigenerálása

php artisan make:model Something -mfs

kapcsolatok: https://github.com/szerveroldali/leirasok/blob/main/SequelizeAsszociaciok.md
