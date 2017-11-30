<?php

namespace CalvilloComMx\Core;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;


class BaseModel extends Model
{

    protected $customDateFormat = 'Y-m-d\TH:i:s.uO';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->customDateFormat);
    }

    public function fromDateTime($value)
    {
        try{
            $value = Carbon::createFromFormat(
                $this->customDateFormat, $value
            )->setTimezone('UTC')->format(
                $this->getDateFormat()
            );
        } catch (\Exception $e) {

        }

        return is_null($value) ? $value : $this->asDateTime($value)->format(
            $this->getDateFormat()
        );

    }
}