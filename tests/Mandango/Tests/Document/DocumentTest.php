<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Tests\Document;

use Mandango\Tests\TestCase;
use Mandango\Document\Document as BaseDocument;

class Document extends BaseDocument
{
}

class DocumentTest extends TestCase
{
    public function testSetGetId()
    {
        $document = new Document($this->mandango);
        $this->assertNull($document->getId());

        $id = new \MongoId($this->generateObjectId());
        $this->assertSame($document, $document->setId($id));
        $this->assertSame($id, $document->getId());
    }

    public function testQueryHashes()
    {
        $hashes = array(md5(1), md5(2), md5(3));

        $document = new Document($this->mandango);
        $this->assertSame(array(), $document->getQueryHashes());
        $document->addQueryHash($hashes[0]);
        $this->assertSame(array($hashes[0] => 1), $document->getQueryHashes());
        $document->addQueryHash($hashes[1]);
        $document->addQueryHash($hashes[2]);
        $this->assertSame(array_combine($hashes, array(1,1,1)), $document->getQueryHashes());
        $document->removeQueryHash($hashes[1]);
        $this->assertSame(array($hashes[0] => 1, $hashes[2] => 1), $document->getQueryHashes());
        $document->clearQueryHashes();
        $this->assertSame(array(), $document->getQueryHashes());
    }

    public function testIsnew()
    {
        $document = new Document($this->mandango);
        $this->assertTrue($document->isNew());

        $document->setIsNew(false);
        $this->assertFalse($document->isNew());
    }
}
