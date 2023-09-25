<?php

class WebFunctions extends DBMySql

{

	function __construct()

	{

		parent::__construct();

	}

	

	function LoggedInUser()

	{

		return $_SESSION[ADMIN_LOGGED_IN]["TableID"];

	}

	

	function getFieldData($StringField, $WhereField, $WhereValue, $TableName)

	{

		$this->query("Select $StringField from $TableName where $WhereField='".$WhereValue."'");

		$this->next_Record();

		return $this->f($StringField);

	}

	

	function getRecord($id, $primarykey, $tablename, $OrderBy='')

	{

		if($OrderBy!='')

		{

			$OrderByQuery = ' order by '.$OrderBy;

		}

		else

		{

			$OrderByQuery = '';

		}
		$this->query("Select * from ".$tablename." where ".$primarykey."='".$id."'".$OrderByQuery);

		$count = $this->num_rows();

		if($count==0)

		{

			$object=0;

		}

		else

		{

			$i=0;

			$count=$this->num_fields();

			while($this->next_Record())

			{

				while($i<$count)

				{

					$FieldName = $this->TableFieldName($i, $tablename);

					$object[$FieldName]=$this->f($FieldName);

					$i++;

				}

			}

		}

		return $object;

	}

	

	function getSelectDropDown($SelectedIndex, $FieldOne, $FieldTwo, $TableName, $SortField)

	{

		$this->query("Select $FieldOne, $FieldTwo from $TableName order by $SortField");

		$Text="";

		while($this->next_Record())

		{

			if($SelectedIndex==$this->f($FieldOne))

			{

				$Text.='<option selected="selected" value="'.$this->f($FieldOne).'">'.$this->f($FieldTwo).'</option>';

			}

			else

			{

				$Text.='<option value="'.$this->f($FieldOne).'">'.$this->f($FieldTwo).'</option>';

			}

		}

		return $Text;

	}

	

	function getSelectDropDownWhereField($SelectedIndex, $FieldOne, $FieldTwo, $WhereField, $WhereValue, $TableName, $SortField)

	{

		$this->query("Select $FieldOne, $FieldTwo from $TableName where $WhereField='".$WhereValue."' order by $SortField");

		$Text="";

		while($this->next_Record())

		{

			if($SelectedIndex==$this->f($FieldOne))

			{

				$Text.='<option selected="selected" value="'.$this->f($FieldOne).'">'.$this->f($FieldTwo).'</option>';

			}

			else

			{

				$Text.='<option value="'.$this->f($FieldOne).'">'.$this->f($FieldTwo).'</option>';

			}

		}

		return $Text;

	}

	

	function GeneratePassword($length)

	{

		$password = "";

		$possible = "0123456789bcdfghjkmnpqrStuwxyz";

		$i = 0;

		while($i < $length)

		{

			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

			if (!strstr($password, $char))

			{

				$password .= $char;

				$i++;

			}

		}

		return $password;

	}

	

	function Redirect($url, $time='0')

	{

		echo '<meta http-equiv=refresh content='.$time.';URL='.$url.'>';

		exit;

	}

	

	function ShowAlert($msg)

	{

?>

<script language="javascript">

	alert("<?php echo $msg; ?>");

</script>

<?php

	}

	

	function GetCurrentDateTime($Format='Y-m-d H:i:s')

	{

		return date($Format);

	}

	

	function FormatDate($DateValue, $Format='M d Y', $Return='Blank')

	{

		if(($DateValue!='') && ($DateValue!='0000-00-00'))

		{

			return date($Format, strtotime($DateValue));

		}

		else

		{

			if($Return=='Blank')

			return '';

			else

			return '-';

		}

	}



	function get_time_difference($start, $end)

	{

		$uts['start']      =    strtotime( $start );

		$uts['end']        =    strtotime( $end );

		if( $uts['start']!==-1 && $uts['end']!==-1 )

		{

			if( $uts['end'] >= $uts['start'] )

			{

				$diff    =    $uts['end'] - $uts['start'];

				if( $days=intval((floor($diff/86400))) )

					$diff = $diff % 86400;

				if( $hours=intval((floor($diff/3600))) )

					$diff = $diff % 3600;

				if( $minutes=intval((floor($diff/60))) )

					$diff = $diff % 60;

					

				$diff    =    intval( $diff );

				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );

			}

			else

			{

				return 0;

			}

		}

	}

	

	function RefreshParentWindow()

	{

?>

<script language="javascript">

	window.opener.location.reload();

</script>

<?php

	}

	

	function WindowClose()

	{

?>

<script language="javascript">

	window.close();

</script>

<?php

	}

	

	function WindowOpen($url)

	{

?>

<script language="javascript">

	window.open("<?php echo $url; ?>");

</script>

<?php

	}

	

	function FilterString($Request)

	{

		if(is_array($Request))

		{

			foreach($Request as $Key => $value)

			{

				$val1 = str_replace("'", "&acute;", $value);

				$Data[$Key] = $val1;

				$_SESSION['REQUEST'][$Key]=$val1;

			}

			return $Data;

		}

	}

	

	function ConfirmationMessage($Message)

	{

		$_SESSION[CONFIRMATION_MESSAGE] = $Message;

	}

	

	function ValidateEmailFormat($Email)

	{

		if(filter_var($Email, FILTER_VALIDATE_EMAIL))

		{

			return true;

		}

		else

		{

			return false;

		}

	}

	

	function IsFieldValueExists($FieldValue, $FieldName, $TableName)

	{

		$this->query("select TableID from $TableName where $FieldName='$FieldValue'");

		$Count = $this->num_rows();

		if($Count==0)

		{

			return true;

		}

		else

		{

			return false;

		}

	}

	

	function GetExtension($File)

	{

		$info = pathinfo($File);

		return strtolower($info['extension']);

	}

	

	function DeleteFile($filename, $filelocations)

	{

		foreach($filelocations as $location)

		{

			unlink($location.'/'.$filename);

		}

	}

	

	function GetMaxID($TableName='', $FieldName='TableID', $Increment=1)

	{

		if($TableName!='')

		{

			$GetMaxIDQuery = "SELECT MAX($FieldName)+$Increment as IncrementedValue FROM $TableName";

			$this->query($GetMaxIDQuery);

			$this->next_Record();

			if(is_numeric($this->f("IncrementedValue")))

			{

				$Return = $this->f("IncrementedValue");

			}

			else

			{

				$Return = 1;

			}

		}

		else

		{

			$Return = 0;

		}

		return $Return;

	}

	

	function GetMinID($TableName='', $FieldName='TableID')

	{

		if($TableName!='')

		{

			$GetMaxIDQuery = "SELECT MIN($FieldName) as DecrementedValue FROM $TableName";

			$this->query($GetMaxIDQuery);

			$this->next_Record();

			if(is_numeric($this->f("DecrementedValue")))

			{

				$Return = $this->f("DecrementedValue");

			}

			else

			{

				$Return = 1;

			}

		}

		else

		{

			$Return = 0;

		}

		return $Return;

	}

	

	function SendEmail($Email='', $Subject='', $Content='')

	{

		$MailHTML = '<html>

		<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>Notification Email</title>

		<style>

		* {

			margin:0px;

			padding:0px;

		}

		body {

			font-family:Arial, Helvetica, sans-serif;

			font-size:12px;

			color:#000;

		}

		a, a:hover {

			color:#000000;

		}

		td {

			padding:5px;

		}

		</style>

		</head>

		<body>

		<div>'.$Content.'<br /><br /><br /><strong>'.EMAIL_NOTIFICATION_FOOTER.'</strong></div>

		</body>

		</html>';

		$Headers  = "MIME-Version: 1.0\r\n";

		$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		$Headers .= "From: ".SYSTEM_EMAIL_FROM_NAME."<".SYSTEM_EMAIL_FROM_EMAIL.">\r\n";

		

		/*echo "test<br />Email = ".$Email;

		echo "<br />Subject = ".$Subject;

		echo "<hr />".$MailHTML;
		
		echo "<hr />".$Headers;

		die();*/

		

		mail($Email, $Subject, $MailHTML, $Headers);

	}

	

	function HistoryBack()

	{

		echo '<script>history.back();</script>';

	}

	

	function uploadfile($File, $dir, $Extension)

	{

		$UplaodFileName = date("Ymdhis")."_".$this->GeneratePassword(8).".".$Extension;

		$UploadFilePath = $dir."/".$UplaodFileName;

	

		if(move_uploaded_file($File['tmp_name'], $UploadFilePath))

			return $UplaodFileName;

		else

			return FALSE;

	}

	

	function convert_number_to_words($number)

	{

		$hyphen      = '-';

		$conjunction = ' and ';

		$separator   = ', ';

		$negative    = 'negative ';

		$decimal     = ' point ';

		$dictionary  = array(

			0                   => 'Zero',

			1                   => 'One',

			2                   => 'Two',

			3                   => 'Three',

			4                   => 'Four',

			5                   => 'Five',

			6                   => 'Six',

			7                   => 'Seven',

			8                   => 'Eight',

			9                   => 'Nine',

			10                  => 'Ten',

			11                  => 'Eleven',

			12                  => 'Twelve',

			13                  => 'Thirteen',

			14                  => 'Fourteen',

			15                  => 'Fifteen',

			16                  => 'Sixteen',

			17                  => 'Seventeen',

			18                  => 'Eighteen',

			19                  => 'Nineteen',

			20                  => 'Twenty',

			30                  => 'Thirty',

			40                  => 'Fourty',

			50                  => 'Fifty',

			60                  => 'Sixty',

			70                  => 'Seventy',

			80                  => 'Eighty',

			90                  => 'Ninety',

			100                 => 'Hundred',

			1000                => 'Thousand',

			1000000             => 'Million',

			1000000000          => 'Billion',

			1000000000000       => 'Trillion',

			1000000000000000    => 'Quadrillion',

			1000000000000000000 => 'Quintillion'

		);

	   

		if (!is_numeric($number)) {

			return false;

		}

	   

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

			// overflow

			trigger_error(

				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,

				E_USER_WARNING

			);

			return false;

		}

	

		if ($number < 0) {

			return $negative . $this->convert_number_to_words(abs($number));

		}

	   

		$string = $fraction = null;

	   

		if (strpos($number, '.') !== false) {

			list($number, $fraction) = explode('.', $number);

		}

	   

		switch (true) {

			case $number < 21:

				$string = $dictionary[$number];

				break;

			case $number < 100:

				$tens   = ((int) ($number / 10)) * 10;

				$units  = $number % 10;

				$string = $dictionary[$tens];

				if ($units) {

					$string .= $hyphen . $dictionary[$units];

				}

				break;

			case $number < 1000:

				$hundreds  = $number / 100;

				$remainder = $number % 100;

				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];

				if ($remainder) {

					$string .= $conjunction . $this->convert_number_to_words($remainder);

				}

				break;

			default:

				$baseUnit = pow(1000, floor(log($number, 1000)));

				$numBaseUnits = (int) ($number / $baseUnit);

				$remainder = $number % $baseUnit;

				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];

				if ($remainder) {

					$string .= $remainder < 100 ? $conjunction : $separator;

					$string .= $this->convert_number_to_words($remainder);

				}

				break;

		}

	   

		if (null !== $fraction && is_numeric($fraction)) {

			$string .= $decimal;

			$words = array();

			foreach (str_split((string) $fraction) as $number) {

				$words[] = $dictionary[$number];

			}

			$string .= implode(' ', $words);

		}

	   

		return $string;

	}

	

	function NumberFormat($Amount='0')

	{

		$Number = number_format($Amount, 2);

		return $Number;

	}

	

	//You do not need to alter these functions

	function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)

	{

		list($imagewidth, $imageheight, $imageType) = getimagesize($image);

		$imageType = image_type_to_mime_type($imageType);

		$newImageWidth = ceil($width * $scale);

		$newImageHeight = ceil($height * $scale);

		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);

		switch($imageType)

		{

			case "image/gif":

			$source=imagecreatefromgif($image); 

			break;

			

			case "image/pjpeg":

			case "image/jpeg":

			case "image/jpg":

			$source=imagecreatefromjpeg($image); 

			break;

			

			case "image/png":

			case "image/x-png":

			$source=imagecreatefrompng($image); 

			break;

		}

		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);

		

		switch($imageType)

		{

			case "image/gif":

			imagegif($newImage,$thumb_image_name);

			break;

			

			case "image/pjpeg":

			case "image/jpeg":

			case "image/jpg":

			imagejpeg($newImage,$thumb_image_name, 100); 

			break;

			

			case "image/png":

			case "image/x-png":

			imagepng($newImage,$thumb_image_name);

			break;

		}

		chmod($thumb_image_name, 0777);

		return $thumb_image_name;

	}

	

	//You do not need to alter these functions

	function getHeight($image)

	{

		$size = getimagesize($image);

		$height = $size[1];

		return $height;

	}

	

	//You do not need to alter these functions

	function getWidth($image)

	{

		$size = getimagesize($image);

		$width = $size[0];

		return $width;

	}

	

	function convertString($str, $ky=HANDLER, $type='E')

	{

		$ky=HANDLER;

		if($type=='D')

		{

			$str = str_replace("##//##\\##", "'", $str);

			for($a=1; $a<=NO_TIMES; $a++)

			{

				$str = base64_decode($str);

			}

		}

		

		if($ky=='')

		{

			return $str;

		}

		else

		{

			$ky=str_replace(chr(32),'',$ky);

			

			if(strlen($ky)<8)exit('key error');

			

			$kl=strlen($ky)<32?strlen($ky):32;

			

			$k=array();

			

			for($i=0;$i<$kl;$i++)

			{

				$k[$i]=ord($ky{$i})&0x1F;

			}

			

			$j=0;

			

			for($i=0;$i<strlen($str);$i++)

			{

				$e=ord($str{$i});

				$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);

				$j++;

				$j=$j==$kl?0:$j;

			}

			

			if($type=='E')

			{

				$str = str_replace("'", "##//##\\##", $str);

				for($a=1; $a<=NO_TIMES; $a++)

				{

					$str = base64_encode($str);

				}

			}

			return $str;

		}

	}

	

	function FilterDescription($Description)

	{

		$return = str_replace("../upload_files", WEB_URL."upload_files", $Description);

		return $return;

	}

	

	function SubStrT($Str, $limit)

	{

		if(strlen($Str)>$limit)

		{

			$return = substr($Str, 0, $limit)."...";

			return $return;

		}

		else

		{

			return $Str;

		}

	}

	

	function clean($string)

	{

		$string = strtolower($string);

		// remove extra spaces from start and end

		$string = trim($string);

		

		// Replaces all spaces with hyphens.

		$string = str_replace(' ', '-', $string);

		

		// Removes special chars.

		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

		

		// Replaces multiple hyphens with single one.

		return preg_replace('/-+/', '-', $string);

	}

	

	function CheckUrlKeyword($String, $Table, $RecordID = '')

	{

		$String = strtolower($String);

		$String = $this->clean($String);

		if($RecordID!='')

			$Query = "select TableID from $Table where UrlKeyword='$String' and TableID!='$RecordID'";

		else

			$Query = "select TableID from $Table where UrlKeyword='$String'";

		$this->query($Query);

		$CountPages = $this->num_rows();

		if($CountPages>0)

			$String = $String."-".$CountPages;

		return $String;

	}

	

	function pagination($total_records, $per_page = 10, $page = 1, $url = '?')

	{

    	$total = $total_records;

        $adjacents = "2"; 



    	$page = ($page == 0 ? 1 : $page);  

    	$start = ($page - 1) * $per_page;								

		

    	$prev = $page - 1;							

    	$next = $page + 1;

        $lastpage = ceil($total/$per_page);

    	$lpm1 = $lastpage - 1;

    	

    	$pagination = "";

    	if($lastpage > 1)

    	{	

    		$pagination .= "<ul class='pagination'>";

                    $pagination .= "<li class='details'>Page $page of $lastpage</li>";

    		if ($lastpage < 7 + ($adjacents * 2))

    		{	

    			for ($counter = 1; $counter <= $lastpage; $counter++)

    			{

    				if ($counter == $page)

    					$pagination.= "<li><a class='current'>$counter</a></li>";

    				else

    					$pagination.= "<li><a href='{$url}no=$counter'>$counter</a></li>";					

    			}

    		}

    		elseif($lastpage > 5 + ($adjacents * 2))

    		{

    			if($page < 1 + ($adjacents * 2))		

    			{

    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)

    				{

    					if ($counter == $page)

    						$pagination.= "<li><a class='current'>$counter</a></li>";

    					else

    						$pagination.= "<li><a href='{$url}no=$counter'>$counter</a></li>";					

    				}

    				$pagination.= "<li class='dot'>...</li>";

    				$pagination.= "<li><a href='{$url}no=$lpm1'>$lpm1</a></li>";

    				$pagination.= "<li><a href='{$url}no=$lastpage'>$lastpage</a></li>";		

    			}

    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))

    			{

    				$pagination.= "<li><a href='{$url}no=1'>1</a></li>";

    				$pagination.= "<li><a href='{$url}no=2'>2</a></li>";

    				$pagination.= "<li class='dot'>...</li>";

    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)

    				{

    					if ($counter == $page)

    						$pagination.= "<li><a class='current'>$counter</a></li>";

    					else

    						$pagination.= "<li><a href='{$url}no=$counter'>$counter</a></li>";					

    				}

    				$pagination.= "<li class='dot'>..</li>";

    				$pagination.= "<li><a href='{$url}no=$lpm1'>$lpm1</a></li>";

    				$pagination.= "<li><a href='{$url}no=$lastpage'>$lastpage</a></li>";		

    			}

    			else

    			{

    				$pagination.= "<li><a href='{$url}no=1'>1</a></li>";

    				$pagination.= "<li><a href='{$url}no=2'>2</a></li>";

    				$pagination.= "<li class='dot'>..</li>";

    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

    				{

    					if ($counter == $page)

    						$pagination.= "<li><a class='current'>$counter</a></li>";

    					else

    						$pagination.= "<li><a href='{$url}no=$counter'>$counter</a></li>";					

    				}

    			}

    		}

    		

    		if ($page < $counter - 1){ 

    			$pagination.= "<li><a href='{$url}no=$next'>Next</a></li>";

                $pagination.= "<li><a href='{$url}no=$lastpage'>Last</a></li>";

    		}else{

    			$pagination.= "<li><a class='current'>Next</a></li>";

                $pagination.= "<li><a class='current'>Last</a></li>";

            }

    		$pagination.= "</ul>\n";		

    	}

    

    

        return $pagination;

    }

}

?>