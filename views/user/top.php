<?php
/** 
 * @var $listUser UserEntity[] 
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Scores</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 0; foreach ($listUser as $user) { $i++;?>
                    <tr>
                        <th scope="row"><?= $i ?></th>
                        <td><a href="https://vk.com/id<?= $user->social_id?>" target="new"><?= $user->social_id?></a></td>
                        <td><?= $user->name?></td>
                        <td><?= $user->scores?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->