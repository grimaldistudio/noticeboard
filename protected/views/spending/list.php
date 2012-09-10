<?php
$this->breadcrumbs = array('Spese');
$this->pageTitle = "Elenco completo spese"; 
?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_advsearchform', array('model'=>$model)); ?>

<?php

 $this->widget('bootstrap.widgets.BootListView', array(
		'dataProvider'=> $model->search(),
		'itemView'=>'_item',   // refers to the partial view named '_proficiency'
                'template'=>"{sorter}\n{items}\n{pager}",        
		'id' => 'spendingslistview',
		'sortableAttributes'=>array(
				'title',
                                'spending_date'
		),
    ));
 ?>   

