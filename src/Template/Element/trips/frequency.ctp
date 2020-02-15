<?php

$id = isset($id) ? $id : '';
$index = isset($index) ? $index : '<%= index %>';
$start_time = isset($start_time) ? $start_time : '';
$end_time = isset($end_time) ? $end_time : '';

?>
<tr>
    <td width="150">
        <?= $this->Form->hidden("frequencies.{$index}.id", ['value' => $id]) ?>
        <?= $this->Form->control("frequencies.{$index}.start_time", ['type' => 'text', 'value' => $start_time, 'label' => false, 'class' => 'form-control time']) ?>
    </td>
    <td width="150">
        <?= $this->Form->control("frequencies.{$index}.end_time", ['type' => 'text', 'value' => $end_time, 'label' => false, 'class' => 'form-control time']) ?>
    </td>
    <td width="200">
        <?= $this->Form->control("frequencies.{$index}.headway_min", ['type' => 'number', 'label' => false]) ?>
    </td>
    <td class="text-center" width="200">
        <?= $this->Form->control("frequencies.{$index}.exact_times", ['type' => 'checkbox', 'label' => false]) ?>
    </td>
    <td class="text-right">
        <?php if($id != ''): ?>
            <a href="#" class="btn btn-danger btn-xs lnk-action lnk-delete lnk-delete-frequency" data-frequency-id="<?= $id ?>"><?= __('Delete') ?></a>
        <?php else: ?>
            <a href="#" class="btn btn-danger btn-xs lnk-action lnk-delete lnk-delete-frequency"><?= __('Delete') ?></a>
        <?php endif; ?>
    </td>
</tr>