@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>
    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{ Form::label('is_active', 'Active') }}
            {{ Form::checkbox('is_active', $post->is_active, $post->is_active) }}
        </div>
        <div class="form-group">
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title']) }}
        </div>

        <div class="form-group">
            {{ Form::label('body', 'Body') }}
            {{ Form::textarea('body', $post->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body']) }}
        </div>
        <div class="form-group">
                {{ Form::file('cover_image') }}
        </div>
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}

    <br><br>
    <div class="col-md-4 col-sm-4">
        <img style="width:100px; height:100px;" src="/storage/cover_images/{{ $post->cover_image }}" alt="">
    </div>
@endsection 


<script src="https://code.jquery.com/jquery-3.4.1.min.js"
integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script>
$(document).ready(function(){
    var post_id = "<?php echo $post->id ?>";
    var post_url = '/posts' + '/' + post_id + '/activation';
$("input:checkbox").change(function() { 
    var isChecked = $("input:checkbox").is(":checked") ? 1:0; 
    $.ajax({
        type:'POST',
        url: post_url,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: $('.checkbox').serialize(),
            success:function(data){
            }
    });
});
});
</script>