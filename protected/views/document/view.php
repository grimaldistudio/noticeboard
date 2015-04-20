<?php $this->breadcrumbs = array($model->document_type->name => array('document/category', 'cat'=>$model->document_type_id),  'Dettaglio'); ?>
<?php $this->pageTitle = "Albo pretorio"; ?>

<a href="">Indietro<i class=""></i></a>
<h2><?php echo $model->subject; ?></h2>


<?php
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
                        'subject',
                        'protocol_number', 
                        'act_number',
                        'act_date' => array('label'=>$model->getAttributeLabel('act_date'), 'type'=>'datetime', 'value' => $model->act_date?strtotime($model->act_date):null),
                        'publication_date_from' => array('label'=>$model->getAttributeLabel('publication_date_from'), 'type'=>'datetime', 'value' => $model->publication_date_from?strtotime($model->publication_date_from):null),
                        'publication_date_to' => array('label'=>$model->getAttributeLabel('publication_date_to'), 'type'=>'datetime', 'value' => $model->publication_date_to?strtotime($model->publication_date_to):null),
                        'entity_id' => array('label'=>$model->getAttributeLabel('entity_id'), 'value' => $model->entity?$model->entity->name:Yii::app()->params['entity']),
                        'proposer_service_id' => array('label'=>$model->getAttributeLabel('proposer_service_id'), 'value' => $model->proposer_service?$model->proposer_service->name:'n/d'),
                        'document_type_id' => array('label'=>$model->getAttributeLabel('document_type_id'), 'value' => $model->document_type?$model->document_type->name:'n/d'),
                        'description' => array('label' => $model->getAttributeLabel('description'), 'type'=>'html', 'value'=>$model->description),
                        'download' => array('label'=>'File', 'type'=>'raw', 'value'=>'<a href="'.Yii::app()->createUrl('/document/download', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getDocumentSize().' KB)', 'visible'=>$model->sync_file==1)
        ),
    'nullDisplay'=>'n/d'
));
?>