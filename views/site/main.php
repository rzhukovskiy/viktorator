<?php
/**
 * @var $botname string
 * @var $bot bool
 * @var $username string
 * @var $lastAction ActionEntity
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
                    <p>Последнее обновление очков: <?= $lastAction ? date("d-m-Y H:i", $lastAction->created_at + 3 * 3600) : 'а не было обновления' ?>.</p>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /.container -->