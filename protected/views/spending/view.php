<?php $this->breadcrumbs = array('Spese' => array('spending/index'),  'Dettaglio'); ?>
<?php $this->pageTitle = "Vedi spesa"; ?>
<h1><?php echo $this->pageTitle; ?></h1>

<?php
$other_files = array();
foreach($model->listOtherDocuments() as $other_doc)
{
    $other_files[] = '<a href="'.Yii::app()->createUrl('/spending/downloadother', array('filename'=>$other_doc)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  '.$other_doc.' ('.$model->getOtherSize($other_doc).' KB)';
}
$other_files_link = implode('<br/>', $other_files);

$this->widget('bootstrap.widgets.BootDetailView', array(
    'data'=>$model,
    'attributes'=>array(
                        'title',
                        'receiver',
                        'amount' => array('label'=>$model->getAttributeLabel('amount'), 'type'=>'raw', 'value'=>$model->amount.' €'),
                        'spending_date' => array('label'=>$model->getAttributeLabel('spending_date'), 'type'=>'datetime', 'value' => $model->spending_date?strtotime($model->spending_date):'n/d'),
                        'attribution_norm',
                        'attribution_mod',
                        'office_id' => array('label'=>$model->getAttributeLabel('office_id'), 'value' => $model->office?$model->office->name:'n/d'),
                        'employee',
                        'description' => array('label' => $model->getAttributeLabel('description'), 'type'=>'html', 'value'=>$model->description),
                        'download_cv' => array('label'=>$model->getAttributeLabel('cv_name'), 'type'=>'raw', 'value'=>$model->hasCV()?'<a href="'.Yii::app()->createUrl('/spending/downloadcv', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getCVSize().' KB)':'n/d'),
                        'download_contract' => array('label'=>$model->getAttributeLabel('contract_name'), 'type'=>'raw', 'value'=>$model->hasContract()?'<a href="'.Yii::app()->createUrl('/spending/downloadcontract', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getContractSize().' KB)':'n/d'),
                        'download_project' => array('label'=>$model->getAttributeLabel('project_name'), 'type'=>'raw', 'value'=>$model->hasProject()?'<a href="'.Yii::app()->createUrl('/spending/downloadproject', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getProjectSize().' KB)':'n/d'),
                        'download_capitulate' => array('label'=>$model->getAttributeLabel('capitulate_name'), 'type'=>'raw', 'value'=>$model->hasCapitulate()?'<a href="'.Yii::app()->createUrl('/spending/downloadcapitulate', array('id'=>$model->id)).'" target="_blank"><img src="'.Yii::app()->baseUrl.'/images/pdficon_large.png" /></a>  ('.$model->getCapitulateSize().' KB)':'n/d'),
                        'download_others' => array('label'=>'Altra documentazione', 'type'=>'raw', 'value'=>$other_files_link)        
        ),
    'nullDisplay'=>'n/d'
));
?>