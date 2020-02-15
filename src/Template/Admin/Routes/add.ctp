<?php

use App\Model\Entity\Route;

?>
<section class="content-header">
    <h1><?= __('Add Route') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($route) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-add') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('route_type', ['label' => __('Type'), 'options' => Route\Type::getRouteTypes()]) ?>
                                        <?= $this->Form->control('route_short_name', ['label' => __('Shortname')]) ?>
                                        <?= $this->Form->control('route_long_name', ['label' => __('Longname')]) ?>
                                        <?= $this->Form->control('route_url', ['label' => __('URL'), 'type' => 'url']) ?>
                                        <?= $this->Form->control('route_desc', ['label' => __('Description')]) ?>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('agency_id', ['label' => __('Agency'), 'options' => $agencies, 'value' => $agency_id]) ?>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <?= $this->Form->label('route_color', __('Color'), ['class' => 'control-label']) ?>
                                                <div id="routeColor" class="input-group colorpicker-component">
                                                    <?= $this->Form->input('route_color', ['label' => false, 'value' => empty($route->route_color) ? '#ff0000' : null]) ?>
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <?= $this->Form->label('route_text_color', __('Text Color'), ['class' => 'control-label']) ?>
                                                <div id="routeTextColor" class="input-group colorpicker-component">
                                                    <?= $this->Form->input('route_text_color', ['label' => false, 'value' => empty($route->route_text_color) ? '#ffffff' : null]) ?>
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center" style="padding-top: 35px;padding-bottom: 20px;">
                                            <span id="colorPreview" class="label" style="font-size:2em;background-color:#ff0000;color:#ffffff;">Sample Text</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= $this->Html->link(__('Cancel'), (isset($_REDIRECT) && $_REDIRECT != null) ? $_REDIRECT : ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
<?php $this->Html->css('/vendors/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css', ['block' => true]); ?>
<?php $this->Html->script('/vendors/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        $('#routeColor, #routeTextColor').colorpicker();

        $('#routeColor').on('changeColor', function(event) {
            $('#colorPreview').css('background-color', event.color.toString());
        });

        $('#routeTextColor').on('changeColor', function(event) {
            $('#colorPreview').css('color', event.color.toString());
        });
    });
</script>
<?php $this->end(); ?>