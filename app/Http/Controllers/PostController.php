<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Favorite;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // $all_posts = Post::all();
        $limit = $request->query('limit', 10);

        // ضمان أن العدد لا يتجاوز حد معين (مثلاً 100) لمنع الطلبات الكبيرة
        $limit = min($limit, 100);

        // جلب المستخدم الحالي إذا كان هناك توكن
        $user = Auth::guard('sanctum')->user();

        // جلب المنشورات مع معلومات المستخدم
        $posts = Post::with(['user'])->paginate($limit);

        // `getCollection()` extracts the actual Collection from the Paginator, This allows modifying the data before returning it
        $posts->getCollection()->transform(function ($post) use ($user) {
            $post->liked = $user ? $post->favoriteByUser()->where('user_id', $user->id)->exists() : false;

            // Fetch users who liked this post
            $post->liked_users = DB::table('favorites')
            // 1️⃣ Join `favorites` with `users` to get user details
            ->join('users', 'favorites.user_id', '=', 'users.id')
            // 2️⃣ Filter by `post_id` to get only relevant favorites, then count it
            ->where('favorites.post_id', $post->id)->count();
            return $post;
        });

        return response()->json([
            'data'=> $posts->items(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
        ]);
    }


    public function orderPostsByCategory(Request $request)
    {
        $limit = $request->query('limit', 10);
        $limit = min($limit, 100);

        $posts = Post::orderByRaw(
            "FIELD(category, 'test', 'Personal', 'Business', 'Sports', 'News', 'Fitness', 'Travel', 'Food')"
        )->paginate($limit);
        return response()->json([
            'data'=> $posts->all(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
        ]);
    }


    public function userPosts(Request $request)
    {
        $limit = $request->query('limit', 10);
        $limit = min($limit, 100);

        $user_id = $request->user()->id;
        $posts = Post::where('user_id', $user_id)->paginate($limit);

        return response()->json([
            'data'=> $posts->all(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'per_page' => $posts->perPage(),
            'total' => $posts->total(),
        ]);
    }


    public function show(Request $request,$postId)
    {
        /*  // All Ways To Get Post
                // $singlePostFromDB = Post::find($postId); //model object
                // $singlePostFromDB = Post::findOrFail($postId); //model object
                // $singlePostFromDB = Post::where('id', $postId)->first(); //model object
                // $postsFromDB = Post::where('id', $postId)->get(); //collection object

                // $firstPostHasTitle =  Post::where('title', 'php')->first()   //select * from posts where title = 'php' limit 1;
                // $allPostsHasTitle = Post::where('title', 'php')->get()   //select * from posts where title = 'php';
        */

        $post = Post::with(['user'])->find($postId);
        if(is_null($post)) {
            return response()->json([
                'message'=> 'post not found'
            ], 404);
        }
        $isFavorite = null;
        if ($request->user()) {
            $isFav = $request->user()->favoritePosts()->where('post_id', $postId)->exists();
            $isFavorite = $isFav ? "yes" : "no";
        }
        // $usersLikeIt = $post->favoriteByUser()->get();
        $likes = $post->favoriteByUser()->count();
        // PostResource formats and filters the post data before returning it in the API response
        return response()->json([
            'post' => PostResource::make($post),
            'likes' => $likes,
            'is_favorite' => $isFavorite
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'=>['required','string','min:3','max:25'],
            'body'=> 'required|string|min:5',
            'image'=>['required', 'image', 'mimes:png,jpg,jpeg,gif', 'max:2048'],
            'category'=>['required', 'in:Test,Personal,Business,News,Sports,Fitness,Travel,Food'],
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
            'title'=> ['string','min:3','max:25'],
            'body'=> 'string|min:5',
            'category'=>['in:Test,Personal,Business,News,Sports,Fitness,Travel,Food'],
        ]);

        $title  = $request->title ?? $post->title;
        $body  = $request->body ?? $post->body;
        $category  = $request->category ?? $post->category;

        // 5- update the post data in DB
        $post->update([
            'title'=> $title,
            'body'=> $body,
            'category'=> $category,
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
    public function getUserFavorites(Request $request)
    {
        $limit = $request->query('limit', 10);
        $limit = min($limit, 100);

        // $fav_posts = Auth::user()->favoritePosts()->paginate($limit);
        $fav_posts = $request->user()->favoritePosts()->paginate($limit);

        return response()->json([
            'data'=> $fav_posts->all(),
            'current_page' => $fav_posts->currentPage(),
            'last_page' => $fav_posts->lastPage(),
            'per_page' => $fav_posts->perPage(),
            'total' => $fav_posts->total(),
        ]);
    }

    public function addToFavorites(Request $request, $postId)
    {
        $status = $request->user()->favoritePosts()->syncWithoutDetaching($postId);
        if (! $status['attached']) {
            return response()->json([
                'message' => 'The post is already in the favorites',
            ], 400);
        }
        return response()->json([
            'message' => 'Post Has Been Add To Favorites Successfully',
        ], 201);
    }

    public function deleteFromFavorites(Request $request, $postId)
    {
        $status = $request->user()->favoritePosts()->detach($postId);
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
