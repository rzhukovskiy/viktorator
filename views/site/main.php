<?php
/** @var $botname string
 *  @var $bot bool
 *  @var username string
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <div class="row">
                <div class="col-md-12">
                    <h2>Добро пожаловать!</h2>
                    <p>Привет, <?= $username ?>!</p>
                    <p>Бот <?= $bot ? "активен и привязан к <strong>$botname</strong>" : "не активен" ?>. Привязать на <a href='/bot/connect'>себя</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /.container -->