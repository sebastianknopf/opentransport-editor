<?php use App\Model\Entity\Client; ?>
<?php if(isset($entity) && isset($primaryKey)): ?>
<div class="box-tools pull-right">
    <?php if(isset($_IDENTITY) && $_IDENTITY->can('transfer', Client::getInstance()) && $_IDENTITY->can('edit', $entity)): ?>
    <button type="button" class="btn btn-box-tool">
        <a href="<?= $this->Url->build(['controller' => 'clients', 'action' => 'transfer', $entity->getSource(), $primaryKey, 'redirect']) ?>">
            <i class="fa fa-random"></i>
        </a>
    </button>
    <?php endif; ?>
    <?php  if(isset($_IDENTITY) && $_IDENTITY->can('edit', $entity)): ?>
    <button type="button" class="btn btn-box-tool">
        <a href="<?= $this->Url->build(['action' => 'edit', $primaryKey]) ?>">
            <i class="fa fa-pencil"></i>
        </a>
    </button>
    <?php endif; ?>
    <button type="button" class="btn btn-box-tool">
        <a href="<?= (isset($_REDIRECT) && $_REDIRECT != null) ? $_REDIRECT : $this->Url->build(['action' => 'index']) ?>">
            <i class="fa fa-times"></i>
        </a>
    </button>
</div>
<?php endif; ?>