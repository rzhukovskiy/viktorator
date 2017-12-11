<?php

/**
 */
class PublicController extends Controller
{
    public function actionList()
    {
        $listGroup = PublicModel::getByAdminId($this->admin->id);
        if (!$listGroup) {
            $listGroup = [];
            $data = VkSdk::getManagedGroupList($this->admin->token);
            if ($data) {
                foreach ($data as $row) {
                    $listGroup[$data['id']] = new PublicEntity([
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
            $this->render('public/list', [
                'listGroup' => $listGroup,
            ]);            
        } else {
            $this->render('public/empty');
        }
    }

    public function actionEdit()
    {
        if (!empty($_POST['Group'])) {
            $config = new ConfigEntity($_POST['Group']);
            $config->save();

            $this->redirect('public/edit');
        }

        $publicEntity = PublicModel::getById($_GET['id']);

        $this->render('public/edit', [
            'publicEntity' => $publicEntity,
        ]);
    }
}