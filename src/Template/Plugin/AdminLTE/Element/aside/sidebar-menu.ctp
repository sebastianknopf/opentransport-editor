<ul class="sidebar-menu" data-widget="tree">
    <li class="header"><?= __('General') ?></li>
    <li>
        <a href="<?= $this->Url->build(['_name' => 'index']) ?>">
            <i class="fa fa-dashboard"></i><span><?= __('Dashboard') ?></span>
        </a>
    </li>
    <?php if($_ACL->check($_IDENTITY->bindNode(null), 'Controllers', 'read')): ?>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-cogs"></i><span><?= __('System') ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <?php if($_IDENTITY->can('view', new \App\Model\Entity\Client())): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Clients', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Clients') ?></a></li>
            <?php endif; ?>
            <?php if($_IDENTITY->can('view', new \App\Model\Entity\Group())): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Groups', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Groups') ?></a></li>
            <?php endif; ?>
            <?php if($_IDENTITY->can('view', new \App\Model\Entity\User())): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Users') ?></a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php if($_ACL->check($_IDENTITY->bindNode(null), 'DataManagement', 'read')): ?>
    <li class="header"><?= __('Data Management') ?></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-map"></i><span><?= __('Network') ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <?php if($_IDENTITY->can('view', \App\Model\Entity\Stop::getInstance())): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Stops', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Stations') ?></a></li>
            <?php endif; ?>
            <?php if($_IDENTITY->can('view', \App\Model\Entity\Shape::getInstance())): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Shapes', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Shapes') ?></a></li>
            <?php endif; ?>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-bus"></i><span><?= __('Trip Data') ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <?php if($_IDENTITY->can('view', \App\Model\Entity\Service::getInstance($_IDENTITY->client_id))): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Services', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Services') ?></a></li>
            <?php endif; ?>
            <?php if($_IDENTITY->can('view', \App\Model\Entity\Agency::getInstance($_IDENTITY->client_id))): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Agencies', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Agencies') ?></a></li>
            <?php endif; ?>
            <?php if($_IDENTITY->can('view', \App\Model\Entity\Route::getInstance($_IDENTITY->client_id))): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Routes', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Routes') ?></a></li>
            <?php endif; ?>
            <?php if($_IDENTITY->can('view', \App\Model\Entity\Trip::getInstance($_IDENTITY->client_id))): ?>
                <li><a href="<?= $this->Url->build(['controller' => 'Trips', 'action' => 'index']) ?>"><i class="fa fa-circle-o"></i> <?= __('Trips') ?></a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php if($_ACL->check($_IDENTITY->bindNode(null), 'DataExchange', 'read')): ?>
    <li class="header"><?= __('Data Exchange') ?></li>
    <li>
        <a href="<?= $this->Url->build(['controller' => 'ApiLogs', 'action' => 'index']) ?>">
            <i class="fa fa-sitemap"></i><span><?= __('REST API') ?></span>
        </a>
    </li>
    <?php endif; ?>
</ul>