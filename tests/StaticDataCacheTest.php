<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   DataContainers/Caches
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Caches;

use PHPUnit_Framework_TestCase;
use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;

// we use these caches for testing the independence of caches
class CacheTypeA {
    use StaticDataCache;
}
class CacheTypeB {
    use StaticDataCache;
}

// we use these caches for testing shared caches
class CacheTypeASub1 extends CacheTypeA { }
class CacheTypeASub2 extends CacheTypeA { }

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Caches\StaticDataCache
 */
class StaticDataCacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getFromCache
     * @covers ::setInCache
     * @dataProvider provideDataForTheCache
     */
    public function testCanStoreDataInTheCache($key, $expectedValue)
    {
        // ----------------------------------------------------------------
        // setup your test

        $cache = new CacheTypeA;
        // make sure the underlying shared cache is EMPTY
        InvokeMethod::onString(CacheTypeA::class, 'resetCache');
        $this->assertNull(InvokeMethod::onObject($cache, 'getFromCache', [$key]));

        // ----------------------------------------------------------------
        // perform the change

        InvokeMethod::onObject($cache, 'setInCache', [$key, $expectedValue]);

        // ----------------------------------------------------------------
        // test the results

        $actualValue = InvokeMethod::onObject($cache, 'getFromCache', [$key]);
        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * @covers ::getFromCache
     * @dataProvider provideDataForTheCache
     */
    public function testReturnsNullIfNotInCache($key, $expectedValue)
    {
        // ----------------------------------------------------------------
        // setup your test

        $cache = new CacheTypeA;
        // make sure the underlying shared cache is EMPTY
        InvokeMethod::onString(CacheTypeA::class, 'resetCache');

        // ----------------------------------------------------------------
        // perform the change

        // ----------------------------------------------------------------
        // test the results

        $this->assertNull(InvokeMethod::onObject($cache, 'getFromCache', [$key]));
    }

    public function provideDataForTheCache()
    {
        return [
            [ 'name', 'harry' ],
            [ 'name', 'sally' ],
            [ 'name', 'fred' ],
        ];
    }

    /**
     * @coversNone
     */
    public function testSupportsIndependentCaches()
    {
        // ----------------------------------------------------------------
        // setup your test

        // make sure we start with a known state
        InvokeMethod::onString(CacheTypeA::class, 'resetCache');
        InvokeMethod::onString(CacheTypeB::class, 'resetCache');

        // we expect CacheTypeA to ONLY have these values
        $expectedResultsA = [
            'harry',
            'sally',
            'fred'
        ];

        // we expect CacheTypeB to ONLY have these values
        $expectedResultsB = [
            'cod',
            'haddock',
            'trout'
        ];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($expectedResultsA as $key => $value) {
            InvokeMethod::onString(CacheTypeA::class, 'setInCache', [$key, $value]);
        }
        foreach ($expectedResultsB as $key => $value) {
            InvokeMethod::onString(CacheTypeB::class, 'setInCache', [$key, $value]);
        }

        // ----------------------------------------------------------------
        // test the results

        $actualResultsA = InvokeMethod::onString(CacheTypeA::class, 'getCache');
        $actualResultsB = InvokeMethod::onString(CacheTypeB::class, 'getCache');

        $this->assertEquals($expectedResultsA, $actualResultsA);
        $this->assertEquals($expectedResultsB, $actualResultsB);
    }


}