@extends('main')

@section('title')
Ana Sayfa
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- POST -->
        @foreach($posts as $post)
        <div class="post">
            <div class="wrap-ut pull-left">
                <div class="userinfo pull-left">
                    <div class="vote_area">
                      <div class="vote_row"><i class="fa fa-chevron-up vote_up vote" data-id="{{$post->id}}" data-type="post" data-vote="up"></i></div>
                      <div class="vote_row"><i class="fa fa-chevron-down vote_down vote" data-id="{{$post->id}}" data-type="post" data-vote="down"></i></div>
                    </div>
                    <div class="vote_count">
                        {{$post->vote}}
                    </div>
                </div>
                <div class="posttext pull-left">
                    <h2><a href="{{url('/posts/' . $post->id)}}">{{$post->title}}</a></h2>
                    <p>@if(strlen($post->content) > 200) {{substr($post->content, 0, 200) . '...'}} @else {{$post->content}} @endif </p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="postinfo pull-left">
                <div class="comments">
                    <div class="commentbg">
                        {{count($post->answers)}}
                        <div class="mark"></div>
                    </div>

                </div>
                <div class="views"><i class="fa fa-eye"></i> {{$post->view}}</div>
                <div class="time"><i class="fa fa-clock-o"></i> {{$post->created_at}}</div>
            </div>
            <div class="clearfix"></div>
        </div><!-- POST -->
        @endforeach

    </div>
</div>

<script>
  //Vote işlemlerini sayfa değişmeden dinamik bir şekilde yapabilmek için hazırlamış olduğumuz urle get isteği gönderiyoruz
  $(function(){

      $(".vote").click(function(){

          var $this = $(this);
          var $id = $this.data('id');
          var $type = $this.data('type');
          var $vote = $this.data('vote');
          var $count = $this.parent().parent().parent().find('.vote_count');

          $.get('{{url('/vote')}}/' + $id + '/' + $type + '/' + $vote, function(data){
              if(data.status == false){
                alert(data.error);
              }else{
                if($vote == 'up')
                  $count.html($count.html()*1+1);
                else
                  $count.html($count.html()*1-1)
              }
          }, 'json');

      });

  });

</script>

@endsection
