@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div>
                    <a class="btn btn-outline-primary mb-2" href="{{route('post.create')}}"> New Post</a>
                </div>
                @foreach($posts as $post)
                    @php
                        $lik = 'Like';
                        if ($post->likes){
                                foreach ($post->likes as $like){
                                    ($like->id == auth('client')->id() ? $lik='Un like' : '');
                                }
                            }
                    @endphp
                    <div class="card mb-2">
                        <div class="card-header">
                            <a href="{{route('client.profile' ,$post->client->id )}}" class="btn"><img
                                    width="30px" height="30px"
                                    src="{{$post->client->avatar ? asset('public/'.$post->client->avatar) : asset(AVATAR)}}"><span>  </span>{{$post->client->name}}
                            </a>


                            <span>{{$post->post_id != null ? 'Share ' .$post->share->client->name . ' post' : ''}}</span>

                            <span class="badge badge-secondary ">{{$post->created_at->diffForHumans()}}</span>
                            {{--                                    //////////////////////-----------------}}
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

                            {{--                                    //---------------------}}
                        </div>
                        @if($post->post_id == null)
                            <div class="card-img">
                                @php
                                    $width ='';
                                    $height ='';
                                    $count = count($post->photos);
                                    if ($count == 1)
                                        $width = $height = '100%';
                                    elseif ($count == 2)
                                        $width = $height = '50%';
                                    elseif ($count >= 3 )
                                        $width = $height ='32%';
                                @endphp
                                @if($count > 0)
                                    @foreach($post->photos as $photo)
                                        <a href="{{route('post.show',$post->id)}}">
                                            @if($photo->type == null)
                                                <img src="{{asset('public/'.$photo->name)}}" width="100%"
                                                     height="100%"><br><br>
                                            @else
                                                <video src="{{asset('public/'.$photo->name)}}" width="100%" controls></video>

                                            @endif
                                        </a>
                                        @break($loop->index == 2)
                                    @endforeach
                                @endif

                            </div>
                        @else


                            {{-----------------start show post share-----------------}}

                            <div class="card-body">
                                @if(isset($post->share))
                                    <div class="card mb-2">


                                        <div class="card-header">
                                            <a href="{{route('client.profile' ,$post->share->client->id )}}"
                                               class="btn"><img
                                                    width="30px" height="30px"
                                                    src="{{$post->share->client->avatar ? asset('public/'.$post->client->avatar) : asset(AVATAR)}}"><span>  </span>{{$post->client->name}}
                                            </a>

                                            <h6 class="badge badge-secondary ">{{$post->share->created_at->diffForHumans()}}</h6>
                                        </div>


                                        <div class="card-img">
                                            @php
                                                $width ='';
                                                $height ='';
                                                $count = count($post->share->photos);
                                                if ($count == 1)
                                                    $width = $height = '100%';
                                                elseif ($count == 2)
                                                    $width = $height = '50%';
                                                elseif ($count >= 3 )
                                                    $width = $height ='32%';
                                            @endphp
                                            @if($count > 0)
                                                @foreach($post->share->photos as $photo)
                                                    <a href="{{route('post.show',$post->share->id)}}">
                                                        @if($photo->type == null)
                                                            <img src="{{asset('public/'.$photo->name)}}" width="100%"
                                                                 height="100%">
                                                        @else
                                                            <video src="{{asset('public/'.$photo->name)}}" width="100%" controls></video>

                                                        @endif
                                                    </a>
                                                    @break($loop->index == 2)
                                                @endforeach
                                            @endif


                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <h1 class="text-center"> <i class="fa fa-sad-tear"></i> post is delete by the owner</h1>
                                    </div>
                                @endif
                    </div>

                    {{--                            end show post share--}}
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
                            <a class="btn btn-primary btn-sm" onclick="like(this)"
                               id="{{$post->id}}">{{$lik}}</a>
                            <a class="btn btn-primary btn-sm" id="{{$post->id}}"
                               href="{{route('post.show',$post->id)}}">comment</a>
                            <a class="btn btn-primary btn-sm" id="{{$post->id}}"
                               href="{{route('post.share',$post->id)}}">share </a>
                        </div>
                    </div>

            </div>
            @endforeach
        </div>
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
                data: {
                    post: post_id
                },
                success: function (data) {
                    console.log(data);
                    if (post.innerHTML.trim() == "Like") post.innerHTML = "Un like";
                    else post.innerHTML = "Like"
                }
            })
        }

        //--------------------end of like post---------------------//

    </script>
@endpush
