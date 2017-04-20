<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use ISODateFormatSerializeDate;
    use SoftDeletes;

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

    protected $attributes = array(
        'description' => '',
    );

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function getImageUrlAttribute()
    {
        return env('APP_URL').'/storage/images/picture/'.$this->image_code;
    }
}
