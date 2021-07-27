<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $follows = DB::table('follows')->where('follow_id' , Auth::id())->pluck('follower_id');
        $posts = Post::with(['likes','comments','client','photos' ,'share','master'])->whereIn('client_id',$follows)->latest()->paginate(PAGINATE);
        return view('frontend.welcome' , compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|array',
            'name.*' => 'mimes:mp4,jpeg,bmp,png,jpg',
        ]);
//        return $request->file('name')->extension();
        $post = Post::create([
//            'name'      => $request->name->store('images','public'),
            'client_id' => Auth::id()
        ]);
        $posts ='';
        foreach($request->name as $name){
//           $posts .= $name->getClientOriginalExtension();
            $post->photos()->create([
               'name'=>$name->store('images' ,'public'),
               'client_id' =>Auth::id(),
                'type' => $name->getClientOriginalExtension() == 'mp4' ? 1 : null
           ]);
        }

        return redirect(route('welcome'))->with('state' , 'photo uploaded');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with(['likes','comments','client','photos' ,'share','master'])->find($id);
        if($post <> null)
            return view('frontend.post.show' ,compact('post'));
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::with(['likes','comments','client','photos' ,'share.photos','master'])->find($id);

        if (count($post->photos) > 0){
            foreach ($post->photos as $photo){
                Storage::disk('public')->delete($photo->name);
                $photo->delete();
            }
        }

//        foreach ($post->likes as $like)
//            $like->delete();
//        foreach ($post->comments as $comment)
//            $comment->delete();
        $post->delete();

        return redirect()->route('welcome');
    }

    //------------------like post----------------//

    public function like(Request $request)
    {
//        return $request->post ;
        $like = $request->user()->likes()->toggle($request->post);
        return $like;
    }

    //------------------comment post----------------//

    public function comment(Request $request)
    {
        if (!$request->comment == null){

            Auth::guard('client')->user()->comments()->attach($request->post_id,['comment' => $request->comment]);
            return redirect()->back();
        }
        return redirect()->back()->with('state' , 'please type comment');

    }

    public function commentEdit(Request $request)
    {
        $comment = DB::table('comments')->find($request->post);
        return response()->json($comment);
    }


    public function commentUpdate(Request $request)
    {
        $request->validate([
            'comment' => 'required'
        ]);
        $comment = DB::table('comments')->where('id',$request->id)->update(['comment' =>$request->comment]);
        if ($comment)
            return response()->json(DB::table('comments')->find($request->id));
        return response()->json(['message' => 'please edit your comment']);
    }


    public function commentDelete(Request $request)
    {
        $comment = DB::table('comments')->where('id',$request->post)->delete();
        if ($comment)
            return response()->json(['message' => 'deleted']);
        return response()->json('some thing wont wrong');
    }

    //------------------share post----------------//

    public function share($id)
    {
        Post::find($id)->master()->create([
           'client_id' => Auth::id(),
        ]);

        return redirect()->back()->with('status' ,'post share');
    }
}
