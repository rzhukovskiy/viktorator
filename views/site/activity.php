<?php
/** @var $listActivity ActionEntity[] */
?>
<div class="container">
    <div class="block">
        <div class="block__body bg-white body_main">
            <form method="post">
                <?php $i = 0; foreach ($listActivity as $activity) {
                    $i++;?>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <input type="hidden" class="form-control" name="Activity[<?=$i?>][id]" value="<?= $activity->id?>">
                            <input type="text" class="form-control" name="Activity[<?=$i?>][description]" placeholder="description"
                                   value="<?= $activity->description ?>">
                        </div>
                        <div class="offset-sm-1 col-sm-5">
                            <input type="text" class="form-control" name="Activity[<?=$i?>][price]" placeholder="price"
                                   value="<?= $activity->price ?>">
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /.container -->