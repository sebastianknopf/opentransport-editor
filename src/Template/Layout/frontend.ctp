<?php

use Cake\Core\Configure;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?= $this->fetch('title') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="<?= $this->Url->image('favicon.ico') ?>" />
        <?= $this->Html->css('/css/w3-css/w3.css') ?>
        <?= $this->Html->css('/vendors/font-awesome/css/all.min.css') ?>
        <?= $this->fetch('css') ?>
    </head>
    <body>
        <!-- Top Menu (Desktop) -->
        <div class="w3-top">
            <div class="w3-bar w3-teal w3-card">
                <?= $this->fetch('header') ?>
            </div>
        </div>
        <!-- Top Menu (Mobile) -->
        <div id="mobileMenu" class="w3-bar-block w3-teal w3-hide w3-hide-large w3-top">
            <?= $this->fetch('mobileMenu') ?>
        </div>
        <!-- Content Wrapper -->
        <div class="w3-container" data-role="app">
            <?= $this->fetch('content') ?>
        </div>
        <!-- Footer -->
        <div class="w3-bottom">
            <?= $this->fetch('footer') ?>
        </div>
        <?= $this->Html->script('/js/jquery-3.4.1.min.js') ?>
        <?= $this->fetch('script') ?>
    </body>
</html>