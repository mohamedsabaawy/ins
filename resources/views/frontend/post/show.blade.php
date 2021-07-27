@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <a href="{{route('client.profile' , $post->client->id)}}" class="btn"><img
                                src="{{$post->client->avatar ?asset('public/'.$post->client->avatar) : asset(AVATAR)}}"
                                width="30px" height="30px" alt="">{{' '.$post->client->name}}</a>


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
                            @if($post->share)
                                <div class="card">
                                    <div class="card-header">

                                        <a href="{{route('client.profile' , $post->share->client->id)}}"
                                           class="btn"><img
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
                                                        <video src="{{asset('public/'.$photo->name)}}" width="100%"
                                                               controls></video>

                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <h1 class="text-center"><i class="fa fa-sad-tear"></i> post is delete by the owner
                                    </h1>
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
                            <a class="btn btn-primary btn-sm" id="{{$post->id}}"
                               href="{{route('post.share',$post->post_id ? ($post->share ? $post->share->id : $post->id) : $post->id)}}">share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-group" method="get" action="{{route('post.comment')}}">
                        <textarea name="comment" class="form-control" id="comment"></textarea>
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        <input type="submit" value="Comment" class="form-control">
                    </form>
                </div>
                {{------------------------  show post's comments-------------------------}}
                @if(count($post->comments) > 0)
                    @foreach($post->comments as $comment)
                        <div class="card {{'comment'.$comment->pivot->id}}" id="comment.{{$comment->pivot->id}}">
                            <div class="card-header">
                                <a class="btn" href="{{route('client.profile' , $comment->id)}}">
                                    <img
                                        src="{{$comment->avatar ? asset('public/' . $comment->avatar) : asset(AVATAR)}}"
                                        width="30px" height="30px">{{' '.$comment->name}}
                                </a>
                                <span
                                    class="badge badge-secondary">{{$comment->pivot->created_at->diffForHumans()}}</span>
                            </div>
                            <div class="card-body">
                                <p id="{{$comment->pivot->id}}">{{$comment->pivot->comment}}</p>
                            </div>
                            <div class="card-footer">
                                <button id="{{$comment->pivot->id}}" class="btn btn-primary"
                                        onclick="commentEdit(this.id)"><i class="fa fa-edit"></i></button>
                                <button id="{{$comment->pivot->id}}" class="btn btn-danger"
                                        onclick="commentDelete({{$comment->pivot->id}})"><i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif

                    {{------------------------  end show post's comments-------------------------}}

            </div>
        </div>
    </div>



    {{----------------------modal comment edit------------------------}}
    <div class="modal fade" id="commentEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="commentTitle">Edit</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="result">
                    <form action="" method="post" id="formEdit">
                        @csrf
                        <input type="text" name="comment" class="form-control" id="commentName">
                        <input type="hidden" name="id" class="form-control" id="commentId">
                        <input type="hidden" name="client_id" class="form-control" id="clientId">
                        <input type="hidden" name="post_id" class="form-control" id="postId">
                        <input type="submit" class="form-control btn btn-primary" value="edit" id="commentUpdate">
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
@push('scripts')

    <!-- Toastr -->
{{--    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>--}}
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    <script>


        //----------like post ----------------//
        function like(post) {


            var post_id = post.id
            $.ajax({
                url: '{{route('post.like')}}',
                type: 'get',
                data: {post: post_id},
                success: function (data) {

                    if (post.innerHTML.trim() == "Like") post.innerHTML = "Un like";
                    else post.innerHTML = "Like"
                }
            })
        }

        //--------------------end of like post---------------------//
        //----------edit comment post ----------------//
        function commentEdit(comment_id) {
            var comment = comment_id;
            $.ajax({
                url: "{{route('comment.edit')}}",
                type: 'get',
                data: {post: comment},
                success: function (data) {
                    $('#commentName').val(data.comment);
                    $('#commentId').val(data.id);
                    $('#clientId').val(data.client_id);
                    $('#postId').val(data.post_id);
                    $('#commentEdit').modal('show');
                }
            })

            // if (post.innerHTML=="Like") post.innerHTML="Un like";
            // else post.innerHTML = "Like"
        }

        $(document).on('click', '#commentUpdate', function (e) {
            e.preventDefault();
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000})
            var form = new FormData($('#formEdit')[0]);
            $.ajax({
                url: '{{route('comment.update')}}',
                type: 'post',
                data: form,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {

                    Toast.fire({
                        icon: data.message ? 'error' : 'success',
                        title: data.message ? data.message : 'your comment edited successful'
                    })

                    // alert($('#'+data.id).html());
                    $('#' + data.id).html(data.comment);
                    $('#commentEdit');
                }
            })
        });

        //--------------------end of like post---------------------//

        //----------delete comment  ----------------//
        function commentDelete(comment_id) {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000})

            var comment = comment_id;
            $.ajax({
                url: "{{route('comment.delete')}}",
                type: 'get',
                data: {
                    post: comment,
                    token : '{{csrf_token()}}'
                },
                success: function (data) {

                    Toast.fire({
                        icon: data.message ? 'success' : 'error',
                        title: data.message ? 'your comment deleted successful' : data
                    })
                    $('.comment' + comment).remove();
                }
            })

            // if (post.innerHTML=="Like") post.innerHTML="Un like";
            // else post.innerHTML = "Like"
        }

    </script>
@endpush
