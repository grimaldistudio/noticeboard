<?php
return CMap::mergeArray(
	require(dirname($GLOBALS['yii']).'/i18n/data/'.basename(__FILE__)),
	array(
		 'dateFormats' =>   array (
		    'full' => 'dd-MM-yyyy',
		    'long' => 'dd-MM-yyyy',
		    'medium' => 'dd-MM-yyyy',
		    'short' => 'd-M-yy',
		  ),
		 'timeFormats' => array (
		    'full' => 'HH:mm:ss',
		    'long' => 'HH:mm:ss z',
		    'medium' => 'HH:mm:ss',
		    'short' => 'HH:mm',
		  )
	)
);