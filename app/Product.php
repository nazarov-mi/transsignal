<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Picture;

class Product extends Model
{
	public $timestamps = false;

	protected $table = 'products';
	protected $fillable = [
		'name',
		'short_desc',
		'desc',
		'price',
		'active',
		'sort',
		'categories'
	];
	protected $appends = [
		'categories',
		'pictures'
	];

	public static $rules = [
		'name'       => 'required|string|max:255',
		'short_desc' => 'required|string',
		'desc'       => 'required|string',
		'price'      => 'required|numeric|min:0',
		'active'     => 'nullable|boolean',
		'sort'       => 'nullable|integer',
	];


	// Relationships

	public function categories()
	{
		return $this->belongsToMany('App\Category', 'category_product', 'product_id', 'category_id');	
	}

	public function getCategoriesAttribute()
	{
		return $this->categories()->pluck('id')->all();
	}

	public function setCategoriesAttribute($categories)
	{
		$this->categories()->detach();
		if (!$categories) return;
		if (!$this->exists) $this->save();

		$this->categories()->attach($categories);
	}

	public function pictures()
	{
		return $this->morphMany('App\Picture', 'imageable');
	}

	public function getPicturesAttribute()
	{
		return $this->pictures()->get();
	}

	public function delete()
	{
		$this->removePictures();

		return parent::delete();
	}

	public function getPictures()
	{
		$pictures = $this->pictures;
		$res = [];
		
		foreach ($pictures as $pic) {
			$res[] = $pic;
		}

		return $res;
	}

	public function addPicture($file, $x, $y, $size)
	{
		return Picture::create($this->id, 'App\Product', $file, $x, $y, $size);
	}

	public function removePictures()
	{
		$pictures = $this->pictures;
		
		foreach ($pictures as $pic) {
			$pic->delete();
		}
	}
}
