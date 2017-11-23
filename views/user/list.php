<?php
/** 
 * @var $listUser UserEntity[] 
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Member</th>
                    <th scope="col">Repost</th>
                    <th scope="col">Scores</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 0; foreach ($listUser as $user) { $i++;?>
                    <tr>
                        <th scope="row"><?= $i ?></th>
                        <td><a href="https://vk.com/id<?= $user->social_id?>" target="new"><?= $user->social_id?></a></td>
                        <td><?= $user->name?></td>
                        <td><?= $user->is_member?></td>
                        <td><?= $user->is_repost?></td>
                        <td><a href="/action/list?user_id=<?= $user->id ?>"><?= $user->scores?></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->