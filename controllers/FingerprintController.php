<?php

/**
 */
class FingerprintController extends BaseController
{
    public function actionIndex()
    {
        $this->template = 'login';

        $hash = isset($_POST['Lead']) ? $_POST['Lead'] : null;
        if ($hash) {
            $lead = new LeadEntity($_POST['Lead']);
            $lead->ip = $_SERVER['REMOTE_ADDR'];
            $lead->config = serialize($lead->config);
            $lead->save();
        }

        $this->render('fingerprint/index');
    }
}