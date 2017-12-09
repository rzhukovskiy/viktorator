<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mediastog</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <link rel="stylesheet" href="/css/font-awesome.min.css">

    <link rel="stylesheet" href="/css/style.min.css">

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
                        <li class="nav-item <?= "$this->controller/$this->action" == 'site/index' ? 'active' : ''?>">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item <?= $this->controller == 'admin' ? 'active' : ''?>">
                            <a class="nav-link" href="/admin/list">Админы</a>
                        </li>
                        <li class="nav-item <?= $this->controller == 'group' ? 'active' : ''?>">
                            <a class="nav-link" href="/group/list">Группы</a>
                        </li>
                        <li class="nav-item <?= "$this->controller/$this->action" == 'site/config' ? 'active' : ''?>">
                            <a class="nav-link" href="/site/config">Настройки</a>
                        </li>
                        <li class="nav-item <?= "$this->controller/$this->action" == 'site/activity' ? 'active' : ''?>">
                            <a class="nav-link" href="/site/activity">Активности</a>
                        </li>
                        <li class="nav-item <?= "$this->controller/$this->action" == 'user/list' ? 'active' : ''?>">
                            <a class="nav-link" href="/user/list">Пользователи</a>
                        </li>
                        <li class="nav-item <?= "$this->controller/$this->action" == 'user/top' ? 'active' : ''?>">
                            <a class="nav-link" href="/user/top">Топчики</a>
                        </li>
                        <li class="nav-item <?= $this->controller == 'error' ? 'active' : ''?>">
                            <a class="nav-link" href="/error/list">Ошибки</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header> <!-- /.header -->

    <div class="content">
        <?= $content ?>
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
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>
