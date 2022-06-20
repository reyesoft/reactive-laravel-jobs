<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Reyesoft\ReactiveLaravelJobs\Debounce;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * @mixin ShouldDebounce
 */
trait Debounceable
{
    public $reactive_job_id = 0;

    /**
     * Dispatch debounced the job with the given arguments.
     */
    public static function dispatchDebounced(): DebouncedDispatch
    {
        return new DebouncedDispatch(new static(...func_get_args()));
    }

    /**
     * Dispatch debounced the job.
     *
     * @param \DateTimeInterface|\DateInterval|int|null $delay
     */
    public function dispatchDebouncedOn($delay): DebouncedDispatch
    {
        return (new DebouncedDispatch($this))->delay($delay);
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

    public function getIdForDebounce(): string
    {
        return static::class.$this->uniqueId();
    }

    public function handle()
    {
        Log::debug('IIII handling id '.$this->reactive_job_id.' / '.$this->getIdForDebounce() );
        if (Cache::get('debounceable_' . $this->getIdForDebounce()) != $this->reactive_job_id) {
            return;
        }

        Cache::forget('debounceable_' . $this->getIdForDebounce());

        return $this->debouncedHandle();
    }
}
