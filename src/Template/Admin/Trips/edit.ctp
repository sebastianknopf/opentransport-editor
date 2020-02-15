<section class="content-header">
    <h1><?= __('Edit Trip') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($trip) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-edit') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabStopTimes" data-toggle="tab"><?= __('Stop Times') ?></a></li>
                            <!--<li><a href="#tabGraphicTimes" data-toggle="tab"><?= __('Graphic Times') ?></a></li>-->
                            <li><a href="#tabFrequencies" data-toggle="tab"><?= __('Frequencies') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('route_id', ['label' => __('Route'), 'options' => $routes]) ?>
                                        <?= $this->Form->control('service_id', ['label' => __('Service'), 'options' => $services]) ?>
                                        <?= $this->Form->control('direction_id', ['label' => __('Direction'), 'options' => $trip->getDirections(), 'empty' => __('Select...')]) ?>
                                        <?= $this->Form->control('shape_id', ['label' => __('Shape'), 'options' => $shapes, 'empty' => __('Select...')]) ?>
                                        <?= $this->Form->control('trip_short_name', ['label' => __('Trip Shortname')]) ?>
                                        <?= $this->Form->control('trip_headsign', ['label' => __('Headsign')]) ?>
                                        <?= $this->Form->control('block_id', ['label' => __('Block ID'), 'type' => 'text']) ?>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?= $this->Form->label('wheelchair_accessible', __('Wheelchair')) ?>
                                                <?= $this->Form->radio('wheelchair_accessible', ['0' => __('N/A'), '1' => __('Yes'), '2' => __('No')], ['default' => '0']) ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <?= $this->Form->label('bikes_allowed', __('Bikes')) ?>
                                                <?= $this->Form->radio('bikes_allowed', ['0' => __('N/A'), '1' => __('Yes'), '2' => __('No')], ['default' => '0']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div id="mapView" style="width:100%;height:600px;cursor:crosshair;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabStopTimes">
                                <div class="row">
                                    <div class="col-lg-9">
                
                                    </div>
                                    <div class="col-lg-2">
                                        <?= $this->Form->control('stop_autocomplete', ['label' => false, 'placeholder' => __('Stop Name'), 'style' => 'width:250px', 'id' => 'stop-autocomplete-input']) ?>
                                    </div>
                                    <div class="col-lg-1 text-right">
                                        <?= $this->Form->button(__('Add'), ['class' => 'btn btn-success', 'id' => 'stop-autocomplete-button', 'type' => 'button']) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 table-responsive">
                                        <table class="table w-100">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th><?= __('Stop') ?></th>
                                                <th class="text-center"><?= __('Arrival Time') ?></th>
                                                <th class="text-center"><?= __('Departure Time') ?></th>
                                                <th class="text-center"><?= __('Entry') ?></th>
                                                <th class="text-center"><?= __('Exit') ?></th>
                                                <th class="text-right"><?= __('Actions') ?></th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblStopTimesList">
                                            <?php for($f = 0; $f < count($trip->stop_times); $f++): ?>
                                                <?php

                                                $stop_time = $trip->stop_times[$f];
                                                $stopDisplayName = $stop_time->stop_id;

                                                if ($stop_time->stop != null) {
                                                    $stopDisplayName = $stop_time->stop->stop_name;

                                                    if (!empty($stop_time->stop->stop_code)) {
                                                        $stopDisplayName .= ' (' . $stop_time->stop->stop_code . ')';
                                                    }
                                                }

                                                ?>
                                                <?= $this->element('trips/stop-time', [
                                                    'index' => $f,
                                                    'id' => $trip->stop_times[$f]->id,
                                                    'stop_id' => $trip->stop_times[$f]->stop_id,
                                                    'stop_name' => $stopDisplayName,
                                                    'arrival_time' => $trip->stop_times[$f]->arrival_time->format('H:i:s'),
                                                    'departure_time' => $trip->stop_times[$f]->departure_time->format('H:i:s')
                                                ]) ?>
                                            <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- graphic timetable disabled -->
                            <!--<div class="tab-pane" id="tabGraphicTimes">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('graph_zoom_level', ['type' => 'range', 'id' => 'rngGraphZoomLevel', 'label' => __('Graph Zoomlevel {0}%', 100), 'min' => 15, 'max' => 100, 'value' => 100]) ?>
                                    </div>
                                </div>
                                <div id="graphWrapper" style="width:100%;height:560px;padding:10px;overflow-y:scroll;">
                                    <div id="graphContainer" style="width:100%;height:5000px;">
                                        <canvas id="cnvGraph" style="width:100%;"></canvas>
                                    </div>
                                </div>
                                <?php $this->append('script'); ?>
                                <?= $this->element('editor/graphic-timetable') ?>
                                <?php $this->end(); ?>
                            </div>-->
                            <div class="tab-pane" id="tabFrequencies">
                                <div class="row">
                                    <div class="col-lg-12 table-responsive">
                                        <table class="table w-100">
                                            <thead>
                                            <tr>
                                                <th class="text-center"><?= __('Start Time') ?></th>
                                                <th class="text-center"><?= __('End Time') ?></th>
                                                <th class="text-center"><?= __('Headway') ?></th>
                                                <th class="text-center"><?= __('Exact Times') ?></th>
                                                <th class="text-right">
                                                    <a href="#" id="btnAddFrequency">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblFrequencyList">
                                            <?php for($f = 0; $f < count($trip->frequencies); $f++): ?>
                                                <?= $this->element('trips/frequency', [
                                                    'index' => $f,
                                                    'id' => $trip->frequencies[$f]->id,
                                                    'start_time' => $trip->frequencies[$f]->start_time->format('H:i:s'),
                                                    'end_time' => $trip->frequencies[$f]->end_time->format('H:i:s')
                                                ]) ?>
                                            <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script id="stopTimeTemplate" type="text/x-underscore-template">
                            <?= $this->element('trips/stop-time') ?>
                        </script>
                        <script id="frequencyTemplate" type="text/x-underscore-template">
                            <?= $this->element('trips/frequency') ?>
                        </script>
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
<?php $this->Html->css('/vendors/easy-autocomplete/dist/easy-autocomplete.min.css', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/leaflet/js/esri-leaflet-geocoding.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/easy-autocomplete/dist/jquery.easy-autocomplete.min.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/jquery-maskedinput/dist/jquery.maskedinput.min.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/underscore/underscore-min.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
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

        // autocomplete
        var $stopId = 0, $stopName = '', $stopCode = '', $stopLat = 0, $stopLon = 0;
        $('#stop-autocomplete-input').easyAutocomplete({
            url: function (phrase) {
                return '<?= $this->Url->build(['controller' => 'Ajax', 'action' => 'selectStopsByNameOrCode']) ?>?q=' + phrase;
            },
            list: {
                onSelectItemEvent: function() {
                    var stop = $('#stop-autocomplete-input').getSelectedItemData();

                    $stopId = stop.stop_id;
                    $stopName = stop.stop_name + (stop.stop_code != '' ? ' (' + stop.stop_code + ')' : null);
                    $stopCode = stop.stop_code;
                    $stopLat = stop.stop_lat;
                    $stopLon = stop.stop_lon;
                }
            },
            getValue: 'label'
        });

        /**
         * Dynamic Shape Loading
         */
        $('#shape-id').change(function () {
            if($shapePolyline != null) {
                mapView.removeLayer($shapePolyline);
            }

            $.getJSON('<?= $this->Url->build(['controller' => 'Ajax', 'action' => 'selectShapePointsById']) ?>?id=' + $('#shape-id').val(), function (shapePoints) {
                if(shapePoints != null) {
                    addShapePolyline(shapePoints);
                }
            });
        });

        /**
         * Stop Times Functions
         */

        var stopTimeItem = _.template($('#stopTimeTemplate').remove().html());
        let stopTimesCount = $('#tblStopTimesList').children().length;

        // add action
        $('#stop-autocomplete-button').click(function () {
            $('#stop-autocomplete-input').val('');

            let idSuffix = new Date().getTime();

            if($stopId != 0) {
                let sItem = $(stopTimeItem({index:stopTimesCount++, stop_id:$stopId, stop_name:$stopName}));
                sItem.find('input.arrival').attr('id', 'arrival-' + idSuffix).data('stop-code', $stopCode).val('00:00:00').change(function () {
                    if($(this).val().length > 5) {
                        updateGraphicTimetable();
                    }
                });
                sItem.find('input.departure').attr('id', 'departure-' + idSuffix).data('stop-code', $stopCode).val('00:00:00').change(function () {
                    if($(this).val().length > 5) {
                        updateGraphicTimetable();
                    }
                });
                sItem.appendTo('#tblStopTimesList');

                addStopMarker($stopId, $stopLat, $stopLon);
                updateGraphicTimetable();
            }
        });

        // up and down action
        $(document).on('click', '.lnk-up', function (e) {
            e.preventDefault();

            var thisRow = $(this).closest('tr');
            var prevRow = thisRow.prev();
            if (prevRow.length) {
                prevRow.before(thisRow);
            }

            updateGraphicTimetable();
        });

        $(document).on('click', '.lnk-down', function (e) {
            e.preventDefault();

            var thisRow = $(this).closest('tr');
            var nextRow = thisRow.next();
            if (nextRow.length) {
                nextRow.after(thisRow);
            }

            updateGraphicTimetable();
        });

        // delete action
        $(document).on('click', '.lnk-delete-stoptime', function (e) {
            e.preventDefault();
            let link = $(this);

            let stopId = $(this).data('stoptime-id');
            $(this).closest('tr').remove();
            if(stopId) {
                $.post('<?= $this->Url->build(['controller' => 'Ajax', 'action' => 'deleteStopTimeById']) ?>/' + stopId).done(function () {
                    link.closest('tr').remove();
                    stopTimesCount--;
                });
            } else {
                link.closest('tr').remove();
                stopTimesCount--;
            }

            removeStopMarker(stopId);
            updateGraphicTimetable();
        });

        /**
         * Frequency Functions
         */

        var frequencyItem = _.template($('#frequencyTemplate').remove().html());
        var frequenciesCount = $('#tblFrequencyList').children().length;

        // add action
        $('#btnAddFrequency').on('click', function (e) {
            var fItem = $(frequencyItem({index: frequenciesCount++}));
            fItem.appendTo('#tblFrequencyList');
        });

        // delete action
        $(document).on('click', '.lnk-delete-frequency', function (e) {
            e.preventDefault();
            let link = $(this);

            let frequencyId = $(this).data('frequency-id');
            if(frequencyId) {
                $.post('<?= $this->Url->build(['controller' => 'Ajax', 'action' => 'deleteFrequencyById']) ?>/' + frequencyId).done(function () {
                    link.closest('tr').remove();
                    frequenciesCount--;
                });
            } else {
                link.closest('tr').remove();
                frequenciesCount--;
            }
        });

        /**
         * Map Support
         */
        let $stopMarkers = new Array();
        let $shapePolyline = null;
        let mapView = L.map('mapView').setView([51.133481, 10.018343], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map Data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }).addTo(mapView);

        var stationIcon = new L.icon({
            iconUrl: '<?= $this->Url->image('Zeichen224.svg'); ?>',
            iconSize: [24, 24]
        });

        // add a stop marker with stop id, lat and lng
        function addStopMarker(id, lat, lng) {
            $stopMarkers[id] = new L.marker([lat, lng], {icon: stationIcon}).addTo(mapView);
            zoomMapToStopMarkers($stopMarkers);
        }

        // remove a stop marker by stop id
        function removeStopMarker(id) {
            mapView.removeLayer($stopMarkers[id]);
            delete $stopMarkers[id];

            zoomMapToStopMarkers($stopMarkers);
        }

        // zooms the map to view all stops
        function zoomMapToStopMarkers(stopMarkerList) {
            let latLngList = new Array();
            $.each(stopMarkerList, function (index, stop) {
                if(stop != undefined) { // filter on stops which are not null
                    latLngList.push(stop.getLatLng());
                }
            });

            mapView.fitBounds(latLngList);
        }

        // add existing stops to map
        <?php foreach($trip->stop_times as $stop_time): ?>
        addStopMarker(<?= $stop_time->stop->stop_id ?>, <?= $stop_time->stop->stop_lat ?>, <?= $stop_time->stop->stop_lon ?>);
        <?php endforeach; ?>

        // adds a polyline by given lat lngs
        function addShapePolyline(latLngList) {
            $shapePolyline = new L.polyline(latLngList, {color: 'red', weight: 3}).addTo(mapView);
            mapView.fitBounds(latLngList);
        }

        // add existing shape polyline
        <?php if($trip->shape != null): ?>
        <?php $shape_points = $trip->shape->decode($trip->shape->shape_polyline); ?>
        addShapePolyline([<?= implode(', ', array_map(function ($entry) { return '[' . implode(', ', $entry) . ']'; }, $shape_points)) ?>]);
        <?php endif; ?>

        // add geosearch control
        new L.esri.Geocoding.Geosearch().addTo(mapView);

        /**
         * GraphicTimetable
         */

        /*$('#rngGraphZoomLevel').change(function () {
            $('#graphContainer').height(5000.0 * ($(this).val() / 100.0));
            $('label[for="rngGraphZoomLevel"]').text(('<?= __('Graph Zoomlevel {0}%') ?>').replace('{0}', $(this).val()));
            updateGraphicTimetable();
        });*/

        /*let ctx = $('#cnvGraph');
        let graphicTimeTable = new GraphicTimeTable(ctx);*/

        function updateGraphicTimetable() {
            /*graphicTimeTable = new GraphicTimeTable(ctx);
            let tripIndex = graphicTimeTable.addTrip('', 'red');

            $.each($('#tblStopTimesList').find('tr'), function (index, row) {
                let inputArrival = $(this).find('input.arrival');
                let inputDeparture = $(this).find('input.departure');

                graphicTimeTable.addStop(inputArrival.data('stop-code'));

                graphicTimeTable.addStopTime(
                    tripIndex,
                    inputArrival.data('stop-code'),
                    inputArrival.val(),
                    inputDeparture.val(),
                    '#' + inputArrival.attr('id'),
                    '#' + inputDeparture.attr('id')
                );

            });

            graphicTimeTable.updateGraph();*/
        }
    });
</script>
<?php $this->end() ?>