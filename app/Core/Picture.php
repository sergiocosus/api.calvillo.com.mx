<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Picture extends Model
{
    use ISODateFormatSerializeDate;
    use SoftDeletes;
    use ImageUrlTrait;
    use Searchable;

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


    public function toSearchableArray()
    {
        $array = [
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
        ];

        return $array;
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function getImageUrlAttribute()
    {
        return $this->getImageUrl('images/picture/'.$this->image_code);
    }

}
