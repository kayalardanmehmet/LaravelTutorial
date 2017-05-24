@extends('main')

@section('title')
Giriş Sayfası
@endsection

@section('content')

<div class="row">

  <div class="col-lg-12 col-md-12">



                            <!-- POST -->
                            <div class="post">
                                <form action="{{url('/login')}}" class="form newtopic" method="post">
                                    <div class="postinfotop">
                                        <h2>Giriş Yap</h2>
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
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="text" name="email" @if(isset($inputs)) value="{{$inputs['email']}}" @endif placeholder="E-mail" class="form-control">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="password" name="password" placeholder="Şifre" class="form-control">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div><!-- acc section END -->


                                    <div class="postinfobot">

                                        <div class="pull-right postreply">
                                            {{ csrf_field() }}
                                            <div class="pull-left"><a href="{{url('/register')}}" class="btn btn-primary" style="margin-right: 15px;">Kayıt Ol</a><button type="submit" class="btn btn-primary">Giriş Yap</button></div>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div><!-- POST -->






                        </div>

</div>

@endsection
