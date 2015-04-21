<?php

/**
 * Generates and cache documents preview
 *
 * @author fabrizio
 */

class PreviewManager {

    const THUMBNAIL_MAX_WIDTH = 350;
    const THUMBNAIL_MAX_HEIGHT = 350;
    
    const FULL_MAX_WIDTH = 550;
    const FULL_MAX_HEIGHT = 550;
    
    private $document_name = null;
    private $blank_image = null;
    private $blank_thumb = null;
    
    private $adapter = null;
    private $path = null;
    private $cache_path = null;
    
    public function __construct($adapter)
    {
        $this->blank_image = Yii::getPathOfAlias('application').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'blank_image.jpg';
        $this->blank_thumb = Yii::getPathOfAlias('application').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'blank_thumb.jpg';        
        $this->adapter = $adapter;
        $this->path = $adapter->getPath();
        $this->cache_path = $adapter->getCachePath();
        $this->document_name = $adapter->getDocumentName();
    }
    
    public function getPreview($page=0)
    {
        if(file_exists($this->path))
        {
            if(!$this->isPagedCached($page))
            {
                // generate cache
                if(!$this->generatePreview($page))
                    return $this->blank_image;
            }
            
            return $this->getPagedCachePath($page);
        }
        else
        {
            return $this->blank_image;
        }
    }
    
    public function getThumbnail()
    {
        if(file_exists($this->path))
        {
            if(!$this->isThumbnailCached())
            {
                // generate cache
                if(!$this->generateThumbnail())
                    return $this->blank_thumb;
            }
            return $this->getThumbnailCachePath();
        }
        else
        {
            return $this->blank_thumb;
        }
    }
    
    private function generatePreview($page)
    {
        if(!is_dir($this->cache_path) && !@mkdir($this->cache_path, 0777, true))
            return false;
        try{
            $pdf = $this->path.'['.$page.']';
            exec("convert -scale ".self::FULL_MAX_WIDTH."x".self::FULL_MAX_HEIGHT." -density 200x200 -quality 70 ".escapeshellarg($pdf)." ".escapeshellarg($this->getPagedCachePath($page)));
            if(file_exists(($this->getPagedCachePath($page))))
                return true;
            else
                return false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    
    private function generateThumbnail()
    {
        try{
            $pdf = $this->path.'[0]';
            exec("convert -scale ".self::THUMBNAIL_MAX_WIDTH."x".self::THUMBNAIL_MAX_HEIGHT." -density 200x200 -quality 70 ".escapeshellarg($pdf)." ".escapeshellarg($this->getThumbnailCachePath()));
            if(file_exists(($this->getThumbnailCachePath())))
                return true;
            else
                return false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    private function isPagedCached($page)
    {
        if(file_exists($this->getPagedCachePath($page)))
            return true;
        return false;
    }
    
    private function isThumbnailCached()
    {
        if(file_exists($this->getThumbnailCachePath()))
            return true;
        return false;
    }

    private function getPagedCachePath($page)
    {
        return $this->cache_path.DIRECTORY_SEPARATOR.$this->document_name.'_'.$page.'.jpg';                
    }
    
    private function getThumbnailCachePath()
    {
        return $this->cache_path.DIRECTORY_SEPARATOR.$this->document_name.'_thumb.jpg';        
    }

    function getNumPagesPdf($filepath){
        $fp = @fopen(preg_replace("/\[(.*?)\]/i", "",$filepath),"r");
        $max=0;
        while(!feof($fp)) {
                $line = fgets($fp,255);
                if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                        preg_match('/[0-9]+/',$matches[0], $matches2);
                        if ($max<$matches2[0]) $max=$matches2[0];
                }
        }
        fclose($fp);
        if($max==0){
            $im = new imagick($filepath);
            $max=$im->getNumberImages();
        }

        return $max;
    }

    public function getDocumentInfo()
    {
        try {
            if(file_exists($this->path))
            {
                return $this->getNumPagesPdf($this->path);
            
            }else
                return 0;
        }
        catch(Exception $e)
        {
            return 0;
        }
    }
}

?>
