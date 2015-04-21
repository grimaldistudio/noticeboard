<div class="preview_container " >
    <div style="height:700px; width: 100%" id="preview_loader">
        <img src="<?php echo Yii::app()->baseUrl?>/images/ajax-loader.gif" />
    </div>
    <div id="preview_inner_container" style="display:none">
        <img src="" alt="preview"  id="preview_img"/>
       <ul class="inline-list">
           <li>  <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl.'/images/misc/pdficon_large.png'), $full_size_url, array('class'=>'', 'target'=>'_blank')); ?>     </li>                           
           <li> <?php echo CHtml::link('<i class="fi-rewind"></i>', '#', array('class'=>'tiny button secondary', 'id'=>'preview_back')); ?></li>
           <li> <span id="current_page">1</span> di <span id="total_pages"> <?php echo $total_pages; ?></li>
           <li><?php echo CHtml::link('<i class="fi-fast-forward"></i>', '#', array('class'=>'tiny button secondary', 'id'=>'preview_next')); ?>    </li>                    
        </div>
    </div>
</div>

<?php

Yii::app()->clientScript->registerScript("previewer-controls", "
   
   var document_pages = ".$total_pages.";
   var preview_url = '".$preview_url."';
       
    function loadDocumentPreview(preview_url, page)
    {
        $('#preview_inner_container').hide();
        $('#preview_loader').show();
        $('#preview_img').attr('src', preview_url+'&page='+(page-1));
        $('#current_page').text(page);
        if(page>=parseInt($('#total_pages').text()))
            $('#preview_next').addClass('disabled');
        else
            $('#preview_next').removeClass('disabled');            
        if(page<=1)
            $('#preview_back').addClass('disabled');            
        else
            $('#preview_back').removeClass('disabled');                        
    }
    
    $('#preview_img').load( function(){
        $('#preview_loader').hide();
        $('#preview_inner_container').show();
    });
    
    $(document).on('click', '#preview_back', function (e){
        e.preventDefault();
        var current_page = parseInt($('#current_page').text());
        if(current_page<=1)
            return;
        loadDocumentPreview(preview_url, current_page-1);    
        return false;
    });
    
    $(document).on('click', '#preview_next', function (e){
        e.preventDefault();
        var current_page = parseInt($('#current_page').text());
        if(current_page>=parseInt($('#total_pages').text()))
            return;
        loadDocumentPreview(preview_url, current_page+1);
        return false;
    });
    
    loadDocumentPreview(preview_url, 1);
", CClientScript::POS_READY);