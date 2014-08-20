<?php
/** @var ProductCategory $model
 * @var $this BackendController
 */

$this->breadcrumbs = array(
    t('Menu')=>'/admin/menu',
    t('Site menu')
);



//$countAll = $model->search()->totalItemCount;


$this->widget('ext.nested-sortable.NestedSortableWidget');


/** @var Menu[] $categories */


$data = Menu::model()->getDataForRecursiveRender();
?>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <h3><?php echo t('{n} menu|{n} menu|{n} menu', Menu::model()->count()); ?></h3>
        <a class="btn btn-success btn-xs" href="/admin/menu/module/create"><span class="glyphicon glyphicon-plus"></span> <?= t('add') ?></a>

        <hr/>

        <?php $this->widget('webroot.themes.commerce.back.widgets.SortableModelsWidget', array(
            'models'=>$data,
            'sortableParent'=>true,
            'controllerUrl'=>'/menu/module/',
        ));?>



    </div>
</div>