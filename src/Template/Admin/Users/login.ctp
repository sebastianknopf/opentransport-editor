<?= $this->Form->create() ?>
    <h2><?= __('Login Form') ?></h2>
    <?= $this->Form->control('username', ['placeholder' => __('Username'), 'label' => false]) ?>
    <?= $this->Form->control('password', ['placeholder' => __('Password'), 'label' => false]) ?>
    <?= $this->Html->link(__('Forgot your password?'), ['action' => 'pwforgot'], ['style' => 'line-height: 30px']) ?><br />
    <br />
    <?= $this->Form->submit(__('Login', ['class' => 'btn btn-default'])) ?>
<?= $this->Form->end() ?>

