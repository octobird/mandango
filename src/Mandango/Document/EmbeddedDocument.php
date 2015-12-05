<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Document;

use Mandango\Group\EmbeddedGroup;

/**
 * The base class for embedded documents.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 *
 * @api
 */
abstract class EmbeddedDocument extends AbstractDocument
{
    /**
     * The root document of the document
     * 
     * Do not modify directly, use setRootAndPath()!
     */
    public $_root;

    /**
     * The path of the document relative to the root document
     * 
     * Do not modify directly, use setRootAndPath()!
     */
    public $_path;

    /**
     * Set the root and path of the embedded document.
     *
     * @param \Mandango\Document\Document $root The root document.
     * @param string                      $path The path.
     *
     * @api
     */
    public function setRootAndPath(Document $root, $path)
    {
        $this->_root = $root;
        $this->_path = $path;

        if (isset($this->data['embeddedsOne'])) {
            foreach ($this->data['embeddedsOne'] as $name => $embedded) {
                $embedded->setRootAndPath($root, $path.'.'.$name);
            }
        }

        if (isset($this->data['embeddedsMany'])) {
            foreach ($this->data['embeddedsMany'] as $name => $embedded) {
                $embedded->setRootAndPath($root, $path . '.' . $name);
            }
        }
    }

    /**
     * Returns if the embedded document is an embedded one document changed.
     *
     * @return bool If the document is an embedded one document changed.
     */
    public function isEmbeddedOneChangedInParent()
    {
        if (empty($this->_root)) {
            return false;
        }

        if ($this->_root instanceof EmbeddedGroup) {
            return false;
        }

        $exPath = explode('.', $this->_path);
        unset($exPath[count($exPath) -1 ]);

        $parentDocument = $this->_root;
        foreach ($exPath as $embedded) {
            $parentDocument = $parentDocument->{'get'.ucfirst($embedded)}();
            if ($parentDocument instanceof EmbeddedGroup) {
                return false;
            }
        }

        $exPath = explode('.', $this->_path);
        $name = $exPath[count($exPath) - 1];

        return $parentDocument->isEmbeddedOneChanged($name);
    }

    /**
     * Returns whether the embedded document is an embedded many new.
     *
     * @return bool Whether the embedded document is an embedded many new.
     */
    public function isEmbeddedManyNew()
    {
        if (empty($this->_root)) {
            return false;
        }

        return false !== strpos($this->_path, '._add');
    }

    public function preInsertEvent() {}
    public function postInsertEvent() {}
    public function preUpdateEvent() {}
    public function postUpdateEvent() {}
    public function preDeleteEvent() {}
    public function postDeleteEvent() {}
}
