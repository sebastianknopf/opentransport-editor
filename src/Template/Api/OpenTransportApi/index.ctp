<?php

use Cake\Core\Configure;
use Cake\Routing\Router;

$this->loadHelper('Markdown.Markdown');

?>
<?php $this->append('title', Configure::read('Theme.title') . '.API') ?>
<?php // append css styles here ?>
<?php $this->append('css'); ?>
<style>
html, body {
    height: 100%;
    font-family: Arial;
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
</style>
<?= $this->element('markdown/markdown-style') ?>
<?php $this->end(); ?>

<?php // append header here ?>
<?php $this->append('header'); ?>
<div class="w3-bar-item w3-padding-large">
    <b><?= Configure::read('Theme.title') ?>.API</b>
</div>
<?php $this->end(); ?>

<?php $this->append('mobileMenu'); ?>
<?php $this->end(); ?>

<!-- Main Content -->
<div id="pMain" data-role="page">
    <div class="w3-display-container">
        <div class="w3-card" style="max-width:800px;margin:35px auto;">
            <div class="w3-container">
                <p><?= $this->Markdown->parse(__('REST_API_INFO_TEXT', Configure::read('App.name'))) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Public Privacy -->
<div id="pPrivacy" data-role="page">
    <div class="w3-display-container">
        <div class="w3-card" style="max-width:800px;margin:35px auto;">
            <div class="w3-container">
                <h1><?= __('Privacy Information') ?></h1>
                <p><?= $this->Markdown->parse(__('SYSTEM_PRIVACY_INFO', Configure::read('App.email'))) ?></p>
            </div>
        </div>
    </div>
</div>

<?php // append footer here ?>
<?php $this->append('footer'); ?>
<div class="w3-bar w3-blue-grey w3-card">
    <a class="w3-bar-item w3-button w3-padding-large w3-right" href="#pPrivacy"><?= __('Privacy') ?></a>
</div>
<?php $this->end(); ?>

<?php // append scripts here ?>
<?php $this->append('script'); ?>
<script>
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
        // basic navigation setup
        window.addEventListener('hashchange', function () {
            if(window.location.hash != '') {
                showPage(window.location.hash.slice(1));
            } else {
                showPage('pMain');
            }
        });

        // load start page
        window.location.hash = null;
        window.location.replace('#pMain');
    });
</script>
<?php $this->end(); ?>
