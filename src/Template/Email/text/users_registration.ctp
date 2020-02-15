<?php $this->loadHelper('Url'); ?>
<?= $this->Markdown->parse(__('EMAIL_TEXT_USERS_REGISTRATION', $appName, $this->Url->build(['controller' => 'Users', 'action' => 'activate', '?' => ['token' => $token], $userId], true), $username, $password)) ?>
