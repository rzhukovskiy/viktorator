<?php
/**
 * @var $config ConfigEntity
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body bg-white body_main">
            <form method="post">
                <div class="form-group row">
                    <label for="Config[group_id]" class="col-sm-2 col-form-label">ID группы</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[group_id]" placeholder="2312315"
                                   value="<?= $config->group_id ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom" title="Айди группы, в которой будет происходить икторина">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[group_secret]" class="col-sm-2 col-form-label">Secret группы</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[group_secret]"
                                   placeholder="fdafEFfgdag" value="<?= $config->group_secret ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="Ключ для проверки, что callback ришел именно от ВК. Берется в настройках callback в группе.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[group_confirm]" class="col-sm-2 col-form-label">Confirm группы</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[group_confirm]" placeholder="wgrerg"
                                   value="<?= $config->group_confirm ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="Строка, которая должна вернутся для подтверждения Callback url. Берется там же в настройках группы.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[topic_id]" class="col-sm-2 col-form-label">Id топика</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[topic_id]" placeholder="21415431"
                                   value="<?= $config->topic_id ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="Топик, куда будут поститься автоматическуие ответы и результаты конкурса.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[app_id]" class="col-sm-2 col-form-label">ID приложения</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[app_id]" placeholder="2341341"
                                   value="<?= $config->app_id ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="ID приложения конкурса. Лучше его не трогать на самом деле.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[app_secret]" class="col-sm-2 col-form-label">Ключ приложения</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[app_secret]"
                                   placeholder="afAUifeir232FAd" value="<?= $config->app_secret ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="Секретный ключ этого приложения. Тоже на всякий случай стоит оставить как есть.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[standalone_id]" class="col-sm-2 col-form-label">ID standalone приложения</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[standalone_id]" placeholder="32354341"
                                   value="<?= $config->standalone_id ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="Это айди приложения, занимающегося автоответом в топике. Для него требуется другой ключ.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[standalone_token]" class="col-sm-2 col-form-label">Token standalone
                        приложения</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[standalone_token]"
                                   placeholder="токен моего приложения" value="<?= $config->standalone_token ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom" title="Это токен для этого приложения.
                             Если поле с айди приложения уже заполнено и сохранено - то можно получить токен по ссылочке ниже, взяв его из адресной строки.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        Чтобы получить токен - жмак <a href="<?= VkSdk::getStandaloneAuthUrl(); ?>"
                                                       target="_blank">сюда</a>, а потом скопируй его из адресной
                        строки.
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[redirect_uri]" class="col-sm-2 col-form-label">Redirect uri</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Config[redirect_uri]"
                                   placeholder="куда редиректим после авторизации" value="<?= $config->redirect_uri ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="Тут все просто - куда редиректим после авторизации. Тоже программистские штучки.">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        Не знаешь для чего параметр? Лучше не трогай.
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /.container -->