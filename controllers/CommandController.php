<?php

/**
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
        list($startDate, $endDate) = $this->getWeekPeriod();

        if (file_exists('lock.lock')) {
            echo "Blocked!\n";
            die;
        }

        $fp = fopen('lock.lock', 'w');
        try {
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            $totalScores = ScoreModel::collect(Globals::$config->group_id, $startDate, $endDate);
            echo $totalScores . "\n";
        } catch (Exception $ex) {
            print_r($ex);
        }

        fclose($fp);
        unlink('lock.lock');
    }

    public function actionUpdate()
    {
        try {
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            ScoreModel::updateTable();
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

    public function actionReset()
    {
        list($startDate, $endDate) = $this->getWeekPeriod();
        $beginOfDay = strtotime("midnight", time()) - 3 * 3600;
        $week = date('Ymd', time());

        $time = 0;
        while(file_exists('lock.lock')) {
            sleep(15);
            $time += 15;
            if ($time > 3600) {
                exit("Timeout\n");
            }
        }
        $fp = fopen('lock.lock', 'w');

        try {
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            ActionModel::clearAllAfterDate($startDate);
            ScoreModel::collect(Globals::$config->group_id, $startDate, $endDate);
            ScoreModel::collectDaily($beginOfDay);

            foreach (UserModel::getTop(12) as $topUser) {
                $topUser->saveToTop($week);
            }

            UserModel::resetAll();
            ActionModel::resetAll();

            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex);
        }

        fclose($fp);
        unlink('lock.lock');
    }

    public function actionDaily()
    {
        $beginOfDay = strtotime("midnight", time()) - 3 * 3600;

        $time = 0;
        while(file_exists('lock.lock')) {
            sleep(15);
            $time += 15;
            if ($time > 3600) {
                exit("Timeout\n");
            }
        }
        $fp = fopen('lock.lock', 'w');
        
        try {
            ScoreModel::init($this->bot->getToken(), Globals::$config->standalone_token);
            ScoreModel::collectDaily($beginOfDay);
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex);
        }

        fclose($fp);
        unlink('lock.lock');
    }

    /**
     * @return array
     */
    private function getWeekPeriod()
    {
        //Moscow gmt +3
        $startDate = new DateTime();
        $startDate->setTimestamp(strtotime('this week'))->setTime(0, 0, 0);
        $startDate = $startDate->getTimestamp() - 3 * 3600;
        if ((7 * 24 * 3600 - time() + $startDate) <= 0) {
            $startDate = new DateTime();
            $startDate->setTimestamp(strtotime('next week'))->setTime(0, 0, 0);
            $startDate = $startDate->getTimestamp() - 3 * 3600;
        } else {
            $startDate -= 3 * 3600;
        }

        $endDate = new DateTime();
        $endDate->setTimestamp(time())->setTime(23, 59, 59);
        $endDate -= 3 * 3600;

        return [$startDate, $endDate];
    }
}