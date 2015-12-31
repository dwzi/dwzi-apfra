{strip}

{if $action == ''}

	{include file="`$module`/`$module`_main.tpl.php"}	

{elseif $action == 'edit' || $action == 'delete'}

	{include file="`$module`/`$module`_a_`$action`.tpl.php"}	

{/if}

{/strip}
