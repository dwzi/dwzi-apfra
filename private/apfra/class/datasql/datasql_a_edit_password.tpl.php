{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
		<input type="password" class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}" value="" {if $apfra_rights[$module]["upd"] == 0}readonly{/if}/>
	{if $apfra_rights[$module]["upd"] != 0}
		<div style="margin: 10px 0px;">
			<div class="btn btn-default"><span id="{$datasql_edit_fields[ti].row[i].col[j].field}_eye" class="glyphicon glyphicon-eye-open"></span></div>
			&nbsp;&middot;&nbsp;
			<span id="{$datasql_edit_fields[ti].row[i].col[j].field}_len" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 Zeichen lang
			&nbsp;&middot;&nbsp;
			<span id="{$datasql_edit_fields[ti].row[i].col[j].field}_ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Gro&szlig;buchstabe
			&nbsp;&middot;&nbsp;
			<span id="{$datasql_edit_fields[ti].row[i].col[j].field}_lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Kleinbuchstabe
			&nbsp;&middot;&nbsp;
			<span id="{$datasql_edit_fields[ti].row[i].col[j].field}_num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Zahl
		</div>
		<input type="password" class="form-control" id="fp_{$datasql_edit_fields[ti].row[i].col[j].field}" name="fp_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if} (Best&auml;tigung)" value="" />
		<div style="margin-top: 10px;">
			<span id="{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Kennworte stimmen &uuml;berein
		</div>		
	{/if}
	</div>
</div>
	
{/strip}
<script type="text/javascript">
<!--

$('#{$datasql_edit_fields[ti].row[i].col[j].field}_eye').click(function(){

	if ($('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').attr('type') == 'password') {
		$('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').attr('type', 'text');
		$('#fp_{$datasql_edit_fields[ti].row[i].col[j].field}').attr('type', 'text');
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_eye").removeClass("glyphicon-eye-open");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_eye").addClass("glyphicon-eye-close");
	} else {
		$('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').attr('type', 'password');
		$('#fp_{$datasql_edit_fields[ti].row[i].col[j].field}').attr('type', 'password');
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_eye").removeClass("glyphicon-eye-close");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_eye").addClass("glyphicon-eye-open");
	}
}); 

$('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').keyup(function(){
    var ucase = new RegExp("[A-Z]+");
	var lcase = new RegExp("[a-z]+");
	var num = new RegExp("[0-9]+");
	
	if($("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val().length >= 8){
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_len").removeClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_len").addClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_len").css("color","#00A41E");
	}else{
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_len").removeClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_len").addClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_len").css("color","#FF0004");
	}
	
	if(ucase.test($("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val())){
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_ucase").removeClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_ucase").addClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_ucase").css("color","#00A41E");
	}else{
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_ucase").removeClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_ucase").addClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_ucase").css("color","#FF0004");
	}
	
	if(lcase.test($("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val())){
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_lcase").removeClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_lcase").addClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_lcase").css("color","#00A41E");
	}else{
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_lcase").removeClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_lcase").addClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_lcase").css("color","#FF0004");
	}
	
	if(num.test($("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val())){
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_num").removeClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_num").addClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_num").css("color","#00A41E");
	}else{
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_num").removeClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_num").addClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_num").css("color","#FF0004");
	}
	
	if($("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val() == $("#fp_{$datasql_edit_fields[ti].row[i].col[j].field}").val() && $("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val() != ""){
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").removeClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").addClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").css("color","#00A41E");
	}else{
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").removeClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").addClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").css("color","#FF0004");
	}
});

$('#fp_{$datasql_edit_fields[ti].row[i].col[j].field}').keyup(function(){

	if($("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val() == $("#fp_{$datasql_edit_fields[ti].row[i].col[j].field}").val() && $("#f_{$datasql_edit_fields[ti].row[i].col[j].field}").val() != ""){
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").removeClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").addClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").css("color","#00A41E");
	}else{
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").removeClass("glyphicon-ok");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").addClass("glyphicon-remove");
		$("#{$datasql_edit_fields[ti].row[i].col[j].field}_pwmatch").css("color","#FF0004");
	}
});
//-->
</script>
{strip}

{/strip}
