{strip}
	
<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post" enctype="multipart/form-data">

	<input type="hidden" id="fa" name="fa" value="import">	
	<input type="hidden" id="f_stamp" name="f_stamp" value="{$f_stamp}">		
	
{if $f_stamp == ""}
	
	<div class="form-group">
		<label class="control-label col-md-2" for="fd_file">Datei *</label>
		<div class="col-md-10">
			<div class="input-group">
				<input type="text" class="form-control" readonly placeholder="W&auml;hlen Sie eine Datei aus">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-folder-open" id="fb_file" style="cursor: pointer;"></span>
					<div style="display: none;">
						<input type="file" id="fd_file" name="fd_file" />                        
					</div>
				</span>
			</div>
			<div class="help-block">(max. {$uploadsize} Bytes)</div>
		</div>
	</div>

{/strip}
<script type="text/javascript">
<!--

$(document).on('click', '#fb_file', function() {
	$('#fd_file').click();
});

$(document).on('change', '#fd_file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('#fd_file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
        
    });
});
//-->
</script>
{strip}
	
	<div class="form-group">
		<div class="col-md-10 col-md-offset-2">
			<input type="checkbox" id="f_reset" name="f_reset" value="1" />
			&nbsp;bestehende Datenbank und Dateien l&ouml;schen? (Es wird ein Backup angelegt)
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-10 col-md-offset-2">
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'import');">Importieren</button>
		</div>
	</div>

	{if $f_error}

	<div class="form-group">
		<div class="col-md-10 col-md-offset-2 alert alert-danger">
		{if $f_error == 1}
			Datei Upload Attacke erkannt, oder mehrere Dateien übertragen
		{elseif $f_error == 2}
			Eine Datei mit 0 Bytes wurde upgeloaded!
		{elseif $f_error == 3}
			Unerlaubter Dateiname! (erlaubt sind: 0-9 A-Z _ - .)
		{elseif $f_error == 4}
			Dateiname zu lang! (max 255. Zeichen)
		{elseif $f_error == 5}
			Unerlaubter Dateityp (erlaubt sind .zip-Dateien)
		{elseif $f_error == 6}
			Datei konnte nicht gespeichert werden (unzureichende Schreibrechte im PHP-Temp Verzeichnis)			
		{elseif $f_error == 7}
			Inhalt der Import-Datei ung&uuml;ltig!			
		{elseif $f_error == 8}
			Import-ZIP-Datei besch&auml;digt!			
		{elseif $f_error == 9}
			Keine Datei ausgew&auml;hlt. Bitte wiederholen Sie den Upload!			
		{elseif $f_error == 10}
			Dateigr&ouml;&szlig;e &uuml;berschritten!			
		{elseif $f_error == 11}
			Backup-Datei kann nicht gespeichert werden (unzureichende Schreibrechte im "bak" Verzeichnis)			
		{else}
			Undefinierter Fehler!
		{/if}
		</div>
	</div>

	{/if}

	{if $f_success}
	
	<div class="form-group">
		<div class="col-md-10 col-md-offset-2 alert alert-success">
			Datei Import erfolgreich!
		</div>
	</div>

	{/if}
	
{else}

	<input type="hidden" id="f_reset" name="f_reset" value="{$f_reset}">		

	<div class="collapsible">
		<div class="row">

			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Tabellen</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
	{foreach $data["tab"] as $valuearr}
		<h5>{$valuearr["aTable"]} ({$valuearr["aTableDesc"]})</h5><br>
		{if $valuearr["fields"]}
		<table class="table table-condensed">
			<thead>
				<th>Datenfeld</th>
				<th>Beschreibung</th>
				<th>Datentyp</th>
			</thead>
			<tbody>
			{foreach $valuearr["fields"] as $valuefieldarr}
			<tr>
				<td>{$valuefieldarr["aField"]}</td>
				<td>{$valuefieldarr["aFieldDesc"]}</td>
				<td>{$valuefieldarr["aFieldType"]}</td>
			</tr>
			{/foreach}
			</tbody>
		</table>
		{/if}
		{if $valuearr["ref"]}
		<table class="table table-condensed">
			<thead>
				<th>Referenz</th>
				<th>Beschreibung</th>
				<th>Datenfeld(er)</th>
				<th>Suchfeld(er)</th>
				<th>Sortierung</th>
			</thead>
			<tbody>
			{foreach $valuearr["ref"] as $valuerefarr}
			<tr>
				<td>{$valuerefarr["aRef"]}</td>
				<td>{$valuerefarr["aRefDesc"]}</td>
				<td>{$valuerefarr["aField"]}</td>
				<td>{$valuerefarr["aSearch"]}</td>
				<td>{$valuerefarr["aOrder"]}</td>
			</tr>
			{/foreach}
			</tbody>
		</table>
		{/if}
		{if $valuearr["ref1n"]}
		<table class="table table-condensed">
			<thead>
				<th>Referenzen 1:n</th>
			</thead>
			<tbody>
			{foreach $valuearr["ref1n"] as $value}
			<tr>
				<td>{$value}</td>
			</tr>
			{/foreach}
			</tbody>
		</table>
		{/if}
		<hr>
	{/foreach}
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Module</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
	{foreach $data["mod"] as $valuearr}
		{$valuearr["aModule"]} ({$valuearr["aModuleDesc"]})<br>
	{/foreach}					
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Men&uuml;</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
	{foreach $data["men"] as $valuearr}
		{$valuearr["aMenu"]}{if $valuearr["aModule"]} ({$valuearr["aModule"]}){/if}<br>
		{foreach $valuearr["subMenu"] as $valuesubarr}
			|-- {$valuesubarr["aMenu"]}{if $valuesubarr["aModule"]} ({$valuesubarr["aModule"]}){/if}<br>
		{/foreach}
	{/foreach}
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Dateien</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
	datasql:<br>
	{foreach $data_files["datasql"] as $value}
		- {$value}<br>
	{/foreach}

	datafile:<br>
	{foreach $data_files["datafile"] as $value}
		- {$value}<br>
	{/foreach}

	{foreach $data_files["mod"] as $key => $valuearr}
		Modul {$key}:<br>
		{foreach $valuearr as $value}
			- {$value}<br>
		{/foreach}
	{/foreach}

	Dokumente:<br>
	{foreach $data_files["doc"] as $value}
		- {$value}<br>
	{/foreach}
	
	{if count($backup_files)}
		<hr>
		Backup:<br>
		{foreach $backup_files as $value}
			- {$value}<br>
		{/foreach}
	{/if}
					</div>
				</div>
			</div>			
		
		</div>	
	</div>	

	<div class="form-group">
		<div class="col-md-10">
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'importgo');">Import durchführen</button>
			&nbsp;&nbsp;
			<button type="submit" class="btn btn-danger" onClick="$('#fa').attr('value', 'importcancel');">Import abbrechen</button>
		</div>
	</div>

{/if}
	
</form>

{/strip}
