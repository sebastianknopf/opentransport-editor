<?php

use App\Model\Entity\Route;
use App\Model\Entity\Trip;

?>
<section class="content-header">
    <h1><?= __('View Route') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view-clientable', ['identity' => $_IDENTITY, 'entity' => $route, 'primaryKey' => $route->route_id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Agency') ?></th>
                                                <td class="consume"><?= h($route->agency != null ? $route->agency->agency_name : $route->agency_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Type') ?></th>
                                                <td class="consume"><?= h(Route\Type::getRouteTypes()[$route->route_type]) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Shortname') ?></th>
                                                <td class="consume"><?= h($route->route_short_name) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Longname') ?></th>
                                                <td class="consume"><?= h($route->route_long_name) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Num Variations') ?></th>
                                                <td class="consume"><?= count($route->route_variations) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('URL') ?></th>
                                                <td class="consume">
                                                    <a href="<?= $route->route_url ?>" target="_blank">
                                                        <?= h($route->route_url) ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Description') ?></th>
                                                <td class="consume"><?= h($route->route_desc) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Colors') ?></th>
                                                <td class="consume">
                                                    <span class="label" style="font-size:1.2em;background-color:<?= $route->route_color ?>;color:<?= $route->route_text_color; ?>">Sample Text</span>
                                                </td>
                                            </tr>
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
                                                <td class="consume"><?= h($route->route_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($route->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($route->modified) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Client') ?></th>
                                                <td><?= isset($route->client) ? h($route->client->longname) : h($route->client_id) ?></td>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Related Trips') ?></h3>
                    <div class="box-tools pull-right">
                        <?php  if(isset($_IDENTITY) && $_IDENTITY->can('add', Trip::getInstance())): ?>
                        <button type="button" class="btn btn-box-tool">
                            <a href="<?= $this->Url->build(['controller' => 'Trips', 'action' => 'add', $route->route_id]) ?>">
                                <i class="fa fa-plus"></i>
                            </a>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(count($route->trips)): ?>
                    <div class="row">
                        <?= $this->Form->create(null, ['valueSources' => 'query', 'id' => 'form-filter-trips']) ?>
                        <div class="col-lg-1">
                            <?= $this->Form->control('direction_id', ['label' => false, 'options' => ['0' => __('Outbound'), '1' => __('Inbound')], 'empty' => __('Direction'), 'class' => 'form-control auto-submit']) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $this->Form->control('route_variation_id', ['label' => false, 'options' => $route->route_variations, 'empty' => __('Variation'), 'class' => 'form-control auto-submit']) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $this->Form->control('service_id', ['label' => false, 'options' => $route->route_services, 'empty' => __('Service'), 'class' => 'form-control auto-submit']) ?>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                    <div class="table-responsive">
                        <table class="w-100 table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" width="50"><?= $this->Paginator->sort('trip_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('route_variation_id', __('Variation')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('start_time', __('Start Time')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('end_time', __('End Time')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('service_id', __('Service')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('trip_short_name', __('Shortname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('trip_headsign', __('Headsign')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('wheelchair_accessible', __('Wheelchair')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('bikes_allowed', __('Bikes')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($route->trips as $trip): ?>
                                <tr>
                                    <td><?= h($trip->trip_id) ?></td>
                                    <td><?= h($trip->route_variation_name) ?></td>
                                    <td><?= h($trip->start_time->format('H:i:s')) ?></td>
                                    <td><?= h($trip->end_time->format('H:i:s')) ?></td>
                                    <td><?= isset($trip->service) ? h($trip->service->service_name) : h($trip->service_id) ?></td>
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
                                                <?= $this->Html->link(__('View'), ['controller' => 'Trips', 'action' => 'view', $trip->trip_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-danger"><?= __('No related trips found!') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->append('script'); ?>
<script>
    $(function () {
        // auto submit action for filter form
        $('.auto-submit').change(function () {
            $('#form-filter-trips').submit();
        });
    });
</script>
<?php $this->end(); ?>
