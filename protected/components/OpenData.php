<?php

class OpenData {
	
	public function getMinDocumentYearByCategory($category_id)
	{
		$sql = "SELECT min(d.date_created) AS date_created FROM documents d WHERE d.document_type_id=:document_type_id";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$minRecord = $command->queryRow(true, array(':document_type_id'=>$category_id));
		if($minRecord['date_created']!==NULL)
		{
			$minDate = strtotime($minRecord['date_created']);
			$minYear = date('Y', $minDate);
			return $minYear;
		}
		return NULL;
	}	

	public function getCategories(){
		$sql = "SELECT dt.id, dt.name FROM document_types dt";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$rows = $command->queryAll(true);
		return $rows;
	}

	public function getMinSpendingYear()
	{
		$sql = "SELECT min(d.spending_date) AS spending_date FROM spendings d";
		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$minRecord = $command->queryRow(true);
		if($minRecord['spending_date']!==NULL)
		{
			$minDate = strtotime($minRecord['spending_date']);
			$minYear = date('Y', $minDate);
			return $minYear;
		}
		return NULL;
	}

	public function getDocuments($year, $category)
	{
		$min_date = $year.'-01-01';
		$max_date = $year.'-12-31';
		$sql = "SELECT protocol_number, publication_number, subject, description, dt.name as document_type, act_number, act_date, publication_date_from, publication_date_to, ps.name AS proposer_service, e.name AS entity
					FROM documents d
					LEFT JOIN document_types dt ON d.document_type_id=dt.id
					LEFT JOIN entities e ON d.entity_id=e.id
					LEFT JOIN proposer_services ps ON d.proposer_service_id=ps.id
					WHERE date_created >= :min_date AND date_created <=:max_date AND d.document_type_id=:category";

		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$records = $command->queryAll(true, array(':min_date'=>$min_date, ':max_date'=>$max_date, ':category'=>$category));		
		return $records;
	}

	public function getExpenses($year)
	{
		$min_spending_date = $year.'-01-01';
		$max_spending_date = $year.'-12-31';
		$sql = "SELECT title, receiver, amount, attribution_norm, o.name as office_name, employee, attribution_mod, description, spending_date 
					FROM spendings s 
					LEFT JOIN spending_offices o ON s.office_id=o.id 
					WHERE spending_date >= :min_spending_date AND spending_date <=:max_spending_date";

		$connection = Yii::app()->db;
		$command = $connection->createCommand($sql);
		$records = $command->queryAll(true, array(':min_spending_date'=>$min_spending_date, ':max_spending_date'=>$max_spending_date));		
		return $records;
	}

	public function exportDocuments($filename, $records, $format)
	{
		if($format=='json')
		{
			$this->jsonExport($filename, $records);
		}
		elseif($format=='csv')
		{
			$header = array('Numero Protocollo', 'Numero di Pubblicazione', 'Oggetto', 'Descrizione', 'Tipologia', 'Numero Atto', 'Data Atto', 'Data di Pubblicazione (Da)', 'Data di Pubblicazione (A)', 'Servizio Proponente', 'Ente');
			$this->csvExport($filename, $header, $records);
		}
	}

	public function exportExpenses($filename, $records, $format)
	{
		if($format=='json')
		{
			$this->jsonExport($filename, $records);
		}
		elseif($format=='csv')
		{
			$header = array('Titolo', 'Beneficiario', 'Importo', 'Norma di Attribuzione', 'Ufficio Competente', 'Impiegato', 'Modalità di Attribuzione', 'Descrizione', 'Data');
			$this->csvExport($filename, $header, $records);
		}		
	}

	protected function jsonExport($filename, $data)
	{
		header('Content-type: application/json');
		header('Content-disposition: attachment; filename='.$filename.'.json');
		header('Pragma: no-cache');		
		echo json_encode($data);
		exit();
	}

	protected function csvExport($filename, $header, $data)
	{

		header('Content-type: application/csv');
		header('Content-disposition: attachment; filename='.$filename.'.csv');
		header('Pragma: no-cache');		
		$this->outputCSV($header, $data);
		exit();
	}

	protected function outputCSV($header, $data) {
        $outputBuffer = fopen("php://output", 'w');
        fputcsv($outputBuffer, $header);
        foreach($data as $val) {
            fputcsv($outputBuffer, $val);
        }
        fclose($outputBuffer);
    }	
}