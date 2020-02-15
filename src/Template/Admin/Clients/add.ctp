<section class="content-header">
    <h1>
        <?= __('Add Client') ?>
    </h1>
    <?= $this->Breadcrumbs->render(['class' => 'breadcrumb']) ?>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <?= $this->Form->create($client) ?>
                <div class="box-header">
                    <?= $this->element('box/toolbar-add') ?>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tabBasedata" data-toggle="tab"><?= __('Basedata') ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabBasedata">
                                        <?= $this->Form->control('shortname', ['required' => true]) ?>
                                        <?= $this->Form->control('longname', ['required' => true]) ?>
                                    </div>
                                </div>
                            </div>
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