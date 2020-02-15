<section class="content-header">
    <h1>
        <?= __('View Client') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view', ['identity' => $_IDENTITY, 'entity' => $client, 'primaryKey' => $client->id]) ?>
                </div>
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs bar_tabs">
                            <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                            <li><a href="#tabMeta" data-toggle="tab"><?= __('Meta') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabBasedata">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('Shortname') ?></th>
                                                <td><?= h($client->shortname) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Longname') ?></th>
                                                <td><?= h($client->longname) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabMeta">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table w-100">
                                            <tr>
                                                <th width="250" scope="row"><?= __('ID') ?></th>
                                                <td class="consume"><?= $this->Number->format($client->id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($client->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($client->modified) ?></td>
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