<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Picture extends Model
{
	public $timestamps = false;

	protected $table = 'pictures';
	protected $fillable = [
		'imageable_id',
		'imageable_type',
		'extension',
		'path',
		'full',
		'preview',
		'small'
	];

	public static $rules = [
		'imageable_id'   => 'required|integer',
		'imageable_type' => 'required|string',
		'extension'      => 'required|string',
		'path'           => 'required|string',
		'full'           => 'required|string',
		'preview'        => 'required|string',
		'small'          => 'required|string'
	];

	// Relationships

	public static function create($parentId, $parentType, $file, $x, $y, $size)
	{
		$ext = $file->getClientOriginalExtension();
		$path = 'uploaded/' . time(); // rtrim(app()->basePath(), '/');
		$full = $path . '/full.' . $ext;
		$preview = $path . '/preview.' . $ext;
		$small = $path . '/small.' . $ext;

		mkdir($path, 0777, true);

		$img = Image::make($file->getRealPath());
		$img->save($full);
		$img->crop($size, $size, $x, $y);

		$img->resize(200, 200);
		$img->save($preview);
		
		$img->resize(100, 100);
		$img->save($small);

		$picture = new Picture([
			'imageable_id' => $parentId,
			'imageable_type' => $parentType,
			'extension' => $ext,
			'path' => $path,
			'full' => $full,
			'preview' => $preview,
			'small' => $small
		]);
		$picture->save();

		return $picture;
	}

	public function delete()
	{
		$path = $this->path;

		if (!empty($path) && is_dir($path)) {
			$files = array_diff(scandir($path), array('.', '..'));

			foreach ($files as $file)
			{
				unlink(realpath($path) . '/' . $file);
			}

			rmdir($path);
		}

		return parent::delete();
	}

	public function imageable()
	{
		return $this->morphTo();
	}
}
