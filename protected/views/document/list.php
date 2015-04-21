<?php if(isset($category))
    $this->breadcrumbs = array($category->name);
else
    $this->breadcrumbs = array('Documenti');
?>
<?php $this->pageTitle = "Albo pretorio"; ?>
<?php if(isset($category))
    $this->pageTitle = $category->name; ?>

<article>
    <p>Il motore di ricerca è attivo sui campi "Numero di pubblicazione" e "Oggetto".</p>
</article>

<?php $widget->run(); ?>

<a name="detail-view"></a>
<div id="detail"></div>

<?php/* $this->widget('bootstrap.widgets.BootGridView', array(
    'id'=>'documents_gridview',
    'dataProvider'=>$model->search(),
    'template'=>"{items}\n{pager}",
    'itemsCssClass'=>'table table-striped table-bordered table-condensed',
    'columns'=>array(
        array('name'=>'document_type_id',
              'value'=>'$data->document_type?$data->document_type->name:\'n/d\'',
              'filter'=>CHtml::listData(DocumentType::model()->findAll(), 'id', 'name')
             ),
        array('name'=>'protocol_number'),
        array('name'=>'publication_number'),
        array('name'=>'subject'),
        array('name'=>'act_number'),
        array('name'=>'entity_id',
              'filter'=>array_merge(array('0'=>Yii::app()->params['entity']), CHtml::listData(Entity::model()->findAll(), 'id', 'name')),
              'value'=>'$data->entity?$data->entity->name:Yii::app()->params[\'entity\']'
             ),
        array('name'=>'proposer_service_id',
              'filter'=>CHtml::listData(ProposerService::model()->findAll(), 'id', 'name'),
              'value'=>'$data->proposer_service?$data->proposer_service->name:\'n/d\''
             ),
        array(
            'class'=>'bootstrap.widgets.BootButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'template'=>'{view}'
        ),
    ),
    'filter'=>$model
)); */?>

