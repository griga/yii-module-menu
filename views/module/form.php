<?php
/**
 * @var $this BackendController
 * @var $form \yg\tb\ActiveForm
 * @var ProductCategory $model
 */

$this->breadcrumbs = [
    t('Catalog') => '/admin/catalog/',
    t('Categories') => '/admin/catalog/category',
    t($model->isNewRecord ? 'New record' : 'Update record'),
];

$this->widget('ext.yg.WellBlocksCollapsible');
$this->widget('ext.yg.NumberFieldWithHandlers');

$form = $this->beginWidget('\yg\tb\ActiveForm', [
    'id' => 'menu-form',
    'labelColWidth'=>3,
])

?>
<h3><?= t($model->isNewRecord ? 'New record' : 'Update record') ?></h3>
<hr/>
<div class="row">
    <div class="col-sm-9">
        <div class="well"><h4><?= t('Menu item') ?></h4>
            <?= $form->textAreaControl($model, 'name', [
                'multilingual'=>true
            ]) ?>

            <?= $form->textControl($model, 'alias') ?>
            <?php $this->widget('ext.chosen.TbChosen', [
                'model'=>$model,
                'attribute'=> 'parent_id',
                'htmlOptions'=>[
                    'empty'=>'',
                    'preSelectedValues' => ($model->isNewRecord && isset($_GET['parent_id']) ? $_GET['parent_id'] : []),
                ],
                'labelColWidth'=>3,
                'data'=> Menu::model()->getAsList($model->id),

            ]); ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="well">
            <div class="<?= $model->hasErrors('enabled') ? 'has-error' : '' ?>">
                <div class="checkbox col-md-offset-5">
                    <label>
                        <?= $form->checkBox($model, 'enabled') ?><b> <?= $model->getAttributeLabel('enabled') ?></b>
                    </label>
                    <?= $form->error($model, 'enabled') ?>
                </div>
            </div>
            <br/>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-sm-9">
        <?= $form->actionButtons($model, 'right'); ?>
    </div>
</div>


<?php $this->endWidget() ?>


