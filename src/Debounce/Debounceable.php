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

/**
 * @mixin ShouldDebounce
 */
trait Debounceable
{
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

    public function getIdForDebounce(): string
    {
        return static::class;
    }

    public function handle()
    {
        if (Cache::decrement('debounceable_' . $this->getIdForDebounce()) > 0) {
            return;
        }

        Cache::forget('debounceable_' . $this->getIdForDebounce());

        return $this->debouncedHandle();
    }
}
