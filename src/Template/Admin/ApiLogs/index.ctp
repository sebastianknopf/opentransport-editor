<?php

use Cake\Core\Configure;
use Cake\I18n\FrozenDate;

?>
<section class="content-header">
    <h1>
        <?= __('REST API') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Request Statistics') ?> <small><?= __('last 8 days') ?></small></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(Configure::read('RestAPI.logRequests')): ?>
                    <div style="height:450px;">
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
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Error Logs') ?> <small><?= __('last 8 days') ?></small></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(Configure::read('RestAPI.logRequests')): ?>
                    <div class="table-responsive">
                        <table class="w-100 table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" width="200"><?= $this->Paginator->sort('created', __('Date')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col" width="200" style="text-align:center;"><?= $this->Paginator->sort('response_code', __('Response Code')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('endpoint', __('Endpoint')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($restLogs as $restLog): ?>
                                <tr>
                                    <td><?= h($restLog->created) ?></td>
                                    <td style="text-align:center;"><?= h($restLog->response_code) ?></td>
                                    <td><?= h($restLog->endpoint) ?></td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $restLog)): ?>
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $restLog->id], ['class' => 'btn btn-xs btn-default btn-outline-primary lnk-action lnk-view']) ?>
                                            <?php endif; ?>
                                            <?php if($_IDENTITY->can('delete', $restLog)): ?>
                                                <?= $this->Html->link(__('Delete'), ['action' => 'delete', $restLog->id], ['class' => 'btn btn-xs btn-danger', 'data-toggle' => 'modal', 'data-target' => '#delete-confirm']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-danger"><?= __('RestAPI requests are not logged! Enable RestAPI request log to view this panel!') ?></div>
                    <?php endif; ?>
                </div>
                <div class="box-footer">
                    <ul class="pagination">
                        <?= $this->Paginator->numbers(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->element('modal/delete-confirm') ?>
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
                    label: '<?= __('Success Requests') ?>',
                    data: [<?php for($d = 0; $d < count($dateList); $d++) { echo (isset($successCountData[$dateList[$d]->format('Y-m-d')]) ? $successCountData[$dateList[$d]->format('Y-m-d')] : 0) . ', '; } ?>],
                    backgroundColor: '#1abb9c'
                }, {
                    label: '<?= __('Error Requests') ?>',
                    data: [<?php for($d = 0; $d < count($dateList); $d++) { echo (isset($errorCountData[$dateList[$d]->format('Y-m-d')]) ? $errorCountData[$dateList[$d]->format('Y-m-d')] : 0) . ', '; } ?>],
                    backgroundColor: '#c9302c'
                }]
            }
        });
    });
</script>
<?php $this->end() ?>
