<?php

use Cake\Core\Configure;
use Cake\I18n\FrozenDate;

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
                    <h3 class="box-title"><?= __('Latest Features') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Version 0.9.0</b>
                            <span class="pull-right">
                                basic trip editing based on pure GTFS data
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b><?= __('Current Version') ?></b>
                            <span class="pull-right">
                                <?= Configure::read('App.version') ?>
                            </span>
                        </li>
                    </ul>
                </div>
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