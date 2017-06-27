<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use ISODateFormatSerializeDate;
    use SoftDeletes;

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
        return env('APP_URL').'/storage/images/category/'.$this->image_code;
    }
}
