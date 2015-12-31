{strip}

	<div class="modal fade" id="Modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button id="modal-add-btnclose" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Eintrag hinzuf&uuml;gen</h4>
		  </div>
		  <div class="modal-body">
			Wollen Sie einen Eintrag hinzuf&uuml;gen?<br>
		  	<br>
			<p id="modal-add-info"></p>
		  </div>
		  <div class="modal-footer">
			<a id="modal-add-btnsubmit" class="btn btn-success">Hinzuf&uuml;gen</a>
			<button id="modal-add-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="Modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button id="modal-del-btnclose" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Verkn&uuml;pfung l&ouml;schen?</h4>
		  </div>
		  <div class="modal-body">
			Sind Sie sicher, dass Sie die Verkn&uuml;pfung zu diesem Eintrag l&ouml;schen wollen?<br>
			<br>
			<p id="modal-del-info"></p>
		  </div>
		  <div class="modal-footer">
			<a id="modal-del-btnsubmit" class="btn btn-danger">L&ouml;schen</a>
			<button id="modal-del-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="Modal-delete-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button id="modal-del-file-btnclose" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Eintrag l&ouml;schen?</h4>
		  </div>
		  <div class="modal-body">
			Sind Sie sicher, dass Sie diese Datei l&ouml;schen wollen?<br>
			<br>
			<p id="modal-del-file-info"></p>
		  </div>
		  <div class="modal-footer">
			<a id="modal-del-file-btnsubmit" class="btn btn-danger">L&ouml;schen</a>
			<button id="modal-del-file-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

{/strip}
<script type="text/javascript">
<!--
$('#Modal-add').on('show.bs.modal', function(e) {
    $('#modal-add-btnsubmit').attr('href', $(e.relatedTarget).data('href'));
    $('#modal-add-info').html($(e.relatedTarget).data('info'));
});

$('#Modal-delete').on('show.bs.modal', function(e) {
    $('#modal-del-btnsubmit').attr('href', $(e.relatedTarget).data('href'));
    $('#modal-del-info').html($(e.relatedTarget).data('info'));
    $('#'+$(e.relatedTarget).data('row')).addClass('danger');
    $('#modal-del-btncancel, #modal-del-btnclose').click(function() {
    	$('#'+$(e.relatedTarget).data('row')).removeClass('danger');
    });
});

$('#Modal-delete-file').on('show.bs.modal', function(e) {
    $('#modal-del-file-btnsubmit').attr('href', $(e.relatedTarget).data('href'));
    $('#modal-del-file-info').html($(e.relatedTarget).data('info'));
    $('#'+$(e.relatedTarget).data('row')).addClass('alert-danger');
    $('#modal-del-file-btncancel, #modal-del-file-btnclose').click(function() {
    	$('#'+$(e.relatedTarget).data('row')).removeClass('alert-danger');
    });
});
//-->
</script>
{strip}

<fieldset>
	<legend>
	{section name=i loop=$datasql_edit_field_legend}
	{if $datasql_edit_field_legend[i]|truncate:6:"" == 'refid_'}
		{$data.ref[{$datasql_edit_field_legend[i]}]}
	{else}
			{$data.{$datasql_edit_field_legend[i]}}
	{/if}
		{if $smarty.section.i.index<$smarty.section.i.max-1}, {/if}
	{/section}
	{if $id != 0}
		<a role="button" class="pull-right btn btn-sm btn-default" target="print" href="{$url}index.php?mod={$module}&a=print&id={$id}"><span class="glyphicon glyphicon-print"></span></a>
	{/if}
	{if $data.ref_benutzer != "" && $data.aLastUpdate != ""}
		<small class="pull-right">
			zuletzt bearbeitet von {$data.ref_benutzer} am {$data.aLastUpdate|date_format:'%d.%m.%Y %H:%M:%S'}&nbsp;
		</small>
	{/if}
	</legend>

	<form role="form" data-toggle="validator" class="form-horizontal" action="{$url}index.php?mod={$module}&p={$page}&s={$search}{$data_filter_url}&a={$action}&id={$id}&backmod={$backmod}&backid={$backid}&backt={$backt}" method="post" enctype="multipart/form-data">

		<input type="hidden" id="fa" name="fa" value="save">
		<input type="hidden" id="t" name="t" value="{$tab}">

	{if $datasql_edit_fields|@count > 1}
		<ul class="nav nav-tabs">
		{section name=ti loop=$datasql_edit_fields}
			{if $tab == ""}{assign var="tab" value="{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}"}{/if}
			<li id="tab_{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}"{if "{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}" == $tab} class="active"{/if}><a href="#{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}" data-toggle="tab" onClick="$('#t').attr('value', '{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}');">{$datasql_edit_fields[ti].desc}</a></li>
		{/section}
	  	</ul>
  	{/if}

  	{if $datasql_edit_fields|@count > 1}
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="tab-content">
	{/if}

				{section name=ti loop=$datasql_edit_fields}

  			{if $datasql_edit_fields|@count > 1}
				<div class="tab-pane{if "{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}" == $tab} active{/if}" id="{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}">
			{/if}

					{section name=i loop=$datasql_edit_fields[ti].row}

					{if $datasql_edit_fields[ti].row[i].desc != ""}
					<div class="page-header">
						{$datasql_edit_fields[ti].row[i].desc}
					</div>
					{/if}

					<div class="row">

						{section name=j loop=$datasql_edit_fields[ti].row[i].col}

						<div class="col-md-{12/$datasql_edit_fields[ti].row[i].col|@count}">

							{if $datasql_edit_fields[ti].row[i].col[j].type == 'aLogDB' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'image' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'file' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'reference1nsub' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'reference1n' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'reference1n_min' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'reference' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'reference_combobox' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'reference_select' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'fixed_fields' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'fixed_edit_fields' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'lookup' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'text' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'textarea' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'datetime' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'date' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'readonly' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'password' ||
								$datasql_edit_fields[ti].row[i].col[j].type == 'checkbox'}

{include file="`$path`../private/apfra/class/datasql/datasql_a_edit_`$datasql_edit_fields[ti].row[i].col[j].type`.tpl.php"}

							{/if}

						</div>

						{/section}

					</div>

					{/section}

  			{if $datasql_edit_fields|@count > 1}
				</div>
				{/if}

				{/section}

  	{if $datasql_edit_fields|@count > 1}
				</div>
			</div>
		</div>
	{/if}
		<div class="form-group">
			<div class="col-md-offset-2 col-md-4">
			{if $apfra_rights[$module]["upd"] == 1}
				<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'save');">Speichern</button>
				&nbsp;
				<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'saveback');">Speichern &amp; zur&uuml;ck</button>
				&nbsp;
			{/if}
			{if $backmod && $backid}
				<a class="btn btn-default" href="{$url}index.php?mod={$backmod}&a=edit&id={$backid}&t={$backt}">Zur&uuml;ck</a>
			{else}
				<a class="btn btn-default" href="{$url}index.php?mod={$module}&p={$page}&s={$search}{$data_filter_url}&sort={$sort}&dir={$dirsort}">Zur&uuml;ck</a>
			{/if}
			</div>
		</div>

	</form>

</fieldset>

{if $errors != 0}
	<div class="alert alert-danger">
		<h4>Fehler bei der Anmeldung!</h4>
		<p>Benutzername oder Kennwort falsch.</p>
	</div>
{/if}

{/strip}
