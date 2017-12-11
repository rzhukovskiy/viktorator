<?php
/**
 * @var $publicEntity PublicEntity
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body bg-white body_main">
            <form method="post">
                <div class="form-group row">
                    <label for="Config[topic_id]" class="col-sm-2 col-form-label">Secret группы</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="hidden" name="Group[id]" value="<?= $publicEntity->id ?>">
                            <input type="text" class="form-control" name="Group[topic_id]"
                                   placeholder="fdafEFfgdag" value="<?= $publicEntity->topic_id ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[post_id]" class="col-sm-2 col-form-label">Secret группы</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Group[post_id]"
                                   placeholder="fdafEFfgdag" value="<?= $publicEntity->post_id ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="">
                                i
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Config[standalone_token]" class="col-sm-2 col-form-label">Secret группы</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="text" class="form-control" name="Group[standalone_token]"
                                   placeholder="fdafEFfgdag" value="<?= $publicEntity->standalone_token ?>">
                            <div data-toggle="tooltip" class="input-group-addon tooltip-default"
                                 data-placement="bottom"
                                 title="">
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