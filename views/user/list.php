<?php
/** 
 * @var $listUser UserEntity[]
 * @var $listGroup PublicEntity[]
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <form class="form-inline">
                <div class="d-flex form-group">
                    <label for="group_id" class="col-auto col-form-label">Группа</label>
                    <select class="col form-control" id="group_id" name="group_id">
                        <?php foreach ($listGroup as $group) { ?>
                            <option value="<?= $group->id ?>"><?= $group->name ?></option>
                        <?php } ?>
                    </select>
                    <div class="d-flex ml-sm-2 form-group">
                        <button type="submit" class="btn btn-info">Показать</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="block__body">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Member</th>
                    <th scope="col">Repost</th>
                    <th scope="col">Scores</th>
                    <th scope="col">Добавить</th>
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
                        <td>
                            <form class="form-inline" action="/user/give">
                                <div class="d-flex form-group">
                                    <input type="hidden" class="form-control" name="user_id" value="<?= $user->id ?>" />
                                    <input type="text" class="form-control" name="scores" />
                                    <div class="d-flex ml-sm-2 form-group">
                                        <button type="submit" class="btn btn-info">+</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->