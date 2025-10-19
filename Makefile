PORT ?= 8000
start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT)  -t public
lint:
	composer exec --verbose phpcs -- --standard=PSR12 app public routes tests
install:
	composer install
	cp .env.example .env
	php artisan key:gen --ansi
	php artisan migrate
	npm ci
	npm run build
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 app public routes tests
