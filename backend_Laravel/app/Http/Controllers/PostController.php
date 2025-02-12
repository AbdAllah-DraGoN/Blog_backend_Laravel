<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $all_posts = Post::all();
        $length = count($all_posts);

        return response()->json([
            'data'=> $all_posts,
            'count'=> $length
        ]);
    }


    public function orderPostsByCategory()
    {
        // $all_posts = Post::inRandomOrder()->get();
        $all_posts = Post::orderByRaw("FIELD(category, 'test', 'Personal', 'Business', 'Sports', 'News', 'Fitness', 'Travel', 'Food')")->get();
        $length = count($all_posts);

        return response()->json([
            'data'=> $all_posts,
            'count'=> $length
        ]);
    }


    public function userPosts(Request $request)
    {
        $user_id = $request->user()->id;
        $allPostsForUser = Post::where('user_id', $user_id)->get();

        $length = count($allPostsForUser);  // === sizeof($allPostsForUser);

        return response()->json([
            'data'=> $allPostsForUser,
            'count'=> $length
        ]);
    }


    public function show($postId)
    {
        /*  // All Ways To Get Post
                // $singlePostFromDB = Post::find($postId); //model object
                // $singlePostFromDB = Post::findOrFail($postId); //model object
                // $singlePostFromDB = Post::where('id', $postId)->first(); //model object
                // $postsFromDB = Post::where('id', $postId)->get(); //collection object

                // $firstPostHasTitle =  Post::where('title', 'php')->first()   //select * from posts where title = 'php' limit 1;
                // $allPostsHasTitle = Post::where('title', 'php')->get()   //select * from posts where title = 'php';
        */

        $post = Post::find($postId);

        if(is_null($post)) {
            return response()->json([
                'message'=> 'post not found'
            ], 404);
        }

        return response()->json([
            'data'=> $post
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'=>['required','string','min:3','max:25'],
            'body'=> 'required|string|min:5',
            'image'=>['required', 'image', 'mimes:png,jpg,jpeg,gif', 'max:2048'],
            'category'=>['required', 'in:test,Business,News,Personal,Sports,Fitness,Travel,Food'],
            // ....
        ]);
        // $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('postsImages', 'public');
        }

        $post = Post::create([
            'title'=> $request->title,
            'body'=> $request->body,
            "image"=> 'storage/' . $path,
            'category'=> $request->category,
            'user_id'=> $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Post Has Been Created Successfully',
            "data"=> $post
        ], 201);
    }


    public function update(Request $request, $postId)
    {
        // 1- select or find the post
        $post = Post::find($postId);

        // 2- check if post exist
        if(is_null($post)) {
            return response()->json([
                'message'=> 'post not found'
            ], 404);
        }

        // 3- check if user has permitions to delete this post
        if ($request->user()->id !== $post->user_id) {
            return response()->json([
                'message' => 'Not Allowed Update This Post By You',
            ], 403);
        }

        // 4- validate post data
        $request->validate([
            'title'=>['required','string','min:3','max:25'],
            'body'=>['required|string|min:5'],
            'image'=>['required', 'iamge', 'mimes:png,jpg,jpeg,gif', 'max:2048'],
            // 'user_id'=>['required','exists:users,id'],
        ]);

        // 5- update the post data in DB
        $post->update([
            'title'=> $request->title,
            'body'=> $request->body,
            'image'=> $request->image,
            'category'=> $request->category,
        ]);

        return response()->json([
            'message' => 'Post Has Been Updated Successfully',
            "data"=> $post
        ], 201);
    }


    public function destroy(Request $request, $postId)
    {
        // 1- select or find the post
        $post = Post::find($postId);

        // 2- check if post exist
        if(is_null($post)) {
            return response()->json([
                'message'=> 'post not found'
            ], 404);
        }

        // 3- check if user has permitions to delete this post
        if ($request->user()->id !== $post->user_id) {
            return response()->json([
                'message' => 'Not Allowed Delete This Post By You',
            ], 403);
        }

        // 4- delete the post from database
        $post->delete();
        // Post::where('id', $postId)->delete(); // do it in one line

        return response()->json([
            'message' => 'Post Has Been Deleted Successfully',
        ]);
    }


    // Favorite Psots
    public function getUserFavorites()
    {
        $fav_posts = Auth::user()->favoritePosts;
        $length = count($fav_posts);

        return response()->json([
            'data' => $fav_posts,
            'count'=> $length
        ], 200);
    }

    public function addToFavorites($postId)
    {
        $status = Auth::user()->favoritePosts()->syncWithoutDetaching($postId);
        if (! $status['attached']) {
            return response()->json([
                'message' => 'The post is already in the favorites',
            ], 400);
        }
        return response()->json([
            'message' => 'Post Has Been Add To Favorites Successfully',
        ], 201);
    }

    public function deleteFromFavorites($postId)
    {
        $status = Auth::user()->favoritePosts()->detach($postId);
        if (! $status) {
            return response()->json([
                'message' => 'The post is not in favourites',
            ], 404);
        }
        return response()->json([
            'message' => 'Post Has Been Remove From Favorites Successfully',
        ]);
    }

}
