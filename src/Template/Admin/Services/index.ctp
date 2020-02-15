<?php

use App\Model\Entity\Service;

$this->loadHelper('Search.Search');

?>
<section class="content-header">
    <h1>
        <?= __('Services') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box <?= $this->Search->isSearch() ? null : 'collapsed-box'; ?>">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Filter Options') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= $this->Search->isSearch() ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?= $this->Form->create(null, ['valueSources' => 'query']) ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('service_id', ['type' => 'text', 'div' => false, 'label' => false, 'placeholder' => __('Service ID')]) ?>
                        </div>
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('service_name', ['div' => false, 'label' => false, 'placeholder' => __('Name')]) ?>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?php if($this->Search->isSearch()): ?>
                                <?= $this->Html->link(__('Reset'), ['?' => ['deleteSessionFilter' => true]], ['class' => 'btn btn-danger']); ?>
                            <?php endif; ?>
                            <?= $this->Form->button(__('Apply'), ['type' => 'submit', 'class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
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
                                    <th scope="col" width="50"><?= $this->Paginator->sort('service_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('service_name', __('Name')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= __('Description') ?></th>
                                    <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= h($service->service_id) ?></td>
                                    <td><?= h($service->service_name) ?></td>
                                    <td style="white-space:normal;"><?= h($service->service_string) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $service)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $service->service_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $service)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $service->service_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $service)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $service->service_id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
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