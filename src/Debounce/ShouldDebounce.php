<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Reyesoft\ReactiveLaravelJobs\Debounce;

use Illuminate\Contracts\Queue\ShouldQueue;

interface ShouldDebounce extends ShouldQueue
{
    public function getIdForDebounce(): string;

    /**
     * @return bool|void
     */
    public function debouncedHandle();
}
