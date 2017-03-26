<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use ISODateFormatSerializeDate;

    protected $fillable = [
        'title',
        'link',
        'image_code',
        'description',
        'latitude',
        'longitude',
        'taken_at',
    ];

    protected $appends = [
        'image_url',
    ];

    protected $dates = ['taken_at'];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function getImageUrlAttribute()
    {
        return env('APP_URL').'/storage/images/picture/'.$this->image_code;
    }
}
