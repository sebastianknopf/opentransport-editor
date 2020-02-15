<?php

use App\Utility\LocaleList;

?>
<section class="content-header">
    <h1><?= __('Add Agency') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($agency) ?>
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
                                        <?= $this->Form->control('agency_name', ['label' => __('Name')]) ?>
                                        <?= $this->Form->control('agency_url', ['label' => __('URL'), 'type' => 'url']) ?>
                                        <?= $this->Form->control('agency_fare_url', ['label' => __('Fare URL'), 'type' => 'url']) ?>
                                        <?= $this->Form->control('agency_phone', ['label' => __('Phone')]) ?>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?php

                                        $timezoneList = array();
                                        foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $identifier) {
                                            $timezoneList[$identifier] = $identifier;
                                        }

                                        ?>
                                        <?= $this->Form->control('agency_timezone', ['label' => __('Timezone'), 'options' => $timezoneList]) ?>
                                        <?= $this->Form->control('agency_lang', ['label' => __('Language'), 'options' => LocaleList::getLocaleList()]) ?>
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