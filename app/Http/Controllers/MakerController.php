<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Maker;

use App\Http\Requests\CreateMakerRequest;

class MakerController extends Controller {

	public function __construct()
	{
		$this->middleware('auth.basic.once', ['except' => ['index','show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$makers = Maker::all();

		return response()->Json(['data'=> $makers], 200);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateMakerRequest $request)
	{
		$values = $request->only(['name', 'phone']);
		
		Maker::create($values);

		return response()->Json(['message' => 'Maker correctly added'], 201);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$maker = Maker::find($id);

		if(!$maker)
		{
			return response()->Json(['message'=> 'This maker does nto exist', 'code' => 404], 404);
		}

		return response()->Json(['data'=> $maker], 200);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CreateMakerRequest $request, $id)
	{
		$maker = Maker::find($id);

		if(!$maker)
		{
			return response()->Json(['message'=> 'This maker does nto exist', 'code' => 404], 404);
		}

		$name = $request->get('name');
		$phone = $request->get('phone');

		$maker->name = $name;
		$maker->phone = $phone;

		$maker->save();

		return response()->Json(['message'=> 'The maker has been updated'], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
				$maker = Maker::find($id);

		if(!$maker)
		{
			return response()->Json(['message'=> 'This maker does nto exist', 'code' => 404], 404);
		}	
		
		$vehicle = $maker->vehicles;

		if (sizeof($vehicle) > 0)
		{
			return response()->Json(['message'=> 'This maker have associated vehicle. Delete his vehicle first.', 'code' => 409], 409);
		}

		$maker->delete();

		return response()->Json(['message'=> 'The maker has been deleted'], 200);
	}

}
