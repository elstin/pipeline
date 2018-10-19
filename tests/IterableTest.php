<?php
/*
 * Copyright 2017, 2018 Alexey Kopytko <alexey@kopytko.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Tests\Pipeline;

use PHPUnit\Framework\TestCase;
use Pipeline\Standard;

/**
 * @covers \Pipeline\Standard
 * @covers \Pipeline\Principal
 */
class IterableTest extends TestCase
{
    private static $usesIterable;

    public static function setUpBeforeClass()
    {
        if (\PHP_VERSION_ID < 70100) {
            self::$usesIterable = false;

            return;
        }

        $reflection = new \ReflectionClass(Standard::class);
        $type = $reflection->getConstructor()->getParameters()[0]->getType();
        self::$usesIterable = $type->isBuiltin(); // Traversable isn't builtin
    }

    protected function setUp()
    {
        if (!self::$usesIterable) {
            $this->markTestSkipped();
        }
    }

    public function testArrayToArray()
    {
        $pipeline = new Standard([1, 2, 3]);
        $this->assertSame([1, 2, 3], $pipeline->toArray());
    }

    public function testArrayToIterator()
    {
        $pipeline = new Standard([1, 2, 3]);
        $this->assertSame([1, 2, 3], iterator_to_array($pipeline));
    }

    public function testEmptyArrayStaysEmpty()
    {
        $pipeline = new Standard([]);

        $pipeline->filter()->map(function ($value) {
            yield $value;
            yield $value;
        })->filter()->unpack();

        $this->assertSame([], $pipeline->toArray());
    }

    public function testArrayFilter()
    {
        $pipeline = new Standard([0, 1, 2, 3, 0]);
        $this->assertSame([1, 2, 3], $pipeline->filter()->toArray());
    }

    public function testArrayMap()
    {
        $pipeline = new Standard([1 => 0, 1, 2, 3]);
        $this->assertSame([0 => 0, 1, 2, 3], $pipeline->map(function ($value) {
            return $value;
        })->toArray());
    }

    public function testArrayMapFilter()
    {
        $pipeline = new Standard([1 => 0, 1, 2, 3]);
        $this->assertSame([0 => 1, 2, 3], $pipeline->map(function ($value) {
            return $value;
        })->filter()->toArray());
    }
}
