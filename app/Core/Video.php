<?php

namespace CalvilloComMx\Core;


class Video extends BaseModel
{

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
