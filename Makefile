install:
	@docker run -it -w /data -v ${PWD}:/data:delegated -v ~/.composer:/root/.composer:delegated --entrypoint composer --rm registry.gitlab.com/grahamcampbell/php:7.4-base update
	@docker run -it -w /data -v ${PWD}:/data:delegated -v ~/.composer:/root/.composer:delegated --entrypoint composer --rm registry.gitlab.com/grahamcampbell/php:7.4-base bin all update

phpspec:
	@rm -f bootstrap/cache/*.php && docker run -it -w /data -v ${PWD}:/data:delegated --entrypoint vendor/bin/phpspec --rm registry.gitlab.com/grahamcampbell/php:7.4-cli run -fpretty

phpstan-analyze:
	@docker run -it -w /data -v ${PWD}:/data:delegated --entrypoint vendor/bin/phpstan --rm registry.gitlab.com/grahamcampbell/php:7.4-cli analyze

phpstan-baseline:
	@docker run -it -w /data -v ${PWD}:/data:delegated --entrypoint vendor/bin/phpstan --rm registry.gitlab.com/grahamcampbell/php:7.4-cli analyze --generate-baseline

psalm-analyze:
	@docker run -it -w /data -v ${PWD}:/data:delegated --entrypoint vendor/bin/psalm --rm registry.gitlab.com/grahamcampbell/php:7.4-cli

psalm-baseline:
	@docker run -it -w /data -v ${PWD}:/data:delegated --entrypoint vendor/bin/psalm --rm registry.gitlab.com/grahamcampbell/php:7.4-cli --set-baseline=psalm-baseline.xml

psalm-show-info:
	@docker run -it -w /data -v ${PWD}:/data:delegated --entrypoint vendor/bin/psalm --rm registry.gitlab.com/grahamcampbell/php:7.4-cli --show-info=true

test: phpspec phpstan-analyze psalm-analyze

clean:
	@rm -rf composer.lock vendor vendor-bin/*/composer.lock vendor-bin/*/vendor
