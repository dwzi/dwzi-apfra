{strip}

{assign var="tmpdesc" value="{$datasql_table}.{$datasql_edit_fields[ti].row[i].col[j].field}"}

<div class="form-group">
	<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{if isset($apfra_db_desc[$tmpdesc])}{$apfra_db_desc[$tmpdesc]}{else}{$tmpdesc}{/if}</label>
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
		<select class="form-control select2" style="width: 100%;" id="fl_{$datasql_edit_fields[ti].row[i].col[j].field}" name="fl_{$datasql_edit_fields[ti].row[i].col[j].field}">
			<option value="">--- Auswahl ---</option>
		{section name=si loop=$data_fixed_fields}
			<optgroup label="{$data_fixed_fields[si].desc} ({$data_fixed_fields[si].table})">
			{section name=soi loop=$data_fixed_fields[si].fields}
				{assign var="tmpdesc" value="{$data_fixed_fields[si].table}.{$data_fixed_fields[si].fields[soi].field}"}
				<option value="{$data_fixed_fields[si].table}.{$data_fixed_fields[si].fields[soi].field}">{$data_fixed_fields[si].fields[soi].desc} ({$data_fixed_fields[si].table}.{$data_fixed_fields[si].fields[soi].field})</option>
			{/section}
			</optgroup>
		{/section}
		</select>
	</div>
</div>
<div class="form-group">
	<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)} col-md-offset-{2*$datasql_edit_fields[ti].row[i].col|@count}">
		<input type=hidden class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" />
	{assign var="tmpf" value=$data.{$datasql_edit_fields[ti].row[i].col[j].field}}
	{assign var="tmpfl" value=","|explode:$tmpf}
		<ol class="sortable" id="sl_{$datasql_edit_fields[ti].row[i].col[j].field}">
	{if $tmpf != ""}
	{section name=st loop=$tmpfl}
		{assign var="tmpdesc" value="{$tmpfl[st]}"}
		<li id="sl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$tmpfl[st]|replace:'.':'_'}" data-value="{$tmpfl[st]}"><span class="glyphicon glyphicon-remove" onClick="$('#sl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$tmpfl[st]|replace:'.':"_"}').remove(); $('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').val($('#sl_{$datasql_edit_fields[ti].row[i].col[j].field}').serializelist());"></span>{$tmpdesc}</li>
	{/section} 
	{/if}
		</ol>
	</div>
</div>
							
{/strip}
<script type="text/javascript">
<!--
$(function () {

  	$("#sl_{$datasql_edit_fields[ti].row[i].col[j].field}").sortable({
  	  onDrop: function ($item, container, _super, event) {
  	    $('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').val($("#sl_{$datasql_edit_fields[ti].row[i].col[j].field}").serializelist());
  	    _super($item, container);
   	  },
  	});  

  	$('#fl_{$datasql_edit_fields[ti].row[i].col[j].field}').select2();
  	$('#fl_{$datasql_edit_fields[ti].row[i].col[j].field}').on("select2:select", function (e) {
        var listEl = $("<li id='sl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+e.params.data.id.replace('.','_')+"' data-value='"+e.params.data.id+"'><span class=\"glyphicon glyphicon-remove\" onClick=\"$('#sl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+e.params.data.id.replace('.','_')+"').remove(); $('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').val($('#sl_{$datasql_edit_fields[ti].row[i].col[j].field}').serializelist());\"></span> "+e.params.data.text+"</li>");
        listEl.hide();
        $("#sl_{$datasql_edit_fields[ti].row[i].col[j].field}").append(listEl);
        listEl.fadeIn(); 
  	    $('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').val($("#sl_{$datasql_edit_fields[ti].row[i].col[j].field}").serializelist());
	});
});

//-->
</script>
{strip}

{/strip}
