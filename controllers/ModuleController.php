<?php

class ModuleController extends CrudController
{
    public $model = 'Menu';
    /**
     *
     */
    public function actionSort()
    {
        $updateData = array();
        if(isset($_POST['data'])){
            $retrieve_data = function($items, $parentId) use (&$updateData, &$retrieve_data){
                foreach($items as $index=>$item){
                    $updateData[] = "({$item['id']},$parentId, $index)";
                    if (isset($item['children']) && is_array($item['children'])) {
                        $retrieve_data($item['children'], $item['id']);
                    }
                }
            };
            $retrieve_data($_POST['data'], 0);
        }



        $sql = 'INSERT INTO {{menu}} (id, parent_id, sort) VALUES '.implode(',',$updateData).' ON DUPLICATE KEY UPDATE parent_id=VALUES(parent_id),sort=VALUES(sort);';
        db()->createCommand($sql)->execute();
        app()->end();
    }
}