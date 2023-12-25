# Catering

Example SPA events site project made using Laravel, Inertia Js, Vue Js and Tailwind


## This Project includes the following:

* Registration,
* Authentication,
* Authorisation,
* CRUD,
* Search,
* Laravel policies,
* Pagination.

### Dependencies

* PHP development enviroment (like XAMPP) is required
* If you are using XAMPP please put the project inside the htdocs folder in XAMPP. This is required.
* Node.js is required
* Laravel is required

### Installing

* Laravel install

```
composer global require laravel/installer
```


### Executing the app

* Activate PHP enviroment
* Copy .env.example into .env 
* The project uses SqLite for convenience but if you prefer another option feel free to use it
* Open the project's root directory in the terminal
  

* Install the necesary files
```
composer install
```
```
npm install
```

* Generate App Key in the .env file
```
php artisan key:generate
```

* Or run this command and copy the key from the terminal to the APP_KEY= line in the .env file
```
php artisan key:generate --show
```

* Migrations and seeding
```
php artisan migrate --seed
```
* Feel free to run this command several times to fiil the database to your liking
```
php artisan db:seed --class=EventSeeder
```


* Run the following line in the terminal
  
```
npm run dev
```

* In another terminal run

```
php artisan serve
```

## Author

Vladeta Milosavljevic

