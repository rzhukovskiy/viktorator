<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.11.2017
 * Time: 18:30
 */
class CallbackController extends BaseController
{
    /** @var $admin BotEntity  */
    private $bot   = null;

    public function init()
    {
        $this->bot = new BotEntity();
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @return null
     */
    public function actionVk()
    {
        $data = json_decode(file_get_contents('php://input'));

        if ($data->type == 'board_post_new'
            && $data->secret == Globals::$config->group_secret
            && $data->object->from_id != ('-' . Globals::$config->group_id)
        ) {
            VkSdk::addComment(Globals::$config->standalone_token, $data->object->from_id);
        }

        echo 'ok';
        exit();
    }
}