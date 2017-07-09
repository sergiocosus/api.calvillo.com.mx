<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directory extends Model
{
    use ISODateFormatSerializeDate;
    use SoftDeletes;
    use ImageUrlTrait;

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

    protected $appends = [
        'image_url',
    ];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function getImageUrlAttribute()
    {
        return $this->getImageUrl('images/directory/'.$this->image_code);
    }
}
