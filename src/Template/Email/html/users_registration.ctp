<?php

$this->loadHelper('Url');
$this->loadHelper('Markdown.Markdown');

?>
<?= $this->Markdown->parse(__('EMAIL_HTML_USERS_REGISTRATION', $appName, $this->Url->build(['controller' => 'Users', 'action' => 'activate', '?' => ['token' => $token], $userId], true), $username, $password)) ?>