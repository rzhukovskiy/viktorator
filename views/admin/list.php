<?php
/** @var $listAdmin AdminEntity[] */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Is active</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listAdmin as $admin) {?>
                    <tr>
                        <th scope="row"><a href="https://vk.com/id<?= $admin->social_id?>" target="new"><?= $admin->social_id?></a></th>
                        <td><?= $admin->name?></td>
                        <td><a href="/admin/activate?id=<?= $admin->id ?>"><?= $admin->is_active?></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->