<?php
/** @var $listAction ActionEntity[] */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

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
                        <li class="nav-item">
                            <a class="nav-link" href="/site/index">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/site/config">Настройки</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/site/admin">Админы</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="/user/list">Пользователи <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header> <!-- /.header -->
    <div class="content">
        <div class="container">
            <div class="block">
                <div class="block__body">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Scores</th>
                            <th scope="col">Content</th>
                            <th scope="col">Is active</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($listAction as $action) {
                            switch ($action->activity) {
                                case 'like':
                                case 'ten_like':
                                case 'first_like':
                                    $itemUrl = 'https://vk.com/wall-' . Globals::$config->group_id . '_' . $action->parent_social_id .
                                        '?w=likes%2Fwall-' . Globals::$config->group_id . '_' . $action->parent_social_id;
                                    break;
                                case 'comment':
                                case 'first_comment':
                                    $itemUrl = 'https://vk.com/wall-' . Globals::$config->group_id . '_' . $action->parent_social_id . '?' . $action->social_id;
                                    break;
                                case 'repost':
                                    $itemUrl = 'https://vk.com/wall-' . Globals::$config->group_id . '_' . $action->parent_social_id .
                                        '?w=shares%2Fwall-' . Globals::$config->group_id . '_' . $action->parent_social_id;
                                    break;
                            }
                            ?>
                            <tr>
                                <th scope="row"><a href="<?= $itemUrl ?>" target="new"><?= $action->activity ?></a></th>
                                <td><?= $action->scores?></td>
                                <td><?= $action->content?></td>
                                <td><a href="/action/deactivate?id=<?= $action->id ?>"><?= $action->is_active ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- /.container -->
    </div> <!-- /.content -->
</div> <!-- /.wrapper -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>


</body>
</html>
