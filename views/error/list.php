<?php
/** 
 * @var $listError ErrorEntity[]
 */
?>
<div class="container">
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
                        <td><?= date("d-m-Y H:i", $error->created_at) ?></td>
                        <td><?= $error->type?></td>
                        <td><?= $error->content?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->