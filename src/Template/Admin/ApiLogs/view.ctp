<section class="content-header">
    <h1>
        <?= __('View Log Entry') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view', ['identity' => $_IDENTITY, 'entity' => $log, 'primaryKey' => $log->id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th scope="row" width="200"><?= __('Method') ?></th>
                                                <td><?= h($log->method) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Endpoint') ?></th>
                                                <td><?= h($log->endpoint) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Query Params') ?></th>
                                                <td><?= nl2br(h($log->query_params)) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Request Data') ?></th>
                                                <td><?= nl2br(h($log->request_data)) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Response Code') ?></th>
                                                <td><?= h($log->response_code) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Response Data') ?></th>
                                                <td><?= nl2br(h($log->response_data)) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Exception') ?></th>
                                                <td><?= h($log->exception) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>