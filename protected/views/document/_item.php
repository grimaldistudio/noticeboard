<table class="table table-striped table-condensed document-item-view detail-view">

    <tr>
        <td style="width: 20%;">
            <label><?php echo Document::model()->getAttributeLabel('document_type_id'); ?></label>
        </td>
        <td>
            <?php echo CHtml::encode($data->document_type?$data->document_type->name:'n/d'); ?>            
        </td>
    </tr>    

    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('protocol_number'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->protocol_number!==null?$data->protocol_number:'n/d'); ?></p></td>
    </tr>

    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('publication_number'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->publication_number!==null?$data->publication_number:'n/d'); ?></p></td>
    </tr>    
    
    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('subject'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->subject); ?></p></td>
    </tr>

    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('act_number'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->act_number?$data->act_number:'n/d'); ?></p></td>        
    </tr>
    
    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('entity_id'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->entity?$data->entity->name:Yii::app()->params['entity']); ?></p></td>
    </tr>

    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('proposer_service_id'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->proposer_service?$data->proposer_service->name:'n/d'); ?></p></td>        
    </tr>
    
    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('publication_date_from'); ?></label></td>
        <td><p><?php echo $data->publication_date_from?date('d-m-Y', strtotime($data->publication_date_from)):'n/d'; ?></p></td>
    </tr>

    <tr>
        <td style="width: 20%;"><label><?php echo Document::model()->getAttributeLabel('publication_date_to'); ?></label></td>
        <td><p><?php echo $data->publication_date_to?date('d-m-Y', strtotime($data->publication_date_to)):'n/d'; ?></p></td>        
    </tr>
    
    <tr>
        <td>&nbsp;</td>
        <td><?php echo CHtml::link('Vedi', array('/document/view', 'id'=>$data->id), array('class'=>'btn btn-primary')); ?></td>
    </tr>
</table>