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

use Mandango\Type\BinDataType;

class BinDataTypeTest extends TestCase
{
    public function testToMongo()
    {
        $type = new BinDataType();
        $this->assertEquals(new \MongoDB\BSON\Binary('123', \MongoDB\BSON\Binary::TYPE_GENERIC), $type->toMongo('123'));
        $this->assertEquals(new \MongoDB\BSON\Binary(file_get_contents(__FILE__), \MongoDB\BSON\Binary::TYPE_GENERIC), $type->toMongo(__FILE__));
    }

    public function testToPHP()
    {
        $type = new BinDataType();
        $this->assertSame('123', $type->toPHP(new \MongoDB\BSON\Binary('123', \MongoDB\BSON\Binary::TYPE_GENERIC)));
    }

    public function testToMongoInString()
    {
        $type = new BinDataType();
        $function = $this->getTypeFunction($type->toMongoInString());

        $this->assertEquals(new \MongoDB\BSON\Binary('123', \MongoDB\BSON\Binary::TYPE_GENERIC), $function('123'));
        $this->assertEquals(new \MongoDB\BSON\Binary(file_get_contents(__FILE__), \MongoDB\BSON\Binary::TYPE_GENERIC), $function(__FILE__));
    }

    public function testToPHPInString()
    {
        $type = new BinDataType();
        $function = $this->getTypeFunction($type->toPHPInString());

        $this->assertSame('123', $function(new \MongoDB\BSON\Binary('123', \MongoDB\BSON\Binary::TYPE_GENERIC)));
    }
}
