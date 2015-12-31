{strip}

{if $action == ''}

{include file="`$path`../private/apfra/class/datafile/datafile_main.tpl.php"}	

{elseif $action == 'edit' || $action == 'delete'}

	{include file="`$path`../private/apfra/class/datafile/datafile_a_`$action`.tpl.php"}	

{/if}

{/strip}
