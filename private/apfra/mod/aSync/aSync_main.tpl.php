{strip}

{section name=i loop=$data}
	
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th colspan="3" bgcolor="{if $data[i].checkTable == 0}#cccccc{else}#ffcccc{/if}">
					{$data[i].table}
				{if $data[i].checkTable == 1}
					&nbsp;(database) <a href="{$url}index.php?mod={$module}&sa=ca&dtable={$data[i].table}" class="btn btn-sm btn-danger">Erzeuge Tabelle!</a>  <a href="{$url}index.php?mod={$module}&sa=dd&dtable={$data[i].table}" class="btn btn-sm btn-danger">L&ouml;sche Tabelle!</a>
				 {elseif $data[i].checkTable == 2}
				 	&nbsp;- {$data[i].aTableDesc} (apfra) <a href="{$url}index.php?mod={$module}&sa=cd&dtable={$data[i].table}" class="btn btn-sm btn-danger">Erzeuge Tabelle!</a>
			 	{else}
			 		&nbsp;- {$data[i].aTableDesc}
			 	{/if}
				</th>
			</tr>
	{if $data[i].checkTable != 2}
			<tr>
				<th width="90">&nbsp;</th>
				<th width="40%">Field</th>
				<th>Type</th>
			</tr>
	{/if}
		</thead>
	{if $data[i].checkTable != 2}
		<tbody>
  		{foreach $data[i].fields as $key => $value}
			<tr>
			{if $value.checkField == 0 || $value.checkField == 4}
				<td>&nbsp;</td>
			{else}
				<td bgcolor="#ffcccc">!</td> 
			{/if}
				<td>
				{if $value.checkField != 4}
					{$value.field}
				{else}
					<i>{$value.field}</i>
				{/if}
				</td>
				<td>
				{if $value.checkField == 0}
					{$value.fieldType}
				{elseif $value.checkField == 1}
					{$value.fieldType} (database)
					&nbsp;<a href="{$url}index.php?mod={$module}&sa=ia&dtable={$data[i].table}&dfield={$value.field}&dftype={$value.fieldType}" class="btn btn-sm btn-success">apfra aktualisieren</a>
					&nbsp;<a href="{$url}index.php?mod={$module}&sa=dd&dtable={$data[i].table}&dfield={$value.field}" class="btn btn-sm btn-danger">Feld in Datenbank l&ouml;schen</a>
				{elseif $value.checkField == 2}
					{$value.fieldType} (apfra)
					&nbsp;<a href="{$url}index.php?mod={$module}&sa=id&dtable={$data[i].table}&dfield={$value.field}&dftype={$value.fieldType}" class="btn btn-sm btn-success">Datenbank aktualisieren</a>
					&nbsp;<a href="{$url}index.php?mod={$module}&sa=da&dtable={$data[i].table}&dfield={$value.field}" class="btn btn-sm btn-danger">Feld in apfra l&ouml;schen</a>
				{elseif $value.checkField == 3}
					<a href="{$url}index.php?mod={$module}&sa=ua&dtable={$data[i].table}&dfield={$value.field}&dftype={$value.dbFieldType}" class="btn btn-sm btn-danger">Das ist korrekt!</a> {$value.dbFieldType} (lt. Datenbank)<br>
					<a href="{$url}index.php?mod={$module}&sa=ud&dtable={$data[i].table}&dfield={$value.field}&dftype={$value.aFieldType}" class="btn btn-sm btn-danger">Das ist korrekt!</a> {$value.aFieldType} (lt. apfra)
				{elseif $value.checkField == 4}
					<i>{$value.fieldType}</i>
				{elseif $value.checkField == 5}
					{$value.fieldType} (missing in database)
					&nbsp;<a href="{$url}index.php?mod={$module}&sa=id&dtable={$data[i].table}&dfield={$value.field}&dftype={$value.fieldType}" class="btn btn-sm btn-success">Datenbank aktualisieren</a>
				{/if}
				</td>
			</tr>
  		{/foreach}
		</tbody>
	{/if}
	</table>

{/section}
</div>
	
{/strip}
