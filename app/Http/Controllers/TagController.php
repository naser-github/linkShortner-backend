<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TagController extends Controller
{
    public function index()
    {
        
        $response = Tag::where( 'fk_user_id', Auth::id() )->get();

        return response($response, 201);
    }

//    public function create()
//    {
    //
//    }

    public function store(Request $request)
    {
        request()->validate([
            'tag_name' => 'required|unique:url_tags'
        ]);

        $tag = new Tag();
        $tag->fk_user_id = Auth::id();
        $tag->tag_name = $request->input('tag_name');
        $response = 'Tag has been created';
        $tag->save();

        return response($response, 201);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tag_name' => 'required',
            'status' => 'required',
        ]);

        $tag = Tag::where('id', $id)->first();

        if (!$tag->tag_name) {
            return response('Tag already exist', 404);
        }

        $tag->tag_name = $request->input('tag_name');
        $tag->tag_status = $request->input('status');
        $response = 'Tag has been updated';
        $tag->save();

        return response($response, 201);
    }


    public function editModal(Request $request)
    {
        $tag = Tag::find($request->id);
        return view('pages.operator.settings.tag-edit-modal', compact('tag'));
    }
}
