@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('New Post') }}</div>

                    <div class="card-body">
                        <form class="form-group" method="post" action="{{route('post.store')}}" enctype="multipart/form-data">
                            @csrf
                            <input class="form-control" type="file" name="name[]" multiple>
                            @error('name')
                                <span>{{$message}}</span>
                            @enderror
                            <input class="form-control btn btn-outline-info" type="submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
