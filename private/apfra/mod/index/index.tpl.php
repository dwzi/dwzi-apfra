{strip}

{if $logged_in}

	<form class="form-horizontal" role="form" action="{$url}index.php" method="get">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="input-group">
					<input type="text" class="form-control" id="s" name="s" value="{$search}" placeholder="Suchbegriff (gesamte Datenbank)" autofocus="autofocus">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
					{if $search != ""}
						<button type="submit" class="btn btn-danger" onClick="document.getElementById('s').value='';"><span class="glyphicon glyphicon-remove"></span></button>
					{/if}
					</span>
				</div>
			</div>
		</div>		
	</form>

	{if count($data)}
	<br/>
	{foreach $data as $table => $fieldarr}

	<div class="panel panel-info">

		<div class="panel-heading">{$table}</div>

		<div class="panel-body">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
					{section name=i loop=$fieldarr["desc"]}
						<th>{$fieldarr["desc"][i]}</th>
					{/section}
						<th width="90">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				{foreach $fieldarr as $id => $fields}
				{if $id != "desc"}
					<tr>
					{foreach $fieldarr[$id] as $field => $value}
						<td>
						{if $field|truncate:3:"" == "www"}
							<a href="{$value}" target="{$field}_{$id}">{$value}</a>
						{elseif $field|truncate:5:"" == "email"}
							<a href="mailto:{$value}">{$value}</a>
						{else}
							{$value}
						{/if}
						</td>
					{/foreach}
						<td>
							<a role="button" class="btn btn-sm btn-default" href="{$url}index.php?mod={$table}&a=edit&id={$id}"><span class="glyphicon glyphicon-pencil"></span></a>
						</td>
					</tr>
				{/if}
				{/foreach}
				</tbody>
			</table>
		</div>
	</div>

	{/foreach}
	
	{/if}
	
{else}

<div class="jumbotron">
	<p>Application Framework</p>
</div>

{/if}

{/strip}
