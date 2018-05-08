<?php

/**
 */
class LeadController extends Controller
{
    public function actionList()
    {        
        $this->render('lead/list', [
            'listLead' => LeadModel::getAll(),
        ]);
    }
}