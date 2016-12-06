<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
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

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
