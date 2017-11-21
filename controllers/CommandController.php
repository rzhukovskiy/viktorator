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
        if (ENV != 'console') {
            exit();
        }
        $this->bot = new BotEntity();

        parent::init();
    }

    public function actionCollect()
    {
        try {
            ScoreModel::updateTable();
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            $totalScores = ScoreModel::collect();
            ScoreModel::updateTable();
            echo $totalScores . "\n";
        } catch (Exception $ex) {
            print_r($ex); die;
        }
    }
}