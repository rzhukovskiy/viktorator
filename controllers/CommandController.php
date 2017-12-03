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
        if (file_exists('lock.lock')) {
            echo "Blocked!\n";
            die;
        }

        try {
            $fp = fopen('lock.lock', 'w');
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);

            $totalScores = ScoreModel::collect();
            ScoreModel::updateTable();

            echo $totalScores . "\n";
        } catch (Exception $ex) {
            print_r($ex);
        }
        unlink('lock.lock');
    }

    public function actionUpdate()
    {
        try {
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            ScoreModel::updateTable();
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex); die;
        }
    }

    public function actionClear()
    {
        try {
            $time = 0;
            while(file_exists('lock.lock')) {
                sleep(15);
                $time += 15;
                if ($time > 3600) {
                    exit("Timeout\n");
                }
            }
            UserModel::resetAll();
            ActionModel::clearAll();
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex); die;
        }
    }

    public function actionReset()
    {
        $date = date('Ymd', time());
        try {
            $time = 0;
            while(file_exists('lock.lock')) {
                sleep(15);
                $time += 15;
                if ($time > 3600) {
                    exit("Timeout\n");
                }
            }
            foreach (UserModel::getTop(12) as $topUser) {
                $topUser->saveToTop($date);
            }
            $fp = fopen('lock.lock', 'w');
            UserModel::resetAll();
            ActionModel::resetAll();
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex); die;
        }
        unlink('lock.lock');
    }

    public function actionDaily()
    {
        $time = time();
        $beginOfDay = strtotime("midnight", $time) - 3 * 3600;
        
        try {
            $time = 0;
            while(file_exists('lock.lock')) {
                sleep(15);
                $time += 15;
                if ($time > 3600) {
                    exit("Timeout\n");
                }
            }
            $fp = fopen('lock.lock', 'w');
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            ScoreModel::collectDaily($beginOfDay);
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex); die;
        }
        unlink('lock.lock');
    }
}