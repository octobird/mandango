<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Tests\Group;

use Mandango\Tests\TestCase;
use Mandango\Group\EmbeddedGroup;

class EmbeddedGroupTest extends TestCase
{
    public function testInitializeSaved()
    {
        $data = array(
            array('name' => 'foo'),
            array('name' => 'bar'),
        );
        $group = new EmbeddedGroup('Model\Comment');
        $group->setMongoType(\Mandango\Group\AbstractGroup::MONGO_TYPE_ARRAY);
        $group->setRootAndPath($article = $this->mandango->create('Model\Article'), 'comments');
        $group->setSavedData($data);
        $this->assertSame(2, $group->count());
        $saved = $group->getSaved();
        $this->assertEquals('foo', $saved[0]->getName());
        $this->assertEquals($article, $saved[0]->_root);
        $this->assertEquals('comments.0', $saved[0]->_path);
        $this->assertEquals('bar', $saved[1]->getName());
        $this->assertEquals($article, $saved[1]->_root);
        $this->assertEquals('comments.1', $saved[1]->_path);

        $data = [
            'en' => ['title' => 'The Apple'],
            'de' => ['title' => 'Die Apfel'],
        ];
        $group = new EmbeddedGroup('Model\Translation');
        $group->setMongoType(\Mandango\Group\AbstractGroup::MONGO_TYPE_OBJECT);
        $group->setRootAndPath($article = $this->mandango->create('Model\Article'), 'translations');
        $group->setSavedData($data);
        $this->assertSame(2, $group->count());
        $saved = $group->getSaved();
        $this->assertEquals('The Apple', $saved['en']->getTitle());
        $this->assertEquals($article, $saved['en']->_root);
        $this->assertEquals('translations.en', $saved['en']->_path);
        $this->assertEquals('Die Apfel', $saved['de']->getTitle());
        $this->assertEquals($article, $saved['de']->_root);
        $this->assertEquals('translations.de', $saved['de']->_path);
    }

    public function testRootAndPath()
    {
        $group = new EmbeddedGroup('Model\Comment');
        $comment = $this->mandango->create('Model\Comment');
        $group->add($comment);
        $group->setRootAndPath($article = $this->mandango->create('Model\Article'), 'comments');
        $this->assertEquals($article, $comment->_root);
        $this->assertEquals('comments._add0', $comment->_path);
    }

    public function testAdd()
    {
        $group = new EmbeddedGroup('Model\Comment');
        $group->setRootAndPath($article = $this->mandango->create('Model\Article'), 'comments');
        $comment = $this->mandango->create('Model\Comment');
        $group->add($comment);
        $this->assertEquals($article, $comment->_root);
        $this->assertEquals('comments._add0', $comment->_path);
    }

    public function testSavedData()
    {
        $group = new EmbeddedGroup('Model\Comment');
        $this->assertNull($group->getSavedData());
        $group->setSavedData($data = array(array('foo' => 'bar'), array('bar' => 'foo')));
        $this->assertSame($data, $group->getSavedData());
    }
    
    public function testDuplicateSplObjectHash()
    {
        $group = new EmbeddedGroup('Model\Comment');
        $comment = $this->mandango->create('Model\Comment');
        $this->assertEquals(0, count($group->getAdd()));
        $group->add($comment);
        $this->assertEquals(1, count($group->getAdd()));
        unset($group);
        $group = new EmbeddedGroup('Model\Comment');
        $this->assertEquals(0, count($group->getAdd()));
    }
}
