<section class="content-header">
    <h1>
        <?= __('Add User') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($user) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-add') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('username', ['required' => true]) ?>
                                        <?= $this->Form->control('email', ['required' => true]) ?>
                                        <?= $this->Form->control('password', ['required' => true]) ?>
                                        <?= $this->Form->control('password_match', ['label' => __('Password (Match)'), 'required' => true, 'type' => 'password']) ?>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <?= $this->Form->control('client_id', ['options' => $clients, 'required' => true, 'class' => 'form-control']) ?>
                                        <?= $this->Form->control('group_id', ['options' => $groups, 'required' => true, 'class' => 'form-control']) ?>
                                        <?= $this->Form->label('send_registration_mail', __('Registration E-Mail')) ?>
                                        <?= $this->Form->radio('send_registration_mail', ['0' => __('Don\'t Send'), '1' => __('Send')], ['default' => '0']) ?>
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