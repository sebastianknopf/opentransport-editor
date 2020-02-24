<?php

use Cake\Core\Configure;
use Cake\I18n\FrozenDate;

$this->loadHelper('SystemUpdate');

?>
<section class="content-header">
    <h1>
        <?= __('Dashboard') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('System Update') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if (!$this->SystemUpdate->isUpdateAvailable()): ?>
                    <div class="alert alert-success"><?= __('Currently all system components are up to date! No need to update anything.') ?></div>
                    <?php else: ?>
                    <div class="alert alert-warning"><?= __('There\'s a system update available for some components! Click the button to see the update details.') ?></div>
                    <?php endif; ?>
                </div>
                <?php if ($this->SystemUpdate->isUpdateAvailable()): ?>
                <div class="box-footer">
                    <b><?= __('Current version is {0}, latest is {1}', Configure::read('App.version'), $this->SystemUpdate->getLatestVersion()) ?></b>
                    <span class="pull-right">
                        <?= $this->Html->link(__('See Update'), ['controller' => 'System', 'action' => 'update'], ['class' => 'btn btn-default']) ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('REST API') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(Configure::read('RestAPI.logRequests')): ?>
                    <div style="height:350px;">
                        <canvas id="logInfoChart" width="100%"></canvas>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger"><?= __('RestAPI requests are not logged! Enable RestAPI request log to view this panel!') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Data Errors') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(count($messages) > 0): ?>
                    <div class="table-responsive">
                        <table class="w-100 table table-striped">
                            <thead>
                                <th scope="col" width="100"><?= __('Level') ?></th>
                                <th scope="col"><?= __('Text') ?></th>
                                <th scope="col" class="actions text-right"><?= __('Actions') ?></th>
                            </thead>
                            <tbody>
                                <?php foreach($messages as $message): ?>
                                <?php

                                $levelClass = 'alert-success';
                                $levelText = __('Info');
                                if($message->level == 1) {
                                    $levelClass = 'alert-warning';
                                    $levelText = __('Warning');
                                } else if($message->level == 2) {
                                    $levelClass = 'alert-danger';
                                    $levelText = __('Error');
                                }

                                ?>
                                <tr>
                                    <td><span class="label <?= $levelClass ?>"><?= $levelText ?></span></td>
                                    <td><?= h($message->long_message) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?= $this->Form->postLink(__('Ignore'), ['action' => 'msgignore', $message->id], ['class' => 'btn btn-xs btn-danger']) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-success"><?= __('No data errors found.') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->Html->script('/vendors/chart-js/Chart.min.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        <?php $date = new FrozenDate(); ?>
        <?php $dateList = []; for($i = 7; $i >=0; $i--) array_push($dateList, clone $date->subDays($i)); ?>
        // display log information chart
        let ctx = $('#logInfoChart');
        let logInfoChart = new Chart(ctx, {
            type: 'bar',
            options: {
                responsive: true,
                maintainAspectRatio: false
            },
            data: {
                labels: ['<?= implode("', '", $dateList) ?>'],
                datasets: [{
                    label: '<?= __('Requests') ?>',
                    data: [<?php for($d = 0; $d < count($dateList); $d++) { echo (isset($apiLogsCount[$dateList[$d]->format('Y-m-d')]) ? $apiLogsCount[$dateList[$d]->format('Y-m-d')] : 0) . ', '; } ?>],
                    backgroundColor: '#1abb9c'
                }]
            }
        });
    });
</script>
<?php $this->end() ?>