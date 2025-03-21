<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function index($postId , Request $request)
    {
        $post = Post::find($postId);

        if(is_null($post)) {
            return response()->json([
                'message'=> 'post not found'
            ], 404);
        }


        $limit = $request->query('limit', 10);
        $limit = min($limit, 100);

        $comments = Comment::where('post_id', $postId)->with('user')->latest()->paginate($limit);

        return response()->json([
            'data'=> $comments->items(),
            'current_page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
            'per_page' => $comments->perPage(),
            'total' => $comments->total(),
        ]);
    }


    public function store(Request $request,$postId)
    {
        $request->validate([
            'body' => 'required|string|max:200',
        ]);

        // 1- select or find the post
        $post = Post::find($postId);

        // 2- check if post exist
        if(is_null($post)) {
            return response()->json([
                'message'=> 'post not found'
            ], 404);
        }

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' =>$request->user()->id,
            'body' => $request->body,
        ]);

        return response()->json([
            'comment'=> $comment,
            'message' => 'Comment Has Been Created Successfully',
        ],201);
    }


        public function update(Request $request, $commentId)
    {
        // 1- select or find the comment
        $comment = Comment::find($commentId);

        // 2- check if comment exist
        if(is_null($comment)) {
            return response()->json([
                'message'=> 'comment not found'
            ], 404);
        }

        // 3- check if user has permitions to delete this comment
        if ($request->user()->id !== $comment->user_id) {
            return response()->json([
                'message' => 'Not Allowed Update This Comment By You',
            ], 403);
        }

        // 4- validate comment data
        $request->validate([
            'content' => 'required|string|min:10|max:200',
        ]);


        // 5- update the comment data in DB
        $comment->update([
            'body'=> $request->content,
        ]);

        return response()->json([
            'message' => 'Comment Has Been Updated Successfully',
            "data"=> $comment
        ], 201);
    }


    public function destroy(Request $request, $commentId)
    {
        $comment = Comment::find($commentId);

        // 2- check if comment exist
        if(is_null($comment)) {
            return response()->json([
                'message'=> 'comment not found'
            ], 404);
        }

        // 3- check if user has permitions to delete this comment
        if ($request->user()->id !== $comment->user_id) {
            return response()->json([
                'message' => 'Not Allowed Update This Comment By You',
            ], 403);
        }

        // 4- delete the comment from database
        $comment->delete();

        return response()->json([
            'message' => 'Comment Has Been Deleted Successfully',
        ]);
    }
}
