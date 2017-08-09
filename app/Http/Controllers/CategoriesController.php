<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CategoriesController extends Controller
{

	const MODEL = 'App\Category';

	use RESTActions;


	public function productAll(Request $request, $id)
	{
		$m = self::MODEL;
		$model = $m::find($id);

		if(is_null($model)) {
			return $this->respond(Response::HTTP_NOT_FOUND);
		}
		
		$list = $model->products()->get()->sortBy('sort');

		return $this->respond(Response::HTTP_OK, $list);
	}

	public function pictureAll(Request $request, $id)
	{
		$m = self::MODEL;
		$model = $m::find($id);

		if(is_null($model)) {
			return $this->respond(Response::HTTP_NOT_FOUND);
		}

		return $this->respond(Response::HTTP_OK, $model->getPictures());
	}

	public function pictureAdd(Request $request, $id)
	{
		$m = self::MODEL;
		$model = $m::find($id);

		if(is_null($model)) {
			return $this->respond(Response::HTTP_NOT_FOUND);
		}

		$file = $request->file('source');
		$x = $request->input('x');
		$y = $request->input('y');
		$size = $request->input('size');
		$picture = $model->addPicture($file, $x, $y, $size);

		return $this->respond(Response::HTTP_OK, $picture);
	}
}
