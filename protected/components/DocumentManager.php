<?php

/**
 * Description of DocumentManager
 *
 * @author fabrizio
 */
class DocumentManager {

    const GROUP_PENDING_TYPE = 1;
    const USER_PENDING_TYPE = 2;
    
    private $entity_id;
    private $document_name;
    private $full_path;
    private $type;
    
    public function __construct($entity_id, $document_name, $type=self::GROUP_PENDING_TYPE)
    {
        $this->type = $type;
        $this->entity_id = $entity_id;
        $this->document_name = $document_name;
        if($this->type==self::GROUP_PENDING_TYPE)
            $this->full_path = self::getPendingGroupPath($this->entity_id, $this->document_name);
        else
            $this->full_path = self::getPendingUserPath($this->entity_id, $this->document_name);            
    }
    
    public function download($force_download = false)
    {
        if(file_exists($this->full_path)){
            if($force_download)
                header('Content-disposition: attachment; filename='.$this->document_name);
            header('Content-type: application/pdf');
            readfile($this->full_path);
            Yii::app()->end();
        }
        else
        {
            throw new CHttpException(404, 'File non trovato');
        }
    }
    
    public function delete()
    {
        // delete file
        if(@unlink($this->full_path))
        {
            // delete cached files
            $this->deleteCacheFiles();
            return true;
        }
        return false;
    }

    public function deleteCacheFiles()
    {
        if($this->type==self::GROUP_PENDING_TYPE)
        {
            $i = 0;
            while(true)
            {
                if(@unlink(self::getPendingGroupCachePath($this->entity_id).DIRECTORY_SEPARATOR.$this->document_name.'_'.$i++.'.jpg') === false)
                     break;
            }
            
            @unlink(self::getPendingGroupCachePath($this->entity_id).DIRECTORY_SEPARATOR.$this->document_name.'_thumb.jpg');
        }
        else
        {
            $i = 0;
            while(true)
            {
                if(@unlink(self::getPendingUserCachePath($this->entity_id).DIRECTORY_SEPARATOR.$this->document_name.'_'.$i++.'.jpg') === false)
                     break;
            }
            
            @unlink(self::getPendingUserCachePath($this->entity_id).DIRECTORY_SEPARATOR.$this->document_name.'_thumb.jpg');            
        }
    }
    
    public function getPath()
    {
        if($this->type==self::GROUP_PENDING_TYPE)
            return self::getPendingGroupPath ($this->entity_id, $this->document_name);
        else
            return self::getPendingUserPath ($this->entity_id, $this->document_name);
    }
    
    public function getCachePath()
    {
        if($this->type==self::GROUP_PENDING_TYPE)
            return self::getPendingGroupCachePath ($this->entity_id);
        else
            return self::getPendingUserCachePath ($this->entity_id);        
    }
    
    public function getDocumentName()
    {
        return $this->document_name;
    }
    
    public static function getPendingGroupPath($group_folder_name, $document_name, $absolute = true)
    {
        if($absolute)
            return Yii::getPathOfAlias('uploads').DIRECTORY_SEPARATOR.'groups'.DIRECTORY_SEPARATOR.$group_folder_name.DIRECTORY_SEPARATOR.'pending'.DIRECTORY_SEPARATOR.$document_name;
        else
            return $group_folder_name.DIRECTORY_SEPARATOR.'pending'.DIRECTORY_SEPARATOR.$document_name;            
    }
    
    public static function getPendingUserPath($user_id, $document_name, $absolute = true)
    {
        if($absolute)
            return Yii::getPathOfAlias('uploads').DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.'u_'.$user_id.DIRECTORY_SEPARATOR.'pending'.DIRECTORY_SEPARATOR.$document_name;        
        else
            return 'u_'.$user_id.DIRECTORY_SEPARATOR.'pending'.DIRECTORY_SEPARATOR.$document_name;                    
    }
    
    public static function getPendingGroupCachePath($group_folder_name, $absolute = true)
    {
        if($absolute)
            return Yii::getPathOfAlias('uploads').DIRECTORY_SEPARATOR.'groups'.DIRECTORY_SEPARATOR.$group_folder_name.DIRECTORY_SEPARATOR.'cache';
        else
            return $group_folder_name.DIRECTORY_SEPARATOR.'cache';                    
    }
    
    public static function getPendingUserCachePath($user_id, $absolute = true)
    {
        if($absolute)
            return Yii::getPathOfAlias('uploads').DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.'u_'.$user_id.DIRECTORY_SEPARATOR.'cache';        
        else
            return 'u_'.$user_id.DIRECTORY_SEPARATOR.'cache';                            
    }
    
/*    public function isOpenedByOtherUser()
    {
        $sql = "SELECT COUNT(1) FROM protocol_sessions WHERE group_id = :group_id AND user_id != :user_id AND date_created>"
    }*/
}

?>
