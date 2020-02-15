<?php $this->loadHelper('Markdown.Markdown'); ?>
<section class="content-header">
    <h1><?= __('Privacy Information') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <?= $this->Markdown->parse(__('SYSTEM_PRIVACY_INFO', \Cake\Core\Configure::read('App.email'))) ?>
        </div>
    </div>
</section>