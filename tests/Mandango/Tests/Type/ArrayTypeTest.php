<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Tests\Type;

use Mandango\Type\ArrayType;

class ArrayTypeTest extends TestCase
{
    public function testToMongo()
    {
        $type = new ArrayType();
        $this->assertEquals([123], $type->toMongo('123'));
    }

    public function testToPHP()
    {
        $type = new ArrayType();
        $this->assertEquals([123], $type->toPHP('123'));
    }

    public function testToMongoInString()
    {
        $type = new ArrayType();
        $function = $this->getTypeFunction($type->toMongoInString());

        $this->assertEquals([123], $function('123'));
    }

    public function testToPHPInString()
    {
        $type = new ArrayType();
        $function = $this->getTypeFunction($type->toPHPInString());

        $this->assertEquals([123], $function('123'));
    }
}
