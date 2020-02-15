<section class="content-header">
    <h1>
        <?= __('Change Password') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-6">
            <div class="box box-primary">
                <?= $this->Form->create($user) ?>
                <div class="box-header">
                    <h3 class="box-title"><?= __('New Password') ?></h3>
                </div>
                <div class="box-body">
                    <?= $this->Form->control('password_current', ['label' => __('Old Password'), 'type' => 'password', 'required' => true, 'value' => false]) ?>
                    <?= $this->Form->control('password', ['label' => __('New Password'), 'type' => 'password', 'value' => '', 'required' => true, 'value' => false]) ?>
                    <?= $this->Form->control('password_match', ['label' => __('New Password (Match)'), 'type' => 'password', 'required' => true, 'value' => false]) ?>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Security Hint') ?></h3>
                </div>
                <div class="box-body">
                    <p><?= __('To ensure the system security, your password should comply with the following requirements') ?></p>
                    <ul class="list-group">
                        <li class="list-group-item"><?= __('your password should use at least 8 characters') ?></li>
                        <li class="list-group-item"><?= __('mix upper-case with lower-case letters and other special chars') ?></li>
                        <li class="list-group-item"><?= __('do not use any kind of content related words') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>