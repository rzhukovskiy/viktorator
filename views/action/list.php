<?php
/** @var $listAction ActionEntity[] */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Scores</th>
                    <th scope="col">Content</th>
                    <th scope="col">Is active</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listAction as $action) {
                    switch ($action->activity) {
                        case 'like':
                        case 'ten_like':
                        case 'first_like':
                            $itemUrl = 'https://vk.com/wall-' . Globals::$config->group_id . '_' . $action->parent_social_id .
                                '?w=likes%2Fwall-' . Globals::$config->group_id . '_' . $action->parent_social_id;
                            break;
                        case 'comment':
                        case 'first_comment':
                            $itemUrl = 'https://vk.com/wall-' . Globals::$config->group_id . '_' . $action->parent_social_id . '?' . $action->social_id;
                            break;
                        default:
                            $itemUrl = 'https://vk.com/wall-' . Globals::$config->group_id;
                    }
                    ?>
                    <tr>
                        <td><?= date("d-m-Y H:i", $action->created_at) ?></td>
                        <td><a href="<?= $itemUrl ?>" target="new"><?= $action->activity ?></a></td>
                        <td><?= $action->scores?></td>
                        <td><?= $action->content?></td>
                        <td><a href="/action/deactivate?id=<?= $action->id ?>"><?= $action->is_active ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->