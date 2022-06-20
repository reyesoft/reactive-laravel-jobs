<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Throttle;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Reyesoft\ReactiveLaravelJobs\Debounce\Debounceable;
use Reyesoft\ReactiveLaravelJobs\Debounce\ShouldDebounce;
use Reyesoft\ReactiveLaravelJobs\Throttle\ShouldThrottle;
use Reyesoft\ReactiveLaravelJobs\Throttle\Throttleable;

final class ThrottleableExampleJob implements ShouldThrottle
{
    use Throttleable;
    use Dispatchable;
    use Queueable;

    public $param1 = '';
    public $param2 = '';

    public function __construct($param1, $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    public function getIdForReactive(): string
    {
        return self::class . $this->param1;
    }

    public function throttleHandle(): void
    {
        Cache::increment('used_for_testing_throttle_' . $this->param1);
    }
}
