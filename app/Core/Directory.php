<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    protected $fillable = [
        'title',
        'link',
        'image_code',
        'description',
        'address',
        'email',
        'phone',
        'cellphone',
        'website_url',
        'youtube_url',
        'facebook_url',
        'latitude',
        'longitude',
    ];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
