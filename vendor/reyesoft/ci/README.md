# reyesoft/ci

```bash
yarn add --dev reyesoft-ci
composer require-dev reyesoft/ci
```

## Backend

### Tools

* cs-fixer

### Install

`composer.json`

```json
{
    "scripts": {
        "ci-double-spaces": [
            "sh vendor/reyesoft/ci/tools/find-double-spaces.sh app",
            "sh vendor/reyesoft/ci/tools/find-double-spaces.sh tests"
        ],
        "ci-php-cs-fixer": "sh vendor/reyesoft/ci/php/scripts/php-cs-fixer.sh",
        "phpstan": [
            "@phpstan-src",
            "@phpstan-tests"
        ],
        "phpstan-src": "./vendor/bin/phpstan analyse -l 7 -c resources/rules/phpstan.src.neon app ./bootstrap/*.php config",
        "phpstan-tests": "./vendor/bin/phpstan analyse -l 7 -c resources/rules/phpstan.tests.neon tests",
        "coverage": [
            "ulimit -Sn 50000 && phpdbg -d memory_limit=-1 -qrr ./vendor/bin/phpunit",
            "php ./vendor/reyesoft/ci/tools/coverage-checker.php"
        ]
    },
    "extra": {
        "coverage": {
            "file": "./bootstrap/cache/clover.xml",
            "thresholds": {
                "global": {
                    "lines": 46
                },
                "/app/Boxer": {
                    "lines": 78
                }
            }
        }
    }
}
```

## Front End

### Tools

* tslint
* sass-lint
* prettier (ts, md and json files)

### Install

`package.json`

```json
{
    "sasslintConfig": "resources/.sass-lint.yml",
    "scripts": {
        "lint": "ng lint && sass-lint -c -q",
        "fix": "ng lint --fix && yarn prettier:fix",
        "prettier:fix": "prettier **/*.{ts,sass,scss,md} --write",
        "prettier:check": "bash node_modules/reyesoft-ci/parallel.bash -s \"yarn prettier **/*.{sass,scss,md} -l\" \"yarn prettier **/*.ts -l\"",
        "precommit": "lint-staged"
    },
    "lint-staged": {
        "*.ts": [
            "yarn tslint --fix",
            "git add"
        ],
        "*.{ts,md,scss,sass}": [
            "yarn prettier:fix",
            "git add"
        ],
        "package.json": [
            "node ./node_modules/reyesoft-ci/js/scripts/yarn-install.js",
            "git add yarn.lock"
        ]
    }
}
```

`yarn fix` for various projects: `ng lint project1 --fix && ng lint project2 --fix && yarn prettier:fix`

## Testing

### PHP 8.0

```bash
docker run -d -v `pwd`:/app --name=php80 -it --rm php:8.0-cli
docker exec -it -w /app php80 bash
# docker stop php80
```

### PHP 7.4

```bash
docker run -d -v `pwd`:/app --name=php74 -it --rm php:7.4-cli
docker exec -it -w /app php74 bash
```
