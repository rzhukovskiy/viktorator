<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 11:52
 */
class BotEntity
{
    private $owner = null;

    public function __construct()
    {
        $adminModel = new AdminModel();
        $adminEntity = $adminModel->findByBotFlag();
        if ($adminEntity) {
            $this->owner = $adminEntity;
        }
    }

    public function isActive()
    {
        return !empty($this->owner);
    }

    public function getOwnerName()
    {
        return !empty($this->owner) ? $this->owner->name : '';
    }

    public function getToken()
    {
        return !empty($this->owner) ? $this->owner->token : '';
    }
}