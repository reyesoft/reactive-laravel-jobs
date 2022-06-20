<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Reyesoft\ReactiveLaravelJobs\Throttle;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class ThrottledDispatch extends PendingDispatch
{
    /**
     * @param ShouldThrottle $job
     */
    public function __construct($job)
    {
        parent::__construct($job);
        $this->getJob()->reactive_job_id = Cache::increment('reactive_job_' . $job->getIdForReactive());
    }

    /**
     * @return Throttleable
     */
    public function getJob()
    {
        return $this->job;
    }
}
