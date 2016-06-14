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
 * DateType.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 *
 * @api
 */
class DateType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function toMongo($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        } elseif (is_string($value)) {
            $value = strtotime($value);
        }

        return new \MongoDB\BSON\UTCDateTime($value * 1000);
    }

    /**
     * {@inheritdoc}
     */
    public function toPHP($value)
    {
        return $value->toDateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function toMongoInString()
    {
        return '%to% = %from%; if (%to% instanceof \DateTime) { %to% = %to%->getTimestamp(); } elseif (is_string(%to%)) { %to% = strtotime(%to%); } %to% = new \MongoDB\BSON\UTCDateTime(%to% * 1000);';
    }

    /**
     * {@inheritdoc}
     */
    public function toPHPInString()
    {
        return '%to% = %from%->toDateTime();';
    }
}
