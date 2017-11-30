<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Category extends BaseModel
{
    use SoftDeletes;
    use ImageUrlTrait;
    use Searchable;

    protected $fillable = [
        'title',
        'link',
        'image_code',
        'description',
        'category_id',
        'priority',
    ];

    protected $appends = [
        'image_url',
    ];


    public function toSearchableArray()
    {
        $array = [
            'title' => $this->title,
            'description' => $this->description,
            'link' => $this->link,
        ];

        return $array;
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function deletedCategories()
    {
        return $this->categories()->onlyTrashed();
    }

    public function pictures()
    {
        return $this->morphedByMany(Picture::class, 'categorizable');
    }

    public function deletedPictures()
    {
        return $this->pictures()->onlyTrashed();
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'categorizable');
    }

    public function directories()
    {
        return $this->morphedByMany(Directory::class, 'categorizable');
    }

    public function deletedDirectories()
    {
        return $this->directories()->onlyTrashed();
    }

    public function getImageUrlAttribute()
    {
        return $this->getImageUrl('images/category/'.$this->image_code);
    }
}
