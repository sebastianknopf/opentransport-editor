<?php

$id = isset($id) ? $id : '';
$stop_id = isset($stop_id) ? $stop_id : '<%= stop_id %>';
$stop_name = isset($stop_name) ? $stop_name : '<%= stop_name %>';
$arrival_time = isset($arrival_time) ? $arrival_time : '';
$departure_time = isset($departure_time) ? $departure_time : '';

$index = isset($index) ? $index : '<%= index %>';

?>
<tr>
    <td style="vertical-align: middle !important;" width="50" class="text-center">
        <a href="#" class="lnk-up"><i class="fa fa-chevron-up"></i></a><br />
        <a href="#" class="lnk-down"><i class="fa fa-chevron-down"></i></a>
    </td>
    <td style="vertical-align: middle !important;" width="300">
        <?= $this->Form->hidden("stop_times.{$index}.id", ['value' => $id]) ?>
        <?php // $this->Form->hidden("stop_times.{$index}.stop_id", ['value' => $stop_id]) ?>
        <input type="hidden" class="inp-stop-id" name="stop_times[<?= $index ?>][stop_id]" value="<?= $stop_id ?>" />
        <span><?= $stop_name ?></span>
    </td>
    <td width="150">
        <div class="input-group">
            <?= $this->Form->control("stop_times.{$index}.arrival_time", ['type' => 'text', 'value' => $arrival_time, 'label' => false, 'class' => 'form-control time arrival']) ?>
        </div>
    </td>
    <td width="150">
        <div class="input-group">
            <?= $this->Form->control("stop_times.{$index}.departure_time", ['type' => 'text', 'value' => $departure_time, 'label' => false, 'class' => 'form-control time departure']) ?>
        </div>
    </td>
    <td width="200">
        <div class="input-group">
            <?= $this->Form->control("stop_times.{$index}.pickup_type", ['options' => ['0' => __('Yes'), '1' => __('No'), '3' => __('Demand')], 'label' => false]) ?>
        </div>
    </td>
    <td width="200">
        <div class="input-group">
            <?= $this->Form->control("stop_times.{$index}.drop_off_type", ['options' => ['0' => __('Yes'), '1' => __('No'), '3' => __('Demand')], 'label' => false]) ?>
        </div>
    </td>
    <td style="vertical-align: middle !important;" class="text-right">
        <?php if($id != ''): ?>
            <a href="#" class="btn btn-danger btn-xs lnk-action lnk-delete lnk-delete-stoptime" data-stoptime-id="<?= $id ?>"><?= __('Delete') ?></a>
        <?php else: ?>
            <a href="#" class="btn btn-danger btn-xs lnk-action lnk-delete lnk-delete-stoptime"><?= __('Delete') ?></a>
        <?php endif; ?>
    </td>
</tr>