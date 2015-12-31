{strip}


<fieldset>
	<legend>Anmelden</legend>

	<form role="form" class="form-horizontal formlogin" action="{$url}index.php?mod={$module}" method="post">

		<input type="hidden" name="login" value="true" />
	
		<div class="form-group">
			<label class="col-md-2 control-label" for="f_aUser">Benutzer</label>
			<div class="col-md-4">
				<input type="text" class="form-control" id="f_aUser" name="f_aUser" placeholder="Benutzername" autofocus="autofocus"/>
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

		