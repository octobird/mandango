<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Group;

use Mandango\Archive;
use Mandango\Document\Document;

/**
 * EmbeddedGroup.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 *
 * @api
 */
class EmbeddedGroup extends Group
{
    /**
     * Set the root and path of the embedded group.
     *
     * @param \Mandango\Document\Document $root The root document.
     * @param string                      $path The path.
     *
     * @api
     */
    public function setRootAndPath(Document $root, $path)
    {
        Archive::set($this, 'root_and_path', array('root' => $root, 'path' => $path));

        foreach ($this->getAdd() as $key => $document) {
            $document->setRootAndPath($root, $path.'._add'.$key);
        }
    }

    /**
     * Returns the root and the path.
     *
     * @api
     */
    public function getRootAndPath()
    {
        return Archive::getOrDefault($this, 'root_and_path', null);
    }

    /**
     * {@inheritdoc}
     */
    public function add($documents)
    {
        parent::add($documents);

        if ($rap = $this->getRootAndPath()) {
            foreach ($this->getAdd() as $key => $document) {
                $document->setRootAndPath($rap['root'], $rap['path'].'._add'.$key);
            }
        }
    }

    /**
     * Set the saved data.
     *
     * @param array $data The saved data.
     */
    public function setSavedData(array $data)
    {
        Archive::set($this, 'saved_data', $data);
    }

    /**
     * Returns the saved data.
     *
     * @return array|null The saved data or null if it does not exist.
     */
    public function getSavedData()
    {
        return Archive::getOrDefault($this, 'saved_data', null);
    }

    /**
     * {@inheritdoc}
     */
    protected function doInitializeSavedData()
    {
        $data = $this->getSavedData();
        if ($data !== null) {
            return $data;
        }

        $rap = $this->getRootAndPath();

        if ($rap['root']->isNew()) {
            return array();
        }

        /* TODO
         * 
         * Suboptimal workaround to query cache bug with EmbeddedGroup:
         * only add path until the first numeric element, but not that element.
         * 
         * Example:
         * 
         * If path is "comments.1.infos", only "comments" is added to the cache.
         * 
         * A better solution is to mark EmbeddedGroups, so the Query class can use
         * MongoDB's array projection operator to project document fields inside
         * arrays.
         */
        $path = [];
        foreach (explode('.', $rap['path']) as $e) {
            if (is_numeric($e)) {
                break;
            }
            $path[] = $e;
        }
        $path = implode('.', $path);

        $rap['root']->addFieldCache($path);

        $result = $rap['root']
            ->getRepository()
            ->getCollection()
            ->findOne(array('_id' => $rap['root']->getId()), array($rap['path']))
        ;

        return ($result && isset($result[$rap['path']])) ? $result[$rap['path']] : array();
    }

    /**
     * {@inheritdoc}
     */
    protected function doInitializeSaved(array $data)
    {
        $documentClass = $this->getDocumentClass();
        $rap = $this->getRootAndPath();
        $mandango = $rap['root']->getMandango();

        $saved = array();
        foreach ($data as $key => $datum) {
            $saved[] = $document = new $documentClass($mandango);
            $document->setDocumentData($datum);
            $document->setRootAndPath($rap['root'], $rap['path'].'.'.$key);
        }

        return $saved;
    }
}
