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

final class DebouncedNotificationJob implements ShouldDebounce
{
    use Debounceable;
    use Dispatchable;
    use Queueable;

    public $param1 = '';

    public function __construct($param1)
    {
        $this->param1 = $param1;
    }

    public function uniqueId()
    {
        return $this->param1;
    }

    public function debouncedHandle(): void
    {
        echo PHP_EOL . $this->param1;
    }
}
```

```php
DebouncedNotificationJob::dispatchDebounced('You have 1 item.')->delay(5);
DebouncedNotificationJob::dispatchDebounced('You have 2 items.')->delay(5);
DebouncedNotificationJob::dispatchDebounced('You have 3 items.')->delay(5);
sleep(10);
DebouncedNotificationJob::dispatchDebounced('You have 4 items.')->delay(5);
sleep(1);
DebouncedNotificationJob::dispatchDebounced('You have 5 items.')->delay(5);

// Do
You have 3 items.
You have 5 items.
```

### Throttle Laravel Job

Dispatch delayed job, then ignores subsequent dispatched jobs for a duration determined by delay value, then repeats this process when.

![Throttle diagram, from RxJs documentation](https://rxjs.dev/assets/images/marble-diagrams/throttle.svg)

On Laravel 9, it can be done with [Unique Jobs](https://laravel.com/docs/9.x/queues#unique-jobs).

```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
 
class ThrottledNotificationJob implements ShouldQueue, ShouldBeUnique
{
    public $param1 = '';

    public function __construct($param1)
    {
        $this->param1 = $param1;
    }

    /**
     * The number of seconds after which the job's unique lock
     * will be released.
     */
    public $uniqueFor = 3600;
 
    public function uniqueId()
    {
        return $this->param1;
    }

    public function handle(): void
    {
        echo PHP_EOL . $this->param1;
    }
}
```

```php
ThrottledNotificationJob::dispatch('You have 1 item.')->delay(5);
ThrottledNotificationJob::dispatch('You have 2 items.')->delay(5);
ThrottledNotificationJob::dispatch('You have 3 items.')->delay(5);
sleep(10);
ThrottledNotificationJob::dispatch('You have 4 items.')->delay(5);
sleep(1);
ThrottledNotificationJob::dispatch('You have 5 items.')->delay(5);

// Do
You have 1 item.
You have 4 items.
```

## Testing

```bash
composer autofix
composer test
```
