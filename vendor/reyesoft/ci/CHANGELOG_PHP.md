# Changelog PHP
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.3] - 2021-06-01

### Added
- cs-fixer `@PHP74Migration:risky` and `@PHP80Migration:risky` can be applied.

### Changed
- friendsofphp/php-cs-fixer. Do `PHP_CS_FIXER_FUTURE_MODE=1 php-cs-fixer fix` and prepare for cs-fixer v3.
- BREAK CHANGE: psr_autoloading is true now, will be change class names.
- BREAK CHANGE: psr_autoloading is true now, will be change class names.

## [1.4.2] - 2021-05-15

### Fixed
- coverage-check fix when warning don't exist or is equal to zero.

## [1.4.1]

### Changed
- squizlabs/php_codesniffer version bump.

## [1.4.0] - 2021-04-06

### Updated
- sebastian/phpcpd updated. Required by Laravel 8.x.

## [1.3.3] - 2020-05-12

### Updated
- sebastian/phpcpd updated. Required by Laravel 7.x. Require php7.3.
 
## [1.3.1] - 2020-05-12

### Removed
- povils/phpmnd
 
## [1.3.0] - 2020-04-24

### Added
- PHPMND Magic Number Detector.
 
### Changed
- Updated all PHP libraries.
 
## [1.2.0] - 2019-12-25

### Changed 
- New version of PHPSTAN.
- New version of PHP Mess Detector. 
- New version of PHPUnit.
- Maybe you need add this
```
    ignoreErrors:
        ## phpstan 0.11 to 0.12
        - '#has no typehint specified\.$#'
        - '#has no return typehint specified\.$#'
        - '#with no typehint specified\.$#'
        - '#with no value type specified in iterable#'
        - '#has no value type specified in iterable#'
```

## Removed
- Remove `\NunoMaduro\Larastan\LarastanServiceProvider::class`.

## [1.1.3] - 2019-12-25

### Changed
- PHP CS FIXER phpdoc_to_comment rule disabled.

## [1.1.2] - 2019-12-25

### Fixed
- Removed PHP CS FIXER rules contained on presets.

### Changed 
- PHP CS FIXER rule single_line_throw disabled. 
