@extends('main')

@section('title')
Kayıt Sayfası
@endsection

@section('content')

<div class="row">

  <div class="col-lg-12 col-md-12">



                            <!-- POST -->
                            <div class="post">
                                <form action="{{url('/register')}}" class="form newtopic" method="post">
                                    <div class="postinfotop">
                                        <h2>Kayıt Ol</h2>
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
                                                        <input type="text" name="name" @if(isset($inputs)) value="{{$inputs['name']}}" @endif placeholder="Ad" class="form-control">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="text" name="surname" @if(isset($inputs)) value="{{$inputs['surname']}}" @endif placeholder="Soyad" class="form-control">
                                                    </div>
                                                </div>
                                                <div>
                                                    <input type="text" name="email" @if(isset($inputs)) value="{{$inputs['email']}}" @endif placeholder="E-mail" class="form-control">
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="password" name="password" placeholder="Şifre" class="form-control" id="pass" name="pass">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <input type="password" name="password-again" placeholder="Şifre Tekrar" class="form-control" id="pass2" name="pass2">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div><!-- acc section END -->


                                    <div class="postinfobot">

                                        <div class="pull-right postreply">
                                            {{ csrf_field() }}
                                            <div class="pull-left"><button type="submit" class="btn btn-primary">Kayıt Ol</button></div>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div><!-- POST -->






                        </div>

</div>

@endsection
