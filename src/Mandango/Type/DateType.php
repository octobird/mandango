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
            if (version_compare(MONGODB_VERSION, '1.2.0', '<')) {
                $value = $value->getTimestamp();
            }
        } elseif (is_string($value)) {
            $value = strtotime($value);
        }

        if (is_int($value)) {
            $value *= 1000;
        }

        return new \MongoDB\BSON\UTCDateTime($value);
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
        return <<<EOF
%to% = %from%; if (%to% instanceof \DateTime) { if (version_compare(MONGODB_VERSION, '1.2.0', '<')) { %to% = %to%->getTimestamp(); }} elseif (is_string(%to%)) { %to% = strtotime(%to%); } if (is_int(%to%)) { %to% *= 1000; } %to% = new \MongoDB\BSON\UTCDateTime(%to%);
EOF;
    }

    /**
     * {@inheritdoc}
     */
    public function toPHPInString()
    {
        return '%to% = %from%->toDateTime();';
    }
}
