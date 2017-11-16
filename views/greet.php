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

    <div class="content">
        <div class="container">
            <div class="block">
                <div class="block__body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Добро пожаловать!</h2>
                            <p>Привет, <?= $username ?>! Но тебя нет в админах, сорян. Жди апрува.</p>

                        </div>
                    </div>
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
