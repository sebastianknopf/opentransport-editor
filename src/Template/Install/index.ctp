<?php

use Cake\Core\Configure;

$this->loadHelper('Markdown.Markdown');

?>
<div class="box  no-border">
    <?= $this->Form->create(null) ?>
    <div class="box-header">
        <h2><?= __('Installation') ?> - <?= __('Requirements') ?></h2>
    </div>
    <div class="box-body">
        <?php echo $this->Flash->render(); ?>
        <p>
            <?= $this->Markdown->parse(__('Welcome to **{0}** installation! This wizard will guide you through the whole installation process.', Configure::read('App.name'))) ?>
        </p>
        <p>
            <?php

            $requirements = [];

            // check php version
            $requirements['php_version'] = version_compare(PHP_VERSION, '5.6');

            // cakephp core version
            $requirements['cake_version'] = version_compare(Configure::version(), '3.8');

            // mbstring loaded
            $requirements['mbstring_loaded'] = extension_loaded('mbstring');

            // intl loaded
            $requirements['intl_loaded'] = extension_loaded('intl');

            // curl loaded
            $requirements['curl_loaded'] = extension_loaded('curl');

            // sqlite3 loaded
            $requirements['sqlite3_loaded'] = extension_loaded('sqlite3');

            // zip loaded
            $requirements['zip_loaded'] = extension_loaded('zip');

            // simplexml loaded
            $requirements['simplexml_loaded'] = extension_loaded('simplexml');

            // set_time_limit available
            $requirements['set_time_limit'] = strpos(ini_get('disable_functions'), 'set_time_limit') === false;

            ?>
            <ul class="list-unstyled">
                <li>
                    <?= $requirements['php_version'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-close" style="color:red;"></i>' ?>
                    <?= __('installed PHP version >= 5.6') ?>
                </li>
                <li>
                    <?= $requirements['php_version'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-close" style="color:red;"></i>' ?>
                    <?= __('installed CakePHP core >= 3.8') ?>
                </li>
                <li>
                    <?= $requirements['mbstring_loaded'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-close" style="color:red;"></i>' ?>
                    <?= __('extension {0} for PHP loaded', 'mbstring') ?>
                </li>
                <li>
                    <?= $requirements['intl_loaded'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-close" style="color:red;"></i>' ?>
                    <?= __('extension {0} for PHP loaded', 'intl') ?>
                </li>
                <li>
                    <?= $requirements['curl_loaded'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:orange;"></i>' ?>
                    <?php if($requirements['curl_loaded']): ?>
                        <?= __('extension {0} for PHP loaded', 'curl') ?>
                    <?php else: ?>
                        <?= __('extension {0} for PHP NOT loaded', 'curl') ?>
                    <?php endif; ?>
                </li>
                <li>
                    <?= $requirements['sqlite3_loaded'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:orange;"></i>' ?>
                    <?php if($requirements['sqlite3_loaded']): ?>
                        <?= __('extension {0} for PHP loaded', 'sqlite3') ?>
                    <?php else: ?>
                        <?= __('extension {0} for PHP NOT loaded', 'sqlite3') ?>
                    <?php endif; ?>
                </li>
                <li>
                    <?= $requirements['zip_loaded'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:orange;"></i>' ?>
                    <?php if($requirements['zip_loaded']): ?>
                        <?= __('extension {0} for PHP loaded', 'zip') ?>
                    <?php else: ?>
                        <?= __('extension {0} for PHP NOT loaded', 'zip') ?>
                    <?php endif; ?>
                </li>
                <li>
                    <?= $requirements['simplexml_loaded'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:orange;"></i>' ?>
                    <?php if($requirements['simplexml_loaded']): ?>
                        <?= __('extension {0} for PHP loaded', 'simplexml') ?>
                    <?php else: ?>
                        <?= __('extension {0} for PHP NOT loaded', 'simplexml') ?>
                    <?php endif; ?>
                </li>
                <li>
                    <?= $requirements['set_time_limit'] ? '<i class="fa fa-check" style="color:green;"></i>' : '<i class="fa fa-warning" style="color:orange;"></i>' ?>
                    <?php if($requirements['set_time_limit']): ?>
                        <?= __('function set_time_limit not locked') ?>
                    <?php else: ?>
                        <?= __('function set_time_limit is locked! Current max execution time limit is {0} seconds', ini_get('max_execution_time')) ?>
                    <?php endif; ?>
                </li>
            </ul>
        </p>
    </div>
    <div class="box-footer no-border">
        <?php $continueEnabled = false; ?>
        <?php if($requirements['php_version'] && $requirements['cake_version'] && $requirements['mbstring_loaded'] && $requirements['intl_loaded']) { $continueEnabled = true; } ?>
        <?= $this->Form->submit(__('Next'), ['class' => $continueEnabled ? 'pull-right btn btn-success' : 'btn btn-success disabled']) ?>
    </div>
    <?php $this->Form->end() ?>
</div>
