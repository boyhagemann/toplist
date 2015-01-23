<?php namespace App\Http\Controllers;

use App\Selection;

class SelectionController extends Controller {

	public function show(Selection $selection)
	{
		$selection->load(['products.tags', 'tags']);
		return view('selection.show', compact('selection'));
	}

}
