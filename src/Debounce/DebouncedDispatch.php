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
        $this->getJob()->reactive_job_id = Cache::increment('debounceable_' . $job->getIdForDebounce());
    }

    /**
     * @return Debounceable
     */
    public function getJob()
    {
        return $this->job;
    }
}
