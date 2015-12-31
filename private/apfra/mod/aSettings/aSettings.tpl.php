{strip}

<fieldset>
	<legend>
	{$data.aUser}
	{if $data.ref_benutzer != "" && $data.aLastUpdate != ""}
		<small class="pull-right">
			zuletzt bearbeitet von {$data.ref_benutzer} am {$data.aLastUpdate|date_format:'%d.%m.%Y %H:%M:%S'}
		</small>
	{/if}
	</legend>

	<form role="form" data-toggle="validator" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

		<input type="hidden" id="fa" name="fa" value="save">

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label col-md-2" for="f_aUser">Benutzername</label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="f_aUser" name="f_aUser" placeholder="Benutzer" value="{$data.aUser}" readonly />
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label col-md-2" for="f_kennwort">Kennwort</label>
					<div class="col-md-10">
						<input type="password" class="form-control" id="f_kennwort" name="f_kennwort" placeholder="Kennwort" value=""/>
						<div style="margin: 10px 0px;">
							<div class="btn btn-default"><span id="kennwort_eye" class="glyphicon glyphicon-eye-open"></span></div>
							&nbsp;&middot;&nbsp;
							<span id="kennwort_len" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Zeichen lang
							&nbsp;&middot;&nbsp;
							<span id="kennwort_ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Gro&szlig;buchstabe
							&nbsp;&middot;&nbsp;
							<span id="kennwort_lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Kleinbuchstabe
							&nbsp;&middot;&nbsp;
							<span id="kennwort_num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Zahl
						</div>
						<input type="password" class="form-control" id="fp_kennwort" name="fp_kennwort" placeholder="Kennwort (Best&auml;tigung)" value="" />
						<div style="margin-top: 10px;">
							<span id="kennwort_pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Kennworte stimmen &uuml;berein
						</div>		
					</div>
				</div>
			</div>
		</div>

{/strip}
<script type="text/javascript">
<!--

$('#kennwort_eye').click(function(){

	if ($('#f_kennwort').attr('type') == 'password') {
		$('#f_kennwort').attr('type', 'text');
		$('#fp_kennwort').attr('type', 'text');
		$("#kennwort_eye").removeClass("glyphicon-eye-open");
		$("#kennwort_eye").addClass("glyphicon-eye-close");
	} else {
		$('#f_kennwort').attr('type', 'password');
		$('#fp_kennwort').attr('type', 'password');
		$("#kennwort_eye").removeClass("glyphicon-eye-close");
		$("#kennwort_eye").addClass("glyphicon-eye-open");
	}
}); 

$('#f_kennwort').keyup(function(){
    var ucase = new RegExp("[A-Z]+");
	var lcase = new RegExp("[a-z]+");
	var num = new RegExp("[0-9]+");
	
	if($("#f_kennwort").val().length >= 8){
		$("#kennwort_len").removeClass("glyphicon-remove");
		$("#kennwort_len").addClass("glyphicon-ok");
		$("#kennwort_len").css("color","#00A41E");
	}else{
		$("#kennwort_len").removeClass("glyphicon-ok");
		$("#kennwort_len").addClass("glyphicon-remove");
		$("#kennwort_len").css("color","#FF0004");
	}
	
	if(ucase.test($("#f_kennwort").val())){
		$("#kennwort_ucase").removeClass("glyphicon-remove");
		$("#kennwort_ucase").addClass("glyphicon-ok");
		$("#kennwort_ucase").css("color","#00A41E");
	}else{
		$("#kennwort_ucase").removeClass("glyphicon-ok");
		$("#kennwort_ucase").addClass("glyphicon-remove");
		$("#kennwort_ucase").css("color","#FF0004");
	}
	
	if(lcase.test($("#f_kennwort").val())){
		$("#kennwort_lcase").removeClass("glyphicon-remove");
		$("#kennwort_lcase").addClass("glyphicon-ok");
		$("#kennwort_lcase").css("color","#00A41E");
	}else{
		$("#kennwort_lcase").removeClass("glyphicon-ok");
		$("#kennwort_lcase").addClass("glyphicon-remove");
		$("#kennwort_lcase").css("color","#FF0004");
	}
	
	if(num.test($("#f_kennwort").val())){
		$("#kennwort_num").removeClass("glyphicon-remove");
		$("#kennwort_num").addClass("glyphicon-ok");
		$("#kennwort_num").css("color","#00A41E");
	}else{
		$("#kennwort_num").removeClass("glyphicon-ok");
		$("#kennwort_num").addClass("glyphicon-remove");
		$("#kennwort_num").css("color","#FF0004");
	}
	
	if($("#f_kennwort").val() == $("#fp_kennwort").val() && $("#f_kennwort").val() != ""){
		$("#kennwort_pwmatch").removeClass("glyphicon-remove");
		$("#kennwort_pwmatch").addClass("glyphicon-ok");
		$("#kennwort_pwmatch").css("color","#00A41E");
	}else{
		$("#kennwort_pwmatch").removeClass("glyphicon-ok");
		$("#kennwort_pwmatch").addClass("glyphicon-remove");
		$("#kennwort_pwmatch").css("color","#FF0004");
	}
});

$('#fp_kennwort').keyup(function(){

	if($("#f_kennwort").val() == $("#fp_kennwort").val() && $("#f_kennwort").val() != ""){
		$("#kennwort_pwmatch").removeClass("glyphicon-remove");
		$("#kennwort_pwmatch").addClass("glyphicon-ok");
		$("#kennwort_pwmatch").css("color","#00A41E");
	}else{
		$("#kennwort_pwmatch").removeClass("glyphicon-ok");
		$("#kennwort_pwmatch").addClass("glyphicon-remove");
		$("#kennwort_pwmatch").css("color","#FF0004");
	}
});
//-->
</script>
{strip}

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label col-md-2" for="f_titel">Titel</label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="f_titel" name="f_titel" placeholder="Titel" value="{$data.titel}"/>
					</div>
				</div>
			</div>
		</div>

			<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label class="control-label col-md-2" for="f_vorname">Vorname *</label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="f_vorname" name="f_vorname" placeholder="Vorname" value="{$data.vorname}" required="required"/>
						<div class="help-block with-errors"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label class="control-label col-md-2" for="f_nachname">Nachname *</label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="f_nachname" name="f_nachname" placeholder="Nachname" value="{$data.nachname}" required="required"/>
						<div class="help-block with-errors"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label class="control-label col-md-2" for="f_email">Email *</label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="f_email" name="f_email" placeholder="Email" value="{$data.email}" required="required"/>
						<div class="help-block with-errors"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label class="control-label col-md-2" for="f_refid_aTheme">Thema</label>
					<div class="col-md-10">
						<select class="combobox form-control" id="f_refid_aTheme" name="f_refid_aTheme" data-error="Bitte füllen Sie dieses Feld aus.">
							<option></option>
						{section name=x loop=$data_theme}
							<option value="{$data_theme[x].id}"{if $data_theme[x].id == $data.refid_aTheme} selected{/if}>{$data_theme[x].aTheme}</option>
						{/section}
						</select>
						<div class="help-block with-errors"></div>
					</div>
				</div>
			</div>
		</div>

{/strip}
<script type="text/javascript">
<!--
$('#f_refid_aTheme').combobox();
//-->
</script>
{strip}
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label col-md-2" for="f_letzterlogin">letzter Login</label>
					<div class="col-md-10">
						<input type="text" class="form-control" id="f_letzterlogin" name="f_letzterlogin" placeholder="letzter Login" value="{$data.letzterlogin|date_format:'%d.%m.%Y %H:%M:%S'}" readonly />
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-offset-2 col-md-4">
				<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'save');">Speichern</button>
				&nbsp;
			</div>
		</div>
		
	</form>

</fieldset>

{/strip}
