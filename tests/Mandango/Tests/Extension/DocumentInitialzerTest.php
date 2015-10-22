<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Tests\Extension;

use Mandango\Tests\TestCase;

class DocumentInitializerTest extends TestCase
{
    public function test__create()
    {
        $article = $this->mandango->create('Model\Article', [
            'title' => 'foo'
        ]);
        $this->assertSame('foo', $article->getTitle());
    }
}
