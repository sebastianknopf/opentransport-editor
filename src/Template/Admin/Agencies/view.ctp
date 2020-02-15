<?php

use App\Model\Entity\Route;
use App\Utility\LocaleList;

?>
<section class="content-header">
    <h1><?= __('View Agency') ?></h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <?= $this->element('box/toolbar-view-clientable', ['identity' => $_IDENTITY, 'entity' => $agency, 'primaryKey' => $agency->agency_id]) ?>
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
                                                <th scope="row"><?= __('Name') ?></th>
                                                <td class="consume"><?= h($agency->agency_name) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('URL') ?></th>
                                                <td class="consume">
                                                    <a href="<?= $agency->agency_url ?>" target="_blank">
                                                        <?= h($agency->agency_url) ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Fare URL') ?></th>
                                                <td class="consume">
                                                    <a href="<?= $agency->agency_fare_url ?>" target="_blank">
                                                        <?= h($agency->agency_fare_url) ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Phone') ?></th>
                                                <td class="consume"><?= h($agency->agency_phone) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Language') ?></th>
                                                <td class="consume"><?= h(LocaleList::getLocaleName($agency->agency_lang)) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Timezone') ?></th>
                                                <td class="consume"><?= h($agency->agency_timezone) ?></td>
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
                                                <th scope="row"><?= __('ID') ?></th>
                                                <td class="consume"><?= h($agency->agency_id) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Created') ?></th>
                                                <td><?= h($agency->created) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Modified') ?></th>
                                                <td><?= h($agency->modified) ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row"><?= __('Client') ?></th>
                                                <td>
                                                    <?= isset($agency->client) ? h($agency->client->longname) : h($agency->client_id) ?>
                                                </td>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= __('Related Routes') ?></h3>
                    <div class="box-tools pull-right">
                        <?php  if(isset($_IDENTITY) && $_IDENTITY->can('add', Route::getInstance())): ?>
                        <button type="button" class="btn btn-box-tool">
                            <a href="<?= $this->Url->build(['controller' => 'Routes', 'action' => 'add', $agency->agency_id]) ?>">
                                <i class="fa fa-plus"></i>
                            </a>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                    <?php if(count($agency->routes)): ?>
                        <table class="w-100 table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" width="50"><?= $this->Paginator->sort('route_id', '#') ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('route_type', __('Type')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('route_short_name', __('Shortname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('route_long_name', __('Longname')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col"><?= $this->Paginator->sort('route_url', __('URL')) ?> <i class="fa fa-sort ml-2"></i></th>
                                <th scope="col" class="actions text-right" width="250"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($agency->routes as $route): ?>
                                <tr>
                                    <td><?= h($route->route_id) ?></td>
                                    <td><?= h(Route\Type::getRouteTypes()[$route->route_type]) ?></td>
                                    <td><?= h($route->route_short_name) ?></td>
                                    <td><?= h($route->route_long_name) ?></td>
                                    <td>
                                        <a href="<?= $route->route_url ?>" target="_blank">
                                            <?= h($route->route_url) ?>
                                        </a>
                                    </td>
                                    <td class="actions text-right">
                                        <div class="btn-group">
                                            <?php if($_IDENTITY->can('view', $route)): ?>
                                                <?= $this->Html->link(__('View'), ['controller' => 'Routes', 'action' => 'view', $route->route_id], ['class' => 'btn btn-xs btn-default']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                    <div class="alert alert-danger"><?= __('No related routes found!') ?></div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>