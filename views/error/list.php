<?php
/** 
 * @var $listError ErrorEntity[]
 * @var $captchaError ErrorEntity
 */
?>
<div class="container">
    <?php if ($captchaError->is_active && !$captchaError->response) {
        $data = unserialize($captchaError->content);
    ?>
        <div class="block">
            <div class="block__body bg-white body_main">
                <form method="post">
                    <div class="form-group row">
                        <label for="Error[response]" class="col-sm-2 col-form-label">Капча</label>
                        <div class="col-sm-5">
                            <img src="<?= $data['captcha_img'] ?>" />
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                <input type="hidden" class="form-control" name="Error[id]" value="<?= $captchaError->id ?>">
                                <input type="text" class="form-control" name="Error[response]" value="<?= $captchaError->response ?>">
                            </div>
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
            <a href="/error/clear">Очистить</a>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Content</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listError as $error) { $data = unserialize($error->content);?>
                    <tr>
                        <td><?= date("d-m-Y H:i", $error->created_at + 3 * 3600) ?></td>
                        <td><?= $error->type ?></td>
                        <td><?= $data['error_msg'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->