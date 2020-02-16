<?php use Cake\Core\Configure; ?>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> <?= Configure::read('App.version') ?>
    </div>
    <strong>Copyright © <?= date('Y') ?> <?= Configure::read('App.name') ?> | <?= $this->Html->link(__('Privacy'), ['controller' => 'system', 'action' => 'privacy']) ?>
</footer>