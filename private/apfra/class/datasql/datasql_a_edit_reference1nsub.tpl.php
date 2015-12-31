{strip}

<div class="form-group">
	<div class="col-md-{12/($datasql_edit_fields[ti].row[i].col|@count)}">
		<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="t_{$datasql_edit_fields[ti].row[i].col[j].field}">
		<thead>
		<tr>
			<th style="white-space: nowrap; width:30px;">&nbsp;</th>
		{section name=y loop=$datasql_edit_fields[ti].row[i].col[j].ref_value}
			{assign var="tmpdesc" value="{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}.{$datasql_edit_fields[ti].row[i].col[j].ref_value[y]}"}
			<th>{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}</th>
		{/section}
			<th style="white-space: nowrap; width:30px;">
			{if $apfra_rights[{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}]["ins"] == 1}
				<a role="button" class="btn btn-sm btn-default" href="#" data-info="" data-href="{$url}index.php?mod={$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}&a=edit&id=0&refid_{$module}={$id}&backmod={$module}&backid={$id}&backt={$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}" data-toggle="modal" data-target="#Modal-add"><span class="glyphicon glyphicon-plus"></span></a>
			{/if}
			</th>
		</tr>
		</thead>

		<tbody>
	{section name=x loop=$dataref1nsub_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}}
		<tr id="trow_{$datasql_edit_fields[ti].tab}_{$smarty.section.x.index}">
			<td>
				<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}&p={$page}&s={$search}&a={$action}&id={$dataref1nsub_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}[x].id}&backmod={$module}&backid={$id}&backt={$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}"><span class="glyphicon glyphicon-pencil"></span></a>
			</td>
		{section name=y loop=$datasql_edit_fields[ti].row[i].col[j].ref_value}
			<td>{$dataref1nsub_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}[x].{$datasql_edit_fields[ti].row[i].col[j].ref_value[y]}}</td>
		{/section}
			<td>
			{if $apfra_rights[{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}]["del"] == 1}
				<a role="button" class="btn btn-sm btn-danger" href="#" data-row="trow_{$datasql_edit_fields[ti].tab}_{$smarty.section.x.index}" data-info="{$dataref1nsub_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}[x].{$datasql_edit_fields[ti].row[i].col[j].ref_value[0]}}" data-href="{$url}index.php?mod={$module}&p={$page}&s={$search}&a={$action}&id={$id}&t={$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}&fa=del_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}&fr={$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}&did={$dataref1nsub_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}[x].id}" data-toggle="modal" data-target="#Modal-delete"><span class="glyphicon glyphicon-trash"></span></a>
			{else}
				&nbsp;
			{/if}
			</td>
		</tr>
	{/section}
		</tbody>

	{if $dataref1nsub_totals}
		<tfoot>
			<tr>
				<td>&Sigma;</td>
			{section name=y loop=$datasql_edit_fields[ti].row[i].col[j].ref_value}
				<td>{$dataref1nsub_total_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}.{$datasql_edit_fields[ti].row[i].col[j].ref_value[y]}}</td>
			{/section}
				<td>&nbsp;</td>
			</tr>
		</tfoot>
	{/if}

		</table>
	</div>
</div>

{/strip}
<script type="text/javascript">
<!--

if (dt_{$datasql_edit_fields[ti].row[i].col[j].field} == undefined) {

	var dt_{$datasql_edit_fields[ti].row[i].col[j].field} = $('#t_{$datasql_edit_fields[ti].row[i].col[j].field}').DataTable({
			"responsive": true,
			"columnDefs": [
				{ orderable: false, targets: [0,-1] }
			],
			"order" : {$dataref1nsub_{$datasql_edit_fields[ti].row[i].col[j].field|replace:'ref1n_':''}_dtorder},
	        "language": {
			  		"sEmptyTable":   	"Keine Daten in der Tabelle vorhanden",
			  		"sInfo":         	"_START_ bis _END_ von _TOTAL_ Einträgen",
			  		"sInfoEmpty":    	"0 bis 0 von 0 Einträgen",
			  		"sInfoFiltered": 	"(gefiltert von _MAX_ Einträgen)",
			  		"sInfoPostFix":  	"",
			  		"sInfoThousands":  	".",
			  		"sLengthMenu":   	"_MENU_ Einträge anzeigen",
			  		"sLoadingRecords": 	"Wird geladen...",
			  		"sProcessing":   	"Bitte warten...",
			  		"sSearch":       	"Suchen",
			  		"sZeroRecords":  	"Keine Einträge vorhanden.",
			  		"oPaginate": {
			  			"sFirst":    	"Erste",
			  			"sPrevious": 	"Zurück",
			  			"sNext":     	"Nächste",
			  			"sLast":     	"Letzte"
			  		},
			  		"oAria": {
			  			"sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
			  			"sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
			  		}
			  	}
	});

// Mozilla fix, first load dataTable autoWith fail
	$('#tab_{$datasql_edit_fields[ti].tab}_{$smarty.section.ti.index}').on('shown.bs.tab', function (e) {
	   $(window).trigger("resize");
	});
}

//-->
</script>
{strip}
{/strip}
