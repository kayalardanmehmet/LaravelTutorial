@extends('main')

@section('title')
Yeni Soru Sor
@endsection

@section('content')

<div class="row">

  <div class="col-lg-12 col-md-12">

    <div class="post">
        <form action="{{url('/posts/new')}}" class="form newtopic" method="post">
            <div class="postinfotop">
                <h2>Soru Sor</h2>
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
                                <input type="text" name="title" @if(isset($inputs)) value="{{$inputs['title']}}" @endif placeholder="Başlık" class="form-control">
                            </div>
                        </div>
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
                    <div class="pull-left"><button type="submit" class="btn btn-primary">Soru Oluştur</button></div>
                    <div class="clearfix"></div>
                </div>


                <div class="clearfix"></div>
            </div>
        </form>
    </div>

  </div>

</div>

@endsection
