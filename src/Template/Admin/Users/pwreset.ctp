<?php if(isset($user)): ?>
    <?= $this->Form->create($user) ?>
    <h2><?= __('Password Reset') ?></h2>
    <?= $this->Form->control('password', ['placeholder' => __('Password'), 'label' => false, 'value' => false, 'type' => 'password']) ?>
    <?= $this->Form->control('password_match', ['placeholder' => __('Password (Match)'), 'label' => false, 'value' => false, 'type' => 'password']) ?>
    <div>
        <?= $this->Form->submit(__('Change', ['class' => 'btn btn-default'])) ?>
    </div>
    <?= $this->Form->end() ?>
<?php else: ?>
    <h2><?= __('Error') ?></h2>
    <p><?= __('The supplied token seems to be invalid!') ?></p>
<?php endif; ?>