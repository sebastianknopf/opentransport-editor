<?= $this->Form->create() ?>
    <h2><?= __('Password Reset') ?></h2>
    <?= $this->Form->control('username', ['placeholder' => __('Username'), 'label' => false]) ?>
    <?= $this->Form->submit(__('Proceed', ['class' => 'btn btn-default'])) ?>
<?= $this->Form->end() ?>