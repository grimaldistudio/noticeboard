<h3><a href="javascript:void()" id="advanced_search_link">Ricerca avanzata</a></h3>
<div id="advanced_search" style="display:none">
<?php $form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
    'id'=>'spending-search-form',
    'method'=>'GET',
    'type'=>'search',
    'htmlOptions'=>array('class'=>'well document-search-form',),
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
        'inputContainer'=>'div.control-group',
        'afterValidate'=>'js:submitSpendingsearchform'
    )
)); ?>

<!--    
<div class="btn-toolbar">
    <?php echo CHtml::link('Esporta in CSV', array('/spending/export'), array('id'=>'csv-export', 'class'=>'btn btn-primary')); ?>
</div>
-->
    
<?php $form->errorSummary($model); ?> 
<div style="padding: 20px">
    <div class="row">
        <div class="span6">
            <?php echo $form->textFieldRow($model, 'title', array('class'=>'span11')); ?>
        </div>
    </div>

    <br/>
    
    <div class="row">
        <div class="span6">
            <?php echo $form->textFieldRow($model, 'receiver', array('class'=>'span11')); ?>
        </div>
    </div>
    
    <br/>
    
    <div class="row">
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'amount_from'); ?>
        </div>

        <div class="span3">
            <?php echo $form->textFieldRow($model, 'amount_to'); ?>
        </div>
    </div>
    
    <br/>

    <div class="row">
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'spending_date_from', array('class'=>'date_field', 'id'=>'spending_date_from')); ?>         
        </div>
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'spending_date_to', array('class'=>'date_field', 'id'=>'spending_date_to')); ?> 
        </div>
    </div>

    <br/>

    <div class="row">
        <div class="span3">
            <?php echo $form->dropDownList($model, 'office_id', CHtml::listData(SpendingOffice::model()->findAll(), 'id', 'name'), array('empty'=>'Seleziona ufficio')); ?>
        </div>
        
        <div class="span3">
            <?php echo $form->textFieldRow($model, 'employee'); ?> 
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

<?php Yii::app()->clientScript->registerScript('spending-search-head', "
    function submitSpendingsearchform(form, data, hasError)
    {
        if(!hasError)
        {
            $.fn.yiiListView.update('spendingslistview', { 
                data: $(form).serialize()
            });
            return false;
        }
        return false;
    }
", CClientScript::POS_HEAD);
?>

<?php Yii::app()->clientScript->registerScript('spending-search-controls', "

var last_query_data = '';

$.datepicker.setDefaults( $.datepicker.regional['it'] );

var dates = $( '.date_field' ).datepicker({
            defaultDate: new Date(),
            changeMonth: true,
            numberOfMonths: 2,
            dateFormat: 'dd/mm/yy',
            onSelect: function( selectedDate ) {
                    var option = this.id == 'spending_date_from' ? 'minDate' : 'maxDate',
                            instance = $( this ).data( 'datepicker' ),
                            date = $.datepicker.parseDate(
                                    instance.settings.dateFormat ||
                                    $.datepicker._defaults.dateFormat,
                                    selectedDate, instance.settings );
                    dates.not( this ).datepicker( 'option', option, date );
            }
    });  

    $('#advanced_search_link').live('click', function(e){
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
