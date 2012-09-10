<?php
$this->pageTitle=Yii::app()->name . ' - Errore';
$this->breadcrumbs=array(
	'Errore',
);
?>

<h2>Errore <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>