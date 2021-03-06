<?php $this->breadcrumbs = array($model->document_type->name => array('document/category', 'cat'=>$model->document_type),  'Dettaglio'); ?>
<?php $this->pageTitle = "Albo pretorio"; ?>

<div class="row">
    <div class="large-7 small-12 columns title-block">
        
        <h2><?php echo $model->name; ?></h2>

        <?php
        $this->widget('zii.widgets.CDetailView', array(
            'data'=>$model,
            'itemCssClass'=>'table table-striped table-condensed',
            'attributes'=>array(
                                'name',
                               // 'protocol_number', 
                                'act_number' => array('label'=>$model->getAttributeLabel('act_number'), 'value' => $model->identifier ) ,
                                'act_date' => array('label'=>$model->getAttributeLabel('act_date'), 'value' => $model->act_date?($model->act_date):null),
                                'publication_date_from' => array('label'=>$model->getAttributeLabel('publication_date_from'), 'type'=>'datetime', 'value' => $model->publication_date_from?strtotime($model->publication_date_from):null),
                                'publication_date_to' => array('label'=>$model->getAttributeLabel('publication_date_to'), 'type'=>'datetime', 'value' => $model->publication_date_to?strtotime($model->publication_date_to):null),
                                'entity' => array('label'=>$model->getAttributeLabel('entity'), 'value' => $model->entity?$model->entity:Yii::app()->params['entity']),
                                //'proposer_service_id' => array('label'=>$model->getAttributeLabel('proposer_service_id'), 'value' => $model->proposer_service?$model->proposer_service->name:'n/d'),
                                'document_type' => array('label'=>$model->getAttributeLabel('document_type'), 'value' => $model->document_type?$model->getTypeDesc($model->document_type):'n/d'),
                                'description' => array('label' => $model->getAttributeLabel('description'), 'type'=>'html', 'value'=>$model->description),
                                'download' => array('label'=>'Documenti', 'type'=>'raw', 'value'=>'<a href="'.Yii::app()->createUrl('/document/downloadfiltered', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getDocumentSize().' KB)', 'visible'=>true)
                ),
            'nullDisplay'=>'n/d'
        ));
        ?>
        
    </div>
    
    <div class="large-5 small-12 columns">
        
           <?php $this->renderPartial('_preview', array(
                                            'total_pages'=>$model->num_pages, 
                                            'full_size_url'=> array('document/viewpdffiltered', 'id'=>$model->id), 
                                            'preview_url'=>Yii::app()->createUrl('document/previewdocfiltered', array('id'=>$model->id,'t'=>time()))
                                    )); ?>
        
    </div>
    
</div>