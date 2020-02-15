<section class="content-header">
    <h1>
        <?= __('Transfer Clientship') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create() ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('entity_class', ['options' => ['Services' => __('Service'), 'Agencies' => __('Agency'), 'Routes' => __('Route'), 'Trips' => __('Trip')], 'value' => $entityClass, 'label' => __('Entity Class')]) ?>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?= $this->Form->control('entity_id', ['type' => 'text', 'value' => $entityId, 'label' => __('Entity ID')]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $this->Form->control('client_id', ['options' => $clients]) ?>
                            <?= $this->Form->label('override_associations', __('Override Associations')) ?><br />
                            <i><?= __('If you don\'t override associations, this can lead to incorrect behaviour! We recommend strictly to override associations!') ?></i><br />
                            <?= $this->Form->radio('override_associations', ['0' => __('Don\'t Override'), '1' => __('Override')], ['default' => '1']) ?>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?php if($entityClass != null): ?>
                        <?= $this->Html->link(__('Cancel'), ['controller' => $entityClass, 'action' => 'view', $entityId], ['class' => 'btn btn-default']) ?>
                        <?php else: ?>
                        <?= $this->Html->link(__('Cancel'), ['_name' => 'index'], ['class' => 'btn btn-default']) ?>
                        <?php endif; ?>
                        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>