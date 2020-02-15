<?php $this->loadHelper('Markdown.Markdown'); ?>

<?php $this->append('css') ?>
<?= $this->element('markdown/markdown-style'); ?>
<?php $this->end(); ?>

<section class="content-header">
    <h1><?= __('User Manual') ?></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#General">
                    <h3 class="box-title"><?= __('General') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_GENERAL_INFO', \Cake\Core\Configure::read('App.name'))) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#System">
                    <h3 class="box-title"><?= __('System') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_SYSTEM_INFO', \Cake\Core\Configure::read('App.name'))) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#Stops">
                    <h3 class="box-title"><?= __('Stations') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_STOPS_INFO', \Cake\Core\Configure::read('App.name'))) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#Shapes">
                    <h3 class="box-title"><?= __('Shapes') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_SHAPES_INFO')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#Services">
                    <h3 class="box-title"><?= __('Services') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_SERVICES_INFO')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#Agencies">
                    <h3 class="box-title"><?= __('Agencies') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_AGENCIES_INFO')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#Routes">
                    <h3 class="box-title"><?= __('Routes') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_ROUTES_INFO')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#Trips">
                    <h3 class="box-title"><?= __('Trips') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_TRIPS_INFO')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header" id="#ApiLogs">
                    <h3 class="box-title"><?= __('REST API') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Markdown->parse(__('MANUAL_REST_INFO', \Cake\Routing\Router::url(['_name' => 'api']))) ?>
                </div>
            </div>
        </div>
    </div>
</section>