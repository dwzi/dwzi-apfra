{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">

		{$tmp_edit_fields = $data.{$datasql_edit_fields[ti].row[i].col[j].field}}

		<ul class="nav nav-tabs">
		{section name=it loop=$tmp_edit_fields}
			<li id="ef_tab_{$tmp_edit_fields[it].tab}_{$smarty.section.it.index}"{if $smarty.section.it.index == 0} class="active"{/if}><a href="#{$tmp_edit_fields[it].tab}_{$smarty.section.it.index}" data-toggle="tab">{$tmp_edit_fields[it].desc}</a></li>
		{/section}
  	</ul>

		<div class="panel panel-default">
			<div class="panel-body">
				<div class="tab-content">

	{section name=it loop=$tmp_edit_fields}

					<div class="tab-pane{if $smarty.section.it.index == 0} active{/if}" id="{$tmp_edit_fields[it].tab}_{$smarty.section.it.index}">

		{section name=ir loop=$tmp_edit_fields[it].row}

					{if $tmp_edit_fields[it].row[ir].desc != ""}
						<div class="page-header">
							{$tmp_edit_fields[it].row[ir].desc}
						</div>
					{/if}

						<div class="row">

			{section name=ic loop=$tmp_edit_fields[it].row[ir].col}

							<div class="col-md-{12/$tmp_edit_fields[it].row[ir].col|@count}" style="border: 1px solid black;">

				{if $tmp_edit_fields[it].row[ir].col[ic].field != ""}
					{$tmp_edit_fields[it].row[ir].col[ic].field} ({$tmp_edit_fields[it].row[ir].col[ic].type})
				{else}
					-
				{/if}

							</div>

			{/section}

						</div>

		{/section}

					</div>

	{/section}

				</div>
			</div>
		</div>

	</div>
</div>

{/strip}
