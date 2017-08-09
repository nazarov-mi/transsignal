<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class UsersController extends Controller
{

	const MODEL = 'App\User';

	use AuthenticatesUsers;
	use RESTActions;


	protected function authenticated($request, $user)
	{
		return response()->json(["username" => $user->username], Response::HTTP_OK);
	}

	public function username()
	{
		return 'username';
	}

	public function check(Request $request)
	{
		return response()->json(Auth::check(), Response::HTTP_OK);
	}
}
