<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>
    
    {{ HTML::style('public/assets/admin/css/bootstrap.min.css'); }}
    {{ HTML::style('public/assets/admin/css/plugins/metisMenu/metisMenu.min.css'); }}
    {{ HTML::style('public/assets/admin/css/sb-admin-2.css'); }}
    {{ HTML::style('public/assets/admin/font-awesome-4.1.0/css/font-awesome.min.css'); }}
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @show
</head>
<body>
    @yield('body')
</body>
</html>