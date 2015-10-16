<?php

/*
 * This file is part of Mandango.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Type;

/**
 * IntegerType.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 *
 * @api
 */
class ArrayType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function toMongo($value)
    {
        return (array) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function toPHP($value)
    {
        return (array) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function toMongoInString()
    {
        return '%to% = (array) %from%;';
    }

    /**
     * {@inheritdoc}
     */
    public function toPHPInString()
    {
        return '%to% = (array) %from%;';
    }
}
