<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/bootstrap';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        public function __construct($id, $module = null) {
            parent::__construct($id, $module);
            
            if(Yii::app()->params['status']!=1)
            {
                die('Applicazione non attiva. Contattare support@engisolution.it per maggior informazioni.');
            }
        }
        
        public function getMenu()
        {
        $items = array();
        
        // home
        $home = array(
                    'label'=>'Documenti', 
                    'icon'=>'file', 
                    'url'=>Yii::app()->homeUrl, 
                    'active'=>$this->id=='document' && $this->action->id=='index',
                    'class' => 'nav-header'            
                );
        $items[] = $home;
        
        foreach(DocumentType::model()->findAll() as $document_type)
        {
            $category = array(
                'label'=>$document_type->name, 
                'url'=>Yii::app()->createUrl('/document/category', array('cat'=>$document_type->id)), 
                'active'=>$this->id=='document' && $this->action->id=='category' && $_GET['cat']==$document_type->id
            );            

            $items[] = $category;
        }

        $spending = array(
            'label'=>'Spese', 
            'icon'=>'briefcase',
            'url'=>array('spending/index'), 
            'active'=>$this->id=='spending',
            'class' => 'nav-header'
        );
        $items[] = $spending;
        
        $this->menu = $items;
        return $this->menu;
    }

}