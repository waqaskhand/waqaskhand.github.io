<div class="Page-Data">
<h1>Generate Code</h1>
<form name="RecordForm" id="RecordForm" method="post" action="" onsubmit="return ValidateDCode();" enctype="multipart/form-data">
<input type="hidden" name="ValidateDCodeFlag" id="ValidateDCodeFlag" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td width="25%" align="left">Discount in %</td>
        <td width="75%" align="left"><input type="number" name="Discount" id="Discount" class="Textfield" value="" required="required" /></td>
    </tr>
	<tr>
	  <td align="left">Number of codes to generate</td>
	  <td align="left">
      	<input type="number" name="Loop" id="Loop" class="Textfield" required="required" />
      </td>
	  </tr>
    <tr>
    	<td>&nbsp;</td>
        <td align="left"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" class="Button" /></td>
    </tr>
</table>
</form>
</div>