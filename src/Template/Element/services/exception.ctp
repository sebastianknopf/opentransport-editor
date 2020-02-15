<?php

$id = isset($id) ? $id : '';
$index = isset($index) ? $index : '<%= index %>';

?>
<tr valign="middle">
    <td width="185">
        <?= $this->Form->hidden("service_exceptions.{$index}.id", ['value' => $id]) ?>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <?= $this->Form->control("service_exceptions.{$index}.date", ['type' => 'text', 'label' => false, 'autocomplete' => 'off']) ?>
        </div>
    </td>
    <td>
        <div class="input-group">
            <?= $this->Form->control("service_exceptions.{$index}.exception_type", ['options' => ['1' => __('additional'), '2' => __('exceptional')], 'label' => false]) ?>
        </div>
    </td>
    <td style="vertical-align: middle !important;" width="50">
        <?php if($id != ''): ?>
            <a href="#" class="btn btn-danger btn-xs lnk-action lnk-delete" data-exception-id="<?= $id ?>"><?= __('Delete') ?></a>
        <?php else: ?>
            <a href="#" class="btn btn-danger btn-xs lnk-action lnk-delete"><?= __('Delete') ?></a>
        <?php endif; ?>
    </td>
</tr>
