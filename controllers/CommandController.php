<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 17.11.2017
 * Time: 19:44
 */
class CommandController extends BaseController
{
    /** @var $admin BotEntity  */
    private $bot = null;

    public function init()
    {
        $this->bot = new BotEntity();

        parent::init();
    }

    public function actionCollect()
    {
        ScoreModel::init($this->bot->getToken());
        $totalScores = ScoreModel::collect();
        echo $totalScores . "\n";
    }
}