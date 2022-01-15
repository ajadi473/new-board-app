<?php

namespace App\Http\Controllers;

use App\Models\comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author' => 'sometimes|max:255',
            'content' => 'required|string',
            'post_id' => 'required|integer',
        ]);

        $author = $request->input('author');
        $content = $request->input('content');
        $post_id = $request->input('post_id');

        $data = comments::create([
            'author' => $author,
            'content' => $content,
            'post_id' => $post_id
        ]);

        return response()->json([
            'message' => 'Comment Created',
            'data' => $data,
        ], 200);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function show($comment)
    {
        if (comments::where('id', $comment)->exists()) {
            $data = comments::where('id', $comment)->paginate()->toJson(JSON_PRETTY_PRINT);
            return response($data, 200);
        } else {
            return response()->json([
                "message" => "Oops! this comment is not found or has been deleted."
            ], 404);
        }
    }

    public function show_all()
    {
        $data = comments::paginate();
        return response($data, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $comment = $request->input('comment_id');

        if (comments::where('id', $comment)->exists()) {
            
            $data = comments::findorFail($comment);

            $data->update([
                'author' => $request->input('author') ? $request->input('author') : $data['author'],
                'content' => $request->input('content') ? $request->input('content') : $data['content'],
                'post_id' => $request->input('post_id') ? $request->input('post_id')  : $data['post_id']
            ]);

            return response()->json([
                "message" => "Comment updated successfully",
                "data" => $data
            ], 200);
            } else {
            return response()->json([
                "message" => "Comment not found"
            ], 404);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment)
    {
        
        if(comments::where('id', $comment)->exists()) {
            $data = comments::findorFail($comment);
            $data->delete();

            return response()->json([
              "message" => "Comment deleted"
            ], 202);
          } else {
            return response()->json([
              "message" => "Comment not found"
            ], 404);
        }
    }
}
