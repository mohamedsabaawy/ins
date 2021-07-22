@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <a href="{{route('client.profile' , $post->client->id)}}" class="btn"><img
                                src="{{$post->client->avatar ?asset('public/'.$post->client->avatar) : asset(AVATAR)}}"
                                width="30px" height="30px">{{' '.$post->client->name}}</a>
                        <span>{{$post->post_id != null ? 'Share ' .$post->share->client->name . ' post' : ''}}</span>

                        <h6 class="badge badge-secondary ">{{$post->created_at->diffForHumans()}}</h6>
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-secondary btn-sm " data-toggle="dropdown"
                                    data-offset="-52" aria-expanded="false">
                                <i class="fas fa-bars"></i></button>
                            <div class="dropdown-menu" role="menu" style="">
                                @if($post->client_id == auth()->id())
                                    <form action="{{route('post.destroy',$post->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="dropdown-item btn btn-danger">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>

                    @if($post->post_id == null)

                        <div class="card-img">
                            @if(count($post->photos) > 0 )
                                @foreach($post->photos as $photo)
                                    @if($photo->type == null)
                                        <img src="{{asset('public/'.$photo->name)}}" width="100%"
                                             height="100%"><br><br>
                                    @else
                                        <video src="{{asset('public/'.$photo->name)}}" width="100%" controls></video>

                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @else
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">

                                    <a href="{{route('client.profile' , $post->share->client->id)}}" class="btn"><img
                                            src="{{$post->client->avatar ?asset('public/'.$post->share->client->avatar) : asset(AVATAR)}}"
                                            width="30px" height="30px">{{' '.$post->share->client->name}}</a>
                                    <h6 class="badge badge-secondary ">{{$post->share->created_at->diffForHumans()}}</h6>

                                </div>
                                <div class="card-body">
                                    <div class="card-img">
                                        @if(count($post->share->photos) > 0 )
                                            @foreach($post->share->photos as $photo)
                                                @if($photo->type == null)
                                                    <img src="{{asset('public/'.$photo->name)}}" width="100%"
                                                         height="100%">
                                                @else
                                                    <video src="{{asset('public/'.$photo->name)}}" width="100%" controls></video>

                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif

                    {{-------------------------- show number of likes and comments and shares --------------------}}
                    @if(count($post->likes) > 0 || count($post->comments) > 0 || count($post->master) > 0)
                        <div class="badge badge-warning">
                            <span > {{$post->likes()->count() > 0 ?$post->likes()->count() .' likes |' : ''}}</span>
                            <span> {{$post->comments()->count() > 0 ?$post->comments()->count() .' comments |' : ''}}</span>
                            <span> {{$post->master()->count() > 0 ?$post->master()->count() .' shares |' : ''}}</span>
                        </div>
                    @endif

                    {{-------------------------- end show number of likes and comments and shares --------------------}}

                    <div class="card-footer">
                        <div class="justify-content-sm-center btn-group">
                            <a class="btn btn-primary btn-sm" id="{{$post->id}}" onclick="like(this)">
                                @php
                                    $lik = 'Like';
                                    if ($post->likes){
                                        foreach ($post->likes as $like){
                                            ($like->id == auth()->id() ? $lik='Un like' :'');
                                        }
                                    }
                                @endphp
                                {{$lik}}
                            </a>
                            <a class="btn btn-primary btn-sm" id="{{$post->id}}" href="#comment">comment </a>
                            <a class="btn btn-primary btn-sm" id="{{$post->id}}" href="{{route('post.share',$post->id)}}">share</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <form class="form-group" method="get" action="{{route('post.comment')}}">
                        <textarea name="comment" class="form-control" id="comment"></textarea>
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        <input type="submit" value="Comment" class="form-control">
                    </form>
                </div>
                @foreach($post->comments as $comment)
                    <div class="card">
                        <div class="card-header">
                            <a class="btn" href="{{route('client.profile' , $comment->id)}}">
                            <img src="{{$comment->avatar ? asset('public/' . $comment->avatar) : asset(AVATAR)}}"
                                 width="30px" height="30px">{{' '.$comment->name}}
                            </a>
                            <span class="badge badge-secondary">{{$comment->pivot->created_at->diffForHumans()}}</span>
                        </div>
                        <div class="card-body">
                            <p>{{$comment->pivot->comment}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        //----------like post ----------------//
        function like(post) {
            var post_id = post.id
            $.ajax({
                url: '{{route('post.like')}}',
                type: 'get',
                data: {post: post_id},
                success: function (data) {
                    console.log(data);
                    if (post.innerHTML.trim() == "Like") post.innerHTML = "Un like";
                    else post.innerHTML = "Like"
                }
            })
        }

        //--------------------end of like post---------------------//
        //----------comment post ----------------//
        function comment(post) {
            var post = post
            $.ajax({
                url: '{{route('post.comment')}}',
                type: 'get',
                data: {post: post},
                success: function (data) {
                    console.log(data);
                }
            })

            // if (post.innerHTML=="Like") post.innerHTML="Un like";
            // else post.innerHTML = "Like"
        }

        //--------------------end of like post---------------------//

    </script>
@endpush
