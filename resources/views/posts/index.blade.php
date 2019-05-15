@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card card-body bg-light">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <img style="width:100%;" src="/storage/cover_images/{{ $post->cover_image }}" alt="">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3><a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                        <small>Written on {{ $post->created_at }}</small>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        {{ Form::label('is_active', 'Active') }}
                        {{ Form::checkbox($post->id, $post->is_active, $post->is_active) }}
                    </div>
                </div>
            </div>
        @endforeach
        {{ $posts->links() }}
    @else 
        <p>No posts found.</p>
    @endif
@endsection

<script src="https://code.jquery.com/jquery-3.4.1.min.js"
integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script>
$(document).ready(function(){
    var post_id = "<?php echo $post->id; ?>";
    var post_url = '/posts' + '/' + post_id + '/activation';

    var box = document.getElementsByTagName('input');
    for(var i=0, len=box.length; i<len; i++) {
        if(box[i].type == 'checkbox') {
            console.log(box[i]);
        }
    }


    $("input:checkbox").change(function() { 
        var isChecked = $("input:checkbox").is(":checked") ? 1:0; 
        var post_id = "<?php echo $post->id; ?>";
        console.log(post_id);

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