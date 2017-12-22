<?php

/**
 */
class CommandController extends BaseController
{
    public function init()
    {
        if (ENV != 'console') {
            exit();
        }

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
            $totalScores = 0;
            foreach (PublicModel::getAllActive() as $group_id => $publicEntity) {
                $totalScores += ScoreModel::collect($publicEntity, $startDate, $endDate);
            }
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
            foreach (PublicModel::getAllActive() as $group_id => $publicEntity) {
                ScoreModel::updateTable($publicEntity);
            }
            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

    public function actionClear()
    {
        list($startDate) = $this->getWeekPeriod();

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
            foreach (PublicModel::getAllActive() as $group_id => $publicEntity) {
                ActionModel::clearAllAfterDate($group_id, $startDate);
                PostModel::clearAllAfterDate($group_id, $startDate);
                CommentModel::clearAllEmpty($group_id);
                UserModel::resetAll($group_id);
            }

            echo "Done!\n";
        } catch (Exception $ex) {
            print_r($ex);
        }

        fclose($fp);
        unlink('lock.lock');
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
            foreach (PublicModel::getAllActive() as $group_id => $publicEntity) {
                ScoreModel::collect($publicEntity, $startDate, $endDate);
                ScoreModel::collectDaily($publicEntity, $beginOfDay, $beginOfDay + 24 * 3600);

                foreach (UserModel::getTop($group_id, 12) as $topUser) {
                    $topUser->saveToTop($week);
                }

                UserModel::resetAll($group_id);
                ActionModel::resetAll($group_id);
            }

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
            foreach (PublicModel::getAllActive() as $group_id => $publicEntity) {
                ScoreModel::collectDaily($publicEntity, $beginOfDay, $beginOfDay + 24 * 3600);
            }
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

            $endDate = new DateTime();
            $endDate->setTimestamp(strtotime('next sunday'))->setTime(20, 59, 59);
            $endDate = $endDate->getTimestamp();
        } else {
            $endDate = new DateTime();
            $endDate->setTimestamp(time())->setTime(20, 59, 59);
            $endDate = $endDate->getTimestamp();
        }

        return [$startDate, $endDate];
    }
}