<?php

class ProposerService extends CActiveRecord{
    
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
        return 'proposer_services';
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array(
        );
    }

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        return array(
            'documents' => array(self::HAS_MANY, 'Document', 'proposer_service_id'),
        );
    }

    /**
    * @return array customized attribute labels (name=>label)
    */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'name' => 'Nome'
        );
    }

}
