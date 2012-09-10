<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestione Installazioni -- Albo Pretorio --">
    <meta name="author" content="Engineering Solution (fabrizio.dammassa@gmail.com)">

    <!-- Le styles -->
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
<!--    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/ico/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo Yii::app()->request->baseUrl; ?>/ico/bootstrap-apple-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Yii::app()->request->baseUrl; ?>/ico/bootstrap-apple-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Yii::app()->request->baseUrl; ?>/ico/bootstrap-apple-114x114.png">

-->
    <?php Yii::app()->clientScript->registerCssFile('/css/custom.css'); ?>
  </head>

  <body>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span-12 header">
                <h1><?php echo Yii::app()->name; ?></h1>
                <p class="info_box">
                    <b><?php echo Yii::app()->params['entity']; ?></b><br/>
                    E-mail: <?php echo Yii::app()->params['contactEmail']; ?>
                </p>
            </div>
        </div>
        
        <div class="row-fluid">
            <div class="span3">
            <div class="well sidebar-nav">
                <?php $this->widget('bootstrap.widgets.BootMenu', array(
                    'type' => 'list',
                    'items' => $this->getMenu(),
                ));
                ?>
            </div><!--/.well -->
            </div><!--/span-->
        
        <div class="span9">
            <?php $this->widget('bootstrap.widgets.BootBreadcrumbs', array('links'=>$this->breadcrumbs));?>
            <?php $this->widget('bootstrap.widgets.BootAlert'); ?>

            <?php echo $content; ?>
        </div><!--/span-->
      </div><!--/row-->
      
        <hr>

      <footer>
        <p>&copy; <?php echo Yii::app()->params['entity']; ?> <?php echo date('Y'); ?> - Realizzato da Engineering Solution srl</p>
      </footer>

    </div> <!-- /container -->

  </body>
</html>