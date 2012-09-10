<?php $model = Spending::model(); ?>
<table class="table table-striped table-condensed document-item-view detail-view">
    
    <tr>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('title'); ?></label></td>
        <td style="width: 30%"><p><?php echo CHtml::encode($data->title); ?></p></td>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('receiver'); ?></label></td>
        <td style="width: 30%"><p><?php echo CHtml::encode($data->receiver); ?></p></td>        
    </tr>
    
    <tr>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('amount'); ?></label></td>
        <td style="width: 30%"><p><?php echo CHtml::encode($data->amount)  ?> €</p></td>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('spending_date'); ?></label></td>
        <td style="width: 30%"><p><?php echo date('d-m-Y', strtotime($data->spending_date)); ?></p></td>
    </tr>
    
    <tr>
        <td colspan="4" style="text-align: right"><?php echo CHtml::link('Vedi', array('/spending/view', 'id'=>$data->id), array('class'=>'btn')); ?></td>
    </tr>
</table>