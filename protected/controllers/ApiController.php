<?php

class ApiController extends Controller{
    
    public function requestTypeRules()
    {
        return array(
            'syncdocument' => array('POST', 'PUT'),
            'syncspending' => array('POST', 'PUT'),
            'deletedocument' => array('DELETE'),
            'deletespending' => array('DELETE')
        );
    }
    
    public function filters()
    {
        return array(
            'requestType',
            'apiAuth'
        );
    }
    public function actionSyncdocument()
    {
        $errors = Document::syncWithMaster($_POST);
        if($errors === true)
        {
            echo "SUCCESS";
            Yii::app()->end();
        }
        else
        {
            if(count($errors)>0)
                throw new CHttpException(500, implode("\n", $errors));
            else
                throw new CHttpException(500, "Undefined error during document synchronization");
        }
    }
    
    public function actionSyncspending()
    {
        $errors = Spending::syncWithMaster($_POST);
        if($errors === true)
        {
            echo "SUCCESS";
            Yii::app()->end();
        }
        else
        {
            if(count($errors)>0)
                throw new CHttpException(500, implode("\n", $errors));
            else
                throw new CHttpException(500, "Undefined error during spending synchronization");
        }
    }
    
    public function actionDeletedocument()
    {
        if(isset($_GET['id']))
        {
            $document = Document::model()->findByAttributes(array('link_id'=>$_GET['id']));
            if($document)
            {
                if($document->delete())
                {
                    echo "SUCCESS";
                    Yii::app()->end();
                }
                else
                {
                    throw new CHttpException(500, "Unable to delete document");
                }
            }
            else
            {
                // if not found return true anyway because the db is already in sync                
                echo "SUCCESS";
                Yii::app()->end();
            }
        }
        throw new CHttpException(404, 'Missing document identifier');
    }
    
    public function actionDeletespending()
    {
        if(isset($_GET['id']))
        {
            $spending = Spending::model()->findByAttributes(array('link_id'=>$_GET['id']));
            if($spending)
            {
                if($spending->delete())
                {
                    echo "SUCCESS";
                    Yii::app()->end();
                }
                else
                {
                    throw new CHttpException(500, "Unable to delete spending");
                }
            }
            else
            {
                // if not found return true anyway because the db is already in sync
                echo "SUCCESS";
                Yii::app()->end();
            }
        }
        throw new CHttpException(404, 'Missing spending identifier');
    }
    
    public function filterRequestType($filterchain)
    {
        $rules = $this->requestTypeRules();
        if(array_key_exists($this->action->id, $rules))
        {
            if(!in_array(Yii::app()->request->requestType, $rules[$this->action->id]))
            {
                throw new CHttpException(405, 'Invalid request method: '.Yii::app()->request->requestType);
            }
        }
        $filterchain->run();
    }
    
    public function filterApiAuth($filterchain)
    {
        $key = Yii::app()->params['apiKey'];
     
        // get headers
        $verb = Yii::app()->request->requestType;
        $uri = Yii::app()->request->requestUri;
        $headers = getallheaders();
        $content_md5 = isset($headers['Content-MD5'])?$headers['Content-MD5']:'';
        $date = isset($headers['Date'])?$headers['Date']:'';
        $authentication = isset($headers['Authorization'])?$headers['Authorization']:'';

        $error = "";
        if($authentication!='' && $date!='')
        {
            $time = strtotime($date);
            if($time!==false)
            {
                $gm_time = time();
                if($gm_time>=$time && $gm_time<=$time+300)
                {
                    // build string_to_sign
                    $string_to_sign = $verb."\n".
                                    $content_md5."\n".
                                    $date."\n".
                                    $uri;

                    // hash
                    $hash = "DMS ".Yii::app()->params['apiUsername'].":".hash_hmac('ripemd160', $string_to_sign, $key);
                    
                    if($hash==$authentication)
                    {
                        $filterchain->run();
                    }
                    else
                    {
                        $error = "Hash mismatch";
                    }

                }
                else
                {
                    $error = "signature expired";
                }
            }
            else
            {
                $error = "invalid date";
            }

        }
        else
        {
            $error = "missing required headers";
        }
        
        throw new CHttpException(403, 'Unauthorized: '.$error);
    }
    
    
    
}