<section class="content-header">
    <h1>
        <?php if($station->parent_station == null): ?>
            <?= __('Edit Station') ?>
        <?php else: ?>
            <?= __('Edit Stop') ?>
        <?php endif; ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($station) ?>
                <?= $this->Form->hidden('location_type', ['value' => $station->parent_station == null ? '1' : '0']) ?>
                <?= $this->Form->hidden('parent_station', ['value' => $station->parent_station == null ? '' : $station->parent_station]) ?>
                <?= $this->Form->hidden('stop_lat', ['id' => 'stopLat']) ?>
                <?= $this->Form->hidden('stop_lon', ['id' => 'stopLon']) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-edit') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <?php if($station->parent_station == null): ?>
                            <li><a href="#tabStopdata" data-toggle="tab"><?= __('Stops') ?></a></li>
                            <?php endif; ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('stop_code', ['class' => 'form-control']) ?>
                                        <?= $this->Form->control('stop_name', ['class' => 'form-control']) ?>
                                        <?php if($station->parent_station != null): ?>
                                            <?= $this->Form->control('platform_code', ['label' => __('Platform Code')]) ?>
                                        <?php endif; ?>
                                        <?= $this->Form->control('stop_desc', ['label' => __('Description'), 'required' => false]) ?>
                                        <?= $this->Form->label('wheelchair_boarding', __('Wheelchair')) ?><br />
                                        <?= $this->Form->radio('wheelchair_boarding', ['0' => __('N/A'), '1' => __('Yes'), '2' => __('No')], ['default' => '0']) ?>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->label(__('Position')) ?><br />
                                        <div id="mapView" style="width:100%;height:400px;cursor:crosshair;"></div>
                                        <?php if($this->Form->isFieldError('stop_lat')): ?>
                                            <div class="error-message"><?= $this->Form->error('stop_lat') ?></div>
                                        <?php elseif($this->Form->isFieldError('stop_lon')): ?>
                                            <div class="error-message"><?= $this->Form->error('stop_lon') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($station->parent_station == null): ?>
                                <div class="tab-pane" id="tabStopdata">
                                    <div class="table-responsive">
                                        <table class="w-100 table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col" width="50"><?= $this->Paginator->sort('stop_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col"><?= $this->Paginator->sort('stop_code', __('Code')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col"><?= $this->Paginator->sort('stop_name', __('Name')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col"><?= $this->Paginator->sort('stop_name', __('Platform')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col"><?= $this->Paginator->sort('wheelchair_boarding', __('Wheelchair')) ?> <i class="fa fa-sort ml-2"></i></th>
                                                <th scope="col" class="text-right">
                                                    <?php if($_IDENTITY->can('add', $station)): ?>
                                                        <a href="<?= $this->Url->build(['action' => 'add', $station->stop_id]) ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </th>
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
                                                            <?php if($_IDENTITY->can('view', $stop)): ?>
                                                                <?= $this->Html->link(__('View'), ['action' => 'view', $stop->stop_id], ['class' => 'btn btn-xs btn-default']) ?>
                                                            <?php endif; ?>
                                                            <?php if($_IDENTITY->can('edit', $stop)): ?>
                                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $stop->stop_id], ['class' => 'btn btn-xs btn-default']) ?>
                                                            <?php endif; ?>
                                                            <?php if($_IDENTITY->can('delete', $stop)): ?>
                                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $stop->stop_id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= $this->Html->link(__('Cancel'), (isset($_REDIRECT) && $_REDIRECT != null) ? $_REDIRECT : ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
<?= $this->element('modal/delete-confirm') ?>
<?php $this->Html->css('/vendors/leaflet/css/leaflet.css', ['block' => true]); ?>
<?php $this->Html->css('/vendors/leaflet/css/esri-leaflet-geocoding.css', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet-geocoding.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        var mapView = L.map('mapView').setView([51.133481, 10.018343], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(mapView);

        // position marker object
        var positionMarker = null;

        // stop marker icon
        <?php if ($station->parent_station == null): ?>
        var stopIcon = L.icon({
           iconUrl: '<?= $this->Url->image('Zeichen224.svg') ?>',
           iconSize: [24, 24]
        });
        <?php else: ?>
        var stopIcon = L.icon({
           iconUrl: '<?= $this->Url->image('Triangle.svg') ?>',
           iconSize: [24, 24]
        });
        <?php endif; ?>

        // add position if specified yet
        if($('#stopLat').val() != 0 && $('#stopLon').val() != 0) {
            var lat = $('#stopLat').val();
            var lon = $('#stopLon').val();

            positionMarker = new L.Marker(new L.LatLng(lat, lon), {'icon': stopIcon});
            positionMarker.addTo(mapView);

            mapView.setView([lat, lon], 14);
        }

        // tooltip functions
        var tooltip = $('<div>').css({
            position: 'absolute',
            fontSize: 14,
            opacity: 0.5,
            padding: 10,
            background: '#333',
            color: '#fff',
            zIndex: 5000,
            border: '1px dashed #999'
        });

        tooltip.text('<?= __('Click on the map to set the position.') ?>');

        $('#mapView').mouseover(function () {
            tooltip.appendTo('body');
            $('#mapView').mousemove(function (e) {
                tooltip.css('left', e.clientX + 20 + 'px');
                tooltip.css('top', e.clientY - 10 + 'px');
            });
        });

        $('#mapView').mouseout(function(e) {
            tooltip.remove();
        });

        // update position on map click
        mapView.on('click', function (e){
            if(positionMarker != null) {
                mapView.removeLayer(positionMarker);
            }

            positionMarker = new L.marker(e.latlng, {'icon': stopIcon});
            positionMarker.addTo(mapView);

            $('#stopLat').val(e.latlng.lat);
            $('#stopLon').val(e.latlng.lng);
        });

        // add geosearch control
        new L.esri.Geocoding.Geosearch().addTo(mapView);
    });
</script>
<?php $this->end(); ?>