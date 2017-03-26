<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use ISODateFormatSerializeDate;

    protected $fillable = [
        'title',
        'link',
        'image_code',
        'youtube_id',
        'description',
        'latitude',
        'longitude',
    ];

    protected $appends = [
        'image_url',
    ];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }


    public function getImageUrlAttribute()
    {
        return env('APP_URL').'/storage/images/video/'.$this->image_code;
    }
}
