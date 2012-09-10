<?php $this->breadcrumbs = array($model->document_type->name => array('document/category', 'cat'=>$model->document_type_id),  'Dettaglio'); ?>
<?php $this->pageTitle = "Vedi documento"; ?>
<h1><?php echo $this->pageTitle; ?></h1>

<?php
$this->widget('bootstrap.widgets.BootDetailView', array(
    'data'=>$model,
    'attributes'=>array(
                        'subject',
                        'protocol_number', 
                        'act_number',
                        'act_date' => array('label'=>$model->getAttributeLabel('act_date'), 'type'=>'datetime', 'value' => strtotime($model->act_date)),
                        'publication_date_from' => array('label'=>$model->getAttributeLabel('publication_date_from'), 'type'=>'datetime', 'value' => strtotime($model->publication_date_from)),
                        'publication_date_to' => array('label'=>$model->getAttributeLabel('publication_date_to'), 'type'=>'datetime', 'value' => strtotime($model->publication_date_to)),
                        'entity_id' => array('label'=>$model->getAttributeLabel('entity_id'), 'value' => $model->entity?$model->entity->name:Yii::app()->params['entity']),
                        'proposer_service_id' => array('label'=>$model->getAttributeLabel('proposer_service_id'), 'value' => $model->proposer_service?$model->proposer_service->name:'n/d'),
                        'document_type_id' => array('label'=>$model->getAttributeLabel('document_type_id'), 'value' => $model->document_type?$model->document_type->name:'n/d'),
                        'description' => array('label' => $model->getAttributeLabel('description'), 'type'=>'html', 'value'=>$model->description),
                        'download' => array('label'=>'File', 'type'=>'raw', 'value'=>'<a href="'.Yii::app()->createUrl('/document/download', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getDocumentSize().' KB)')
        ),
    'nullDisplay'=>'n/d'
));
?>