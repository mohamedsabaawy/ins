<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            @auth()
                <a href="{{route('client.profile',auth()->id())}}" type="button" class="btn btn-default">
                    Profile
                </a>

                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                    Search
                </button>

            @endauth
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.login.form') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.register.form') }}">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img width="30px" height="30px" class=""
                                     src="{{Auth::user()->avatar ? asset('public/'.auth()->user()->avatar) : asset('ph.png') }}"> {{ ' '.Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('client.edit.form',auth()->id()) }}">
                                    {{ __('Edit') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('client.logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('client.logout') }}" method="POST"
                                      class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="py-4">
        @if (session('state'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('state') }}
            </div>
        @endif
        {{----------------------modal search------------------------}}
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Search</h4>
                        <input type="text" name="search" class="form-control" id="search">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="result">

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                        {{--                                <button type="button" class="btn btn-primary">Save changes</button>--}}
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        @yield('content')
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>--}}


{{--<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>--}}
<script src="{{asset('plugins/jquery/jquery.form.js')}}"></script>
@stack('scripts')
<script>
    $('#search').keyup(function () {
        var search = $(this).val();
        if (search != '') {
            $.ajax({
                    url: '{{route('client.search')}}',
                    type: 'get',
                    data: {search: search},
                    success: function (data) {
                        $('#result').html(data.result)
                    }
                }
            )
        } else {
            $('#result').empty();
        }
    })

    function follow(follow) {
        // alert(follow.id)
        var follow_id = follow.id
        $.ajax({
            url: '{{route('client.follow')}}',
            type: 'get',
            data: {follow: follow_id},
            success: function (data) {
                console.log(data);
                if (follow.innerHTML.trim() == 'Follow') follow.innerHTML = 'Un follow'
                else follow.innerHTML = 'Follow'
            }
        })
    }



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
</script>
</body>
</html>
