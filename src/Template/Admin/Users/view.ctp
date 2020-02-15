<section class="content-header">
    <h1>
        <?= __('View User') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view', ['identity' => $_IDENTITY, 'entity' => $user, 'primaryKey' => $user->id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-12">
                                       <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Username') ?></th>
                                                <td><?= h($user->username) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Email') ?></th>
                                                <td><?= h($user->email) ?></td>
                                            </tr>
                                            <?php if($user->has('client')): ?>
                                                <tr>
                                                    <th scope="row"><?= __('Client') ?></th>
                                                    <td><?= $this->Html->link($user->client->longname, ['controller' => 'clients', 'action' => 'view', $user->client->id]) ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if($user->has('group')): ?>
                                                <tr>
                                                    <th scope="row"><?= __('Group') ?></th>
                                                    <td><?= $this->Html->link($user->group->name, ['controller' => 'Groups', 'action' => 'view', $user->group->id]) ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabMeta">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('ID') ?></th>
                                                <td class="consume"><?= $this->Number->format($user->id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($user->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($user->modified) ?></td>
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