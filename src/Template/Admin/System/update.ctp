<?php

$this->loadHelper('SystemUpdate');

$currentUpdateJobId = $this->SystemUpdate->getCurrentUpdateJobId();

?>
<section class="content-header">
    <h1>
        <?= __('System Update') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Recent Updates') ?></h3>
                </div>
                <div class="box-body">
                    <?php if ($this->SystemUpdate->isUpdateAvailable()): ?>
                    <?php $changesList = $this->SystemUpdate->getLatestChanges(\Cake\Core\Configure::read('App.version')); ?>
                    <dl class="dl-horizontal">
                        <?php foreach ($changesList as $changeInfo): ?>
                        <dt><?= __('by') ?> <?= h($changeInfo['author']) ?></dt>
                        <dd><i><?= h($changeInfo['message']) ?></i></dd>
                        <?php endforeach; ?>
                    </dl>
                    <?php else: ?>
                    <div class="alert alert-success"><?= __('Currently all system components are up to date! No need to update anything.') ?></div>
                    <?php endif; ?>
                </div>
                <?php if ($this->SystemUpdate->isUpdateAvailable()): ?>
                <div class="box-footer">
                    <div class="pull-right">
                        <?php if ($currentUpdateJobId != null): ?>
                        <span id="lblUpdateJobStatus"></span> <?= $this->Html->image('AjaxLoader.gif', ['alt' => 'Ajax Loader', 'id' => 'imgUpdateStatusLoading', 'width' => 20, 'height' => 20]) ?>
                        <?php endif; ?>
                        <?= $this->Form->postLink(__('Run Update'), [], ['class' => 'btn btn-warning' . ($currentUpdateJobId != null ? ' disabled' : null), 'id' => 'lnkRunUpdate']); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php if ($currentUpdateJobId != null): ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        // update the current process status with an interval of 500ms
        setInterval(function () {
            $.getJSON('<?= \Cake\Routing\Router::url(['controller' => 'Ajax', 'action' => 'jobStatus', $currentUpdateJobId]) ?>', function (response) {
                if (response.queuedJobs.length > 0) {
                    var updateJob = response.queuedJobs[0];


                    switch(true) {
                        case updateJob.pending:
                            $('#lblUpdateJobStatus').text('pending ...');
                            break;

                        case updateJob.failed:
                            $('#lblUpdateJobStatus').text('failed!');
                            break;

                        case updateJob.completed:
                            $('#lblUpdateJobStatus').remove();
                            $('#imgUpdateStatusLoading').remove();
                            $('#lnkRunUpdate').removeClass('disabled');
                            window.location.reload();
                            break;

                        default:
                            $('#lblUpdateJobStatus').text(updateJob.status);
                            break;
                    }
                }
            });
        }, 500);
    });
</script>
<?php $this->end() ?>
<?php endif; ?>
