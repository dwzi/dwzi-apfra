{strip}
	
<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post" enctype="multipart/form-data">

	<input type="hidden" id="fa" name="fa" value="">	
	<input type="hidden" id="step" name="step" value="{$step}">	

	{if $step != 1}<input type="hidden" id="f_table" name="f_table" value="{$f_table}">{/if}	
	<input type="hidden" id="f_stamp" name="f_stamp" value="{$f_stamp}">	

	<div class="stepwizard">
		<div class="stepwizard-row">
		{section name=i loop=$steps}
			<div class="stepwizard-step" style="width: {round(100/$steps|@count)}%;">
				<button type="submit" id="btn_{$steps[i].step}" class="btn {if $steps[i].step == $step}btn-primary{else}btn-{if $steps[i].ok}success{else}default{/if}{/if}"{if $steps[i].step == 99} disabled="disabled"{/if} onClick="$('#fa').attr('value', 'direct'); $('#step').attr('value', '{$steps[i].step}');">{$steps[i].step}</button>
				<p>{$steps[i].desc}</p>
			</div>
		{/section}
		</div>
	</div>	

{if $step == 1}

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4>Bitte w&auml;hlen Sie eine Tabelle aus</h4>
		</div>
	</div>
	
	<div class="funkyradio">
	{section name=i loop=$data}
		<div class="funkyradio-default col-md-offset-2 col-md-8">
			<input class="form-control" type="radio" name="f_table" id="f_table_{$data[i].id}" value="{$data[i].aTable}" {if $data[i].aTable == $f_table}checked="checked" {/if} onChange="$('#f_fields').attr('value', ''); $('#f_filter').attr('value', ''); $('#f_order').attr('value', ''); $('#btn_2').removeClass('btn-success');$('#btn_2').addClass('btn-default'); $('#btn_3').removeClass('btn-success');$('#btn_3').addClass('btn-default'); $('#btn_4').removeClass('btn-success');$('#btn_4').addClass('btn-default');" />
			<label for="f_table_{$data[i].id}">{$data[i].aTable} ({$data[i].aTableDesc})</label>
		</div>			
	{/section}
	</div> 
	
{elseif $step == 2}

	{if $f_stamp == "" || 1==1}
	
	<div class="form-group">
		<label class="control-label col-md-2" for="fd_file">Datei *</label>
		<div class="col-md-10">
			<div class="input-group">
				<input type="text" class="form-control" readonly placeholder="W&auml;hlen Sie eine Datei aus">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-folder-open" id="fb_file" style="cursor: pointer;"></span>
					<div style="display: none;">
						<input type="file" id="fd_file" name="fd_file" onChange="$('#fa').attr('value', 'upload'); this.form.submit();" />                        
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
		<label class="control-label col-md-2" for="f_imp_hdr">Kopfzeile?</label>
		<div class="col-md-10">
			<input type="checkbox" id="f_imp_hdr" name="f_imp_hdr" value="1"{if $f_imp_hdr == "1"} checked{/if}/>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-2" for="f_imp_sep">Feld-Trennzeichen</label>
		<div class="col-md-2">
			<select class="selectpicker form-control" id="f_imp_sep" name="f_imp_sep" data-error="Bitte füllen Sie dieses Feld aus.">
				<option></option>
				<option value="\t"{if $f_imp_sep == "\t"} selected{/if}>Tab</option>
				<option value=","{if $f_imp_sep == ","} selected{/if}>, Komma</option>
				<option value=";"{if $f_imp_sep == ";"} selected{/if}>; Semikolon</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-2" for="f_imp_enc">Feld-Begrenzung</label>
		<div class="col-md-2">
			<select class="selectpicker form-control" id="f_imp_enc" name="f_imp_enc" data-error="Bitte füllen Sie dieses Feld aus.">
				<option></option>
				<option value=""{if $f_imp_enc == ""} selected{/if}>(leer)</option>
				<option value="\""{if $f_imp_enc == "\""} selected{/if}>"</option>
				<option value="'"{if $f_imp_enc == "'"} selected{/if}>'</option>
			</select>
		</div>
	</div>
	
{/strip}
<script type="text/javascript">
<!--
$('#f_imp_sep').selectpicker();
$('#f_imp_enc').selectpicker();
//-->
</script>
{strip}
	
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
				Unerlaubter Dateityp (erlaubt sind .csv und .txt-Dateien)
			{elseif $f_error == 6}
				Datei konnte nicht gespeichert werden (unzureichende Schreibrechte im PHP-Temp Verzeichnis)			
			{elseif $f_error == 7}
				(undefiniert, nicht vorhanden)			
			{elseif $f_error == 8}
				Datei besch&auml;digt!			
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

	{/if}

{elseif $step == 3}

	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th colspan="{$data_header|@count}">Tabellen-Felder</th>
		</tr>
		<tr>
		{section name=i loop=$data_header}
			<th>
				<select class="selectpicker form-control" id="f_field_{$smarty.section.i.index}" name="f_field_{$smarty.section.i.index}">
					<option></option>
					<option value="">--- ignorieren ---</option>
				{section name=j loop=$data_fields}
					<option value="{$data_fields[j].field}"{if $data_fields[j].field == $data_header[i]} selected{/if}>{$data_fields[j].desc}</option>
				{/section}
				</select>
{/strip}
<script type="text/javascript">
<!--
$('#f_field_{$smarty.section.i.index}').selectpicker();
//-->
</script>
{strip}
			</th>
		{/section}
		</tr>
		<tr>
			<th colspan="{$data_header|@count}">Import-Datei</th>
		</tr>
		<tr>
		{section name=i loop=$data_header}
			<th>{$data_header[i]}</th>
		{/section}
		</tr>
		</thead>
		<tbody>		
	{section name=i loop=$data}
		<tr>
		{foreach $data[i] as $key => $value}
			<td>{$value}</td>
		{/foreach}
		</tr>
	{/section}
		</tbody>	
	</table>
		
{/if}


	<div class="row"><div class="col-md-12"><p>&nbsp;</p></div></div>	

	<div class="row">
		<div class="col-md-2 col-md-offset-4 text-center">
		{if $step > 1}
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'prev'); $('#step').attr('value', '{$step-1}');">&laquo; zur&uuml;ck</button>
		{/if}
		</div>
		<div class="col-md-2 text-center">
		{if $step < $steps|@count}	
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'next'); $('#step').attr('value', '{$step+1}');">weiter &raquo;</button>
		{elseif $step == $steps|@count}
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'import');">Import</button>
		{/if}
		</div>
	</div>

</form>

{/strip}
