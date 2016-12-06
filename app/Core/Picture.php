<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    // For images code uniqid()

    protected $fillable = [
        'title',
        'link',
        'image_code',
        'description',
        'latitude',
        'longitude',
        'taken_at',
    ];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
