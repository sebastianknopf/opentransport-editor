<?php

use App\Model\Entity\User;

?>
<section class="content-header">
    <h1>
        <?= __('Users') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header">
                    <?= $this->element('box/toolbar-index') ?>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="w-100 table table-striped" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th scope="col" width="50"><?= $this->Paginator->sort('id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('username') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('group_id') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('email') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $this->Number->format($user->id) ?></td>
                                    <td class="consume"><?= h($user->username) ?></td>
                                    <td><?= $user->has('group') ? $user->group->name : '' ?></td>
                                    <td><?= h($user->email) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $user)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $user->id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $user)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $user)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $user->id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer">
                    <ul class="pagination">
                        <?= $this->Paginator->numbers(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->element('modal/delete-confirm') ?>