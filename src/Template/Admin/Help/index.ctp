<?php

$this->extend('/common/base');

$this->Breadcrumbs->add(__('Home'), ['_name' => 'index'], ['class' => 'breadcrumb-item']);
$this->Breadcrumbs->add(__('Help'), [], ['class' => 'breadcrumb-item active']);

$this->assign('title', __('Help overview'));

?>
<div class="col-lg-3">
    <h2><?= __('Help overview') ?></h2>
    <small><?= __('Overview about important features and FAQs') ?></small>
    <ul class="list-group clear-list m-t">
        <li class="list-group-item">
            <span class="label bg-info text-white mr-3">1</span>
            <?= __('System info') ?>
        </li>
        <li class="list-group-item">
            <span class="label bg-info text-white mr-3">2</span>
            TestItem
        </li>
    </ul>
</div>
<div class="col-lg-8 d-none d-lg-block">
    <h2><?= __('Latest features and updates') ?></h2>
    <small><?= __('Latest added features and changes') ?></small>
</div>
<div class="col-lg-12 mt-3">
    <div class="box">
        <div class="box-heading">
            <h3><?= __('System info') ?></h3>
        </div>
        <div class="box-content">
            System Info
        </div>
    </div>
</div>

