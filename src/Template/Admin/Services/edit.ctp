<section class="content-header">
    <h1><?= __('Edit Service') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($service) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-edit') ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabExceptions" data-toggle="tab"><?= __('Deviations') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <?= $this->Form->control('service_name', ['class' => 'form-control', 'type' => 'text', 'label' => __('Name')]) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <?= $this->Form->input('start_date', ['type' => 'text', 'class' => 'form-control date', 'autocomplete' => 'off', 'data-date-format' => 'dd.mm.yyyy']) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <?= $this->Form->input('end_date', ['type' => 'text', 'class' => 'form-control date', 'autocomplete' => 'off', 'data-date-format' => 'dd.mm.yyyy']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('monday', ['label' => __('Monday'), 'type' => 'checkbox']) ?>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('tuesday', ['label' => __('Tuesday'), 'type' => 'checkbox']) ?>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('wednesday', ['label' => __('Wednesday'), 'type' => 'checkbox']) ?>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('thursday', ['label' => __('Thursday'), 'type' => 'checkbox']) ?>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('friday', ['label' => __('Friday'), 'type' => 'checkbox']) ?>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('saturday', ['label' => __('Saturday'), 'type' => 'checkbox']) ?>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-6 col-xs-6 mt-2">
                                        <?= $this->Form->control('sunday', ['label' => __('Sunday'), 'type' => 'checkbox']) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabExceptions">
                                <div class="row">
                                    <div class="col-lg-12 table-responsive">
                                        <table class="table w-100">
                                            <thead>
                                            <tr>
                                                <th><?= __('Date') ?></th>
                                                <th><?= __('Deviation') ?></th>
                                                <th class="text-right">
                                                    <a href="#" id="btnAddException">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="tblExceptionList">
                                            <?php for($e = 0; $e < count($service->service_exceptions); $e++): ?>
                                                <?= $this->element('services/exception', ['index' => $e, 'id' => $service->service_exceptions[$e]->id]) ?>
                                            <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <script id="exceptionTemplate" type="text/x-underscore-template">
                                <?= $this->element('services/exception') ?>
                            </script>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= $this->Html->link(__('Cancel'), (isset($_REDIRECT) && $_REDIRECT != null) ? $_REDIRECT : ['action' => 'index'], ['class' => 'btn btn-default']) ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
<?php $this->Html->css('/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.css', ['block' => true]); ?>
<?php $this->Html->script('/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js', ['block' => true]); ?>
<?php $this->Html->script('/vendors/underscore/underscore-min.js', ['block' => true]); ?>
<?php $this->append('script'); ?>
<script>
    $(function () {
        // datepicker
        $(document).on('focus', '.date', function () {
            $(this).datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1
            });
        });

        // exceptions edit functions
        var exceptionItem = _.template($('#exceptionTemplate').remove().html());
        var exceptionCount = $('#tblExceptionList').children().length;

        // add exceptions button
        $('#btnAddException').on('click', function (e) {
            e.preventDefault();
            $(exceptionItem({index: exceptionCount++})).appendTo('#tblExceptionList');
        });

        // delete a single exception
        $(document).on('click', '.lnk-delete', function (e) {
            e.preventDefault();
            var link = $(this);

            // in case of existing exception, this has to be deleted by AJAX call!
            var exceptionId = $(this).data('exception-id');
            if(exceptionId) {
                $.post('<?= $this->Url->build(['controller' => 'Ajax', 'action' => 'deleteServiceExceptionById']) ?>/' + exceptionId).done(function () {
                    link.closest('tr').remove();
                    exceptionCount--;
                });
            } else {
                link.closest('tr').remove();
                exceptionCount--;
            }
        });
    });
</script>
<?php $this->end(); ?>
