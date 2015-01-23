<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\TagRepository;
use App\Tag;

class TagController extends Controller {

	public function index(TagRepository $repository)
	{
		$tags = $repository->all();
		return view('tag.index', compact('tags'));
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  Tag $tag
	 * @return Response
	 */
	public function show(Tag $tag)
	{
		$tag->load(['selections']);
		return view('tag.show', compact('tag'));
	}

}
