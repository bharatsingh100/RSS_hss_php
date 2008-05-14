<h2><?=$shakha->name?></h2>
<?php //var_dump($this->session->ro_userdata('errs')); ?>
<form action="<?=$this->uri->uri_string()?>" method="post" enctype="multipart/form-data" name="form1">
<input type="hidden" name="MAX_FILE_SIZE" value="200000" />
  <h3>Checklist for Uploading Contacts</h3>
    <ol>
    <li> Your spreadsheet must have column headers. (Name, E-mail etc.)</li>
    <li> Each family member on separate line.</li>
    <li> Contacts from same family are combined together using a Alpha characters in a separate column (i.e. A, B, AA, BB etc.)</li>
    <li> Gender specified by M or F only</li>
    <li> If there is Age column, change it to year of birth (4 digit)</li>
    <li> Your file is comma separated and has CSV extension.</li>
    <li> <a href="/spreadsheet_template.xls">Sample Spreadsheet</a></li>
  </ol>
  <hr />
  <br />
  <p>Once your contacts are uploaded to the database, you will be notified via e-mail. It may take upto 2-3 days for this to happen.</p>
  <p><strong>Select File</strong>: 
    <label>
    <input type="file" name="contacts" id="contacts">
    </label>
  </p>
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Upload File">
    </label>
  </p>
</form>
