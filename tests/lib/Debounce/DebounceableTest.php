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
        $debounced_ids = [
            'case_a_1',
            'case_a_1',
            'case_a_1',
            'case_a_2',
            'case_a_2',
        ];

        // send to queue
        foreach ($debounced_ids as $debounced_id) {
            DebounceableExampleJob::dispatchDebounced($debounced_id, 'another_value');
        }
        // run queued jobs
        foreach ($debounced_ids as $debounced_id) {
            (new DebounceableExampleJob($debounced_id, 'some_value'))->handle();
        }

        static::assertSame(1, Cache::get('used_for_testing_case_a_1'));
        static::assertSame(1, Cache::get('used_for_testing_case_a_2'));
    }

    public function testCreateDebounceableJobViaConstructor(): void
    {
        Queue::fake();
        $debounced_ids = [
            'case_b_1',
            'case_b_1',
            'case_b_1',
            'case_b_2',
            'case_b_2',
        ];

        // send to queue
        foreach ($debounced_ids as $debounced_id) {
            (new DebounceableExampleJob($debounced_id, 'some_value'))->dispatchDebouncedOn(PHP_INT_MAX);
        }
        // run queued jobs
        foreach ($debounced_ids as $debounced_id) {
            (new DebounceableExampleJob($debounced_id, 'some_value'))->handle();
        }

        static::assertSame(1, Cache::get('used_for_testing_case_b_1'));
        static::assertSame(1, Cache::get('used_for_testing_case_b_2'));
    }
}
