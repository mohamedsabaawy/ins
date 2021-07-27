@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('New Post') }}</div>

                    <div class="card-body">
                        <form id="photoUpload" class="form-group" method="post" action="{{route('post.store')}}"
                              enctype="multipart/form-data">
                            @csrf
                            <input class="form-control @error('name')is-invalid @enderror @error('name.*')is-invalid @enderror " type="file" name="name[]"
                                   multiple>
                            @error('name')
                            <span class="invalid-feedback">
                                <strong >
                                    {{$message}}
                                </strong>
                            </span>
                            @enderror
                            @error('name.*')
                            <span class="invalid-feedback">
                                <strong >
                                    {{$message}}
                                </strong>
                            </span>
                            @enderror
                            <input id="Photo" class="form-control btn btn-outline-info" type="submit" value="upload">
                        </form>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        0%
                    </div>
                </div><br>
                <div id="success">

                </div><br>
            </div>


        </div>
    </div>

    @push('scripts')
    <script>

    </script>
    @endpush
@endsection
