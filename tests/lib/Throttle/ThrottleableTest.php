<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Throttle;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Reyesoft\ReactiveLaravelJobs\Debounce\Throttleable;
use Reyesoft\ReactiveLaravelJobs\Debounce\DebouncedDispatch;
use Tests\TestCase;

/**
 * @internal
 * @covers \Reyesoft\ReactiveLaravelJobs\Throttle\Throttleable
 * @covers \Reyesoft\ReactiveLaravelJobs\Throttle\ThorttledDispatch
 */
final class ThrottleableTest extends TestCase
{
    public function testCreateThrottleableJob(): void
    {
        Queue::fake();
        $debounced_values = [
            ['param' => 'case_a_1'],
            ['param' => 'case_a_1'],
            ['param' => 'case_a_1'],
            ['param' => 'case_a_1'],
            ['param' => 'case_a_2'],
            ['param' => 'case_a_2'],
        ];

        // send to queue
        foreach ($debounced_values as &$value) {
            /** @var DebouncedDispatch $job */
            $job = ThrottleableExampleJob::dispatchThrottled($value['param'], 'another_value');
            $value['reactive_job_id'] = $job->getJob()->reactive_job_id;
        }
        // run queued jobs
        foreach ($debounced_values as &$value) {
            $job = (new ThrottleableExampleJob($value['param'], 'some_value'));
            $job->reactive_job_id = $value['reactive_job_id'];
            $job->handle();
        }

        static::assertSame(1, Cache::get('used_for_testing_throttle_case_a_1'));
        static::assertSame(1, Cache::get('used_for_testing_throttle_case_a_2'));
    }

    public function testCreateThrottleableJobViaConstructor(): void
    {
        Queue::fake();
        $debounced_values = [
            ['param' => 'case_b_1'],
            ['param' => 'case_b_1'],
            ['param' => 'case_b_1'],
            ['param' => 'case_b_1'],
            ['param' => 'case_b_2'],
            ['param' => 'case_b_2'],
        ];

        // send to queue
        foreach ($debounced_values as &$value) {
            /** @var DebouncedDispatch $job */
            $job = (new ThrottleableExampleJob($value['param'], 'another_value'))->dispatchThrottledOn(PHP_INT_MAX);
            $value['reactive_job_id'] = $job->getJob()->reactive_job_id;
        }
        // run queued jobs
        foreach ($debounced_values as &$value) {
            $job = (new ThrottleableExampleJob($value['param'], 'some_value'));
            $job->reactive_job_id = $value['reactive_job_id'];
            $job->handle();
        }

        static::assertSame(1, Cache::get('used_for_testing_throttle_case_b_1'));
        static::assertSame(1, Cache::get('used_for_testing_throttle_case_b_2'));
    }
}
