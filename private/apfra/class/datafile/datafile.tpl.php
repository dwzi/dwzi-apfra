{strip}

{if $action == ''}

{include file="datafile/datafile_main.tpl.php"}

{elseif $action == 'edit' || $action == 'delete'}

	{include file="datafile/datafile_a_`$action`.tpl.php"}	

{/if}

{/strip}
