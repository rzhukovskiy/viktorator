<?php
/** @var $config ConfigEntity*/
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mediastog::settings</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/font-awesome.min.css">

    <link rel="stylesheet" href="/css/style.min.css">

    <link href="../node_modules/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i,900,900i&amp;subset=cyrillic"
          rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="wrapper">

    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/site/index">Home</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="/site/config">Настройки <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/site/admin">Админы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/user/collect">Пользователи</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header> <!-- /.header -->

    <div class="content">
        <div class="container">
            <div class="block">
                <div class="block__body bg-white body_main">
                    <form method="post">
                        <div class="form-group row">
                            <label for="Config[group_id]" class="col-sm-2 col-form-label">ID группы</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="Config[group_id]" id="Config[group_id]" placeholder="какую группу контролируем" value="<?= $config->group_id?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Config[group_key]" class="col-sm-2 col-form-label">Ключ группы</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="Config[group_key]" id="Config[group_key]" placeholder="ключ группы" value="<?= $config->group_key?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Config[app_id]" class="col-sm-2 col-form-label">ID приложения</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="Config[app_id]" id="Config[app_id]" placeholder="айдишник моего приложения" value="<?= $config->app_id?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Config[app_secret]" class="col-sm-2 col-form-label">Ключ приложения</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="Config[app_secret]" id="Config[app_secret]" placeholder="ключ приложения" value="<?= $config->app_secret?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Config[redirect_uri]" class="col-sm-2 col-form-label">Redirect uri</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="Config[redirect_uri]" id="Config[redirect_uri]" placeholder="куда редиректим после авторизации" value="<?= $config->redirect_uri?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.content -->

    <footer class="footer">
        <hr>
        <div class="container">
            <p>© Company 2017</p>
        </div>
    </footer> <!-- /.footer -->

</div> <!-- /.wrapper -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script src="/js/jquery.scrollbar.js"></script>

</body>
</html>
