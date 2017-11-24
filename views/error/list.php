<?php
/** 
 * @var $listError ErrorEntity[]
 * @var $captchaError ErrorEntity
 */
?>
<div class="container">
    <?php if ($captchaError->is_active) {
        $data = unserialize($captchaError->content);
    ?>
        <div class="block">
            <div class="block__body bg-white body_main">
                <form method="post">
                    <div class="form-group row">
                        <label for="Error[response]" class="col-sm-2 col-form-label">Капча</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                <input type="hidden" class="form-control" name="Error[id]" value="<?= $captchaError->id ?>">
                                <input type="text" class="form-control" name="Error[response]" value="<?= $captchaError->response ?>">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <img src="<?= $data['captcha_img'] ?>" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>

    <div class="block">
        <div class="block__body">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Content</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listError as $error) { ?>
                    <tr>
                        <td><?= date("d-m-Y H:i", $error->created_at + 3 * 3600) ?></td>
                        <td><?= $error->type?></td>
                        <td><?= $error->content?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->