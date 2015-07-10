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
 * @link      http://code.ganbarodigital.com/php-file-system
 */

namespace GanbaroDigital\DataContainers\Caches;

trait StaticDataCache
{
    /**
     * our cached data
     *
     * @var array
     */
    protected static $cachedData = [];

    /**
     * do we have this result set in the cache?
     *
     * @param  string|int|double $key
     *         the index to search for
     * @return mixed|null
     */
    protected static function getFromCache($key)
    {
        if (isset(static::$cachedData[$key])) {
            return static::$cachedData[$key];
        }

        return null;
    }

    /**
     * store data about a key to speed up repeated calls
     *
     * @param string|int|double $key
     *        the data key to store information about
     * @param mixed $data
     *        the data to store in the cache
     */
    protected static function setInCache($key, $data)
    {
        static::$cachedData[$key] = $data;
    }

    /**
     * empty out the cache completely
     *
     * this is mostly provided for unit testing purposes
     *
     * @return void
     */
    protected static function resetCache()
    {
        static::$cachedData = [];
    }

    /**
     * return a copy of the cache for inspection
     *
     * this is mostly provided for unit testing purposes
     *
     * @return array
     */
    protected static function getCache()
    {
        return static::$cachedData;
    }
}