<h2><?=$shakha->name?></h2>
<?php
//var_dump($this->session->userdata('import_c'));
if($this->session->ro_userdata('errs'))
{
	echo '<h3>Please fix following errors:</h3>';
	echo "<ul>\n";
	foreach($this->session->userdata('errors') as $msg)
		echo '<li>' . $msg . "<li>\n";
	echo "<\ul>";
}
?>
<form action="/shakha/finish_import/<?=$this->uri->segment(3)?>" method="post" name="form1">
  <h3>Preview of Imported Contacts</h3>
  <p>1. Make sure all the contacts are correctly matched with their fields and family.</p>
  <p>2. Uncheck any contact that is not correct.</p>
  <p>3. If too many contacts are incorrect, start the Import Contacts process again.</p>
  <hr />
    <p>&nbsp;</p>
    <table>
    
<?php
	echo "\t<tr>\n";
	echo "<td>&nbsp;</td>\n\t\t";
	foreach($data[0] as $key => $val)
		echo '<td><strong>' . $val . "</strong></td>\n\t\t";
	echo "\t</tr>\n";
	unset($data[0]);
	$i = -1;
	foreach($data as $d){
		echo "\t<tr>\n";
		echo '<td><input name="fin[]" type="checkbox" value="'.++$i.'" checked />'."</td>\n\t\t";
		foreach($d as $key => $val)
			echo '<td>' . $val . "</td>\n\t\t";
		echo "\t</tr>\n";
	}
?>
</table>
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Import Contacts">
    </label>
  </p>
</form>
