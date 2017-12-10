<?php

/**
 */
class GroupController extends Controller
{
    public function actionList()
    {
        if (!empty($_POST['Error'])) {
            $errorEntity = ErrorModel::getById($_POST['Error']['id']);
            $errorEntity->response = $_POST['Error']['response'];
            $errorEntity->save();

            $this->redirect('error/list');
        }
        
        $listGroup = GroupModel::getByAdminId($this->admin->id);
        if (!$listGroup) {
            $listGroup = [];
            $data = VkSdk::getManagedGroupList($this->admin->id, $this->admin->token);
            if ($data) {
                foreach ($data as $row) {
                    $listGroup[$data['id']] = new GroupEntity([
                        'id'        => $row['id'],
                        'name'      => $row['name'],
                        'slug'      => $row['screen_name'],
                        'picture'   => $row['photo_50'],
                        'admin_id'  => $this->admin->id,
                    ]);

                    $listGroup[$data['id']]->save();
                }
            }
        }        
        
        if ($listGroup) {
            $this->render('group/list', [
                'listGroup' => $listGroup,
            ]);            
        } else {
            $this->render('group/empty');
        }
    }

    public function actionEdit()
    {
        if (!empty($_POST['Group'])) {
            $config = new ConfigEntity($_POST['Group']);
            $config->save();

            $this->redirect('group/edit');
        }

        $groupEntity = GroupModel::getById($_GET['id']);

        $this->render('group/edit', [
            'config' => Globals::$config,
        ]);
    }
}