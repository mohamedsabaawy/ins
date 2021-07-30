@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(auth('client') ->id() == $client->id)
                    <div>
                        <a class="btn btn-outline-primary mb-2" href="{{route('post.create')}}"> New Post</a>
                    </div>
                @endif
                @php
                    $follow = 'Follow';
                    if ($client->followers){
                        foreach ($client->followers as $follower){
                            if ($follower->id == auth('client')->id() ) {
                                $follow = 'Un follow';
                            }
                        }
                    }
                @endphp
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('client.profile' ,$client->id)}}" class="btn"><img width="60px" height="60px"
                                                                                            src="{{$client->avatar ? asset('public/'.$client->avatar) : asset(AVATAR)}}" class="rounded-circle"><span
                                style="font-size: 25px">{{'  '.$client->name}}</span></a>
                        @if(auth()->id() != $client->id)
                            <a class="btn btn-secondary float-right" onclick="follow(this)" id="{{$client->id}}">
                                {{$follow}}
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="badge badge-pill " style="width: 22% ;font-size: 15px ; float: left">
                            follows {{$client->follows()->count()-1}}</div>
                        <div class="badge badge-pill " style="width: 22%;font-size: 15px ; float: left">
                            followers {{$client->followers()->count()-1}}</div>
                        <div class="badge badge-pill " style="width: 22%;font-size: 15px ; float: left">
                            posts {{$client->posts()->count()}}</div>
                    </div>

                </div>
                <br>
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
                                    width="60px" height="60px"  class="rounded-circle"
                                    src="{{$post->client->avatar ? asset('public/'.$post->client->avatar) : asset(AVATAR)}}"><span>  </span>{{$post->client->name}}
                            </a>


                            {{-------------------      show name of share post owner      -------------------}}
                            <span
                                class="badge badge-warning">{{$post->post_id != null ? 'Share ' . (isset($post->share->client) ? $post->share->client->name.'\'s' : 'this'). ' post' : ''}}</span>
                            {{-------------------      end show name of share post owner      -------------------}}

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
                            @php
                                $width = 0;
                                if (count($post->photos) == 1)
                                    $width = '100%';
                                elseif(count($post->photos) == 2)
                                    $width = '50%';
                                elseif (count($post->photos) >= 3)
                                    $width = '33%';
                            @endphp
                            <div class="card-img row-cols-3">
                                @if(count($post->photos) > 0 )
                                    @foreach($post->photos as $photo)
                                        <a href="{{route('post.show',$post->id)}}">
                                            @if($photo->type == null)
                                                <img src="{{asset('public/'.$photo->name)}}" width="100%"
                                                     height="100%" ><br/><br/>
                                            @else
                                                <video src="{{asset('public/'.$photo->name)}}" width="100%"
                                                       controls></video>

                                            @endif
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="card-body">
                                @if(isset($post->share->client))
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="{{route('client.profile' ,$post->share->client->id )}}"
                                               class="btn"><img
                                                    width="60px" height="60px"  class="rounded-circle"
                                                    src="{{$post->share->client->avatar ? asset('public/'.$post->share->client->avatar) : asset(AVATAR)}}"><span>  </span>{{$post->share->client->name}}
                                            </a>
                                            <h6 class="badge badge-secondary float-right">{{$post->created_at->diffForHumans()}}</h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($post->share->photos as $photo)
                                                <a href="{{route('post.show',$post->id)}}">
                                                    @if($photo->type == null)
                                                        <img src="{{asset('public/'.$photo->name)}}" width="100%"
                                                             height="100%">
                                                    @else
                                                        <video src="{{asset('public/'.$photo->name)}}" width="100%"
                                                               controls></video>

                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                @else

                                    <div>
                                        <h1 class="text-center"><i class="fa fa-sad-tear"></i> post is delete by the
                                            owner</h1>
                                    </div>
                                @endif
                            </div>
                        @endif
                        {{-------------------------- show number of likes and comments and shares --------------------}}
                        @if(count($post->likes) > 0 || count($post->comments) > 0 || count($post->master) > 0)
                            <div class="badge badge-warning">
                                <span> {{$post->likes()->count() > 0 ?$post->likes()->count() .' likes |' : ''}}</span>
                                <span> {{$post->comments()->count() > 0 ?$post->comments()->count() .' comments |' : ''}}</span>
                                <span> {{$post->master()->count() > 0 ?$post->master()->count() .' shares |' : ''}}</span>
                            </div>
                        @endif

                        {{-------------------------- end show number of likes and comments and shares --------------------}}


                        <div class="card-footer">


                            <div class="justify-content-sm-center btn-group">
                                <a class="btn btn-primary btn-sm" id="{{$post->id}}" onclick="like(this)">{{$lik}}</a>
                                <a class="btn btn-primary btn-sm" id="{{$post->id}}"
                                   href="{{route('post.show',$post->id)}}">comment</a>
                                <a class="btn btn-primary btn-sm" id="{{$post->id}}"
                                   href="{{route('post.share',$post->post_id ? ($post->share ? $post->share->id : $post->id) : $post->id)}}">share</a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
