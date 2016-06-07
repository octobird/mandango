<?php

/*
 * This file is part of Mandango.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango;

class IdMap implements \ArrayAccess
{
    private $idMap = [];

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->idMap);
    }

    public function offsetGet($offset)
    {
        return $this->idMap[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->idMap[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->idMap[$offset]);
    }

    public function clear()
    {
        $this->idMap = [];
    }
}
