<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Icons -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body id="app-layout">
        <div class="container">

            <div class="col-md-12">
                <div class="jumbotron">
                    <div class="text-center ">
                        <a href="" class="text-muted"><u><h3>{{ config('app.name') }}</h3></u></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h3><strong>Hello!</strong></h3>

                    @yield('content') 

                    <p>Regards,</p><br>
                    {{ config('app.name') }}
                    <small> @yield('footer') </small>
                </div>
            </div>

            <div class="col-md-12">
                <div class="jumbotron">
                    <div class="text-center text-muted">
                        <h6>© 2017 {{ config('app.name') }}. All rights reserved. </h6>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>




