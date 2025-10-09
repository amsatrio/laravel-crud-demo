create-project:
	composer create-project laravel/laravel laravel-crud-demo
start:
	php artisan serve
migrate:
	php artisan migrate
migrate-fresh:
	php artisan migrate:fresh

create-controller-model-migration-seeder:
	php artisan make:model MRole -rms