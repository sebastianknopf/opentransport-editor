<?php

use App\Model\Entity\Shape;

$this->loadHelper('Search.Search');

?>
<section class="content-header">
    <h1>
        <?= __('Shapes') ?>
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
                            <?= $this->Form->control('shape_id', ['type' => 'text', 'div' => false, 'label' => false, 'placeholder' => __('Shape ID')]) ?>
                        </div>
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('shape_name', ['div' => false, 'label' => false, 'placeholder' => __('Name')]) ?>
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
                                    <th scope="col" width="50"><?= $this->Paginator->sort('shape_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="consume"><?= $this->Paginator->sort('shape_name', __('Name')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="actions text-right" width="300"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($shapes as $shape): ?>
                                <tr>
                                    <td><?= h($shape->shape_id) ?></td>
                                    <td><?= h($shape->shape_name) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $shape)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $shape->shape_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('add', $shape)): ?>
                                                <?= $this->Form->postLink(__('Copy'), ['action' => 'copy', $shape->shape_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $shape)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $shape->shape_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $shape)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $shape->shape_id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
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