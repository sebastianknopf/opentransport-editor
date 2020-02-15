<?php

use Acl\Controller\Component\AclComponent;
use Cake\Controller\ComponentRegistry;

$registry = new ComponentRegistry();
$acl = new AclComponent($registry);

$this->loadHelper('Form');

?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <?= $hasVisibleChildren ? '<b>' . $data->alias . '</b>' : $data->alias ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.read', ['type' => 'checkbox', 'label' => __('View'), 'disabled' => true, 'checked' => $acl->check(['model' => 'Groups', 'foreign_key' => $group->id], $data->alias, 'read')]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.create', ['type' => 'checkbox', 'label' => __('Create'), 'disabled' => true, 'checked' => $acl->check(['model' => 'Groups', 'foreign_key' => $group->id], $data->alias, 'create')]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.update', ['type' => 'checkbox', 'label' => __('Edit'), 'disabled' => true, 'checked' => $acl->check(['model' => 'Groups', 'foreign_key' => $group->id], $data->alias, 'update')]) ?>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
        <?= $this->Form->control('Aco.' . $data->alias . '.delete', ['type' => 'checkbox', 'label' => __('Delete'), 'disabled' => true, 'checked' => $acl->check(['model' => 'Groups', 'foreign_key' => $group->id], $data->alias, 'delete')]) ?>
    </div>
</div>