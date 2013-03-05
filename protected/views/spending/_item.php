<?php $model = Spending::model(); ?>
<table class="table table-striped table-condensed document-item-view detail-view">
    
    <tr>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('title'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->title); ?></p></td>
    </tr>

    <tr>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('receiver'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->receiver); ?></p></td>        
    </tr>
    
    <tr>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('amount'); ?></label></td>
        <td><p><?php echo CHtml::encode($data->amount)  ?> €</p></td>
    </tr>

    <tr>
        <td style="width: 20%"><label><?php echo $model->getAttributeLabel('spending_date'); ?></label></td>
        <td><p><?php echo $data->spending_date?date('d-m-Y', strtotime($data->spending_date)):'n/d'; ?></p></td>
    </tr>
    
    <tr>
        <td>&nbsp;</td>
        <td><?php echo CHtml::link('Vedi', array('/spending/view', 'id'=>$data->id), array('class'=>'btn btn-primary')); ?></td>
    </tr>
</table>