<?php

$acos = \Cake\ORM\TableRegistry::getTableLocator()->get('Acos')->find();
$acos = $acos->toArray();

$this->loadHelper('Tools.Tree');

?>
<section class="content-header">
    <h1>
        <?= __('Add Group') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($group) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-add') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabPermissions" data-toggle="tab"><?= __('Permissions') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <?= $this->Form->control('name', ['required' => true, 'class' => 'form-control']) ?>
                            </div>
                            <div class="tab-pane" id="tabPermissions">
                                <style>
                                    ul.acl-tree, ul.acl-tree ul {
                                        list-style: none;
                                        padding: 0;
                                    }

                                    ul.acl-tree li {
                                        margin: 12px 0;
                                    }
                                </style>
                                <?= $this->Tree->generate($acos, ['class' => 'acl-tree', 'element' => 'permissions/permissions-add']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= $this->Html->link(__('Cancel'), (isset($_REDIRECT) && $_REDIRECT != null) ? $_REDIRECT : ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>