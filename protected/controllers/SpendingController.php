<?php

class SpendingController extends Controller{
    
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
                $this->_model=Spending::model()->findByPk($_GET['id']);
            }

            if($this->_model===null || !$this->_model->isActive())
                throw new CHttpException(404,'La pagina richiesta non esiste.');

        }

        return $this->_model;        
    }
    
    public function actionIndex()
    {
        $model = new Spending('search');
        $model->unsetAttributes();
        if(isset($_GET['Spending']))
            $model->attributes = $_GET['Spending'];

        $params =array(
            'model'=>$model,
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
    
    public function actionDownloadCV()
    {
        $model = $this->loadModel();
        $model->downloadCV();
    }
    
    public function actionDownloadContract()
    {
        $model = $this->loadModel();
        $model->downloadContract();
    }    

    public function actionDownloadCapitulate()
    {
        $model = $this->loadModel();
        $model->downloadCapitulate();
    }        
    
    public function actionDownloadOther($filename)
    {
        $model = $this->loadModel();
        $model->downloadOther($filename);
    }    
    
    public function actionExport()
    {
        set_time_limit(600); // 10 minutes
        $model = new Spending('search');
        $model->unsetAttributes();
        if(isset($_GET['Spending']))
            $model->attributes = $_GET['Spending'];

        $params =array(
            'model'=>$model,
        );

        $filename = $model->exportToCSV();
        if($filename===false)
        {
            throw new CHttpException(500, 'Impossibile esportare le spese in CSV');
        }
        $this->redirect(Yii::app()->baseUrl.'/csv/'.$filename);
    }
}