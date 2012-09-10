<?php if(isset($category))
    $this->breadcrumbs = array($category->name);
else
    $this->breadcrumbs = array('Documenti');
?>
<?php $this->pageTitle = "Elenco completo documenti"; ?>
<?php if(isset($category))
    $this->pageTitle = $category->name; ?>

<h1><?php echo $this->pageTitle; ?></h1>

<?php $this->renderPartial('_advsearchform', array('model'=>$model)); ?>

<?php

 $this->widget('bootstrap.widgets.BootListView', array(
		'dataProvider'=> $model->search(),
		'itemView'=>'_item',   // refers to the partial view named '_proficiency'
                'template'=>"{sorter}\n{items}\n{pager}",        
		'id' => 'documentslistview',
		'sortableAttributes'=>array(
				'protocol_number',
                                'publication_date_from',
                                'publication_date_to',
                                'proposer_service_id',
                                'document_type_id'
		),
    ));
 ?>   

