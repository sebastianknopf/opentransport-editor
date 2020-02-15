<?php

use Cake\Core\Configure;
use Cake\Routing\Router;

$this->loadHelper('Markdown.Markdown');

?>
<?php $this->append('title', Configure::read('Theme.title') . '.Info') ?>
<?= $this->Html->css('/vendors/leaflet/css/leaflet.css', ['block' => true]) ?>
<?php $this->append('css'); ?>
<style>
html, body {
    height: 100%;
    font-family: Arial;
}

#mobileMenu {
    z-index: 9999;
    margin-top: 47px;
}

div[data-role=app] {
    height: 100%;
    padding-top: 47px;
    padding-bottom: 46px;
}

div[data-role=page] {
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    display: none;
}

#mapView {
    height: 100%;
    z-index: 0;
}

li.tripItem {
    padding: 0;
    border-bottom: 1px dashed #607d8b;
}

li.tripItem:last-child {
    border: 0;
}

p.tripShortDetails b {
    color: #009688;
}

ul.timeLine {
    width: 450px;
    list-style: none;
    padding: 16px 32px;
    margin: 16px;
    border-left: 3px solid #009688;
    line-height: 1.4em;
}

ul.timeLine li {
    position: relative;
    padding-top: 0px !important;
    padding-bottom: 6px;
    margin-bottom: 18px;
    border: none;
}

ul.timeLine li:last-child {
    margin: 0;
}

ul.timeLine li::after {
    position: absolute;
    display: block;
    border-radius: 50%;
    box-shadow: 0 0 0 4px #009688;
    background: #fff;
    left: -38px;
    top: 8px;
    width: 8px;
    height: 8px;
    content: "";
}

ul.timeLine li p {
    position: relative;
    display: block;
    padding: 0;
    margin: 0;
}

ul.timeLine div.timeItem {
    display: inline-block;
}

ul.timeLine div.textItem {
    display: inline-block;
    position: absolute;
    left: 85px;
}
</style>
<?php $this->end(); ?>

<?php $this->append('header'); ?>
<a id="lnkToggleMenu" class="w3-bar-item w3-button w3-padding-large w3-hide-large" href="javascript:void(0)" onClick="mobileMenu()">
    <i class="fa fa-bars"></i>
</a>
<a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-small" href="#pMap"><?= __('Map') ?></a>
<a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-small" href="#pSearch"><?= __('Search') ?></a>
<!--<a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-small" href="#pAlerts"><?= __('Alerts') ?></a>-->
<div class="w3-bar-item w3-padding-large">
    <b><?= Configure::read('Theme.title') ?>.Info</b>
</div>
<a class="w3-bar-item w3-button w3-padding-large w3-right" href="#pSettings">
    <i class="fa fa-cog"></i>
</a>
<?php $this->end(); ?>

<?php $this->append('mobileMenu'); ?>
<a class="w3-bar-item w3-button w3-padding-large" href="#pMap" onClick="mobileMenu()"><?= __('Overview Map') ?></a>
<a class="w3-bar-item w3-button w3-padding-large" href="#pSearch" onClick="mobileMenu()"><?= __('Route Search') ?></a>
<!--<a class="w3-bar-item w3-button w3-padding-large" href="#pAlerts" onClick="mobileMenu()"><?= __('Service Alerts') ?></a>-->
<a id="lnkRequestLocation" class="w3-bar-item w3-button w3-padding-large" href="#" onClick="mobileMenu()"><?= __('Current Location') ?></a>
<?php $this->end(); ?>


<!-- Pages Section -->
<!-- Loading Page -->
<div id="pLoading" data-role="page">
    <div class="w3-display-container" style="height:100%;">
        <div class="w3-display-middle">
            <img width="35" height="35"src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHdpZHRoPSI0MHB4IiBoZWlnaHQ9IjQwcHgiIHZpZXdCb3g9IjAgMCA0MCA0MCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWw6c3BhY2U9InByZXNlcnZlIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEuNDE0MjE7IiB4PSIwcHgiIHk9IjBweCI+CiAgICA8ZGVmcz4KICAgICAgICA8c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWwogICAgICAgICAgICBALXdlYmtpdC1rZXlmcmFtZXMgc3BpbiB7CiAgICAgICAgICAgICAgZnJvbSB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIC13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoLTM1OWRlZykKICAgICAgICAgICAgICB9CiAgICAgICAgICAgIH0KICAgICAgICAgICAgQGtleWZyYW1lcyBzcGluIHsKICAgICAgICAgICAgICBmcm9tIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKC0zNTlkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICB9CiAgICAgICAgICAgIHN2ZyB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSA1MCU7CiAgICAgICAgICAgICAgICAtd2Via2l0LWFuaW1hdGlvbjogc3BpbiAxLjVzIGxpbmVhciBpbmZpbml0ZTsKICAgICAgICAgICAgICAgIC13ZWJraXQtYmFja2ZhY2UtdmlzaWJpbGl0eTogaGlkZGVuOwogICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBzcGluIDEuNXMgbGluZWFyIGluZmluaXRlOwogICAgICAgICAgICB9CiAgICAgICAgXV0+PC9zdHlsZT4KICAgIDwvZGVmcz4KICAgIDxnIGlkPSJvdXRlciI+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwwQzIyLjIwNTgsMCAyMy45OTM5LDEuNzg4MTMgMjMuOTkzOSwzLjk5MzlDMjMuOTkzOSw2LjE5OTY4IDIyLjIwNTgsNy45ODc4MSAyMCw3Ljk4NzgxQzE3Ljc5NDIsNy45ODc4MSAxNi4wMDYxLDYuMTk5NjggMTYuMDA2MSwzLjk5MzlDMTYuMDA2MSwxLjc4ODEzIDE3Ljc5NDIsMCAyMCwwWiIgc3R5bGU9ImZpbGw6YmxhY2s7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNNS44NTc4Niw1Ljg1Nzg2QzcuNDE3NTgsNC4yOTgxNSA5Ljk0NjM4LDQuMjk4MTUgMTEuNTA2MSw1Ljg1Nzg2QzEzLjA2NTgsNy40MTc1OCAxMy4wNjU4LDkuOTQ2MzggMTEuNTA2MSwxMS41MDYxQzkuOTQ2MzgsMTMuMDY1OCA3LjQxNzU4LDEzLjA2NTggNS44NTc4NiwxMS41MDYxQzQuMjk4MTUsOS45NDYzOCA0LjI5ODE1LDcuNDE3NTggNS44NTc4Niw1Ljg1Nzg2WiIgc3R5bGU9ImZpbGw6cmdiKDIxMCwyMTAsMjEwKTsiLz4KICAgICAgICA8L2c+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwzMi4wMTIyQzIyLjIwNTgsMzIuMDEyMiAyMy45OTM5LDMzLjgwMDMgMjMuOTkzOSwzNi4wMDYxQzIzLjk5MzksMzguMjExOSAyMi4yMDU4LDQwIDIwLDQwQzE3Ljc5NDIsNDAgMTYuMDA2MSwzOC4yMTE5IDE2LjAwNjEsMzYuMDA2MUMxNi4wMDYxLDMzLjgwMDMgMTcuNzk0MiwzMi4wMTIyIDIwLDMyLjAxMjJaIiBzdHlsZT0iZmlsbDpyZ2IoMTMwLDEzMCwxMzApOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksMjguNDkzOUMzMC4wNTM2LDI2LjkzNDIgMzIuNTgyNCwyNi45MzQyIDM0LjE0MjEsMjguNDkzOUMzNS43MDE5LDMwLjA1MzYgMzUuNzAxOSwzMi41ODI0IDM0LjE0MjEsMzQuMTQyMUMzMi41ODI0LDM1LjcwMTkgMzAuMDUzNiwzNS43MDE5IDI4LjQ5MzksMzQuMTQyMUMyNi45MzQyLDMyLjU4MjQgMjYuOTM0MiwzMC4wNTM2IDI4LjQ5MzksMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxMDEsMTAxLDEwMSk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMy45OTM5LDE2LjAwNjFDNi4xOTk2OCwxNi4wMDYxIDcuOTg3ODEsMTcuNzk0MiA3Ljk4NzgxLDIwQzcuOTg3ODEsMjIuMjA1OCA2LjE5OTY4LDIzLjk5MzkgMy45OTM5LDIzLjk5MzlDMS43ODgxMywyMy45OTM5IDAsMjIuMjA1OCAwLDIwQzAsMTcuNzk0MiAxLjc4ODEzLDE2LjAwNjEgMy45OTM5LDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoMTg3LDE4NywxODcpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTUuODU3ODYsMjguNDkzOUM3LjQxNzU4LDI2LjkzNDIgOS45NDYzOCwyNi45MzQyIDExLjUwNjEsMjguNDkzOUMxMy4wNjU4LDMwLjA1MzYgMTMuMDY1OCwzMi41ODI0IDExLjUwNjEsMzQuMTQyMUM5Ljk0NjM4LDM1LjcwMTkgNy40MTc1OCwzNS43MDE5IDUuODU3ODYsMzQuMTQyMUM0LjI5ODE1LDMyLjU4MjQgNC4yOTgxNSwzMC4wNTM2IDUuODU3ODYsMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxNjQsMTY0LDE2NCk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMzYuMDA2MSwxNi4wMDYxQzM4LjIxMTksMTYuMDA2MSA0MCwxNy43OTQyIDQwLDIwQzQwLDIyLjIwNTggMzguMjExOSwyMy45OTM5IDM2LjAwNjEsMjMuOTkzOUMzMy44MDAzLDIzLjk5MzkgMzIuMDEyMiwyMi4yMDU4IDMyLjAxMjIsMjBDMzIuMDEyMiwxNy43OTQyIDMzLjgwMDMsMTYuMDA2MSAzNi4wMDYxLDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoNzQsNzQsNzQpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksNS44NTc4NkMzMC4wNTM2LDQuMjk4MTUgMzIuNTgyNCw0LjI5ODE1IDM0LjE0MjEsNS44NTc4NkMzNS43MDE5LDcuNDE3NTggMzUuNzAxOSw5Ljk0NjM4IDM0LjE0MjEsMTEuNTA2MUMzMi41ODI0LDEzLjA2NTggMzAuMDUzNiwxMy4wNjU4IDI4LjQ5MzksMTEuNTA2MUMyNi45MzQyLDkuOTQ2MzggMjYuOTM0Miw3LjQxNzU4IDI4LjQ5MzksNS44NTc4NloiIHN0eWxlPSJmaWxsOnJnYig1MCw1MCw1MCk7Ii8+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4K" />
        </div>
    </div>
</div>
<!-- Map Page -->
<div id="pMap" class="w3-stretch" data-role="page">
    <div id="mapView"></div>
</div>
<!-- Departures Page -->
<div id="pDepartures" data-role="page">
    <h1><?= __('Departures') ?></h1>
    <div class="w3-padding-16" id="divDeparturesArea"></div>
</div>
<!-- Search Page -->
<div id="pSearch" data-role="page">
    <h1><?= __('Route Search') ?></h1>
    <form>
        <input id="edtSearchRouteName" type="text" class="w3-input" placeholder="<?= __('Route Name') ?>" autocomplete="off" /><br />
        <button id="btnSearchSubmit" class="w3-button w3-block w3-teal"><?= __('Search') ?></button>
    </form>
    <div class="w3-padding-16" id="divRoutesArea"></div>
</div>
<!-- TripDetails Page -->
<div id="pTripDetails" data-role="page">
    <h1><?= __('Trip Details') ?></h1>
    <div class="w3-padding-16" id="divTripsArea"></div>
</div>
<!-- Alerts Page -->
<div id="pAlerts" data-role="page">
    <h1>Service Alerts</h1>
    <div id="divAlertArea">
        <p class="w3-text-red w3-large w3-center"><?= __('There are no currently active service alerts') ?></p>
    </div>
    <button id="btnAlertsSubmit" class="w3-button w3-block w3-teal"><?= __('Reload') ?></button>
</div>
<!-- Settings Page -->
<div id="pSettings" data-role="page">
    <h1><?= __('Settings') ?></h1>
    <p><?= __('Set up your preferences for searching, map display and application behaviour here.') ?></p>
    <form>
        <div class="w3-row">
            <div class="w3-half w3-container">
                <h2><?= __('Search Preferences') ?></h2>
                <p>
                    <input id="cbxWheelchairAccessible" type="checkbox" class="w3-check" />
                    <label for="cbxWheelchairAccessible"><?= __('Wheelchair Accessible') ?></label><br />
                    <input id="cbxBikesAllowed" type="checkbox" class="w3-check" />
                    <label for="cbxBikesAllowed"><?= __('Bikes Allowed') ?></label>
                </p>
            </div>
            <div class="w3-half w3-container">
                <h2><?= __('Map Display') ?></h2>
                <p>
                    <input id="cbxStopsLoading" type="checkbox" class="w3-check" checked="checked" />
                    <label for="cbxStopsLoading"><?= __('Stops / POIs') ?></label><br />
                    <input id="cbxVehiclePositionsLoading" type="checkbox" class="w3-check" checked="checked" />
                    <label for="cbxVehiclePositionsLoading"><?= __('Vehicle Positions') ?></label>
                </p>
            </div>
        </div>
    </form>
</div>
<!-- App Info Page -->
<div id="pAppInfo" data-role="page">
    <h1><?= __('Application Info') ?></h1>
    <p><?= __('Thank your for using {0} application! In this section you can find some basic instructions.', Configure::read('Theme.title')) ?></p>
    <button onClick="accordionMenu('accInfoMap')" class="w3-button w3-block w3-light-grey w3-left-align"><?= __('Map View') ?></button>
    <div id="accInfoMap" class="w3-container w3-hide w3-show">
        <p><?= $this->Markdown->parse(__('FRONTEND_INFO_TEXT_MAP_VIEW')) ?></p>
    </div>
    <button onClick="accordionMenu('accInfoDepartures')" class="w3-button w3-block w3-light-grey w3-left-align"><?= __('Departures') ?></button>
    <div id="accInfoDepartures" class="w3-container w3-hide">
        <p><?= $this->Markdown->parse(__('FRONTEND_INFO_TEXT_DEPARTURES')) ?></p>
    </div>
    <button onClick="accordionMenu('accInfoTrip')" class="w3-button w3-block w3-light-grey w3-left-align"><?= __('Trip Details') ?></button>
    <div id="accInfoTrip" class="w3-container w3-hide">
        <p><?= $this->Markdown->parse(__('FRONTEND_INFO_TEXT_TRIP_DETAILS')) ?></p>
    </div>
    <button onClick="accordionMenu('accInfoSearch')" class="w3-button w3-block w3-light-grey w3-left-align"><?= __('Route Search') ?></button>
    <div id="accInfoSearch" class="w3-container w3-hide">
        <p><?= $this->Markdown->parse(__('FRONTEND_INFO_TEXT_ROUTE_SEARCH')) ?></p>
    </div>
    <!--<button onClick="accordionMenu('accInfoAlerts')" class="w3-button w3-block w3-light-grey w3-left-align"><?= __('Service Alerts') ?></button>
    <div id="accInfoAlerts" class="w3-container w3-hide">
        <p><?= __('FRONTEND_INFO_TEXT_SERVICE_ALERTS') ?></p>
    </div>-->
    <button onClick="accordionMenu('accInfoSettings')" class="w3-button w3-block w3-light-grey w3-left-align"><?= __('Settings') ?></button>
    <div id="accInfoSettings" class="w3-container w3-hide">
        <p><?= $this->Markdown->parse(__('FRONTEND_INFO_TEXT_SETTINGS')) ?></p>
    </div>
</div>
<!-- Privacy Page -->
<div id="pPrivacy" data-role="page">
    <h1><?= __('Privacy') ?></h1>
    <p><?= $this->Markdown->parse(__('SYSTEM_PRIVACY_INFO', Configure::read('App.email'))) ?></p>
</div>
<!-- / Pages Section -->
<!-- Modals Section -->
<!-- Alert Modal -->
<div id="dlgAlert" class="w3-modal">
    <div class="w3-modal-content w3-animate-top">
        <div class="w3-container">
            <h2 id="lblAlertTitle"></h2>
            <span class="w3-button w3-display-topright w3-xlarge w3-close"><b>&times;</b></span>
            <p id="lblAlertMessage"></p>
        </div>
        <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
            <button id="btnAlertPositive" class="w3-button w3-close w3-teal w3-right"><?= __('Close') ?></button>
            <button id="btnAlertNegative" class="w3-button w3-close w3-blue-grey w3-right w3-hide"><?= __('Cancel') ?></button>
        </div>
    </div>
</div>
<!-- / Modals Section -->

<?php $this->append('footer'); ?>
<div class="w3-bar w3-blue-grey w3-card">
    <a class="w3-bar-item w3-button w3-padding-large" href="#pAppInfo"><?= __('Information') ?></a>
    <a class="w3-bar-item w3-button w3-padding-large w3-right" href="#pPrivacy"><?= __('Privacy') ?></a>
</div>
<?php $this->end(); ?>


<?= $this->Html->script('/vendors/leaflet/js/leaflet.js', ['block' => true]) ?>
<?= $this->Html->script('/vendors/moment/min/moment.min.js', ['block' => true]) ?>
<?php $this->append('script'); ?>
<script>
function accordionMenu(menuId) {
    let accordionMenu = $('#' + menuId);
    if(accordionMenu.hasClass('w3-show')) {
        accordionMenu.removeClass('w3-show');
    } else {
        accordionMenu.addClass('w3-show');
    }
}

function mobileMenu() {
    let mobileMenu = $('#mobileMenu');
    if(mobileMenu.hasClass('w3-show')) {
        mobileMenu.removeClass('w3-show');
    } else {
        mobileMenu.addClass('w3-show');
    }
}

function showAlert(title, message, callbackPositive = null, callbackNegative = null) {
    let alertDialog = $('#dlgAlert');

    alertDialog.find('#lblAlertTitle').text(title);
    alertDialog.find('#lblAlertMessage').text(message);

    if(callbackPositive != null && callbackNegative != null) {
        let btnPositive = alertDialog.find('#btnAlertPositive').addClass('w3-show');
        let btnNegative = alertDialog.find('#btnAlertNegative').addClass('w3-show');

        btnPositive.text('<?= __('OK') ?>').on('click', callbackPositive);
        btnNegative.text('<?= __('Cancel') ?>').on('click', callbackNegative);
    } else if(callbackPositive != null) {
        let btnPositive = alertDialog.find('#btnAlertPositive').addClass('w3-show');
        btnPositive.text('<?= __('OK') ?>').on('click', callbackPositive);
    }

    alertDialog.show();
    alertDialog.find('.w3-close').click(function (e) {
        alertDialog.hide();
    });
}

function showPage(pageId) {
    $('div[data-role="page"]').hide();

    if(pageId != null) {
        let page = $('#' + pageId);
        page.show();
    }
}

function loadPage(pageId, pageTitle = null) {
    if(pageId != null) {
        window.location.hash = '#' + pageId;

        if(pageTitle != null) {
            $('#' + pageId).find('h1').text(pageTitle);
        }
    }
}

$(function () {
    // global function variables initialisation
    let $defaultConfig = {
        wheelchairAccessible: $('#cbxWheelchairAccessible').prop('checked'),
        bikesAllowed: $('#cbxBikesAllowed').prop('checked'),
        stopsLoading: $('#cbxStopsLoading').prop('checked'),
        vehiclePositionsLoading: $('#cbxVehiclePositionsLoading').prop('checked'),
        stopsLoadingDistance: 120
    };

    let $mapView = L.map('mapView');
    let $stopsLayer = new L.featureGroup().addTo($mapView);

    // basic navigation setup
    window.addEventListener('hashchange', function () {
        if(window.location.hash != '') {
            showPage(window.location.hash.slice(1));
        } else {
            showPage('pMap');
        }

        $mapView.invalidateSize();
    });

    // load start page
    window.location.hash = null;
    window.location.replace('#pMap');

    // settings event handlers
    $('#cbxWheelchairAccessible').on('click', function (event) {$defaultConfig.wheelchairAccessible = $(this).prop('checked');});
    $('#cbxBikesAllowed').on('click', function (event) {$defaultConfig.bikesAllowed = $(this).prop('checked');});
    $('#cbxStopsLoading').on('click', function (event) {$defaultConfig.stopsLoading = $(this).prop('checked');});
    $('#cbxVehiclePositionsLoading').on('click', function (event) {$defaultConfig.vehiclePositionsLoading = $(this).prop('checked');});

    // request location link
    $('#lnkRequestLocation').on('click', function (event) {
        event.preventDefault();

        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                $mapView.setView([position.coords.latitude, position.coords.longitude], 13);
                loadPage('pMap');
            }, function (error) {
                showAlert('<?= __('Location Error') ?>', '<?= __('An error occurred while requesting your current location!') ?>');
            });
        } else {
            showAlert('<?= __('Location Error') ?>', '<?= __('Geolocation is not supported by this browser!') ?>');
        }
    });

    function getTime(timeString) {
        var timeElements = timeString.split(':');

        var date = new Date();
        date.setHours(timeElements[0]);
        date.setMinutes(timeElements[1]);
        date.setSeconds(timeElements[2]);

        return date.getTime();
    }

    // ajax functions
    function loadTripDetails(objectId, objectType, timeRef = null) {
        var date = new Date();
        
        var requestUrl = null;
        if(objectType == 'byRouteId') {
            requestUrl = '<?= Router::url('/api/trips/byRouteId.json', true) ?>';
            requestUrl += '?routeId=' + objectId;
            requestUrl += '&date=' + moment().format('YYYY-MM-DD') + '&time=' + moment().format('HH:mm:ss');
        } else if(objectType == 'byTripId') {
            requestUrl = '<?= Router::url('/api/trips/byTripId.json', true) ?>';
            requestUrl += '?tripId=' + objectId;

            if (timeRef != null) {
                requestUrl += '&time=' + timeRef;
            }
        }

        if ($defaultConfig.wheelchairAccessible) {
            requestUrl += '&wheelchairEnabled=1';
        }

        if ($defaultConfig.bikesAllowed) {
            requestUrl += '&bikesEnabled=1';
        }

        $.getJSON(requestUrl, function (response) {
            if(response.result != null && response.result.trips != null && response.result.trips.length > 0) {
                var tripsList = $('<ul>').addClass('w3-ul');

                $.each(response.result.trips, function (index, trip) {
                    var tripItem = $('<li>').addClass('w3-bar tripItem').css({cursor:'pointer'}).data('trip-id', trip.trip_id);

                    var tripItemDiv = $('<div>').addClass('w3-bar-item').appendTo(tripItem);
                    $('<span>').addClass('w3-large w3-text-teal').text(trip.trip_short_name + ' ' + trip.trip_headsign).append('<br>').appendTo(tripItemDiv);
                    $('<span>').addClass('w3-small').text(trip.route.route_short_name).append('<br>').appendTo(tripItemDiv);

                    if(trip.frequencies.length > 0) {
                        for(var f = 0; f < trip.frequencies.length; f++) {
                            if(getTime(trip.frequencies[f].start_time) <= date.getTime() && getTime(trip.frequencies[f].end_time) >= date.getTime()) {
                                $('<span>').addClass('w3-small').text('<?= __('departs every ') ?>' + (trip.frequencies[f].headway_secs / 60) + 'min').append('<br>').appendTo(tripItemDiv);
                            }
                        }
                    }

                    $('<p>').addClass('tripShortDetails').html('<b>' + trip.stop_times[0].departure_time + '</b> ' + trip.stop_times[0].stop.stop_name).appendTo(tripItemDiv);

                    if(trip.stop_times.length > 2) {
                        var verboseTripDetails = $('<div>').addClass('tripVerboseDetails').css('display', 'none');
                        var verboseStopTimes = $('<ul>').addClass('timeLine').appendTo(verboseTripDetails);
                        for(var i = 1; i < trip.stop_times.length - 1; i++) {
                            var stopTimeItem = $('<li>');

                            if(trip.stop_times[i].arrival_time != trip.stop_times[i].departure_time) {
                                var stopTimeItemArrival = $('<p>');
                                $('<div>').addClass('timeItem').text('an ' + trip.stop_times[i].arrival_time).appendTo(stopTimeItemArrival);
                                $('<div>').addClass('textItem').text(trip.stop_times[i].stop.stop_name).appendTo(stopTimeItemArrival);
                                stopTimeItemArrival.appendTo(stopTimeItem);
                            }

                            var stopTimeItemDeparture = $('<p>');
                            $('<div>').addClass('timeItem').text('ab ' + trip.stop_times[i].departure_time).appendTo(stopTimeItemDeparture);
                            $('<div>').addClass('textItem').text(trip.stop_times[i].stop.stop_name).appendTo(stopTimeItemDeparture);
                            stopTimeItemDeparture.appendTo(stopTimeItem);

                            stopTimeItem.appendTo(verboseStopTimes);
                        }

                        verboseTripDetails.appendTo(tripItemDiv);
                    }

                    $('<p>').addClass('tripShortDetails').html('<b>' + trip.stop_times[trip.stop_times.length - 1].departure_time + '</b> ' + trip.stop_times[trip.stop_times.length - 1].stop.stop_name).appendTo(tripItemDiv);

                    tripItem.on('click', function (event) {
                        event.preventDefault();
                        $(this).find('.tripVerboseDetails').slideToggle();
                    });

                    tripItem.appendTo(tripsList);
                });

                $('#divTripsArea').empty().append(tripsList);
            } else {
                var message = $('<p>').addClass('w3-text-red w3-large w3-center').text('<?= __('No matching trip results found!') ?>');
                $('#divTripsArea').empty().append(message);
            }
        });
    }

    function loadRoutes(routeName) {
        var requestUrl = '<?= Router::url('/api/routes/byRouteName.json', true) ?>';
        requestUrl += '?routeName=' + routeName;

        $.getJSON(requestUrl, function (response) {
            if(response.result != null && response.result.routes != null && response.result.routes.length > 0) {
                var routesList = $('<ul>').addClass('w3-ul');

                $.each(response.result.routes, function (index, route) {
                    var routeItem = $('<li>').addClass('w3-bar routeItem').css({cursor:'pointer'}).data('route-id', route.route_id);

                    var routeItemDiv = $('<div>').addClass('w3-bar-item').appendTo(routeItem);
                    $('<span>').addClass('w3-large w3-text-teal').text(route.route_short_name).append('<br>').appendTo(routeItemDiv);
                    $('<span>').addClass('w3-small').text(route.agency.agency_name).append('<br>').appendTo(routeItemDiv);
                    $('<p>').text(route.route_desc).appendTo(routeItemDiv);

                    routeItem.on('click', function (event) {
                        event.preventDefault();

                        loadTripDetails($(this).data('route-id'), 'byRouteId');
                        loadPage('pTripDetails', '<?= __('Trip Overview') ?>');
                    });

                    routeItem.appendTo(routesList);
                });

                $('#divRoutesArea').empty().append(routesList);
            } else {
                var message = $('<p>').addClass('w3-text-red w3-large w3-center').text('<?= __('No matching route results found!') ?>');
                $('#divRoutesArea').empty().append(message);
            }
        });
    }

    function loadDepartures(stopId) {
        var date = new Date();
        
        var requestUrl = '<?= Router::url('/api/trips/byStopId.json', true) ?>';
        requestUrl += '?stopId=' + stopId + '&arrivals=0&date=' + moment().format('YYYY-MM-DD') + '&time=' + moment().format('HH:mm:ss');

        if ($defaultConfig.wheelchairAccessible) {
            requestUrl += '&wheelchairEnabled=1';
        }

        if ($defaultConfig.bikesAllowed) {
            requestUrl += '&bikesEnabled=1';
        }

        $.getJSON(requestUrl, function (response) {
            if(response.result != null && response.result.trips != null && response.result.trips.length > 0) {
                var tripsList = $('<ul>').addClass('w3-ul');

                $.each(response.result.trips, function (index, trip) {
                    var tripItem = $('<li>').addClass('w3-bar tripItem').css({cursor:'pointer'}).data('trip-id', trip.trip_id).data('trip-start-time', trip.start_time);

                    var tripItemDiv = $('<div>').addClass('w3-bar-item').appendTo(tripItem);
                    $('<span>').addClass('w3-large w3-text-teal').text(trip.trip_short_name + ' ' + trip.trip_headsign).append('<br>').appendTo(tripItemDiv);
                    $('<span>').addClass('w3-small').text(trip.route.route_short_name).append('<br>').appendTo(tripItemDiv);

                    if(trip.frequencies.length > 0) {
                        for(var f = 0; f < trip.frequencies.length; f++) {
                            if(getTime(trip.frequencies[f].start_time) <= date.getTime() && getTime(trip.frequencies[f].end_time) >= date.getTime()) {
                                $('<span>').addClass('w3-small').text('<?= __('departs every ') ?>' + (trip.frequencies[f].headway_secs / 60) + 'min').append('<br>').appendTo(tripItemDiv);
                            }
                        }
                    }

                    $('<p>').addClass('tripShortDetails').html('<b>' + trip.stop_times[0].departure_time + '</b> ' + trip.stop_times[0].stop.stop_name).appendTo(tripItemDiv);

                    tripItem.on('click', function (event) {
                        event.preventDefault();

                        loadTripDetails($(this).data('trip-id'), 'byTripId', $(this).data('trip-start-time'));
                        loadPage('pTripDetails', trip.trip_short_name != undefined ? trip.trip_short_name : '<?= __('Trip Details') ?>');
                    });

                    tripItem.appendTo(tripsList);
                });

                $('#divDeparturesArea').empty().append(tripsList);
            } else {
                var message = $('<p>').addClass('w3-text-red w3-large w3-center').text('<?= __('No departure results found!') ?>');
                $('#divDeparturesArea').empty().append(message);
            }
        });
    }

    // map functions setup
    $mapView.setView([51, 9], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map Data © <a href="https://openstreetmap.org">OpenStreetMap</a> Contributors',
        maxZoom: 17
    }).addTo($mapView);

    // custom stop icon
    var stopIcon = L.icon({
       iconUrl: '<?= $this->Url->image('Zeichen224.svg') ?>',
       iconSize: [24, 24]
    });

    // load stops on each map move
    $mapView.on('moveend', function () {
        var requestUrl = '<?= Router::url('/api/stops/byLatLon.json', true) ?>';
        requestUrl += '?refLat=' + $mapView.getCenter().lat;
        requestUrl += '&refLon=' + $mapView.getCenter().lng;
        requestUrl += '&refDistance=' + $defaultConfig.stopsLoadingDistance;

        if($defaultConfig.wheelchairAccessible) {
            requestUrl += '&wheelchairEnabled=1';
        }

        $.getJSON(requestUrl, function (response) {
            if(response.result != null && response.result.stops != null && response.result.stops.length > 0) {
                $stopsLayer.clearLayers();
                $.each(response.result.stops, function (index, stop) {
                    L.marker([stop.stop_lat, stop.stop_lon], {icon: stopIcon, stopId: stop.stop_id, stopName: stop.stop_name}).on('click', function (event) {
                        loadDepartures(this.options.stopId);
                        loadPage('pDepartures', this.options.stopName);
                    }).addTo($stopsLayer);
                });
            }
        });
    });

    // search form functionality
    $('#btnSearchSubmit').on('click', function (event) {
        event.preventDefault();
        loadRoutes($('#edtSearchRouteName').val());
    });

    // alert reload functionality
    $('#btnAlertsSubmit').on('click', function (event) {
        event.preventDefault();
    });
});
</script>
<?php $this->end(); ?>
