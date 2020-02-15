<?php

use Cake\Core\Configure;

Configure::load('database');
$this->loadHelper('Markdown.Markdown');

?>
<div class="box no-border">
    <?= $this->Form->create(null) ?>
    <div class="box-header">
        <h2><?= __('Installation') ?> - <?= __('Conclusion') ?></h2>
    </div>
    <div class="box-body">
        <p>
            <?= $this->Markdown->parse(__('Great! The installation ran successfully.')) ?>
        </p>
        <p>
            <?= $this->Form->textarea('conclusion', ['rows' => 10, 'style' => 'width:100%;resize:none;', 'readonly' => true, 'default' => file_get_contents(LOGS . 'install.log')]) ?>
        </p>
    </div>
    <div class="box-footer no-border">
        <?= $this->Form->submit(__('Finish & Login'), ['class' => 'pull-right btn btn-success']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
