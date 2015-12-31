{strip}

<fieldset>
	<legend>Abmelden</legend>

<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

	<input type="hidden" name="logout" value="true" />

	<h4>Willst Du das crm wirklich verlassen?</h4>
	<p>Ein korrektes Abmelden ist aus sicherheitstechnischen Gr&uuml;nden unbedingt erforderlich.</p>
	<p>
		<button type="submit" class="btn btn-danger">Abmelden</button>
		&nbsp;
		<a class="btn btn-default" href="{$url}index.php?mod=index">Abbruch</a>
	</p>

</form>

</fieldset>

{/strip}
