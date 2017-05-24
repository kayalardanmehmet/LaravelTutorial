<!DOCTYPE html>
<html lang="en" style="height: 100%;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        <link href="{{asset('assets/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('custom.css')}}" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
          <![endif]-->

        <!-- fonts -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">



        <script   src="https://code.jquery.com/jquery-3.2.1.min.js"   integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="   crossorigin="anonymous"></script>
        <script src="{{asset('assets/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    </head>
    <body>

        <div class="container-fluid">

            <div class="headernav">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-1 col-xs-3 col-sm-2 col-md-2" style="font-size: 29px; font-family: 'Open Sans Light', sans-serif; margin-top: 16px; height: 38px"><a href="{{url('/')}}">Q&A</a></div>
                        @if(session()->has('email'))
                        <div class="col-lg-4 search hidden-xs hidden-sm col-md-3">
                            <div class="wrap">
                                <form action="#" method="post" class="form">
                                    <div class="pull-left txt"><input type="text" class="form-control" placeholder="Sorularda Ara"></div>
                                    <div class="pull-right"><button class="btn btn-default" type="button"><i class="fa fa-search"></i></button></div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-12 col-sm-5 col-md-4 avt">
                            <div class="stnt pull-left">
                                <form action="03_new_topic.html" method="post" class="form">
                                    <a class="btn btn-primary" href="{{url('/posts/new')}}">Yeni Soru Sor</a>
                                </form>
                            </div>

                            <div class="avatar env pull-left dropdown">
                                <a data-toggle="dropdown" href="#"><i class="fa fa-user"></i> <b class="caret"></b>
                                <div class="status green">&nbsp;</div>
                                <ul class="dropdown-menu" role="menu">
                                    <li role="presentation"><a role="menuitem" style="margin-left: 15px;" tabindex="-3" href="{{url('/logout')}}">Çıkış Yap</a></li>
                                </ul>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>


            <section class="content">

                <div class="container" style="margin-top: 20px;">
                    @yield('content')
                </div>

            </section>

            <footer>
                <div class="container">
                    <div class="row">
                    </div>
                </div>
            </footer>
        </div>

        <!-- END REVOLUTION SLIDER -->
    </body>
</html>
