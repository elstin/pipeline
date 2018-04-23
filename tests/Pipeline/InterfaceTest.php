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

namespace Pipeline;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Pipeline\Standard
 * @covers \Pipeline\Principal
 */
class InterfaceTest extends TestCase
{
    private function takesStandardInterface(Interfaces\StandardPipeline $pipeline)
    {
        return $pipeline->map()->filter()->unpack();
    }

    public function testStandardInterface()
    {
        $pipeline = new Standard();
        $pipeline = $this->takesStandardInterface($pipeline->map()->filter()->unpack());

        $this->assertInstanceOf(Interfaces\StandardPipeline::class, $pipeline);
    }

    private function takesPrincipalInterface(Interfaces\PrincipalPipeline $pipeline)
    {
        return $pipeline;
    }

    public function testPrincipalInterface()
    {
        $pipeline = new Standard();

        $this->assertInstanceOf(Interfaces\PrincipalPipeline::class, $this->takesPrincipalInterface($pipeline));
    }
}