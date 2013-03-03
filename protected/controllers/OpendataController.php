<?php

class OpendataController extends Controller{

    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        $this->layout = 'bootstrap_sidebar';
    }

    public function actionIndex()
    {
    	$openData = new OpenData();
    	$categories = $openData->getCategories();
    	$categoryYears = array();
    	foreach($categories as $category)
    	{
			$categoryYears[$category['id']] = array(
													'minYear' => $openData->getMinDocumentYearByCategory($category['id']), 
													'name' => $category['name']
												);  		
    	}

    	$minSpendingYear = $openData->getMinSpendingYear();

    	$data = array(
    		'minSpendingYear' => $minSpendingYear,
    		'categoryYears' => $categoryYears
    	);
    	
    	$this->render('index', $data);
    }

    public function actionDocuments($year, $category, $format = 'json')
    {
        if($this->validateYear($year))
        {
            if($this->validateCategory($category))
            {
                if($this->validateFormat($format))
                {
                    $openData = new OpenData();
                    $records = $openData->getDocuments($year, $category);
                    $filename = 'documenti-'.$year.'-'.$category;
                    $openData->exportDocuments($filename, $records, $format);
                    Yii::app()->end;
                }
            }
        }
       throw new CHttpException(400, 'Formato parametri non corretto');

    }

    public function actionExpenses($year, $format = 'json')
    {
        if($this->validateYear($year))
        {
            if($this->validateFormat($format))
            {
                $openData = new OpenData();
                $records = $openData->getExpenses($year);
                $filename = 'documenti-'.$year;                    
                $openData->exportExpenses($filename, $records, $format);
                Yii::app()->end;
            }
        }
       throw new CHttpException(400, 'Formato parametri non corretto');
    }

    protected function validateYear($year)
    {
        $year = (int)$year;
        if(is_int($year))
            return true;

        return false;
    }

    protected function validateFormat($format)
    {
        if($format=='json' || $format=='csv')
            return true;

        return false;
    }

    protected function validateCategory($category)
    {
        $category = (int)$category;        
        if(is_int($category))
            return true;

        return false;
    }
}

?>