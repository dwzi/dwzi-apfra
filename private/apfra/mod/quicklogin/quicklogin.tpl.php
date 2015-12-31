{strip}


<fieldset>
	<legend>Anmelden</legend>

	<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

		<input type="hidden" name="login" value="true" />

		<div class="form-group">
			<label class="col-md-2 control-label" for="f_aUser">Benutzer</label>
			<div class="col-md-4">
				<select class="combobox form-control" class="form-control" id="f_aUser" name="f_aUser" data-error="Bitte füllen Sie dieses Feld aus." autofocus="autofocus">
					<option></option>
				{section name=x loop=$data_user}
					<option value="{$data_user[x].aUser}">{$data_user[x].nachname}, {$data_user[x].vorname} ({$data_user[x].aUser})</option>
				{/section}
				</select>
			</div>
		</div>

			<div class="form-group">
				<label class="col-md-2 control-label" for="f_kennwort">Kennwort</label>
				<div class="col-md-4">
					<input type="password" class="form-control" id="f_kennwort" name="f_kennwort" placeholder="Kennwort" />
				</div>
			</div>

		<div class="form-group">
			<div class="col-md-offset-2 col-md-4">
				<button type="submit" class="btn btn-success">Anmelden</button>
				&nbsp;
				<a class="btn btn-default" href="{$url}index.php?mod={$module}">Abbruch</a>
			</div>
		</div>

</form>

</fieldset>
{if $errors != 0}
	<div class="alert alert-danger">
		<h4>Fehler bei der Anmeldung!</h4>
		<p>Benutzername oder Kennwort falsch.</p>
	</div>
{/if}

{/strip}
<script type="text/javascript">
<!--
$('#f_aUser').combobox();
//-->
</script>
{strip}

{/strip}
