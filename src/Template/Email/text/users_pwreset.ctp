<?php $this->loadHelper('Url'); ?>
<?= __('EMAIL_TEXT_USERS_PWRESET', $appName, $this->Url->build(['controller' => 'Users', 'action' => 'pwreset', '?' => ['token' => $token]], true)) ?>
