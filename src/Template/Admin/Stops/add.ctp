<section class="content-header">
    <h1>
        <?php if($parent_station == null): ?>
            <?= __('Add Station') ?>
        <?php else: ?>
            <?= __('Add Stop') ?>
        <?php endif; ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($station) ?>
                <?= $this->Form->hidden('location_type', ['value' => $parent_station == null ? '1' : '0']) ?>
                <?= $this->Form->hidden('parent_station', ['value' => $parent_station == null ? '' : $parent_station]) ?>
                <?= $this->Form->hidden('stop_lat', ['id' => 'stopLat']) ?>
                <?= $this->Form->hidden('stop_lon', ['id' => 'stopLon']) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-add') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('stop_code') ?>
                                        <?= $this->Form->control('stop_name') ?>
                                        <?php if($parent_station != null): ?>
                                        <?= $this->Form->control('platform_code', ['label' => __('Platform Code')]) ?>
                                        <?php endif; ?>
                                        <?= $this->Form->control('stop_desc', ['label' => __('Description'), 'required' => false]) ?>
                                        <?= $this->Form->label('wheelchair_boarding', __('Wheelchair')) ?><br />
                                        <?= $this->Form->radio('wheelchair_boarding', ['0' => __('N/A'), '1' => __('Yes'), '2' => __('No')], ['default' => '0']) ?>
                                        <?php if($parent_station == null): ?>
                                        <?= $this->Form->label(__('Stop Pair')) ?>
                                        <?= $this->Form->control('create_stops', ['label' => __('Create Stops'), 'type' => 'checkbox']) ?>
                                        <?php endif; ?>
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
<?php $this->Html->css('/vendors/leaflet/css/leaflet.css', ['block' => true]); ?>
<?php $this->Html->css('/vendors/leaflet/css/esri-leaflet-geocoding.css', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet-geocoding.js', ['block' => true]); ?>
<?php $this->append('script') ?>
<script>
    $(function () {
        var mapView = L.map('mapView').setView([51.133481, 10.018343], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(mapView);

        // position marker object
        var positionMarker = null;

        // add position if specified yet
        if($('#stopLat').val() != 0 && $('#stopLon').val() != 0) {
            var lat = $('#stopLat').val();
            var lon = $('#stopLon').val();

            positionMarker = new L.Marker(new L.LatLng(lat, lon));
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

        // stop marker icon
        <?php if ($parent_station == null): ?>
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