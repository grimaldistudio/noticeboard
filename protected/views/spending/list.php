<?php
$this->breadcrumbs = array('Spese');
$this->pageTitle = "Elenco completo spese"; 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->widget('bootstrap.widgets.BootGridView', array(
    'id'=>'spendings_gridview',
    'dataProvider'=>$model->search(),
    'template'=>"{items}\n{pager}",
    'itemsCssClass'=>'table table-striped table-bordered table-condensed',
    'columns'=>array(
        array('name'=>'title'),
        array('name'=>'receiver'),
        array('name'=>'amount'),
        array('name'=>'spending_date',
             'filter'=>false,
              'type' => 'datetime',
              'value' => '$data->spending_date?strtotime($data->spending_date):\'n/a\'',
             ),
        array(
            'class'=>'bootstrap.widgets.BootButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'template'=>'{view}'
        ),
    ),
    'filter'=>$model
)); ?>
