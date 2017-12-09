<?php
/**
 * @var $widgetEntity WidgetEntity
 * @var $group_id string
 */
?>
<div class="container">
    <div class="block">
        <form id="widgetConfig">
            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">Заголовок</label>
                <div class="col-sm-8">
                    <?= $widgetEntity ? "<input type='hidden' name='id' value='$widgetEntity->id'/>" : '' ?>
                    <input type="hidden" name="group_id" value="<?= $group_id ?>"/>
                    <input type="hidden" name="group_social_id" value="<?= $group_id ?>"/>
                    <input type="text" class="form-control" id="title" name="title" 
                           placeholder="Привет, %name%" value="<?= $widgetEntity->title ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="text" class="col-sm-4 col-form-label">Текст (заголовок)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="text" name="text" 
                           placeholder="Основной текст. %username% понимает." value="<?= $widgetEntity->text ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="button_url" class="col-sm-4 col-form-label">Текст (текст)</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="main_text" name="main_text" 
                           placeholder="И это тоже текст. Путаница уже" value="<?= $widgetEntity->main_text ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="button_text" class="col-sm-4 col-form-label">Текст кнопки</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="button_text" name="button_text" 
                           placeholder="Узнать больше" value="<?= $widgetEntity->button_text ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="button_url" class="col-sm-4 col-form-label">Урл кнопки</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="button_url" name="button_url" 
                           placeholder="https://vk.com" value="<?= $widgetEntity->button_url ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-primary" id="addWidget">Постановка виджета</button>
                </div>
            </div>
        </form>
    </div>
</div> <!-- /.container -->