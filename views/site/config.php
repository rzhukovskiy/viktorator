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