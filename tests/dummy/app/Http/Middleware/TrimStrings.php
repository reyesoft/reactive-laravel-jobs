<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace DummyApp\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * @internal
 */
class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
