<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 25/03/17
 * Time: 09:52 PM
 */

namespace CalvilloComMx\Core;


use Carbon\Carbon;

trait ISODateFormatSerializeDate
{
    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format(Carbon::ISO8601);
    }
}