<?php

use App\Model\Entity\Group;

?>
<section class="content-header">
    <h1>
        <?= __('Groups') ?>
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
                                    <th scope="col"><?= $this->Paginator->sort('name') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($groups as $group): ?>
                                <tr>
                                    <td><?= $this->Number->format($group->id) ?></td>
                                    <td><?= h($group->name) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $group)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $group->id], ['class' => 'btn btn-xs btn-default btn-outline-primary lnk-action lnk-view']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $group)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $group->id], ['class' => 'btn btn-xs btn-default btn-outline-primary  lnk-action lnk-edit']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $group)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $group->id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
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