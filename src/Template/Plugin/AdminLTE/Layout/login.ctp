<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo Configure::read('Theme.title'); ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Facivon -->
        <link rel="icon" type="image/x-icon" href="<?= $this->Url->image('favicon.ico') ?>" />
        <!-- Bootstrap 3.3.7 -->
        <?php echo $this->Html->css('AdminLTE./bower_components/bootstrap/dist/css/bootstrap.min'); ?>
        <!-- Font Awesome -->
        <?php echo $this->Html->css('AdminLTE./bower_components/font-awesome/css/font-awesome.min'); ?>
        <!-- Ionicons -->
        <?php echo $this->Html->css('AdminLTE./bower_components/Ionicons/css/ionicons.min'); ?>
        <!-- Theme style -->
        <?php echo $this->Html->css('AdminLTE.AdminLTE.min'); ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php echo $this->fetch('css'); ?>
        <style>
            body {
                overflow: hidden;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo $this->Url->build(); ?>"><?php echo Configure::read('Theme.logo.large') ?></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Flash->render('auth'); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <!-- jQuery 3 -->
        <?php echo $this->Html->script('AdminLTE./bower_components/jquery/dist/jquery.min'); ?>
        <!-- Bootstrap 3.3.7 -->
        <?php echo $this->Html->script('AdminLTE./bower_components/bootstrap/dist/js/bootstrap.min'); ?>
        <?php echo $this->fetch('script'); ?>
        <?php echo $this->fetch('scriptBottom'); ?>
    </body>
</html>