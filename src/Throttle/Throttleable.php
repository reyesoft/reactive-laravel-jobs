<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Reyesoft\ReactiveLaravelJobs\Throttle;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * @mixin ShouldThrottle
 */
trait Throttleable
{
    public $reactive_job_id = 0;

    /**
     * Dispatch debounced the job with the given arguments.
     */
    public static function dispatchThrottled(): ThrottledDispatch
    {
        return new ThrottledDispatch(new static(...func_get_args()));
    }

    /**
     * Dispatch debounced the job.
     *
     * @param \DateTimeInterface|\DateInterval|int|null $delay
     */
    public function dispatchThrottledOn($delay): ThrottledDispatch
    {
        return (new ThrottledDispatch($this))->delay($delay);
    }

    /**
     * Like Laravel Unique jobs
     *
     * @return string|int
     */
    public function uniqueId()
    {
        return '';
    }

    public function getIdForReactive(): string
    {
        return static::class.$this->uniqueId();
    }

    public function handle()
    {
        if (intval(Cache::get('reactive_job_' . $this->getIdForReactive(), 0)) !== $this->reactive_job_id) {
            return;
        }

        Cache::forget('reactive_job_' . $this->getIdForReactive());

        return $this->throttleHandle();
    }
}
