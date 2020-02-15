<?php

use App\Model\Entity\Client;

?>
<section class="content-header">
    <h1>
        <?= __('Clients') ?>
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
                                    <th scope="col"><?= $this->Paginator->sort('shortname', __('Shortname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('longname', __('Longname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= $this->Number->format($client->id) ?></td>
                                    <td><?= h($client->shortname) ?></td>
                                    <td><?= h($client->longname) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $client)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $client->id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $client)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $client->id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $client)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $client->id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
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
                    <div class="pull-right">
                        <?php if($_IDENTITY->can('transfer', Client::getInstance())): ?>
                        <div class="bg-white text-right p-4">
                            <?= $this->Html->link(__('Transfer Clientship'), ['action' => 'transfer'], ['class' => 'btn btn-warning']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->element('modal/delete-confirm') ?>