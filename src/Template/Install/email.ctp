<?php

use Cake\Core\Configure;

Configure::load('database');
$this->loadHelper('Markdown.Markdown');

?>
<div class="box  no-border">
    <?= $this->Form->create(null) ?>
    <div class="box-header">
        <h2><?= __('Installation') ?> - <?= __('SMPT / E-Mail') ?></h2>
    </div>
    <div class="box-body">
        <?php echo $this->Flash->render(); ?>
        <p>
            <?= __('If you want to send emails, you must enter your SMTP credentials here. You can also skip this part of installation and change the credentials in the config file manually.') ?>
        </p>
        <p>
            <?= $this->Form->control('email.host', ['label' => __('Host'), 'default' => Configure::read('EmailTransport.smtp.host')]) ?>
            <?= $this->Form->control('email.port', ['label' => __('Port'), 'default' => Configure::read('EmailTransport.smtp.port')]) ?>
            <?= $this->Form->control('email.username', ['label' => __('Username'), 'default' => Configure::read('EmailTransport.smtp.username')]) ?>
            <?= $this->Form->control('email.password', ['label' => __('Password'), 'default' => Configure::read('EmailTransport.smtp.password')]) ?>
        </p>
    </div>
    <div class="box-footer no-border">
        <div class="pull-right active">
            <?= $this->Html->link(__('Skip'), ['?' => ['skipAction' => true]], ['class' => 'btn btn-warning']) ?>
            <?= $this->Form->button(__('Next'), ['type' => 'submit', 'class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php $this->Form->end() ?>
</div>
