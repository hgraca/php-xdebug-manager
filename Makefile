CURRENT_BRANCH="$(shell git rev-parse --abbrev-ref HEAD)"

default: help

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

composer-install:
	composer install

composer-update:
	composer update

#coverage:
#	php -dzend_extension=xdebug.so bin/phpunit --coverage-text --coverage-clover=coverage.clover.xml
#
#cs-fix:
#	bin/php-cs-fixer fix --verbose
#
#test:
#	bin/phpunit
##	bin/humbug
#
#test-debug:
#	php -dzend_extension=xdebug.so bin/phpunit
#
#test-acceptance:
#	bin/phpunit --testsuite acceptance
#
#test-functional:
#	bin/phpunit --testsuite functional
#
#test-humbug:
#	bin/humbug
#
#test-integration:
#	bin/phpunit --testsuite integration
#
#test-unit:
#	bin/phpunit --testsuite unit

### DOCKER
up:
	docker-compose -f /home/herberto/Development/workspace/00-hgraca/php/php-xdebug-manager/tests/storage/php_pecl/docker-compose.yml up -d

stop:
	docker-compose -f /home/herberto/Development/workspace/00-hgraca/php/php-xdebug-manager/tests/storage/php_pecl/docker-compose.yml stop

down:
	docker-compose -f /home/herberto/Development/workspace/00-hgraca/php/php-xdebug-manager/tests/storage/php_pecl/docker-compose.yml down

ll:
	docker exec -it php_pecl bash -ic "ls -la"

install:
	docker exec -it php_pecl bash -ic "bin/console xdebug:install && php -v"

off:
	docker exec -it php_pecl bash -ic "bin/console xdebug:off && php -v"

on:
	docker exec -it php_pecl bash -ic "bin/console xdebug:on && php -v"

rename:
	docker exec -it php_pecl bash -ic 'bin/console xdebug:rename-project bladibla'

ide-config:
	docker exec -it php_pecl bash -ic 'echo $$PHP_IDE_CONFIG'

xdebug-config:
	docker exec -it php_pecl bash -ic 'cat /usr/local/etc/php/conf.d/xdebug.ini'

php-v:
	docker exec -it php_pecl bash -ic 'php -v'

php-info:
	docker exec -it php_pecl bash -ic "php -r 'phpinfo();'"

test:
	vendor/bin/phpunit

