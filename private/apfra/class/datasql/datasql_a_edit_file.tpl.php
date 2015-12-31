{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}
{assign var="tmpfileinfo" value="{$datasql_edit_fields[ti].row[i].col[j].field}_fileinfo"}

<div class="form-group has-feedback" id="tdiv_{$datasql_edit_fields[ti].row[i].col[j].field}">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="fd_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} *{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
	{if $data.$tmpfileinfo}
		<div class="input-group" style="margin-bottom: 10px;">
			<input type="text" class="form-control" id="fi_{$datasql_edit_fields[ti].row[i].col[j].field}" name="fi_{$datasql_edit_fields[ti].row[i].col[j].field}" value="{$data.$tmpfileinfo["name"]} ({$data.$tmpfileinfo["size"]} bytes)" readonly="readonly" />
			<span class="input-group-addon">
				<a href="{$url}index.php?mod={$module}&a=file&col={$datasql_edit_fields[ti].row[i].col[j].field}&id={$id}" target="{$module}_{$datasql_edit_fields[ti].row[i].col[j].field}_{$id}"><span class="glyphicon glyphicon-file"></span></a>
			</span>
			<span class="input-group-addon label-danger">
				<a role="button" href="#" data-row="tdiv_{$datasql_edit_fields[ti].row[i].col[j].field}" data-info="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}: {$data.$tmpfileinfo["name"]} ({$data.$tmpfileinfo["size"]} bytes)" data-href="{$url}index.php?mod={$module}&a={$action}&fa=delf_{$datasql_edit_fields[ti].row[i].col[j].field}&p={$page}&pp={$perpage}&s={$search}&sort={$sort}&dir={$dirsort}&id={$id}&t={$tab}" data-toggle="modal" data-target="#Modal-delete-file"><span class="glyphicon glyphicon-trash btn-danger"></span></a>
			</span>
		</div>
	{/if}
		<div class="input-group">
			<input type="text" class="form-control" readonly placeholder="W&auml;hlen Sie eine Datei aus">
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-folder-open" id="fb_{$datasql_edit_fields[ti].row[i].col[j].field}" style="cursor: pointer;"></span>
				<div style="display: none;">
					<input type="file" id="fd_{$datasql_edit_fields[ti].row[i].col[j].field}" name="fd_{$datasql_edit_fields[ti].row[i].col[j].field}" data-error="Bitte f&uuml;llen Sie dieses Feld aus."
						{if isset($datasql_edit_fields[ti].row[i].col[j].required) && $datasql_edit_fields[ti].row[i].col[j].required == 1} required="required"{/if}
						{if $apfra_rights[$module]["upd"] == 0} readonly="readonly"{/if}/> {* TODO: multiple ?*}                        
				</div>
			</span>
		</div>
		<div class="help-block">(max. {$uploadsize} Bytes)</div>
		<div class="help-block with-errors"></div>
	</div>
</div>

{/strip}
<script type="text/javascript">
<!--

$(document).on('click', '#fb_{$datasql_edit_fields[ti].row[i].col[j].field}', function() {
	$('#fd_{$datasql_edit_fields[ti].row[i].col[j].field}').click();
});

$(document).on('change', '#fd_{$datasql_edit_fields[ti].row[i].col[j].field}', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('#fd_{$datasql_edit_fields[ti].row[i].col[j].field}').on('fileselect', function(event, numFiles, label) {
        
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
{/strip}
