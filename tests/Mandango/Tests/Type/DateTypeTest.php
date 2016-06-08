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

use Mandango\Type\DateType;

class DateTypeTest extends TestCase
{
    public function testToMongo()
    {
        $type = new DateType();

        $time = time();
        $this->assertEquals(new \MongoDB\BSON\UTCDateTime($time), $type->toMongo($time));

        $date = new \DateTime();
        $date->setTimestamp($time);
        $this->assertEquals(new \MongoDB\BSON\UTCDateTime($time), $type->toMongo($date));

        $string = '2010-02-20';
        $this->assertEquals(new \MongoDB\BSON\UTCDateTime(strtotime($string)), $type->toMongo($string));
    }

    public function testToPHP()
    {
        $type = new DateType();

        $time = time();
        $date = new \DateTime();
        $date->setTimestamp($time);

        $this->assertEquals($date, $type->toPHP(new \MongoDB\BSON\UTCDateTime($time * 1000)));
    }

    public function testToMongoInString()
    {
        $type = new DateType();
        $function = $this->getTypeFunction($type->toMongoInString());

        $time = time();
        $this->assertEquals(new \MongoDB\BSON\UTCDateTime($time), $function($time));

        $date = new \DateTime();
        $date->setTimestamp($time);
        $this->assertEquals(new \MongoDB\BSON\UTCDateTime($time), $function($date));

        $string = '2010-02-20';
        $this->assertEquals(new \MongoDB\BSON\UTCDateTime(strtotime($string)), $function($string));
    }

    public function testToPHPInString()
    {
        $type = new DateType();
        $function = $this->getTypeFunction($type->toPHPInString());

        $time = time();
        $date = new \DateTime();
        $date->setTimestamp($time);

        $this->assertEquals($date, $function(new \MongoDB\BSON\UTCDateTime($time * 1000)));
    }
}
