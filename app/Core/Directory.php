<?php

namespace CalvilloComMx\Core;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Directory extends BaseModel
{
    use SoftDeletes;
    use ImageUrlTrait;
    use Searchable;

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
        return $this->getImageUrl('images/directory/'.$this->image_code);
    }


	public function getRelatedDirectoriesAttribute() {
		logger($this->id);

		$relatedDirectories = $this->categories()->first()
			->directories()
			->where('id', '!=', $this->id)
			->orderByRaw('RAND()')
			->limit(3)
			->get();

		logger($relatedDirectories);


		$randomDirectory = Directory::orderByRaw('RAND()')->first();
		logger($randomDirectory);

		$relatedDirectories->push($randomDirectory);

		return $relatedDirectories;
	}
}
