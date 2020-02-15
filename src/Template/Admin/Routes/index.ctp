<?php

use App\Model\Entity\Route;

$this->loadHelper('Search.Search');

?>
<section class="content-header">
    <h1>
        <?= __('Routes') ?>
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
                            <?= $this->Form->control('route_id', ['type' => 'text', 'div' => false, 'label' => false, 'placeholder' => __('Route ID')]) ?>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('route_type', ['div' => false, 'label' => false, 'options' => Route\Type::getRouteTypes(), 'empty' => __('Route Type')]) ?>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('route_short_name', ['div' => false, 'label' => false, 'placeholder' => __('Shortname')]) ?>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('route_long_name', ['div' => false, 'label' => false, 'placeholder' => __('Longname')]) ?>
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
                                    <th scope="col" width="50"><?= $this->Paginator->sort('route_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('agency_id', __('Agency')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('route_type', __('Type')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('route_short_name', __('Shortname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('route_long_name', __('Longname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('route_url', __('URL')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($routes as $route): ?>
                                <tr>
                                    <td><?= h($route->route_id) ?></td>
                                    <td><?= isset($route->agency) ? h($route->agency->agency_name) : h($route->agency_id) ?></td>
                                    <td><?= h(Route\Type::getRouteTypes()[$route->route_type]) ?></td>
                                    <td><?= h($route->route_short_name) ?></td>
                                    <td><?= h($route->route_long_name) ?></td>
                                    <td>
                                        <a href="<?= $route->route_url ?>" target="_blank">
                                            <?= h($route->route_url) ?>
                                        </a>
                                    </td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $route)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $route->route_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $route)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $route->route_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $route)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $route->route_id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
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