{strip}

{if $action == ''}

{include file="`$path`../private/apfra/class/datasql/datasql_main.tpl.php"}	

{elseif $action == 'edit' || $action == 'delete'}

	{include file="`$path`../private/apfra/class/datasql/datasql_a_`$action`.tpl.php"}	

{/if}

{/strip}
