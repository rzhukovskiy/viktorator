<div class="container">
    <div class="block">
        <div class="block__body">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Scores</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listUser as $user) {?>
                    <tr>
                        <th scope="row"><a href="https://vk.com/id<?= $user->social_id?>" target="new"><?= $user->social_id?></a></th>
                        <td><?= $user->name?></td>
                        <td><a href="/action/list?user_id=<?= $user->id ?>"><?= $user->scores?></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->