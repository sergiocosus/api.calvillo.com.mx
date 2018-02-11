<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10/02/18
 * Time: 09:20 PM
 */

namespace CalvilloComMx\Http\Controllers;


use App;
use CalvilloComMx\Core\Category;
use CalvilloComMx\Core\Directory;
use Roumen\Sitemap\Sitemap;
use URL;

class SitemapController extends Controller {
	/**
	 * @var Sitemap
	 */
	private $sitemap;
	public $base = 'https://calvillo.com.mx/';

	public $categoriesProcessed = [];

	public $imageSizes = [
		'xs',
		'sm',
		'md',
		'lg',
		'xlg',
	];

	/**
	 * SitemapController constructor.
	 */
	public function __construct(Sitemap $sitemap) {
		$this->sitemap = $sitemap;
	}

	public function get() {
			// create new sitemap object

		// set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
		// by default cache is disabled
		$this->sitemap->setCache('laravel.sitemap', 60);

		// check if there is cached sitemap and build new only if is not
		if (!$this->sitemap->isCached()) {
			// add item to the sitemap (url, date, priority, freq)
			$this->sitemap->add($this->base);
			$this->sitemap->add($this->base . 'mapa');
			$this->sitemap->add($this->base. 'contacto');


			$category = Category::whereLink('principal')->with(['categories', 'pictures', 'directories'])->first();
			$this->sitemap->add($this->base.'galeria/'.$category->link, $category->updated_at, null, null);


			$this->processCategory($category);

			$directories = Directory::get();

			foreach ($directories as $directory) {
				$images = $this->processPicture($directory->image_url, $directory->title);

				$this->sitemap->add($this->base.'directorio/'.$directory->link, $directory->updated_at, null, null, $images, $directory->title);
			}


		}

		// show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
		return $this->sitemap->render('xml');
	}

	public function processCategory( Category $category ) {
		foreach ($category->pictures as $picture) {
			$images = $this->processPicture($picture->image_url, $picture->title);

			$this->sitemap->add($this->base.'galeria/'.$category->link . 'foto/' . $picture->link,
				$picture->updated_at, null, null, $images, $picture->title);
		}


		foreach ($category->categories as $childCategory) {
			if (in_array($childCategory->id, $this->categoriesProcessed)) {
				continue;
			}

			$images = $this->processPicture($category->image_url, $category->title);
			$this->sitemap->add($this->base.'galeria/'.$childCategory->link, $childCategory->updated_at, null, null, $images, $category->title);
			$this->categoriesProcessed[] = $category->id;
			$this->processCategory($childCategory);
		}
	}

	public function processPicture( $image_url, $title ) {
		$images = [];

		foreach ($this->imageSizes as $imageSize) {
			$images[] =
				['url' => $image_url. '_' . $imageSize, 'title' => $title, 'geo_location' => 'Calvillo, Aguascalientes, México'];
		}
		return $images;
	}
}