@extends('main')

@section('title')
Soru ve Cevapları
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="post">
            <div class="wrap-ut pull-left">
                <div class="userinfo pull-left">
                    <div class="vote_area">
                      <div class="vote_row"><i class="fa fa-chevron-up vote_up vote" data-id = "{{$post->id}}" data-type="post" data-vote="up"></i></div>
                      <div class="vote_row"><i class="fa fa-chevron-down vote_down vote" data-id = "{{$post->id}}" data-type="post" data-vote="down"></i></div>
                    </div>
                    <div class="vote_count">
                        {{$post->vote}}
                    </div>
                </div>
                <div class="posttext pull-left">
                    <h2><a href="02_topic.html">{{$post->title}}</a></h2>
                    <p>{{$post->content}}</p>
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
        </div>
        <!-- Cevaplar -->

        @foreach($post->answers as $answer)
        <div class="post">
            <div class="wrap-ut pull-left">
                <div class="userinfo pull-left">
                    <div class="vote_area">
                      <div class="vote_row"><i class="fa fa-chevron-up vote_up vote" data-id = "{{$answer->id}}" data-type="answer" data-vote="up"></i></div>
                      <div class="vote_row"><i class="fa fa-chevron-down vote_down vote" data-id = "{{$answer->id}}" data-type="answer" data-vote="down"></i></div>
                    </div>
                    <div class="vote_count">
                        {{$answer->vote}}
                    </div>
                </div>
                <div class="posttext pull-left">
                    <p>{{$answer->content}}</p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="postinfo pull-left">
                <div class="time"><i class="fa fa-clock-o"></i> {{$answer->created_at}}</div>
            </div>
            <div class="clearfix"></div>
        </div><!-- POST -->
        @endforeach

        <div class="post">
            <form action="{{url('/answers/new/' . $post->id)}}" class="form newtopic" method="post">
                <div class="postinfotop">
                    <h2>Cevap Yaz</h2>
                </div>

                <!-- acc section -->
                <div class="accsection">
                    <div class="acccap">
                        <div class="userinfo pull-left">&nbsp;</div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="topwrap">
                        <div class="userinfo pull-left">
                        </div>
                        <div class="posttext pull-left">
                            @if(isset($error))
                            <div class="row"><div class="col-md-12"><div class="alert alert-danger"><strong>Hata!</strong> {{$error}}</div></div></div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <textarea style="width: 100%;" name="content" placeholder="İçerik">@if(isset($inputs)){{$inputs['content']}}@endif</textarea>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div><!-- acc section END -->


                <div class="postinfobot">

                    <div class="pull-right postreply">
                        {{ csrf_field() }}
                        <div class="pull-left"><button type="submit" class="btn btn-primary">Cevap Ver</button></div>
                        <div class="clearfix"></div>
                    </div>


                    <div class="clearfix"></div>
                </div>
            </form>
        </div>

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
