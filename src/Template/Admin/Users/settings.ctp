<?php

use App\Utility\LocaleList;

?>
<section class="content-header">
    <h1>
        <?= __('User Settings') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <?= $this->Form->create($user) ?>
                <div class="box-body">
                    <div class="row form-group">
                        <?php

                        $dateSetting = $user->getUserSetting('Date.toStringFormat');

                        $dateFormats = [
                            'yyyy-MM-dd' => 'yyyy-mm-dd',
                            'dd.MM.yyyy' => 'dd.mm.yyyy'
                        ];

                        ?>
                        <?php if($dateSetting != null): ?>
                            <?= $this->Form->hidden('user_settings.0.id', ['value' => $dateSetting->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->hidden('user_settings.0.name', ['value' => 'Date.toStringFormat']) ?>
                        <div class="col-lg-4">
                            <?= $this->Form->control('user_settings.0.value', ['label' => __('Date Format'), 'options' => $dateFormats, 'value' => !is_null($dateSetting) ? $dateSetting->value : null]) ?>
                        </div>

                    </div>
                    <div class="row form-group">
                        <?php

                        $timeSetting = $user->getUserSetting('Time.toStringFormat');

                        $timeFormats = [
                            'yyyy-MM-dd HH:mm:ss' => 'yyyy-mm-dd HH:mm:ss',
                            'dd.MM.yyyy HH:mm:ss' => 'dd.mm.yyyy HH:mm:ss'
                        ];

                        ?>
                        <?php if($timeSetting != null): ?>
                            <?= $this->Form->hidden('user_settings.1.id', ['value' => $timeSetting->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->hidden('user_settings.1.name', ['value' => 'Time.toStringFormat']) ?>
                        <div class="col-lg-4">
                            <?= $this->Form->control('user_settings.1.value', ['label' => __('Time Format'), 'options' => $timeFormats, 'value' => !is_null($timeSetting) ? $timeSetting->value : null]) ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <?php

                        $timezoneSetting = $user->getUserSetting('App.defaultTimezone');

                        $timezoneList = array();
                        foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $identifier) {
                            $timezoneList[$identifier] = $identifier;
                        }

                        ?>
                        <?php if($timezoneSetting != null): ?>
                            <?= $this->Form->hidden('user_settings.2.id', ['value' => $timezoneSetting->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->hidden('user_settings.2.name', ['value' => 'App.defaultTimezone']) ?>
                        <div class="col-lg-4">
                            <?= $this->Form->control('user_settings.2.value', ['label' => __('Timezone'), 'options' => $timezoneList, 'value' => !is_null($timezoneSetting) ? $timezoneSetting->value : null]) ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <?php

                        $localeSetting = $user->getUserSetting('App.defaultLocale');

                        $localeList = LocaleList::getLocaleList();

                        ?>
                        <?php if($localeSetting != null): ?>
                            <?= $this->Form->hidden('user_settings.3.id', ['value' => $localeSetting->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->hidden('user_settings.3.name', ['value' => 'App.defaultLocale']) ?>
                        <div class="col-lg-4">
                            <?= $this->Form->control('user_settings.3.value', ['label' => __('Preferred Language'), 'options' => $localeList, 'value' => !is_null($localeSetting) ? $localeSetting->value : null]) ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <?php

                        $numResultsSetting = $user->getUserSetting('Paginator.resultsLength');

                        ?>
                        <?php if($numResultsSetting != null): ?>
                            <?= $this->Form->hidden('user_settings.4.id', ['value' => $numResultsSetting->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->hidden('user_settings.4.name', ['value' => 'Paginator.resultsLength']) ?>
                        <div class="col-lg-4">
                            <?= $this->Form->control('user_settings.4.value', ['label' => __('Max. Results / Page'), 'type' => 'number', 'value' => !is_null($numResultsSetting) ? $numResultsSetting->value : 20]) ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <?php

                        $themeColorSkin = $user->getUserSetting('Theme.skin');

                        $availableColorSkins = [
                            'blue' => __('Blue'),
                            'blue-light' => __('Blue Light'),
                            'yellow' => __('Yellow'),
                            'yellow-light' => __('Yellow Light'),
                            'green' => __('Green'),
                            'green-light' => __('Green Light'),
                            'purple' => __('Purple'),
                            'purple-light' => __('Purple Light'),
                            'red' => __('Red'),
                            'red-light' => __('Red Light'),
                            'black' => __('Minimal Black'),
                            'black-light' => __('Minimal Light')
                        ];

                        ?>
                        <?php if($themeColorSkin != null): ?>
                            <?= $this->Form->hidden('user_settings.5.id', ['value' => $themeColorSkin->id]) ?>
                        <?php endif; ?>
                        <?= $this->Form->hidden('user_settings.5.name', ['value' => 'Theme.skin']) ?>
                        <div class="col-lg-4">
                            <?= $this->Form->control('user_settings.5.value', ['label' => __('Theme'), 'options' => $availableColorSkins, 'value' => !is_null($themeColorSkin) ? $themeColorSkin->value : \Cake\Core\Configure::read('Theme.skin')]) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= $this->Html->link(__('Change Password'), ['action' => 'pwchange'], ['class' => 'btn btn-warning']) ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>