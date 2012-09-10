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
        $model = new Document('search');
        $model->unsetAttributes();
        if(isset($_GET['Document']))
            $model->attributes = $_GET['Document'];

        $params =array(
            'model'=>$model,
        );

        if(!isset($_GET['ajax']))
            $this->render('list', $params);
        else
            $this->renderPartial('list', $params);                        
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
        $this->layout = "bootstrap";
        $model = $this->loadModel();
        $this->render('view', array('model'=>$model));	        
    }
    
    public function actionDownload()
    {
        $model = $this->loadModel();
        $model->download();
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
}