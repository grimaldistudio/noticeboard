<h3><a href="javascript:void()" id="advanced_search_link">Ricerca avanzata</a></h3>

<div id="advanced_search" style="display:none">
<?php $form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
    'id'=>'document-search-form',
    'method'=>'GET',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well document-search-form',),
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
        'inputContainer'=>'div.control-group',
        'afterValidate' => 'js:submitDocumentsearchform'
    )
)); ?>
<div class="btn-toolbar">
    <?php echo CHtml::link('Esporta in CSV', array('/document/export'), array('id'=>'csv-export', 'class'=>'btn btn-primary')); ?>
</div>


<?php $form->errorSummary($model); ?> 
<div style="padding: 20px">
    <div class="row">
        <div class="span6">
            <?php echo $form->textFieldRow($model, 'subject', array('class'=>'span11')); ?>
        </div>
    </div>

    <br/>
    
    <div class="row">
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'protocol_number'); ?>
        </div>

        <div class="span3">
            <?php echo $form->textFieldRow($model, 'act_number'); ?>
        </div>
    </div>
    
    <br/>

    <div class="row">
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'publication_date_from', array('class'=>'date_field', 'id'=>'publication_date_from')); ?>         
        </div>
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'publication_date_to', array('class'=>'date_field', 'id'=>'publication_date_to')); ?> 
        </div>
    </div>

    <br/>

    <div class="row">
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'act_date_from', array('class'=>'date_field', 'id'=>'act_date_from')); ?>         
        </div>
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'act_date_to', array('class'=>'date_field', 'id'=>'act_date_to')); ?> 
        </div>
    </div>

    <br/>

    <div class="row">
        <div class="span3">
            <?php echo $form->dropDownList($model, 'document_type_id', CHtml::listData(DocumentType::model()->findAll(), 'id', 'name'), array('empty'=>'Seleziona tipo di documento')); ?>
        </div>
        
        <div class="span3">
            <?php echo $form->dropDownList($model, 'entity_id', array_merge(array('0'=>Yii::app()->params['entity']), CHtml::listData(Entity::model()->findAll(), 'id', 'name')), array('empty'=>'Seleziona ente')); ?>
        </div>
    </div>
    
    <br/>
    
    <div class="row">
        <div class="span3">
            <?php echo $form->dropDownList($model, 'proposer_service_id', CHtml::listData(ProposerService::model()->findAll(), 'id', 'name'), array('empty'=>'Seleziona servizio proponente')); ?>
        </div>
    </div>
</div>

<div class="btn-toolbar">
    <?php echo CHtml::submitButton('Cerca', array('class'=>'btn btn-primary btn-filter')); ?>
    <?php echo CHtml::resetButton('Reset', array('class'=>'btn btn-reset')); ?>    
</div>    
<?php 
$this->endWidget();
?>

</div>

<?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php Yii::app()->clientScript->registerScriptFile('/js/jquery-ui-i18n.min.js'); ?>
<?php Yii::app()->clientScript->registerCssFile('/css/overcast/jquery-ui.css'); ?>

<?php Yii::app()->clientScript->registerScript('document-search-head', "
    function submitDocumentsearchform(form, data, hasError)
    {
        if(!hasError)
        {
            last_query_data = $(form).serialize();
            $.fn.yiiListView.update('documentslistview', { 
                data: $(form).serialize()
            });
            return false;
        }
        return false;
    }
", CClientScript::POS_HEAD);
?>

<?php Yii::app()->clientScript->registerScript('document-search-controls', "

var last_query_data = '';
$.datepicker.setDefaults( $.datepicker.regional['it'] );

var dates = $( '.date_field' ).datepicker({
            defaultDate: new Date(),
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: 'dd/mm/yy',
            onSelect: function( selectedDate ) {
                    var option = this.id == 'publication_date_from' || this.id == 'act_date_from' ? 'minDate' : 'maxDate',
                            instance = $( this ).data( 'datepicker' ),
                            date = $.datepicker.parseDate(
                                    instance.settings.dateFormat ||
                                    $.datepicker._defaults.dateFormat,
                                    selectedDate, instance.settings );
                    dates.not( this ).datepicker( 'option', option, date );
            }
    });  

    $('#advanced_search_link').on('click', function(e){
        e.preventDefault();
        $('#advanced_search').toggle();
    });
    
    $('#csv-export').on('click', function(e){
        e.preventDefault();
        link = $(e.currentTarget).attr('href');
        if(!last_query_data || last_query_data=='')
            window.open(link, '_blank');
        else
            window.open(link+'?'+last_query_data, '_blank');
        return false;
    });
"); ?>
