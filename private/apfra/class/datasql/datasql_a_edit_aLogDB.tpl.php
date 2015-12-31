{strip}

<div class="form-group">
	<div class="col-md-{12/($datasql_edit_fields[ti].row[i].col|@count)}">
		<table class="table table-striped">

		<thead>
		<tr>
			<th>Stamp</th>
			<th>Benutzer</th>
			<th>Aktion</th>
			<th>Daten</th>
		</tr>
		</thead>
		
		<tbody>
	{section name=x loop=$data_history}
		<tr>
			<td>{$data_history[x].stamp}</td>
			<td>{$data_history[x].aUser}</td>
			<td>{$data_history[x].action}</td>
			<td>
				<table class="table table-condensed">
			{foreach $data_history[x].afields as $key => $value}
				{assign var="tmpdesc" value="{$datasql_table}.{$key}"}
				<tr>
					<td>
					{if $key == "aLastUpdate"}
						Letztes Update
					{else}
						{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}
					{/if}
					</td>
					<td>{$value}</td>
				</tr>
			{/foreach}
				</table>
			</td>
		</tr>
	{/section}
		</tbody>

		</table>								
	</div>									
</div>

{/strip}
