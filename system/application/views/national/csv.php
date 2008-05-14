<?php 
		$delimiter = ",";
		$newline = "\r\n";
		print($this->dbutil->csv_from_result($query, $delimiter, $newline)); ?>