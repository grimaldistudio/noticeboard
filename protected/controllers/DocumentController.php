<?php

class DocumentController extends Controller{
    
    private $_model = null;
    
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        $this->layout = 'bootstrap_sidebar';
    }
    
    protected function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
            {
                $this->_model=Document::model()->findByPk($_GET['id']);
            }

            if($this->_model===null || !$this->_model->isActive())
                throw new CHttpException(404,'La pagina richiesta non esiste.');

        }

        return $this->_model;        
    }
    
    public function actionIndex()
    {

    $criteria = new CDbCriteria;

    if (isset($_REQUEST['sSearch']) && isset($_REQUEST['sSearch']{0})) {
        $criteria->addSearchCondition('publication_number', $_REQUEST['sSearch'], true, 'OR', 'LIKE');
        $criteria->addSearchCondition('subject', $_REQUEST['sSearch'], true, 'OR', 'LIKE');
    }    
    
    $criteria->addCondition('DATE(NOW()) BETWEEN DATE(publication_date_from) AND DATE(publication_date_to)');    
    $criteria->addCondition('status = 1'); 
 
    $sort = new EDTSort('Document', array('document_type'));
    $sort->defaultOrder = 'id';
    
    $pagination = new EDTPagination();

    $dataProvider = new CActiveDataProvider('Document', array(
        'criteria'      => $criteria,
        'pagination'    => $pagination,
        'sort'          => $sort,
    ));


 $columns = array(       
        array('name'=>'protocol_number'),
     
    );

$widget=$this->createWidget('ext.EDataTables.EDataTables', array(
 'id'            => 'table',
      'datatableTemplate' => "<'row'<'large-6 columns'l><'large-6 columns'f>r>t<'row'<'large-6 columns'i><'large-6 columns'p>>",
                             'itemsCssClass'=>'table table-striped table-bordered table-hover',
 'dataProvider'  => $dataProvider,
 'ajaxUrl'       => $this->createUrl('/document/index'),
     'pager'=>array('cssFile'=>false,
                       'header'=>'',
                       //'firstPageLabel'=>'&lt;&lt;',
                      // 'prevPageLabel'=>'&lt;',
                       //'nextPageLabel'=>'&gt;', 
                       //'lastPageLabel'=>'&gt;&gt;',
                       //'maxButtonCount'=>5,
                       'class'=>'pagination'),
    'pagerCssClass'=>'pagination pagination-centered', 
 'columns'       => $columns,
    
    
));

if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
  $this->render('list', array('widget' => $widget,));
  return;
} else {
  echo json_encode($widget->getFormattedData(intval($_REQUEST['sEcho'])));
  Yii::app()->end();
}


        /*if(!isset($_GET['ajax']))
            $this->render('list', $params);
        else
            $this->renderPartial('list', $params);                        */
    }
    
    
    public function actionFilterByDocumentType($id)
    {    
 
    $criteria = new CDbCriteria;

    if (isset($_REQUEST['sSearch']) && isset($_REQUEST['sSearch']{0})) {
        $criteria->addSearchCondition('publication_number', $_REQUEST['sSearch'], true, 'OR', 'LIKE');
        $criteria->addSearchCondition('identifier', $_REQUEST['sSearch'], true, 'OR', 'LIKE');
        $criteria->addSearchCondition('name', $_REQUEST['sSearch'], true, 'OR', 'LIKE');
    }
    
    $criteria->addSearchCondition('document_type', $id, true, 'AND');
    
    $criteria->addCondition('publication_requested = 1');
    $criteria->addCondition('status = 1');
    
    $criteria->addCondition('date(NOW()) >= date(publication_date_from) OR publication_date_from IS NULL');
 
    $sort = new EDTSort('Document', array('document_type'));
    $sort->defaultOrder = 'act_date DESC';
    
    $pagination = new EDTPagination();

    $dataProvider = new CActiveDataProvider('Document', array(
        'criteria'      => $criteria,
        'pagination'    => $pagination,
        'sort'          => $sort,
    ));


 $columns = array(       
      //  array('name'=>'protocol_number'),
        array(
            'name'=>'act_number',
            'type'=>'raw',
           'value'=>function($data) { 
                        return CHtml::link(($data->act_number)?$data->act_number:'Prot. N. '.$data->identifier, "", array("onclick"=>"javascript:showMe(".$data->id.",'ViewFilterByDocumentType')"));                   
                },  
                        ),
        array('name'=>'name'),
       array('name'=>'act_date'),
      array('name'=>'document_type',
              'value'=>'$data->document_type?$data->getTypeDesc($data->document_type):\'n/d\'',
             // 'filter'=>'$data->getTypeDesc($data->document_type)'
             ),
     //'publication_date_from',
     // 'publication_date_to',
       // array('name'=>'act_number'),
        array('name'=>'entity',
              //'filter'=>array_merge(array('0'=>Yii::app()->params['entity']), CHtml::listData(Entity::model()->findAll(), 'id', 'name')),
              'value'=>'$data->entity?$data->entity:Yii::app()->params[\'entity\']'
             ),
   //     array('name'=>'proposer_service_id',
   //           'filter'=>CHtml::listData(ProposerService::model()->findAll(), 'id', 'name'),
   //           'value'=>'$data->proposer_service?$data->proposer_service->name:\'n/d\''
   //          ),
//        array(
//            'class'=>'bootstrap.widgets.BootButtonColumn',
//            'htmlOptions'=>array('style'=>'width: 50px'),
//            'template'=>'{view}'
//        ),
    );

$widget=$this->createWidget('ext.EDataTables.EDataTables', array(
 'id'            => 'table',
      'datatableTemplate' => "<'row'<'large-6 columns'l><'large-6 columns'f>r>t<'row'<'large-6 columns'i><'large-6 columns'p>>",
                             'itemsCssClass'=>'table table-striped table-bordered table-hover',
 'dataProvider'  => $dataProvider,
 'ajaxUrl'       => $this->createUrl('/document/FilterByDocumentType/'.$id),
     'pager'=>array('cssFile'=>false,
                       'header'=>'',
                       //'firstPageLabel'=>'&lt;&lt;',
                      // 'prevPageLabel'=>'&lt;',
                       //'nextPageLabel'=>'&gt;', 
                       //'lastPageLabel'=>'&gt;&gt;',
                       //'maxButtonCount'=>5,
                       'class'=>'pagination'),
    'pagerCssClass'=>'pagination pagination-centered', 
 'columns'       => $columns,
    
    
));

if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
  $this->render('list', array('widget' => $widget,));
  return;
} else {
  echo json_encode($widget->getFormattedData(intval($_REQUEST['sEcho'])));
  Yii::app()->end();
}


        /*if(!isset($_GET['ajax']))
            $this->render('list', $params);
        else
            $this->renderPartial('list', $params);                        */
    }
    
    
    
    
    public function actionCategory($cat = 0)
    {
        if($cat==0)
        {
            $this->redirect('index');
        }
        
        // check if exists a document type with $doc_type->id==$cat
        if(($category = DocumentType::model()->findByPk($cat))==null)
        {
            throw new CHttpException(400, 'Tipologia documento non valida');
        }
        
        $model = new Document('search');
        $model->unsetAttributes();
        if(isset($_GET['Document']))
            $model->attributes = $_GET['Document'];

        $model->document_type_id = $cat;
        
        $params =array(
            'model'=>$model,
            'category'=>$category
        );

        if(!isset($_GET['ajax']))
            $this->render('list', $params);
        else
            $this->renderPartial('list', $params);                
    }
    
    public function actionView()
    {
        $this->layout = 'bootstrap_sidebar';
        $model = $this->loadModel();
              
        $pm = new PreviewManager($model);
        $model->total_pages = intval($pm->getDocumentInfo());
                 
        $this->render('view', array('model'=>$model));	        
    }
    
    public function actionViewFilterByDocumentType($id) {
        
        $this->layout = 'bootstrap_sidebar';
        $model = $this->loadModel();
              
        $pm = new PreviewManager($model);
        $model->total_pages = intval($pm->getDocumentInfo());
                 
        $this->render('view_filterbydocument', array('model'=>$model));
        
    }
    
    public function actionDownload()
    {
        $model = $this->loadModel();
        if($model->sync_file==1)
            $model->download();
        else
            throw new CHttpException(404, 'Il file non è disponibile per il download');
    }
    
    public function actionDownloadfiltered()
    {
        $model = $this->loadModel();        
        $model->download(true);
    }
    
    public function actionExport()
    {
        set_time_limit(600); // 10 minutes
        $model = new Document('search');
        $model->unsetAttributes();
        if(isset($_GET['Document']))
            $model->attributes = $_GET['Document'];

        $params =array(
            'model'=>$model,
        );

        $filename = $model->exportToCSV();
        if($filename===false)
        {
            throw new CHttpException(500, 'Impossibile esportare i documenti in CSV');
        }
        $this->redirect(Yii::app()->baseUrl.'/csv/'.$filename);
    }
    
      public function actionPreviewdoc($page = 0)
    {
        $model = $this->loadModel();
    
        $pm = new PreviewManager($model);
        header('Content-Type: image/jpeg');
        readfile($pm->getPreview(intval($page)));
    }
    
      public function actionPreviewdocfiltered($page = 0)
    {
        $model = $this->loadModel();
    
        $pm = new PreviewManager($model);
        header('Content-Type: image/jpeg');
        readfile($pm->getPreview(intval($page)));
    }
    
     public function actionViewpdf()
    {
        $model = $this->loadModel();      
        $model->download();
    }
    
     public function actionViewpdffiltered()
    {      
        $model = $this->loadModel();              
        $model->download();
    }
    
}
