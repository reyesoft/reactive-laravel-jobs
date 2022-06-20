# Reactive Laravel Jobs

## Installation

```
composer require reyesoft/reactive-laravel-jobs
```

## Features

* A same job dispatched various times can be grouped based on some value, like user_id.
* Delay time can be changed (takes last delay value).

## Fantastic Laravel jobs types

### Debounce Laravel Job

![Debounce diagram, from RxJs documentation](https://rxjs.dev/assets/images/marble-diagrams/debounce.svg)

Dispatch delayed job only after delay value and passed without another job dispatched.

```php
use Reyesoft\ReactiveLaravelJobs\Debounce\Debounceable;
use Reyesoft\ReactiveLaravelJobs\Debounce\ShouldDebounce;

final class DebounceableExampleJob implements ShouldDebounce
{
    use Debounceable;
    use Dispatchable;
    use Queueable;

    public $param1 = '';

    public function __construct($param1)
    {
        $this->param1 = $param1;
    }

    public function getIdForDebounce(): string
    {
        return self::class . $this->param1;
    }

    public function debouncedHandle(): void
    {
        // handle job things...
    }
}
```

```php
SendNotificationJob::dispatchDebounced('You have 1 item.')->delay(5);
SendNotificationJob::dispatchDebounced('You have 2 items.')->delay(5);
SendNotificationJob::dispatchDebounced('You have 3 items.')->delay(5);
sleep(10);
SendNotificationJob::dispatchDebounced('You have 4 items.')->delay(5);
sleep(1);
SendNotificationJob::dispatchDebounced('You have 5 items.')->delay(5);

// Do
You have 3 items.
You have 5 items.
```

### Throttle Laravel Job

Dispatch delayed job, then ignores subsequent dispatched jobs for a duration determined by delay value, then repeats this process when.


![Throttle diagram, from RxJs documentation](https://rxjs.dev/assets/images/marble-diagrams/throttle.svg)

```php
```

## Testing

```bash
## build and push
docker build -t pablorsk/reactive-laravel-jobs:7.4 --build-arg PHP_VERSION=7.4 -f resources/Dockerfile ./resources/
docker build -t pablorsk/reactive-laravel-jobs:8.0 --build-arg PHP_VERSION=8.0 -f resources/Dockerfile ./resources/
docker push pablorsk/reactive-laravel-jobs:7.4
docker push pablorsk/reactive-laravel-jobs:8.0

## local use
docker run -it --rm --name jsonapi74 -e PHP_EXTENSIONS="sqlite3 pdo_mysql pdo_sqlite" -v "$PWD":/usr/src/app pablorsk/reactive-laravel-jobs:7.4 bash
docker run -it --rm --name jsonapi80 -e PHP_EXTENSIONS="sqlite3 pdo_mysql pdo_sqlite" -v "$PWD":/usr/src/app pablorsk/reactive-laravel-jobs:8.0 bash
```
