init:
	docker compose -f ./docker/docker-compose.yaml up -d --build
	docker compose -f ./docker/docker-compose.yaml exec -itd php /bin/sh -c "\
		php -dxdebug.mode=off bin/console doctrine:migrations:migrate --no-interaction;\
		php -dxdebug.mode=off bin/console doctrine:fixtures:load --no-interaction;\
	"

build:
	docker compose -f ./docker/docker-compose.yaml build

up:
	docker compose -f ./docker/docker-compose.yaml up -d

stop:
	docker compose -f ./docker/docker-compose.yaml stop

down:
	docker compose -f ./docker/docker-compose.yaml down

remove:
	docker compose -f ./docker/docker-compose.yaml down --remove-orphans --volumes --rmi all

grum:
	docker compose -f ./docker/docker-compose.yaml up -d
	docker compose -f ./docker/docker-compose.yaml exec -it php /bin/sh -c "php -dxdebug.mode=off vendor/bin/grumphp run"

e2e:
	docker compose -f ./docker/docker-compose.yaml up -d
	docker compose -f ./docker/docker-compose.yaml exec -it php /bin/sh -c "\
		php -dxdebug.mode=off bin/console doctrine:database:drop --if-exists --force --no-interaction --env=test;\
		php -dxdebug.mode=off bin/console doctrine:database:create --if-not-exists --no-interaction --env=test;\
		php -dxdebug.mode=off bin/console doctrine:migrations:migrate --no-interaction --env=test;\
		php -dxdebug.mode=off bin/console doctrine:fixtures:load --no-interaction --env=test;\
		php -dxdebug.mode=off bin/phpunit ./tests/E2E;\
	"
