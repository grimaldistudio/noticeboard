<?php $this->pageTitle = "Open Data"; ?>

<?php
    $this->breadcrumbs = array($this->pageTitle);
?>

<h1><?php echo $this->pageTitle; ?></h1>

<h3>Documenti</h3>
<section id="documents">
    <table class="table table-bordered" style="width: 500px">
        <?php foreach($categoryYears as $categoryId => $categoryData): ?>
            <tr class="open-data-header">
                <td colspan="2"><b><?php echo CHtml::encode($categoryData['name']); ?></b></td>
            </tr>
            <?php if($categoryData['minYear']!==NULL): ?>
                <?php for($i=date('Y'); $i>=$categoryData['minYear']; $i--): ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('opendata/documents', array('year'=>$i, 'category'=>$categoryId, 'format'=>'json')); ?>" rel="tooltip" title="Download in formato JSON">
                            <img src="<?php echo Yii::app()->baseUrl.'/images/json-dl.png'; ?>" alt="Download in formato JSON" />
                        </a>

                        <a href="<?php echo Yii::app()->createUrl('opendata/documents', array('year'=>$i, 'category'=>$categoryId, 'format'=>'csv')); ?>" rel="tooltip" title="Download in formato CSV">
                            <img src="<?php echo Yii::app()->baseUrl.'/images/csv-dl.png'; ?>" alt="Download in formato CSV" />
                        </a>                        
                    </td>
                </tr>
                <?php endfor; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</section>

<?php if($minSpendingYear!==NULL): ?>
<h3>Spese</h3>
<section id="expenses">
    <table class="table table-bordered" style="width: 500px">
        <?php for($i=date('Y'); $i>=$minSpendingYear; $i--): ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td>
                    <a href="<?php echo Yii::app()->createUrl('opendata/expenses', array('year'=>$i, 'format'=>'json')); ?>" rel="tooltip" title="Download in formato JSON">
                        <img src="<?php echo Yii::app()->baseUrl.'/images/json-dl.png'; ?>" alt="Download in formato JSON" />
                    </a>

                    <a href="<?php echo Yii::app()->createUrl('opendata/expenses', array('year'=>$i, 'format'=>'csv')); ?>" rel="tooltip" title="Download in formato CSV">
                        <img src="<?php echo Yii::app()->baseUrl.'/images/csv-dl.png'; ?>" alt="Download in formato CSV" />
                    </a>                                            
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</section>
<?php endif; ?>

<a href="http://opendefinition.org/">
  <img alt="This material is Open Data" border="0" src="http://assets.okfn.org/images/ok_buttons/od_80x15_blue.png">
</a>