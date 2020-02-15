<?php

$this->loadHelper('Url');
$this->loadHelper('Markdown.Markdown');

?>
<?= $this->Markdown->parse(__('EMAIL_HTML_USERS_PWRESET', $appName, $this->Url->build(['controller' => 'Users', 'action' => 'pwreset', '?' => ['token' => $token]], true))) ?>