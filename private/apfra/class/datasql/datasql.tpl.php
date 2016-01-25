{strip}

{if $action == ''}

{include file="datasql/datasql_main.tpl.php"}	

{elseif $action == 'edit' || $action == 'delete'}

	{include file="datasql/datasql_a_`$action`.tpl.php"}

{/if}

{/strip}
