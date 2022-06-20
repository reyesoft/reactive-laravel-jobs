<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Reyesoft\ReactiveLaravelJobs\Throttle;

use Illuminate\Contracts\Queue\ShouldQueue;

interface ShouldThrottle extends ShouldQueue
{
    public function getIdForReactive(): string;

    /**
     * @return bool|void
     */
    public function throttleHandle();
}
