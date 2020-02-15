<?php

use Cake\Core\Configure;

Configure::load('database');
$this->loadHelper('Markdown.Markdown');

?>
<div class="box  no-border">
    <?= $this->Form->create($user) ?>
    <div class="box-header">
        <h2><?= __('Installation') ?> - <?= __('Admin User') ?></h2>
    </div>
    <div class="box-body">
        <?php echo $this->Flash->render(); ?>
        <p>
            <?= __('You have to create at least one admin user! Enter your preferred credentials in the boxes below.') ?>
        </p>
        <p>
            <?= $this->Form->control('username', ['label' => __('Username')]) ?>
            <?= $this->Form->control('email', ['label' => __('E-Mail')]) ?>
            <?= $this->Form->control('password', ['label' => __('Password')]) ?>
        </p>
    </div>
    <div class="box-footer no-border">
        <?= $this->Form->submit(__('Next'), ['class' => 'pull-right btn btn-success']) ?>
    </div>
    <?php $this->Form->end() ?>
</div>
