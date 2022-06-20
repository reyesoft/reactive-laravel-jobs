<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests\Debounce;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Reyesoft\ReactiveLaravelJobs\Debounce\Debounceable;
use Reyesoft\ReactiveLaravelJobs\Debounce\DebouncedDispatch;
use Tests\TestCase;

/**
 * @internal
 * @covers \Reyesoft\ReactiveLaravelJobs\Debounce\Debounceable
 * @covers \Reyesoft\ReactiveLaravelJobs\Debounce\DebouncedDispatch
 */
final class DebounceableTest extends TestCase
{
    public function testCreateDebounceableJob(): void
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
            $job = DebounceableExampleJob::dispatchDebounced($value['param'], 'another_value');
            $value['reactive_job_id'] = $job->getJob()->reactive_job_id;
        }
        // run queued jobs
        foreach ($debounced_values as &$value) {
            $job = (new DebounceableExampleJob($value['param'], 'some_value'));
            $job->reactive_job_id = $value['reactive_job_id'];
            $job->handle();
        }

        static::assertSame(1, Cache::get('used_for_testing_case_a_1'));
        static::assertSame(1, Cache::get('used_for_testing_case_a_2'));
    }

    public function testCreateDebounceableJobViaConstructor(): void
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
            $job = (new DebounceableExampleJob($value['param'], 'another_value'))->dispatchDebouncedOn(PHP_INT_MAX);
            $value['reactive_job_id'] = $job->getJob()->reactive_job_id;
        }
        // run queued jobs
        foreach ($debounced_values as &$value) {
            $job = (new DebounceableExampleJob($value['param'], 'some_value'));
            $job->reactive_job_id = $value['reactive_job_id'];
            $job->handle();
        }

        static::assertSame(1, Cache::get('used_for_testing_case_b_1'));
        static::assertSame(1, Cache::get('used_for_testing_case_b_2'));
    }
}
