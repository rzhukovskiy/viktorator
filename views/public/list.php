<?php
/** 
 * @var $listGroup PublicEntity[]
 */
?>

<div class="container">
    <div class="block">
        <div class="block__body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item active" role="presentation">
                    <a class="nav-link active" href="/group/list" aria-controls="home" role="tab" data-toggle="tab">Администрируемые</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="/group/active" aria-controls="profile" role="tab"
                       data-toggle="tab">Активные</a>
                </li>
            </ul>
        </div>
        <div class="block__body bg-white body_main tab-content">
            <div class="tab-pane active" role="tabpanel" id="home">
                <div class="row">
                    <?php foreach ($listGroup as $group) { ?>
                        <div class="col-md-4 col-lg-3 py-2">
                            <div class="communities-container">
                                <div class="communities-container__header <?= $group->isActive() ? 'active' : '' ?>">
                                    <?= $group->isActive() ? 'Подключено' : 'Не подключено' ?>
                                </div>
                                <div class="communities-container__body">
                                    <div class="communities-container__img">
                                        <img src="<?= $group->picture ?>">
                                    </div>
                                    <div class="communities-container__title">
                                        <?= $group->name ?>
                                    </div>
                                </div>
                                <div class="communities-container__footer">
                                    <a href="https://vk.com/<?= $group->slug ?>" target="_blank" class="button-blue">
                                        <?= $group->slug ?>
                                    </a>
                                    <?php if(!$group->isActive()) { ?>
                                        <a href="<?= VkSdk::getGroupAuthUrl($group->id) ?>" class="button-grey">
                                            Подключить
                                        </a>
                                    <?php } else { ?>
                                        <a href="/public/edit?id=<?= $group->id ?>" class="button-grey">
                                            Редактировать
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="tab-pane" role="tabpanel" id="profile">
                <div class="row">
                    <?php foreach ($listGroup as $group) {
                        if (!$group->isActive()) continue;
                        ?>
                        <div class="col-md-4 col-lg-3 py-2">
                            <div class="communities-container">
                                <div class="communities-container__header <?= $group->isActive() ? 'active' : '' ?>">
                                    <?= $group->isActive() ? 'Подключено' : 'Не подключено' ?>
                                </div>
                                <div class="communities-container__body">
                                    <div class="communities-container__img">
                                        <img src="<?= $group->picture ?>">
                                    </div>
                                    <div class="communities-container__title">
                                        <?= $group->name ?>
                                    </div>
                                </div>
                                <div class="communities-container__footer">
                                    <a href="https://vk.com/<?= $group->slug ?>" target="_blank" class="button-blue">
                                        <?= $group->slug ?>
                                    </a>
                                    <?php if(!$group->isActive()) { ?>
                                        <a href="<?= VkSdk::getGroupAuthUrl($group->id) ?>" class="button-grey">
                                            Подключить
                                        </a>
                                    <?php } else { ?>
                                        <a href="/public/edit?id=<?= $group->id ?>" class="button-grey">
                                            Редактировать
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div> <!-- /.container -->