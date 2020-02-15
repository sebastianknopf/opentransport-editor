<?php

use App\Model\Entity\Trip;

$this->loadHelper('Search.Search');

?>
<section class="content-header">
    <h1>
        <?= __('Trips') ?>
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
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('trip_id', ['type' => 'text', 'div' => false, 'label' => false, 'placeholder' => __('Trip ID')]) ?>
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('direction_id', ['div' => false, 'label' => false, 'options' => ['0' => __('Outbound'), '1' => __('Inbound')], 'empty' => __('Direction')]) ?>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('service_id', ['div' => false, 'label' => false, 'options' => $services, 'empty' => __('Service')]) ?>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('route_id', ['div' => false, 'label' => false, 'options' => $routes, 'empty' => __('Route')]) ?>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('trip_short_name', ['div' => false, 'label' => false, 'placeholder' => __('Trip Shortname')]) ?>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('trip_headsign', ['div' => false, 'label' => false, 'placeholder' => __('Headsign')]) ?>
                        </div>
                    </div>
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
                                    <th scope="col" width="50"><?= $this->Paginator->sort('trip_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('start_time', __('Start Time')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('end_time', __('End Time')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('service_id', __('Service')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('route_id', __('Route')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('trip_short_name', __('Trip Shortname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('trip_headsign', __('Headsign')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('wheelchair_accessible', __('Wheelchair')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col"><?= $this->Paginator->sort('bikes_allowed', __('Bikes')) ?> <i class="fa fa-sort ml-2"></i></th>
                                    <th scope="col" class="actions text-right" width="300"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($trips as $trip): ?>
                                <tr>
                                    <td><?= h($trip->trip_id) ?></td>
                                    <td><?= h($trip->start_time->format('H:i:s')) ?></td>
                                    <td><?= h($trip->end_time->format('H:i:s')) ?></td>
                                    <td><?= isset($trip->service) ? h($trip->service->service_name) : h($trip->service_id) ?></td>
                                    <td><?= isset($trip->route) ? h($trip->route->route_short_name) : h($trip->route_id) ?></td>
                                    <td><?= h($trip->trip_short_name) ?></td>
                                    <td><?= h($trip->trip_headsign) ?></td>
                                    <td>
                                        <?php if($trip->wheelchair_accessible == '0'): ?>
                                            <span class="label label-default"><?= __('N/A') ?></span>
                                        <?php elseif($trip->wheelchair_accessible == '1'): ?>
                                            <span class="label label-success"><?= __('Yes') ?></span>
                                        <?php else: ?>
                                            <span class="label label-danger"><?= __('No') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($trip->bikes_allowed == '0'): ?>
                                            <span class="label label-default"><?= __('N/A') ?></span>
                                        <?php elseif($trip->bikes_allowed == '1'): ?>
                                            <span class="label label-success"><?= __('Yes') ?></span>
                                        <?php else: ?>
                                            <span class="label label-danger"><?= __('No') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $trip)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $trip->trip_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('add', $trip)): ?>
                                                <?= $this->Html->link(__('Copy'), ['action' => 'copy', $trip->trip_id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'modal', 'data-target' => '#copy-options']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('edit', $trip)): ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $trip->trip_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $trip)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $trip->trip_id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
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
<div class="modal fade" id="copy-options" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= __('Copy Options') ?></h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'form-copyoptions']) ?>
                <?= $this->Form->control('service_id', [ 'label' => __('Service'), 'options' => $services, 'empty' => __('Select...')]) ?>
                <?= $this->Form->control('trip_short_name', ['label' => __('Trip Shortname'), 'type' => 'text']) ?>
                <?= $this->Form->control('trip_headsign', ['label' => __('Headsign'), 'type' => 'text']) ?>
                <div class="row">
                    <div class="col-lg-3">
                        <?= $this->Form->control('stop_times_reference', ['type' => 'text', 'class' => 'form-control time', 'label' => __('Reference')]) ?>
                    </div>
                    <div class="col-lg-9">
                        <div class="form-group">
                            <?= $this->Form->label('stop_times_reverse', __('Stop Order'), ['class' => 'control-label']) ?><br />
                            <?= $this->Form->radio('stop_times_reverse', ['0' => __('Leave'), '1' => __('Reverse')], ['default' => '0', 'hiddenField' => false]) ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <button type="button" class="btn btn-success" id="confirm"><?= __('Copy') ?></button>
            </div>
        </div>
    </div>
</div>
<?= $this->element('modal/delete-confirm') ?>
<?php $this->Html->script('/vendors/jquery-maskedinput/dist/jquery.maskedinput.min.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(document).ready(function () {
        // time input
        $.mask.definitions['H'] = "[0-2]";
        $.mask.definitions['h'] = "[0-9]";
        $.mask.definitions['M'] = "[0-5]";
        $.mask.definitions['m'] = "[0-9]";
        $.mask.definitions['S'] = "[0-5]";
        $.mask.definitions['s'] = "[0-9]";

        $(document).on('focus', '.time', function () {
            $(this).mask('Hh:Mm:Ss');
        });

        $('#copy-options').on('show.bs.modal', function (e) {
            $(this).find('#confirm').on('click', function () {
                $href = $(e.relatedTarget).attr('href');

                $post = $('#form-copyoptions');
                $post.attr('action', $href);
                $post.submit();
            });
        });
    });
</script>
<?php $this->end(); ?>