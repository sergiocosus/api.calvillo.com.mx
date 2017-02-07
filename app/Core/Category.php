<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'link',
        'image_code',
        'description',
        'priority',
    ];

    protected $appends = [
        'image_url',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function pictures()
    {
        return $this->morphedByMany(Picture::class, 'categorizable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'categorizable');
    }

    public function directories()
    {
        return $this->morphedByMany(Directory::class, 'categorizable');
    }

    public function getImageUrlAttribute()
    {
        return env('APP_URL').'/storage/images/category/'.$this->image_code;
    }
}
