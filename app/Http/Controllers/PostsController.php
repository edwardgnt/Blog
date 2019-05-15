<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    //  $posts = Post::all();
    //  $posts = Post::where('title', 'Post Two')->get();
    //  $posts = DB::select('SELECT * FROM posts');


    //  $posts = Post::orderBy('created_at', 'desc')->get();
    //  $posts = Post::orderBy('created_at', 'desc')->take(1)->get();

        $posts = Post::orderBy('created_at', 'desc')->paginate(3);

        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle image upload
        if($request->hasFile('cover_image')) {
            // Get filename with extenstion
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();

            // Filename to store
            $fileNameToStore = $filename. '_' .time(). '.'.$extension;  

            // Upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        } else {
            $fileNameToStore = 'noimage.jpg';
        }



        // Create Post
        $post = new Post(); 
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

                // Handle image upload
                if($request->hasFile('cover_image')) {
                    // Get filename with extenstion
                    $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
        
                    // Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        
                    // Get just ext
                    $extension = $request->file('cover_image')->getClientOriginalExtension();
        
                    // Filename to store
                    $fileNameToStore = $filename. '_' .time(). '.'.$extension;  
        
                    // Upload image
                    $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        
                }



        // Updating a post
        $post = Post::find($id);
        $post->title = $request->title;
        $post->body = $request->body;

        // if(!isset($request->is_active)) {
        //     $post->is_active = 0;
        // } else {
        //     $post->is_active = 1;
        // }

        $post->is_active = (!isset($request->is_active) ? 0 : 1);

        
        

        if($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }

        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if(auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        if($post->cover_image != 'noimage.jpg') {
            // Delete image
            Storage::delete('public/cover_images/' .$post->cover_image);
        }

        $post->delete();

        return redirect('/posts')->with('success', 'Post Removed');
    }

    public function activation(Request $request, $id)
    {
        $post = Post::find($id);
        echo "inside controller";

        if($post->is_active == 1){
            $post->is_active = 0;
        } else {
            $post->is_active = 1;
        }

        return response()->json([
        'data' => [
            'success' => $post->save(),
        ]
        ]);
    }
}

