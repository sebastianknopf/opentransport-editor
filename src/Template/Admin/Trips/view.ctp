<?php

use App\Model\Entity\Route;
use App\Model\Entity\Trip;

?>
<section class="content-header">
    <h1><?= __('View Trip') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view-clientable', ['identity' => $_IDENTITY, 'entity' => $trip, 'primaryKey' => $trip->trip_id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabTravelTimes" data-toggle="tab"><?= __('Travel Times') ?></a></li>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Route') ?></th>
                                                <td class="consume">
                                                    <?= h($trip->route != null ? $trip->route->route_long_name : $trip->route_id) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Variation') ?></th>
                                                <td class="consume"><?= h($trip->route_variation_name) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Service') ?></th>
                                                <td class="consume"><?= h($trip->service != null ? $trip->service->service_name : $trip->service_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Direction') ?></th>
                                                <td class="consume"><?= h($trip->getDirectionString($trip->direction_id)) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Shape') ?></th>
                                                <td class="consume"><?= h($trip->shape != null ? $trip->shape->shape_name : $trip->shape_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Trip Shortname') ?></th>
                                                <td class="consume"><?= h($trip->trip_short_name) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Headsign') ?></th>
                                                <td class="consume"><?= h($trip->trip_headsign) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Block ID') ?></th>
                                                <td class="consume"><?= h($trip->block_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Wheelchair') ?></th>
                                                <td>
                                                    <?php if($trip->wheelchair_accessible == 0): ?>
                                                        <span class="label label-default"><?= __('N/A') ?></span>
                                                    <?php elseif($trip->wheelchair_accessible == 1): ?>
                                                        <span class="label label-success"><?= __('Yes') ?></span>
                                                    <?php else: ?>
                                                        <span class="label label-danger"><?= __('No') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Bikes') ?></th>
                                                <td>
                                                    <?php if($trip->bikes_allowed == 0): ?>
                                                        <span class="label label-default"><?= __('N/A') ?></span>
                                                    <?php elseif($trip->bikes_allowed == 1): ?>
                                                        <span class="label label-success"><?= __('Yes') ?></span>
                                                    <?php else: ?>
                                                        <span class="label label-danger"><?= __('No') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div id="mapView" style="width:100%;height:600px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabTravelTimes">
                                <h3><?= __('Stop Times') ?></h3>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <thead>
                                                <th width="300"><?= __('Stop') ?></th>
                                                <th width="150"><?= __('Arrival Time') ?></th>
                                                <th width="150"><?= __('Departure Time') ?></th>
                                                <th width="200"><?= __('Entry') ?></th>
                                                <th><?= __('Exit') ?></th>
                                            </thead>
                                            <tbody>
                                                <?php foreach($trip->stop_times as $stop_time): ?>
                                                <tr>
                                                    <?php

                                                    $stopDisplayName = $stop_time->stop_id;

                                                    if ($stop_time->stop != null) {
                                                        $stopDisplayName = $stop_time->stop->stop_name;

                                                        if (!empty($stop_time->stop->stop_code)) {
                                                            $stopDisplayName .= ' (' . $stop_time->stop->stop_code . ')';
                                                        }
                                                    }

                                                    ?>
                                                    <td><?= $stopDisplayName ?></td>
                                                    <td><?= $stop_time->arrival_time->format('H:i:s') ?></td>
                                                    <td><?= $stop_time->departure_time->format('H:i:s') ?></td>
                                                    <td><?php switch($stop_time->pickup_type) { case 0: echo '<span class="label label-success">' . __('Yes') . '</span>'; break; case 1: echo '<span class="label label-danger">' . __('No') . '</span>'; break; case 3: echo '<span class="label label-warning">' . __('Demand') . '</span>'; break; } ?></td>
                                                    <td><?php switch($stop_time->drop_off_type) { case 0: echo '<span class="label label-success">' . __('Yes') . '</span>'; break; case 1: echo '<span class="label label-danger">' . __('No') . '</span>'; break; case 3: echo '<span class="label label-warning">' . __('Demand') . '</span>';; break; } ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <h3><?= __('Frequencies') ?></h3>
                                        <table class="table w-100">
                                            <thead>
                                            <th width="150"><?= __('Start Time') ?></th>
                                            <th width="150"><?= __('End Time') ?></th>
                                            <th width="200"><?= __('Headway') ?></th>
                                            <th><?= __('Exact Times') ?></th>
                                            </thead>
                                            <tbody>
                                            <?php foreach($trip->frequencies as $frequency): ?>
                                                <tr>
                                                    <td><?= $frequency->start_time->format('H:i:s') ?></td>
                                                    <td><?= $frequency->end_time->format('H:i:s') ?></td>
                                                    <td><?= $frequency->headway_min ?></td>
                                                    <td><?= $frequency->exact_times ? '<span class="label label-success">' . __('Yes') . '</span>' : '<span class="label label-danger">' . __('No') . '</span>' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
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
                                                <td class="consume"><?= h($trip->trip_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($trip->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($trip->modified) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Client') ?></th>
                                                <td><?= isset($trip->client) ? h($trip->client->longname) : h($trip->client_id) ?></td>
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
        /**
         * Map Support
         */
        let $stopMarkers = new Array();
        let $shapePolyline = null;
        let mapView = L.map('mapView').setView([51.133481, 10.018343], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map Data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
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

        new L.esri.Controls.Geosearch().addTo(mapView);
    });
</script>
<?php $this->end() ?>