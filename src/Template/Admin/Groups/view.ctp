<?php

$acos = \Cake\ORM\TableRegistry::getTableLocator()->get('Acos')->find();
$acos = $acos->toArray();

$this->loadHelper('Tools.Tree');

?>
<section class="content-header">
    <h1>
        <?= __('View Group') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view', ['identity' => $_IDENTITY, 'entity' => $group, 'primaryKey' => $group->id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabPermissions" data-toggle="tab"><?= __('Permissions') ?></a></li>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Name') ?></th>
                                                <td><?= h($group->name) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane p-2" id="tabPermissions">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <style>
                                            ul.acl-tree, ul.acl-tree ul {
                                                list-style: none;
                                                padding: 0;
                                            }

                                            ul.acl-tree li {
                                                margin: 12px 0;
                                            }
                                        </style>
                                        <?= $this->Tree->generate($acos, ['class' => 'acl-tree', 'element' => 'permissions/permissions-view']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabMeta">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('ID') ?></th>
                                                <td class="consume"><?= $this->Number->format($group->id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($group->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($group->modified) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>