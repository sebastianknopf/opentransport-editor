<?php

use Cake\Core\Configure;

Configure::load('database');
$this->loadHelper('Markdown.Markdown');

?>
<div class="box  no-border">
    <?= $this->Form->create(null) ?>
    <div class="box-header">
        <h2><?= __('Installation') ?> - <?= __('Database') ?></h2>
    </div>
    <div class="box-body">
        <?php echo $this->Flash->render(); ?>
        <p>
            <?= __('To store your data anywhere we need access to a database. Please enter credentials for your database here.') ?>
        </p>
        <p>
            <?= $this->Form->control('database.host', ['label' => __('Host'), 'default' => Configure::read('Datasources.default.host')]) ?>
            <?= $this->Form->control('database.port', ['label' => __('Port'), 'default' => Configure::read('Datasources.default.port')]) ?>
            <?= $this->Form->control('database.username', ['label' => __('Username'), 'default' => Configure::read('Datasources.default.username')]) ?>
            <?= $this->Form->control('database.password', ['label' => __('Password'), 'default' => Configure::read('Datasources.default.password')]) ?>
            <?= $this->Form->control('database.dbname', ['label' => __('Database Name'), 'default' => Configure::read('Datasources.default.database')]) ?>
        </p>
    </div>
    <div class="box-footer no-border">
        <?= $this->Form->submit(__('Next'), ['class' => 'pull-right btn btn-success']) ?>
    </div>
    <?php $this->Form->end() ?>
</div>
