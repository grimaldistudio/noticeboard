<?php

class Document extends CActiveRecord{
    
    const ENABLED = 1;
    const DISABLED = 0;

    public $act_date_to;
    public $act_date_from;
    
    public $total_pages;
    
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
        return 'documents';
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array(
            array('subject,protocol_number,publication_number,document_type_id,entity_id,proposer_service_id,act_number,publication_date_from,publication_date_to,act_date_from,act_date_to', 'safe', 'on'=>'search'),
            array('publication_date_from,publication_date_to,act_date', 'default', 'value'=>new CDbExpression('NULL'), 'setOnEmpty'=>true, 'on'=>'create,update'),
            array('publication_date_from', 'date', 'format'=>'dd/MM/yyyy', 'timestampAttribute'=>'publication_date_from', 'allowEmpty'=>true, 'on'=>'search'),
            array('publication_date_to', 'date', 'format'=>'dd/MM/yyyy', 'timestampAttribute'=>'publication_date_to', 'allowEmpty'=>true, 'on'=>'search'),            
            array('act_date_from', 'date', 'format'=>'dd/MM/yyyy', 'timestampAttribute'=>'act_date_from', 'allowEmpty'=>true, 'on'=>'search'),
            array('act_date_to', 'date', 'format'=>'dd/MM/yyyy', 'timestampAttribute'=>'act_date_to', 'allowEmpty'=>true, 'on'=>'search'),                        
        );
    }

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        return array(
            'proposer_service' => array(self::BELONGS_TO, 'ProposerService', 'proposer_service_id'),
            'entity' => array(self::BELONGS_TO, 'Entity', 'entity_id'),
            'document_type' => array(self::BELONGS_TO, 'DocumentType', 'document_type_id')
        );
    }

    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'subject' => 'Oggetto',
            'entity_id' => 'Ente',
            'protocol_number' => 'Numero protocollo',
            'publication_number' => 'Numero di pubblicazione',
            'act_number' => 'Numero atto',
            'act_date' => 'Data atto',
            'publication_date_from' => 'Inizio pubblicazione',
            'publication_date_to' => 'Fine pubblicazione',
            'description' => 'Descrizione',
            //'num_pages' => 'Numero di pagine',
            'document_type_id' => 'Tipologia',
            'proposer_service_id' => 'Servizio proponente',
            'relative_path' => 'Percorso relativo',
            'status' => 'Stato',
            'date_created' => 'Data di creazione',
            'last_updated' => 'Ultimo aggiornamento',
            'document_size' => 'Dimensione file',
            'act_date_from' => 'Data atto dal',
            'act_date_to' => 'Data atto al'
        );
    }

    public function getStatusArray()
    {
        return array(
            self::DISABLED => 'Disabilitato',
            self::ENABLED => 'Attivo'
        );
    }
    
    public function getDocumentSize()
    {
        if(is_file($this->getPath()))
        return sprintf("%.2f", filesize($this->getPath())/1000.0);
        else
            return false;
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

        $criteria->with = array('entity', 'proposer_service', 'document_type');
        
        if(!$this->hasErrors('publication_date_from') && $this->publication_date_from)
            $criteria->addCondition("publication_date_from>='".date('Y-m-d ', $this->publication_date_from)."'");
        
        if(!$this->hasErrors('publication_date_to') && $this->publication_date_to)
            $criteria->addCondition("publication_date_to<='". date('Y-m-d H:i:s', $this->publication_date_to)."'");
        
        if(!$this->hasErrors('act_date_from') && $this->act_date_from)
            $criteria->addCondition("act_date>='".date('Y-m-d H:i:s', $this->act_date_from)."'");
        
        if(!$this->hasErrors('act_date_to') && $this->act_date_to)
            $criteria->addCondition("act_date<='". date('Y-m-d H:i:s', $this->act_date_to)."'");        
        
        $criteria->compare('act_number', $this->act_number, true);
       // $criteria->compare('num_pages', $this->num_pages, true);
        $criteria->compare('subject',$this->subject,true);
        $criteria->compare('protocol_number', $this->protocol_number, true);
        $criteria->compare('publication_number', $this->publication_number, true);
        $criteria->compare('document_type_id', $this->document_type_id);
        
        if($this->entity_id==0)
            $criteria->addCondition("t.entity_id IS NULL");
        elseif($this->entity_id>0)
            $criteria->compare('entity_id', $this->entity_id);
        
        $criteria->compare('proposer_service_id', $this->proposer_service_id);
        
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.id DESC',
            ),
            'pagination'=>array(
                'pageSize'=>5
            ),
        ));			
    }
    
    public function getRelativePath()
    {
        if(is_null($this->relative_path))
        {
            $time = strtotime($this->date_created);
            $this->relative_path = 'saved'.DIRECTORY_SEPARATOR.date('Y', $time).DIRECTORY_SEPARATOR.date('m', $time).DIRECTORY_SEPARATOR.date('d', $time);        
        }
        return $this->relative_path;
    }
    
    public function getDocumentName()
    {
        return 'documento_'.$this->link_id.'.pdf';
    }
    
    public function getPath()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.$this->relative_path.DIRECTORY_SEPARATOR.$this->getDocumentName();
    }       
    
    public function getCachePath()
    {
        return Yii::getPathOfAlias('documents').DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$this->getRelativePath();
    }
    
    public function download($force_download = false)
    {
        $path = $this->getPath();
        if(file_exists($path)){
            if($force_download)
                header('Content-disposition: attachment; filename='.$this->getDocumentName());
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
    
    
    public function exportToCSV()
    {
        $this->validate();
        $sql = "SELECT VT.* FROM (SELECT '".$this->getAttributeLabel('protocol_number')."', ".
                       "'".$this->getAttributeLabel('subject')."', ".
                       "'".$this->getAttributeLabel('description')."', ".                
                       "'".$this->getAttributeLabel('document_type_id')."',".
                       "'".$this->getAttributeLabel('act_number')."', ".
                       "'".$this->getAttributeLabel('act_date')."', ".
                       "'".$this->getAttributeLabel('publication_date_from')."', ".
                       "'".$this->getAttributeLabel('publication_date_to')."', ".
                       "'".$this->getAttributeLabel('proposer_service_id')."', ".
                       "'".$this->getAttributeLabel('entity_id')."' UNION ";          
        
        $sql .= " ( SELECT 
                        IF(protocol_number='', 'n/d', protocol_number), 
                        subject, 
                        description, 
                        dt.name, 
                        IF(act_number='', 'n/d', act_number), 
                        IF(act_date='0000-00-00 00:00:00', 'n/d', DATE_FORMAT(act_date, '%d-%m-%Y')), 
                        IF(publication_date_from='0000-00-00 00:00:00', 'n/d', DATE_FORMAT(publication_date_from, '%d-%m-%Y')),
                        IF(publication_date_to='0000-00-00 00:00:00', 'n/d', DATE_FORMAT(publication_date_to, '%d-%m-%Y')),                        
                        IFNULL(ps.name, 'n/d'), 
                        IFNULL(e.name, 'n/d') 
                    FROM documents d
                    LEFT JOIN entities e ON d.entity_id = e.id
                    LEFT JOIN document_types dt ON d.document_type_id = dt.id
                    LEFT JOIN proposer_services ps ON d.proposer_service_id = ps.id
                    WHERE status = 1 ";

        $where_conditions = "";
        if(!$this->hasErrors('publication_date_from') && $this->publication_date_from)
        {
            $where_conditions .= " AND publication_date_from>='".date('Y-m-d H:i:s', $this->publication_date_from)."'";
        }
        if(!$this->hasErrors('publication_date_to') && $this->publication_date_to)
        {
            $where_conditions .= " AND publication_date_to<='". date('Y-m-d H:i:s', $this->publication_date_to)."'";
        }
        
        if(!$this->hasErrors('act_date_from') && $this->act_date_from)
        {
            $where_conditions .= " AND act_date>='".date('Y-m-d H:i:s', $this->act_date_from)."'";            
        }

        if(!$this->hasErrors('act_date_to') && $this->act_date_to)
        {
            $where_conditions .= " AND act_date<='". date('Y-m-d H:i:s', $this->act_date_to)."'";        
        }

        $params = array();
        
        if($this->act_number)
        {
            $where_conditions .= " AND act_number LIKE :act_number";
            $params[':act_number'] = '%'.$this->act_number.'%';
        }
        
        if($this->subject)
        {
            $where_conditions .= " AND subject LIKE :subject";
            $params[':subject'] = '%'.$this->subject.'%';
        }
        
        if($this->protocol_number)
        {
            $where_conditions .= " AND protocol_number LIKE :protocol_number";
            $params[':protocol_number'] = '%'.$this->protocol_number.'%';
        }
        
        if($this->document_type_id>0)
        {
            $where_conditions .= " AND document_type_id = :document_type_id";
            $params[':document_type_id'] = $this->document_type_id;
        }
        
        if($this->entity_id==0)
            $where_conditions .= " AND entity_id IS NULL";
        elseif($this->entity_id>0)
        {
            $where_conditions .= " AND entity_id = :entity_id";
            $params[':entity_id'] = $this->entity_id;
        }
        
        if($this->proposer_service_id>0)
        {
            $where_conditions .= " AND proposer_service_id = :proposer_service_id";
            $params[':proposer_service_id'] = $this->proposer_service_id;
        }
        
        $order = " ORDER BY d.id ASC";
        $limit = " LIMIT 10000 )) AS VT ";
        $outfile = "export_documenti_".date('d-m-Y_H:i:s').'.csv'; 
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
    
    public static function syncWithMaster($master_document)
    {
        // update fields
        $errors = Document::validateMasterDocument($master_document);
        if($errors===true)
        {
            $document = Document::model()->findByAttributes(array('link_id'=>$master_document['id']));

            // create doument type if not exists
            $document_type = DocumentType::model()->findByPk($master_document['document_type']);
            if(!$document_type)
            {
                $document_type = new DocumentType('insert');
                $document_type->id = $master_document['document_type'];
                $document_type->name = trim($master_document['document_type_name']);
                $document_type->save();
            }
            else {
                $document_type->name = trim($master_document['document_type_name']);
                $document_type->save();
            }
            $document_type_value = $document_type->id;

            // create proposer service if not exists
            $master_document['proposer_service'] = trim($master_document['proposer_service']);
            if($master_document['proposer_service']==null || $master_document['proposer_service']=='')
            {
                $proposer_service_value = new CDbExpression('NULL');
            }
            else
            {
                $proposer_service = ProposerService::model()->findByAttributes(array('name'=>$master_document['proposer_service']));
                if(!$proposer_service)
                {
                    $proposer_service = new ProposerService('insert');
                    $proposer_service->name = $master_document['proposer_service'];
                    $proposer_service->save();
                }
                $proposer_service_value = $proposer_service->id;
            }

            // create entity if not exists
            $master_document['entity'] = trim($master_document['entity']);
            if($master_document['entity']==null || $master_document['entity']=='')
            {
                $entity_value = new CDbExpression('NULL');
            }
            else
            {
                $entity = Entity::model()->findByAttributes(array('name'=>$master_document['entity']));
                if(!$entity)
                {
                    $entity = new Entity('insert');
                    $entity->name = $master_document['entity'];
                    $entity->save();
                }

                $entity_value = $entity->id;
            }

            if($document)
            {
                // update document
                $document->scenario = 'update';
                $document->protocol_number = $master_document['identifier'];
                $document->document_type_id = $document_type_value;
                $document->proposer_service_id = $proposer_service_value;
                $document->entity_id = $entity_value;
                $document->subject = $master_document['name'];
                $document->publication_number = $master_document['publication_number'];
                $document->sync_file = $master_document['sync_file'];                
                $document->description  = $master_document['description'];
                $document->status = $master_document['status'];
                $document->relative_path = $master_document['relative_path'];
                $document->act_number = $master_document['act_number'];
                $document->act_date = $master_document['act_date']!=''?$master_document['act_date']:new CDbExpression('NULL');
                $document->publication_date_from = $master_document['publication_date_from']!=''?$master_document['publication_date_from']:new CDbExpression('NULL');
                $document->publication_date_to = $master_document['publication_date_to']!=''?$master_document['publication_date_to']:new CDbExpression('NULL');
                $document->last_updated = new CDbExpression('CURRENT_TIMESTAMP'); 
            }
            else
            {
                // create
                $document = new Document('create');
                $document->link_id = $master_document['id'];
                $document->protocol_number = $master_document['identifier'];
                $document->document_type_id = $document_type_value;
                $document->proposer_service_id = $proposer_service_value;
                $document->entity_id = $entity_value;
                $document->publication_number = $master_document['publication_number'];
                $document->sync_file = $master_document['sync_file'];                 
                $document->subject = $master_document['name'];
                $document->description  = $master_document['description'];
                $document->status = ($master_document['status']==1 && $master_document['publication_requested']==1)?self::ENABLED:self::DISABLED;
                $document->relative_path = $master_document['relative_path'];
                $document->act_number = $master_document['act_number'];
                $document->act_date = $master_document['act_date']!=''?$master_document['act_date']:new CDbExpression('NULL');
                $document->publication_date_from = $master_document['publication_date_from']!=''?$master_document['publication_date_from']:new CDbExpression('NULL');
                $document->publication_date_to = $master_document['publication_date_to']!=''?$master_document['publication_date_to']:new CDbExpression('NULL');
                $document->date_created = new CDbExpression('CURRENT_TIMESTAMP');                 
                $document->last_updated = new CDbExpression('CURRENT_TIMESTAMP'); 
            }
            
            if($document->save())
            {
                return true;
            }
            else
            {
                return $document->getErrors();
            }

        }
        
        return $errors;
    }
    
    public static function validateMasterDocument($master_document)
    {
        $errors = array();
        $required_attributes = array('id', 
                                    'name', 
                                    'identifier', 
                                    'relative_path',
                                    'publication_number',
                                    'sync_file',
                                    'description', 
                                    'status', 
                                    'publication_date_from',
                                    'publication_date_to',
                                    'act_date',
                                    'act_number',
                                    'entity',
                                    'proposer_service',
                                    'document_type',
                                    'document_type_name',
                                    'publication_requested'
                                    );
        
        foreach($required_attributes as $attribute)
        {
            if(!array_key_exists($attribute, $master_document))
            {
                $errors[] = $attribute.' is missing in the master document payload';
            }
        }
        
        if(empty($errors))
        {
            return true;
        }
        
        return $errors;
    }
    
     protected function afterFind()
        {
          if(isset($this->act_date)) $this->act_date = Yii::app()->locale->dateFormatter->format('dd/MM/y', $this->act_date);
          //if(isset($this->publication_date_from)) $this->publication_date_from = Yii::app()->locale->dateFormatter->format('dd/MM/y H:i', $this->publication_date_from);
         // if(isset($this->publication_date_to)) $this->publication_date_to = Yii::app()->locale->dateFormatter->format('dd/MM/y H:i', $this->publication_date_to);     
     
          return parent::afterFind();
        } 
    
    
}
