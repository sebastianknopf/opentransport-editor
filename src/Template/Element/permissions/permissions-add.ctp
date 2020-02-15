<?php

$this->loadHelper('Form');

?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <?= $hasVisibleChildren ? '<b>' . $data->alias . '</b>' : $data->alias ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.read', ['type' => 'checkbox', 'label' => __('View')]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.create', ['type' => 'checkbox', 'label' => __('Create')]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.update', ['type' => 'checkbox', 'label' => __('Edit')]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.delete', ['type' => 'checkbox', 'label' => __('Delete')]) ?>
    </div>
</div>