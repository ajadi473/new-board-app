<?php

namespace App\Http\Controllers;

use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
class PostsController extends Controller
{

    public function slugTitle($title)
    {
        $link = Str::slug($title,'-');

        return $link;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'bail|required|max:255',
            'creation_date' => 'required|date',
            'author_name' => 'required|string',
        ]);

        $title = $request->input('title');
        $creation_date = $request->input('creation_date');
        $author_name = $request->input('author_name');

        $data = posts::create([
            'title' => $title,
            'creation_date' => $creation_date,
            'author_name' => $author_name,
            'link' => $this->slugTitle($title)
        ]);

        return response()->json([
            'message' => 'Post Created',
            'data' => $data,
        ], 200);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function show($posts)
    {
        if (posts::where('id', $posts)->exists()) {
            $data = posts::where('id', $posts)->with('comment')->paginate()->toJson(JSON_PRETTY_PRINT);
            return response($data, 200);
        } else {
            return response()->json([
                "message" => "Oops! this post is m.i.a."
            ], 404);
        }
    }

    public function show_all()
    {
        // reset all votes
        // $data = posts::where('upvotes', '!=', 0)->update(['upvotes' => 0]);
        // $query->explain();

        $data = posts::with('comment')->paginate();
        return response($data, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $posts)
    {

        if (posts::where('id', $posts)->exists()) {
            $data = posts::findorFail($posts);

            $data->update([
                'title' => $request->input('title') ? $request->input('title') : $data['title'],
                'creation_date' => $request->input('creation_date') ? $request->input('creation_date') : $data['creation_date'],
                'author_name' => $request->input('author_name') ? $request->input('author_name')  : $data['author_name'],
                'link' => $this->slugTitle($request->input('title')) ? $this->slugTitle($request->input('title')) : $data['link']
            ]);

            return response()->json([
                "message" => "Post updated successfully",
                "data" => $data
            ], 200);
            } else {
            return response()->json([
                "message" => "Post not found"
            ], 404);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy($posts)
    {
        if(posts::where('id', $posts)->exists()) {
            $data = posts::findorFail($posts);
            $data->delete();

            return response()->json([
              "message" => "Post deleted"
            ], 202);
          } else {
            return response()->json([
              "message" => "Post not found"
            ], 404);
        }
    }

    public function upvotePost($posts)
    {
        
        if (posts::where('id', $posts)->exists()) {
            $data = posts::where('id', $posts);
            $upvote = $data->increment('upvotes', 1);
            return response()->json([
                "message" => "Post upvoted successfully",
                "data" => $data->get()
            ], 200);
        } else {
            return response()->json([
                "message" => "Oops! this post does not exist."
            ], 404);
        }
    }
}
