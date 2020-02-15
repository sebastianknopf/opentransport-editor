<section class="content-header">
    <h1><?= __('Edit Shape') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($shape) ?>
                <?php for($i = 0; $i < count($shape->points); $i++): ?>
                    <?= $this->Form->hidden('shape_polyline.' . $i . '.lat', ['value' => $shape->points[$i]['lat'], 'class' => 'shape_polyline']) ?>
                    <?= $this->Form->hidden('shape_polyline.' . $i . '.lon', ['value' => $shape->points[$i]['lon'], 'class' => 'shape_polyline']) ?>
                <?php endfor; ?>
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
                                    <div class="col-lg-12">
                                        <?= $this->Form->control('shape_name', ['class' => 'form-control', 'type' => 'text', 'label' => __('Name')]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $this->Form->label(__('Shape')) ?><br />
                                        <div id="mapView" style="width:100%;height:450px;"></div>
                                        <div id="toolTip" style="display: none; position: absolute;background: #666;color: white;opacity: 0.5;padding: 10px;border: 1px dashed #999;font-family: sans-serif;font-size: 12px;height: 20px;line-height: 20px;z-index: 1000;"></div>
                                        <?php if($this->Form->isFieldError('shape_polyline')): ?>
                                            <div class="error-message"><?= $this->Form->error('shape_polyline') ?></div>
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
<?php $this->Html->script('/vendors/leaflet/js/leaflet-editable.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet-geocoding.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        // mapview setup
        var mapView = L.map('mapView', {editable: true}).setView([51.133481, 10.018343], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(mapView);

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

        tooltip.text('<?= __('Click Strg+Point to continue the line.') ?>');

        $('#mapView').mouseover(function () {
            tooltip.appendTo('body');
            $('#mapView').mousemove(function (e) {
                tooltip.css('left', e.clientX + 20 + 'px');
                tooltip.css('top', e.clientY - 10 + 'px');
            });
        });

        mapView.on('editable:drawing:click', function (e) {
            if (e.layer.editor._drawnLatLngs.length < 1) {
                tooltip.text('<?= __('Click on the map to continue line.') ?>');
            } else {
                tooltip.text('<?= __('Click on last point to finish line.') ?>');
            }
        });

        mapView.on('editable:vertex:ctrlclick', function (e) {
            tooltip.text('<?= __('Click on last point to finish line.') ?>');
            e.vertex.continue();
        });

        mapView.on('editable:drawing:end', function () {
            tooltip.text('<?= __('Click Strg+Point to continue the line.') ?>');
            tooltip.remove();
        });

        $('#mapView').mouseout(function(e) {
            tooltip.remove();
        });

        <?php $latLngList = array(); ?>
        <?php foreach($shape->points as $point): ?>
        <?php array_push($latLngList, 'new L.LatLng(' . $point['lat'] . ', ' . $point['lon'] . ')'); ?>
        <?php endforeach; ?>
        // draw existing polyline and enable continuing
        var pointList = [<?= implode(', ', $latLngList) ?>];
        var polyline = new L.Polyline(pointList);
        polyline.addTo(mapView);

        mapView.fitBounds(polyline.getBounds());

        polyline.enableEdit();

        // update hidden inputs with coordinates after editing a feature
        mapView.on('editable:editing', function () {
            $('.shape_polyline').remove();
            $.each(polyline.getLatLngs(), function (index, latLng) {
                $('<input>').attr({type: 'hidden', name: 'shape_polyline[' + index + '][lat]'}).val(latLng.lat).addClass('shape_polyline').appendTo('form');
                $('<input>').attr({type: 'hidden', name: 'shape_polyline[' + index + '][lon]'}).val(latLng.lng).addClass('shape_polyline').appendTo('form');
            });
        });

        // add geosearch control
        new L.esri.Geocoding.Geosearch().addTo(mapView);
    });
</script>
<?php $this->end(); ?>
