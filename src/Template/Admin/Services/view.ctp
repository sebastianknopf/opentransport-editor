<section class="content-header">
    <h1><?= __('View Service') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view-clientable', ['identity' => $_IDENTITY, 'entity' => $service, 'primaryKey' => $service->service_id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content bg-white p-4">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Name') ?></th>
                                                <td class="consume"><?= h($service->service_name) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Start') ?></th>
                                                <td class="consume"><?= h($service->start_date) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('End') ?></th>
                                                <td class="consume"><?= h($service->end_date) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Service Days') ?></th>
                                                <td class="consume"><?= h($service->service_days) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <?php if(count($service->service_exceptions)): ?>
                                    <div class="row mt-4">
                                        <div class="col-lg-12 table-responsive">
                                            <h2><?= __('Deviations') ?></h2>
                                            <table class="table w-100">
                                                <thead>
                                                    <tr>
                                                        <th><?= __('Date') ?></th>
                                                        <th><?= __('Deviation') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblExceptionList">
                                                <?php foreach($service->service_exceptions as $exception): ?>
                                                    <tr>
                                                        <td width="185" class="align-middle">
                                                            <?= h($exception->date) ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            <?php if($exception->exception_type == 1): ?>
                                                                <span class="label label-success"><?= __('additional') ?></span>
                                                            <?php else: ?>
                                                                <span class="label label-danger"><?= __('exceptional') ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tab-pane" id="tabMeta">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('ID') ?></th>
                                                <td class="consume"><?= h($service->service_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($service->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($service->modified) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Client') ?></th>
                                                <td><?= isset($service->client) ? h($service->client->longname) : h($service->client_id) ?></td>
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