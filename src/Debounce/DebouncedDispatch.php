<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Reyesoft\ReactiveLaravelJobs\Debounce;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class DebouncedDispatch extends PendingDispatch
{
    /**
     * @param ShouldDebounce $job
     */
    public function __construct($job)
    {
        parent::__construct($job);
        /** @phpstan-ignore-next-line  */
        if (Queue::size($this->job->queue) === 0) {
             Cache::put('debounceable_' . $job->getIdForDebounce(), 1);
            $id =1;
        } else {
            $id = Cache::increment('debounceable_' . $job->getIdForDebounce());
        }
        $this->getJob()->reactive_job_id = $id;
    }

    /**
     * @return Debounceable
     */
    public function getJob()
    {
        return $this->job;
    }
}
