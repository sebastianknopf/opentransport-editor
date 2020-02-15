<section class="content-header">
    <h1>
        <?php if($station->parent_station == null): ?>
            <?= __('View Station') ?>
        <?php else: ?>
            <?= __('View Stop') ?>
        <?php endif; ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view', ['identity' => $_IDENTITY, 'entity' => $station, 'primaryKey' => $station->stop_id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <?php if($station->parent_station == null): ?>
                                <li><a href="#tabStopdata" data-toggle="tab"><?= __('Stops') ?></a></li>
                            <?php endif; ?>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Code') ?></th>
                                                <td class="consume"><?= h($station->stop_code) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Name') ?></th>
                                                <td><?= h($station->stop_name) ?></td>
                                            </tr>
                                            <?php if($station->parent_station != null): ?>
                                            <tr>
                                                <th scope="row"><?= __('Platform Code') ?></th>
                                                <td><?= h($station->platform_code) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th scope="row"><?= __('Desc') ?></th>
                                                <td><?= h($station->stop_desc) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Wheelchair') ?></th>
                                                <td>
                                                    <?php if($station->wheelchair_boarding == 0): ?>
                                                        <span class="label label-default"><?= __('N/A') ?></span>
                                                    <?php elseif($station->wheelchair_boarding == 1): ?>
                                                        <span class="label label-success"><?= __('Yes') ?></span>
                                                    <?php else: ?>
                                                        <span class="label label-danger"><?= __('No') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php if($station->parent_station == null): ?>
                                            <tr>
                                                <th scope="row"><?= __('Num Stops') ?></th>
                                                <td><?= $this->Number->format(count($stops)) ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div id="mapView" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <?php if($station->parent_station == null): ?>
                                <div class="tab-pane" id="tabStopdata">
                                    <?php if(count($stops)): ?>
                                        <table class="w-100 table table-striped" cellpadding="0" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th scope="col" width="50"><?= $this->Paginator->sort('stop_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col"><?= $this->Paginator->sort('stop_code', __('Code')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col" class="consume"><?= $this->Paginator->sort('stop_name', __('Name')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col" class="consume"><?= $this->Paginator->sort('platform_code', __('Platform')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col"><?= $this->Paginator->sort('wheelchair_boarding', __('Wheelchair')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($stops as $stop): ?>
                                                <tr>
                                                    <td><?= h($stop->stop_id) ?></td>
                                                    <td><?= h($stop->stop_code) ?></td>
                                                    <td><?= h($stop->stop_name) ?></td>
                                                    <td><?= h($stop->platform_code) ?></td>
                                                    <td>
                                                        <?php if($stop->wheelchair_boarding == '0'): ?>
                                                            <span class="label label-default"><?= __('N/A') ?></span>
                                                        <?php elseif($stop->wheelchair_boarding == '1'): ?>
                                                            <span class="label label-success"><?= __('Yes') ?></span>
                                                        <?php else: ?>
                                                            <span class="label label-danger"><?= __('No') ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="actions text-right">
                                                        <div class="btn-group">
                                                            <?= $this->Html->link(__('View'), ['action' => 'view', $stop->stop_id], ['class' => 'btn btn-xs btn-default']) ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p class="alert alert-danger"><?= __('No assigned stops where found!') ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="tab-pane" id="tabMeta">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('ID') ?></th>
                                                <td class="consume"><?= h($station->stop_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($station->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($station->modified) ?></td>
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
<?php $this->Html->css('/vendors/leaflet/css/leaflet.css', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/leaflet.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        var mapView = L.map('mapView').setView([51.133481, 10.018343], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18
        }).addTo(mapView);

        // position marker object
        var lat = <?= $station->stop_lat; ?>;
        var lon = <?= $station->stop_lon; ?>;

        var stationIcon = new L.icon({
            iconUrl: '<?= $this->Url->image('Zeichen224.svg'); ?>',
            iconSize: [24, 24]
        });

        var stopIcon = new L.icon({
            iconUrl: '<?= $this->Url->image('Triangle.svg'); ?>',
            iconSize: [16, 16]
        });

        <?php foreach($stops as $stop): ?>
        // stop marker object
        L.marker([<?= $stop->stop_lat ?>, <?= $stop->stop_lon ?>], {icon: stopIcon}).addTo(mapView);
        <?php endforeach; ?>

        // add station icon as last one to display above the stop icons
        <?php if($station->parent_station != null): ?>
        L.marker([lat, lon], {icon: stopIcon}).addTo(mapView);
        <?php else: ?>
        L.marker([lat, lon], {icon: stationIcon}).addTo(mapView);
        <?php endif; ?>

        mapView.setView([lat, lon], 14);
    });
</script>
<?php $this->end(); ?>