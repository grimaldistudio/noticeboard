<?php

class Spending extends CActiveRecord{
    
    const ENABLED = 1;
    const DISABLED = 0;

    public $spending_date_from;
    public $spending_date_to;
    
    public $amount_from;
    public $amount_to;
    
    /**
    * Returns the static model of the specified AR class.
    * @return CActiveRecord the static model class
    */
    public static function model($className=__CLASS__)
    {
        return CActiveRecord::model($className);
    }

    /**
    * @return string the associated database table name
    */
    public function tableName()
    {
        return 'spendings';
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array(
            array('title,amount,spending_date_from,spending_date_to,receiver,employee,office_id', 'safe', 'on'=>'search'),
            array('amount_from,amount_to', 'match', 'pattern'=>'/^[0-9]+(\.[0-9]{0,2})?$/', 'on'=>'search'),
            array('spending_date_from', 'date', 'format'=>'dd/MM/yyyy', 'timestampAttribute'=>'spending_date_from', 'allowEmpty'=>true, 'on'=>'search'),
            array('spending_date_to', 'date', 'format'=>'dd/MM/yyyy', 'timestampAttribute'=>'spending_date_to', 'allowEmpty'=>true, 'on'=>'search')            
        );
    }

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        return array(
            'office' => array(self::BELONGS_TO, 'SpendingOffice', 'office_id')
        );
    }

    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'title' => 'Oggetto',
            'receiver' => 'Beneficiario',
            'amount' => 'Importo',
            'attribution_norm' => 'Norma di attribuzione',
            'attribution_mod' => 'Modalità di attribuzione',
            'employee' => 'Responsabile procedimento',
            'office_id' => 'Uffico responsabile',
            'description' => 'Descrizione',
            'cv_name' => 'CV Incaricato',
            'contract_name' => 'Contratto',
            'project_name' => 'Progetto',
            'capitulate_name' => 'Capitolato',
            'status' => 'Stato',
            'spending_date' => 'Data', 
            'date_created' => 'Data di creazione',
            'last_updated' => 'Ultimo aggiornamento',
            'spending_date_from' => 'Data da',
            'spending_date_to' => 'Data a',
            'amount_from' => 'Importo min',
            'amount_to' => 'Importo max'
        );
    }

    public function getStatusArray()
    {
        return array(
            self::DISABLED => 'Disabilitato',
            self::ENABLED => 'Attivo'
        );
    }
    
    public function getDocumentSize($path)
    {
        return sprintf("%.2f", filesize($path)/1000.0);
    }
    
    public function getStatusDesc()
    {
        if($this->status==self::ENABLED)
            return "Attivo";
        else
            return "Disabilitato";
    }
    
    public function beforeSave()
    {
        if ($this->isNewRecord){
            $this->date_created = new CDbExpression('CURRENT_TIMESTAMP');
        }
        $this->last_updated = new CDbExpression('CURRENT_TIMESTAMP');
        return parent::beforeSave();
    }
    
    public function search()
    {
        $this->validate();
        $criteria=new CDbCriteria;

        $criteria->with = array('office');
        
        if(!$this->hasErrors('spending_date_from') && $this->spending_date_from)
            $criteria->addCondition("spending_date>='".date('Y-m-d H:i:s', $this->spending_date_from)."'");
        
        if(!$this->hasErrors('spending_date_to') && $this->spending_date_to)
            $criteria->addCondition("spending_date<='". date('Y-m-d H:i:s', $this->spending_date_to)."'");
        
        if(!$this->hasErrors('amount_from') && $this->amount_from)
        {
            $criteria->addCondition("amount>='". $this->amount_from."'");            
        }
        
        if(!$this->hasErrors('amount_to') && $this->amount_to)
        {
            $criteria->addCondition("amount<='". $this->amount_to."'");            
        }
        
        $criteria->compare('title',$this->title,true);
        $criteria->compare('receiver',$this->receiver,true);
        $criteria->compare('employee',$this->employee,true);
        $criteria->compare('attribution_norm',$this->attribution_norm,true);        
        $criteria->compare('attribution_mod',$this->attribution_mod,true);                

        $criteria->compare('office_id', $this->office_id);
        
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.spending_date DESC',
            ),
            'pagination'=>array(
                'pageSize'=>5
            ),
        ));			
    }
    
    public function exportToCSV()
    {
        $this->validate();
        $sql = "SELECT VT.* FROM (SELECT '".$this->getAttributeLabel('title')."', ".
                       "'".$this->getAttributeLabel('receiver')."', ".
                       "'".$this->getAttributeLabel('amount')."', ".                
                       "'".$this->getAttributeLabel('attribution_norm')."',".
                       "'".$this->getAttributeLabel('office_id')."', ".
                       "'".$this->getAttributeLabel('employee')."', ".
                       "'".$this->getAttributeLabel('attribution_mod')."', ".
                       "'".$this->getAttributeLabel('description')."', ".
                       "'".$this->getAttributeLabel('spending_date')."' UNION ";
        
        $sql .= " ( SELECT 
                        title, 
                        receiver, 
                        amount, 
                        attribution_norm, 
                        IFNULL(o.name, 'n/d'), 
                        employee,
                        attribution_mod,
                        description,
                        IF(spending_date='0000-00-00 00:00:00', 'n/d', DATE_FORMAT(spending_date, '%d-%m-%Y'))                      
                    FROM spendings s
                    LEFT JOIN spending_offices o ON s.office_id = o.id
                    WHERE status = 1 ";

        $where_conditions = "";

        if(!$this->hasErrors('spending_date_from') && $this->spending_date_from)
        {
            $where_conditions .= " AND spending_date>='".date('Y-m-d H:i:s', $this->spending_date_from)."'";
        }
        
        if(!$this->hasErrors('spending_date_to') && $this->spending_date_to)
        {
            $where_conditions .= " AND spending_date<='".date('Y-m-d H:i:s', $this->spending_date_to)."'";
        }
        
        $params = array();
        if(!$this->hasErrors('amount_from') && $this->amount_from)
        {
            $where_conditions .= " AND amount>= :amount_from";
            $params[':amount_from'] = $this->amount_from;
        }
        
        if(!$this->hasErrors('amount_to') && $this->amount_to)
        {
            $where_conditions .= " AND amount<= :amount_to";
            $params[':amount_to'] = $this->amount_to;
        } 
        
        if($this->title)
        {
            $where_conditions .= " AND title LIKE :title";
            $params[':title'] = '%'.$this->title.'%';
        }
        
        if($this->receiver)
        {
            $where_conditions .= " AND receiver LIKE :receiver";
            $params[':receiver'] = '%'.$this->receiver.'%';
        }
        
        if($this->employee)
        {
            $where_conditions .= " AND employee LIKE :employee";
            $params[':employee'] = '%'.$this->employee.'%';
        }
        
        if($this->attribution_norm)
        {
            $where_conditions .= " AND attribution_norm LIKE :attribution_norm";
            $params[':attribution_norm'] = '%'.$this->attribution_norm.'%';
        }
        
        if($this->attribution_mod)
        {
            $where_conditions .= " AND attribution_mod LIKE :attribution_mod";
            $params[':attribution_mod'] = '%'.$this->attribution_mod.'%';
        }
        
        if($this->office_id>0)
        {
            $where_conditions .= " AND office_id = :office_id";
            $params[':office_id'] = $this->office_id;
        }
        
        $order = " ORDER BY s.id ASC";
        $limit = " LIMIT 10000 )) AS VT ";
        $outfile = "export_spese_".date('d-m-Y_H:i:s').'.csv'; 
        $outfilepath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'csv'.DIRECTORY_SEPARATOR.$outfile;
        $csv = " INTO OUTFILE '".$outfilepath."'
                 FIELDS TERMINATED BY ','
                 ENCLOSED BY '\"'
                 LINES TERMINATED BY '\\n'    
            ";
        
        $sql .= $where_conditions . $order .$limit.$csv;
        
        try {
            Yii::app()->db->createCommand($sql)->execute($params);   
            return $outfile;
        }
        catch(Exception $e)
        {
            Yii::log("Error during spendings export: ".$e->getMessage(), 'error');
            return false;
        }
    }
    
    public function listOtherDocuments()
    {
        $other_path = $this->getOtherDir();
        $files = array();
        if(is_dir($other_path))
            $files =  scandir($other_path);
        else
            $files = array();
        
        $ret = array();
        foreach($files as $file)
        {
            if($file=='.' || $file=='..')
            {
                continue;
            }
            $ret[] = $file;
        }
        return $ret;
    }
    
    public function getOtherDir()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'spendings'.DIRECTORY_SEPARATOR.$this->link_id.DIRECTORY_SEPARATOR.'others';        
    }
    
    public function getOtherPath($name)
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'spendings'.DIRECTORY_SEPARATOR.$this->link_id.DIRECTORY_SEPARATOR.'others'.DIRECTORY_SEPARATOR.$name;
    }
    
    public function getOtherSize($name)
    {
        $path = $this->getOtherPath($name);
        $size = filesize($path);
        return sprintf("%.2f", $size/1000.00);
    }
    
    public function downloadOther($name, $force_download = false)
    {
        $path = $this->getOtherPath($name);
        $this->download($path, $force_download);
    }
    
    public function getCapitulateName()
    {
        return $this->capitulate_name;
    }
    
    public function getCapitulatePath()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'spendings'.DIRECTORY_SEPARATOR.$this->link_id.DIRECTORY_SEPARATOR.$this->getCapitulateName();
    }
    
    public function getCapitulateSize()
    {
        $path = $this->getCapitulatePath();
        $size = filesize($path);
        return sprintf("%.2f", $size/1000.00);
    }

    public function downloadCapitulate($force_download = false)
    {
        $path = $this->getCapitulatePath();
        $this->download($path, $force_download);
    }
    
    public function getContractName()
    {
        return $this->contract_name;
    }
    
    public function getContractPath()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'spendings'.DIRECTORY_SEPARATOR.$this->link_id.DIRECTORY_SEPARATOR.$this->getContractName();
    }
    
    public function downloadContract($force_download = false)
    {
        $path = $this->getContractPath();
        $this->download($path, $force_download);
    }
    
    public function getContractSize()
    {
        $path = $this->getContractPath();
        $size = filesize($path);
        return sprintf("%.2f", $size/1000.00);
    }
    
    public function getProjectName()
    {
        return $this->project_name;
    }
    
    public function getProjectPath()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'spendings'.DIRECTORY_SEPARATOR.$this->link_id.DIRECTORY_SEPARATOR.$this->getProjectName();
    }
    
    public function downloadProject($force_download = false)
    {
        $path = $this->getProjectPath();
        $this->download($path, $force_download);
    }
    
    public function getProjectSize()
    {
        $path = $this->getProjectPath();
        $size = filesize($path);
        return sprintf("%.2f", $size/1000.00);
    }
    
    public function getCVName()
    {
        return $this->cv_name;
    }
    
    public function getCVPath()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'spendings'.DIRECTORY_SEPARATOR.$this->link_id.DIRECTORY_SEPARATOR.$this->getCVName();
    }
    
    public function downloadCV($force_download = false)
    {
        $path = $this->getCVPath();
        $this->download($path, $force_download);
    }
    
    public function getCVSize()
    {
        $path = $this->getCVPath();
        $size = filesize($path);
        return sprintf("%.2f", $size/1000.00);
    }
    
    private function download($path, $force_download)
    {
        if(file_exists($path)){
            if($force_download)
                header('Content-disposition: attachment; filename='.$this->getCVName());
            header('Content-type: application/pdf');
            readfile($path);
            Yii::app()->end();
        }
        else {
            throw new CHttpException(404, 'File non trovato');
        }
    }
    

    public function isActive()
    {
        return $this->status == self::ENABLED;
    }
    
    public static function syncWithMaster($master_spending)
    {
        // update fields
        $errors = Spending::validateMasterSpending($master_spending);
        if($errors===true)
        {
            $spending = Spending::model()->findByAttributes(array('link_id'=>$master_spending['id']));

            // create office if not exists
            $master_spending['office'] = trim($master_spending['office']);
            if($master_spending['office']==null || $master_spending['office']=='')
            {
                $office_value = new CDbExpression('NULL');
            }
            else
            {
                $office = SpendingOffice::model()->findByAttributes(array('name'=>$master_spending['office']));
                if(!$office)
                {
                    $office = new SpendingOffice('insert');
                    $office->name = $master_spending['office'];
                    $office->save();
                }

                $office_value = $office->id;
            }

            if($spending)
            {
                // update document
                $spending->office_id = $office_value;
                $spending->title = $master_spending['title'];
                $spending->description  = $master_spending['description'];
                $spending->status = ($master_spending['status']==1 && $master_spending['publication_requested']==1)?self::ENABLED:self::DISABLED;
                $spending->amount = $master_spending['amount'];
                $spending->receiver = $master_spending['receiver'];
                $spending->attribution_norm = $master_spending['attribution_norm'];
                $spending->attribution_mod = $master_spending['attribution_mod'];
                $spending->spending_date = $master_spending['spending_date'];
                $spending->employee = $master_spending['employee'];
                $spending->cv_name = $master_spending['cv_name'];
                $spending->contract_name = $master_spending['contract_name'];
                $spending->project_name = $master_spending['project_name'];
                $spending->capitulate_name = $master_spending['capitulate_name'];
                $spending->last_updated = new CDbExpression('CURRENT_TIMESTAMP');                                        
            }
            else
            {
                // create
                $spending = new Spending('create');
                $spending->link_id = $master_spending['id'];
                $spending->office_id = $office_value;
                $spending->title = $master_spending['title'];
                $spending->description  = $master_spending['description'];
                $spending->status = ($master_spending['status']==1 && $master_spending['publication_requested']==1)?self::ENABLED:self::DISABLED;
                $spending->amount = $master_spending['amount'];
                $spending->receiver = $master_spending['receiver'];
                $spending->attribution_norm = $master_spending['attribution_norm'];
                $spending->attribution_mod = $master_spending['attribution_mod'];
                $spending->spending_date = $master_spending['spending_date'];
                $spending->employee = $master_spending['employee'];
                $spending->cv_name = $master_spending['cv_name'];
                $spending->contract_name = $master_spending['contract_name'];
                $spending->project_name = $master_spending['project_name'];
                $spending->capitulate_name = $master_spending['capitulate_name'];            
                $spending->date_created = new CDbExpression('CURRENT_TIMESTAMP');
                $spending->last_updated = new CDbExpression('CURRENT_TIMESTAMP');                
            }
        
            if($spending->save())
            {
                return true;
            }
            else
            {
                return $spending->getErrors();
            }

        }
        
        return $errors;
    }
    
    public static function validateMasterSpending($master_spending)
    {
        $errors = array();
        $required_attributes = array('id', 
                                    'title', 
                                    'amount', 
                                    'spending_date', 
                                    'employee', 
                                    'office', 
                                    'attribution_norm', 
                                    'attribution_mod', 
                                    'receiver', 
                                    'description', 
                                    'status', 
                                    'cv_name', 
                                    'capitulate_name', 
                                    'contract_name', 
                                    'project_name',
                                    'publication_requested');
        
        foreach($required_attributes as $attribute)
        {
            if(!array_key_exists($attribute, $master_spending))
            {
                $errors[] = $attribute.' is missing in the master spending payload';
            }
        }
        
        if(empty($errors))
        {
            return true;
        }
        
        return $errors;
    }
    
    public function hasCV()
    {
        if($this->cv_name!=null && $this->cv_name!='')
        {
            if(file_exists($this->getCVPath()))
                return true;
        }
        
        return false;
    }
    
    public function hasContract()
    {
        if($this->contract_name!=null && $this->contract_name!='')
        {
            if(file_exists($this->getContractPath()))
                return true;
        }
        
        return false;        
    }
    
    public function hasProject()
    {
        if($this->project_name!=null && $this->project_name!='')
        {
            if(file_exists($this->getProjectPath()))
                return true;
        }
        
        return false;        
    }
    
    public function hasCapitulate()
    {
        if($this->capitulate_name!=null && $this->capitulate_name!='')
        {
            if(file_exists($this->getCapitulatePath()))
                return true;
        }
        
        return false;        
    }
}
