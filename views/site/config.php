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
                    <label for="Config[topic_id]" class="col-sm-2 col-form-label">Id топика</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="Config[topic_id]" id="Config[topic_id]" placeholder="ID топика с конкурсом" value="<?= $config->topic_id?>">
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