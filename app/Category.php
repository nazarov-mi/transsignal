<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	public $timestamps = false;

	protected $table = 'categories';
	protected $fillable = [
		'name',
		'active',
		'sort',
	];
	protected $appends = [
		'pictures'
	];

	public static $rules = [
		'name'   => 'required|string|unique:categories|max:255',
		'active' => 'nullable|boolean',
		'sort'   => 'nullable|integer'
	];


	// Relationships

	public function products()
	{
		return $this->belongsToMany('App\Product', 'category_product', 'category_id', 'product_id');	
	}

	public function delete()
	{
		$this->removePictures();

		return parent::delete();
	}

	public function getPictures()
	{
		$pictures = $this->morphMany('App\Picture', 'parent');
		$res = [];

		foreach ($pictures as $pic) {
			$res[] = $pic;
		}

		return $res;
	}

	public function pictures()
	{
		return $this->morphMany('App\Picture', 'imageable');
	}

	public function getPicturesAttribute()
	{
		return $this->pictures()->get();
	}

	public function addPicture($file, $crop)
	{
		return Picture::create($this->id, 'App\Category', $file, $crop);
	}

	public function removePictures()
	{
		$pictures = $this->getPictures();

		foreach ($pictures as $pic) {
			$pic->delete();
		}
	}
}
