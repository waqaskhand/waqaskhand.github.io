<?php
	include_once("../classes/config.php");
	$Web->query("select * from subscriptions order by Date desc");
	
	if($Web->num_rows()>0)
	{
		include_once("../classes/export.class.php");
		$filename = 'subscribers.xls';
		$xls = new ExportXLS($filename);
		
		$header[0] = "S.No";
		$header[1] = "Email";
		$header[2] = "Date";
		$xls->addHeader($header);
		$Sno=0;
		while($Web->next_Record())
		{
			$Sno++;
			$record[0] = $Sno;
			$record[1] = $Web->f('Email');
			$record[2] = $Web->FormatDate($Web->f('Date'));
			$xls->addRow($record);
		}
		$xls->sendFile();
	}
	else
	{
		echo 'No record found to export.';
	}
?>