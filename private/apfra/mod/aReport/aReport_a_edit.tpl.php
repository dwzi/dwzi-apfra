{strip}

	<div class="modal fade" id="Modal-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Eintrag hinzuf&uuml;gen</h4>
		  </div>
		  <div class="modal-body">
			Wollen Sie einen Eintrag hinzuf&uuml;gen?<br>
		  	<br>
			<p id="modal-add-info"></p>
		  </div>
		  <div class="modal-footer">
			<a id="modal-add-btnsubmit" class="btn btn-success">Hinzuf&uuml;gen</a>
			<button id="modal-add-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="Modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Eintrag l&ouml;schen?</h4>
		  </div>
		  <div class="modal-body">
			Sind Sie sicher, dass Sie diesen Eintrag l&ouml;schen wollen?<br>
			<br>
			<p id="modal-del-info"></p>
		  </div>
		  <div class="modal-footer">
			<a id="modal-del-btnsubmit" class="btn btn-danger">L&ouml;schen</a>
			<button id="modal-del-btncancel" type="button" class="btn btn-default" data-dismiss="modal">Abbruch</button>
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

{/strip}
<script type="text/javascript">
<!--
$('#Modal-add').on('show.bs.modal', function(e) {
    $('#modal-add-btnsubmit').attr('href', $(e.relatedTarget).data('href'));
    $('#modal-add-info').html($(e.relatedTarget).data('info'));
});

$('#Modal-delete').on('show.bs.modal', function(e) {
    $('#modal-del-btnsubmit').attr('href', $(e.relatedTarget).data('href'));
    $('#modal-del-info').html($(e.relatedTarget).data('info'));
    $('#'+$(e.relatedTarget).data('row')).addClass('danger');
    $('#modal-del-btncancel').click(function() {
    	$('#'+$(e.relatedTarget).data('row')).removeClass('danger');
    });    
});
//-->
</script>
{strip}

<fieldset>
	<legend>
	{section name=i loop=$datasql_edit_field_legend}
		{$data.{$datasql_edit_field_legend[i]}}
		{if $smarty.section.i.index<$smarty.section.i.max-1}, {/if}
	{/section}
	{if $data.ref_benutzer != "" && $data.aLastUpdate != ""}
		<small class="pull-right">
			zuletzt bearbeitet von {$data.ref_benutzer} am {$data.aLastUpdate|date_format:'%d.%m.%Y %H:%M:%S'}
		</small>
	{/if}
	</legend>

	<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}&p={$page}&s={$search}&a={$action}&id={$id}&backmod={$backmod}&backid={$backid}&backt={$backt}" method="post">

		<input type="hidden" id="fa" name="fa" value="save">
		<input type="hidden" id="t" name="t" value="{$tab}">

	{if $datasql_edit_fields|@count > 1}
		<ul class="nav nav-tabs">
		{section name=ti loop=$datasql_edit_fields}
			{if $tab == ""}{assign var="tab" value="{$datasql_edit_fields[ti].tab}"}{/if}
			<li{if $datasql_edit_fields[ti].tab == $tab} class="active"{/if}><a href="#{$datasql_edit_fields[ti].tab}" data-toggle="tab" onClick="$('#t').attr('value', '{$datasql_edit_fields[ti].tab}');">{$datasql_edit_fields[ti].desc}</a></li>
		{/section}
	  	</ul>
  	{/if}

  	{if $datasql_edit_fields|@count > 1}
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="tab-content">
	{/if}
				
				{section name=ti loop=$datasql_edit_fields}
  	
  			{if $datasql_edit_fields|@count > 1}
				<div class="tab-pane{if $datasql_edit_fields[ti].tab == $tab} active{/if}" id="{$datasql_edit_fields[ti].tab}">
			{/if}

					{section name=i loop=$datasql_edit_fields[ti].row}

					{if $datasql_edit_fields[ti].row[i].desc != ""}
					<div class="page-header">
						{$datasql_edit_fields[ti].row[i].desc}
					</div>
					{/if}

					<div class="row">

						{section name=j loop=$datasql_edit_fields[ti].row[i].col}

						<div class="col-md-{12/$datasql_edit_fields[ti].row[i].col|@count}">
						
						{if $datasql_edit_fields[ti].row[i].col[j].type == 'text'}
	
							<div class="form-group">
								<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{$datasql_edit_fields[ti].row[i].col[j].desc}</label>
								<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
								{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> "" || $datasql_edit_fields[ti].row[i].col[j].field|truncate:3:"" == "www" || $datasql_edit_fields[ti].row[i].col[j].field|truncate:5:"" == "email"}
									<div class="input-group">
								{/if}
									<input type="text" class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{$datasql_edit_fields[ti].row[i].col[j].desc}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}"/>
									{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> ""}
										<span class="input-group-addon">
											<a href="{$datasql_edit_fields[ti].row[i].col[j].link|replace:'#field#':{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}}" target="{$datasql_edit_fields[ti].row[i].col[j].field}_{$id}"><span class="glyphicon glyphicon-file"></span></a>
										</span>
									{elseif $datasql_edit_fields[ti].row[i].col[j].field|truncate:3:"" == "www"}
										<span class="input-group-addon">
											<a href="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" target="{$datasql_edit_fields[ti].row[i].col[j].field}_{$id}"><span class="glyphicon glyphicon-globe"></span></a>
										</span>
									{elseif $datasql_edit_fields[ti].row[i].col[j].field|truncate:5:"" == "email"}
										<span class="input-group-addon">
											<a href="mailto:{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}"><span class="glyphicon glyphicon-envelope"></span></a>
										</span>
									{/if}
								{if $datasql_edit_fields[ti].row[i].col[j].link|default:"" <> "" || $datasql_edit_fields[ti].row[i].col[j].field|truncate:3:"" == "www" || $datasql_edit_fields[ti].row[i].col[j].field|truncate:5:"" == "email"}
									</div>
								{/if}
								</div>
							</div>

						{elseif $datasql_edit_fields[ti].row[i].col[j].type == 'fields'}
					
							<div class="form-group">
								<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{$datasql_edit_fields[ti].row[i].col[j].desc}</label>
								<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
									<select class="form-control select2" style="width: 100%;" id="fl_{$datasql_edit_fields[ti].row[i].col[j].field}" name="fl_{$datasql_edit_fields[ti].row[i].col[j].field}">
										<option value="">--- Auswahl ---</option>
									{section name=si loop=$datareport_def}
										<optgroup label="{$apfra_db_desc[{$datareport_def[si].table}]} ({$datareport_def[si].table})">
										{section name=soi loop=$datareport_def[si].fields}
											{assign var="tmpdesc" value="{$datareport_def[si].table}.{$datareport_def[si].fields[soi].field}"}
											<option value="{$datareport_def[si].table}.{$datareport_def[si].fields[soi].field}">{$apfra_db_desc[{$tmpdesc}]} ({$datareport_def[si].table}.{$datareport_def[si].fields[soi].field})</option>
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
									<li id="sl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$tmpfl[st]|replace:'.':'_'}" data-value="{$tmpfl[st]}"><span class="glyphicon glyphicon-remove" onClick="$('#sl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$tmpfl[st]|replace:'.':"_"}').remove(); $('#f_{$datasql_edit_fields[ti].row[i].col[j].field}').val($('#sl_{$datasql_edit_fields[ti].row[i].col[j].field}').serializelist());"></span> {$apfra_db_desc[$tmpdesc]} ({$tmpdesc})</li>
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
					
						{elseif $datasql_edit_fields[ti].row[i].col[j].type == 'filter'}
					
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{$datasql_edit_fields[ti].row[i].col[j].desc}</label>
									<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
										<input type="hidden" id="sfc_{$datasql_edit_fields[ti].row[i].col[j].field}" name="sfc_{$datasql_edit_fields[ti].row[i].col[j].field}" value="{$data_filter|@count}">
										<select class="form-control select2" style="width: 100%;" id="fl_{$datasql_edit_fields[ti].row[i].col[j].field}" name="fl_{$datasql_edit_fields[ti].row[i].col[j].field}">
											<option value="">--- Auswahl ---</option>
										{section name=si loop=$datareport_def}
											<optgroup label="{$apfra_db_desc[{$datareport_def[si].table}]} ({$datareport_def[si].table})">
											{section name=soi loop=$datareport_def[si].fields}
												{assign var="tmpdesc" value="{$datareport_def[si].table}.{$datareport_def[si].fields[soi].field}"}
												<option value="{$datareport_def[si].table}.{$datareport_def[si].fields[soi].field}">{$apfra_db_desc[{$tmpdesc}]} ({$datareport_def[si].table}.{$datareport_def[si].fields[soi].field})</option>
											{/section}
											</optgroup>
										{/section}
										</select>
									</div>
								</div>
							</div>
							
							<div id="sf_{$datasql_edit_fields[ti].row[i].col[j].field}">

							{foreach $data_filter as $dfnr => $dfarr}

								<div id="sf_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}">
									<div class="col-md-6">
										<div class="form-group">
											<div class="col-md-1 col-md-offset-1">
												<div class="btn btn-default"><span class="glyphicon glyphicon-remove" onClick="$('#sf_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}').remove(); $('#sfc_{$datasql_edit_fields[ti].row[i].col[j].field}').val(parseInt($('#sfc_{$datasql_edit_fields[ti].row[i].col[j].field}').val())-1);"></span></div>
											</div>
											<div class="col-md-2">
											{if $dfnr > 1}
												<select class="form-control select2" style="width: 100%;" id="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_junc" name="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_junc">
													<option value="and"{if $dfarr.junc == "and"} selected{/if}>und</option>
													<option value="or"{if $dfarr.junc == "or"} selected{/if}>oder</option>
												</select>
											{else}
												&nbsp;
											{/if}
											</div>
											<div class="col-md-8">
												<input type="text" class="form-control" value="{$dfarr.col}" id="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_col" name="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_col" />
			 								</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<div class="col-md-12">
												<select class="form-control select2" style="width: 100%;" id="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_op" name="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_op">
													<option value="="{if $dfarr.op == "="} selected{/if}>=</option>
													<option value="!="{if $dfarr.op == "!="} selected{/if}>!=</option>
													<option value=">"{if $dfarr.op == ">" || $dfarr.op == "&gt;"} selected{/if}>&gt;</option>
													<option value=">="{if $dfarr.op == ">=" || $dfarr.op == "&gt;="} selected{/if}>&gt;=</option>
													<option value="<"{if $dfarr.op == "<" || $dfarr.op == "&lt;"} selected{/if}>&lt;</option>
													<option value="<="{if $dfarr.op == "<=" || $dfarr.op == "&lt;="} selected{/if}><=</option>
													<option value="like"{if $dfarr.op == "like"} selected{/if}>like</option>
													<option value="not like"{if $dfarr.op == "not like"} selected{/if}>not like</option>
												</select>
			 								</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="col-md-12">
												<input type="text" class="form-control" value="{$dfarr.val}" id="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_val" name="sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_val" />
			 								</div>
										</div>
									</div>
								</div>
							{/foreach} 
 						</div>						
								
{/strip}
<script type="text/javascript">
<!--
$(function () {

{foreach $data_filter as $dfnr => $dfarr}
	$('#sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_op').select2({ minimumResultsForSearch: Infinity });
	$('#sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_{$dfnr}_junc').select2({ minimumResultsForSearch: Infinity });
{/foreach} 

  	$('#fl_{$datasql_edit_fields[ti].row[i].col[j].field}').select2();
  	$('#fl_{$datasql_edit_fields[ti].row[i].col[j].field}').on("select2:select", function (e) {
  	  	var sfc = parseInt($('#sfc_{$datasql_edit_fields[ti].row[i].col[j].field}').val())+1;
        var listEl = $("<div id=\"sf_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"\">"+
				"<div class=\"col-md-6\">"+
					"<div class=\"form-group\">"+
						"<div class=\"col-md-1 col-md-offset-1\">"+
							"<span class=\"btn btn-default glyphicon glyphicon-remove\" onClick=\"$('#sf_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"').remove(); $('#sfc_{$datasql_edit_fields[ti].row[i].col[j].field}').val(parseInt($('#sfc_{$datasql_edit_fields[ti].row[i].col[j].field}').val())-1);\"></span>"+
						"</div>"+
						"<div class=\"col-md-2\">"+
							(sfc > 1 ? "<select class=\"form-control select2\" style=\"width: 100%;\" id=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_junc\" name=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_junc\">"+
								"<option value=\"and\">und</option>"+
								"<option value=\"or\">oder</option>"+
							"</select>" : "&nbsp;")+
						"</div>"+
						"<div class=\"col-md-8\">"+
							"<input type=\"text\" class=\"form-control\" value=\""+e.params.data.text.substr(e.params.data.text.indexOf('(')+1,e.params.data.text.indexOf(')')-e.params.data.text.indexOf('(')-1)+"\" id=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_col\" name=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_col\" />"+
							"</div>"+
					"</div>"+
				"</div>"+
				"<div class=\"col-md-2\">"+
					"<div class=\"form-group\">"+
						"<div class=\"col-md-12\">"+
							"<select class=\"form-control select2\" style=\"width: 100%;\" id=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_op\" name=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_op\">"+
								"<option value=\"=\">=</option>"+
								"<option value=\"!=\">!=</option>"+
								"<option value=\">\">&gt;</option>"+
								"<option value=\">=\">&gt;=</option>"+
								"<option value=\"<\">&lt;</option>"+
								"<option value=\"<=\"><=</option>"+
								"<option value=\"like\">like</option>"+
								"<option value=\"not like\">not like</option>"+
							"</select>"+
							"</div>"+
					"</div>"+
				"</div>"+
				"<div class=\"col-md-4\">"+
					"<div class=\"form-group\">"+
						"<div class=\"col-md-12\">"+
							"<input type=\"text\" class=\"form-control\" value=\"\" id=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_val\" name=\"sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_"+sfc+"_val\" />"+
						"</div>"+
					"</div>"+
				"</div>"+
			"</div>");
        $("#sf_{$datasql_edit_fields[ti].row[i].col[j].field}").append(listEl);
        $('#sfc_{$datasql_edit_fields[ti].row[i].col[j].field}').val(sfc);
		$('#sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_'+sfc+'_op').select2({ minimumResultsForSearch: Infinity });
		$('#sfl_{$datasql_edit_fields[ti].row[i].col[j].field}_'+sfc+'_junc').select2({ minimumResultsForSearch: Infinity });
	});
});

//-->
</script>
{strip}					
						{elseif $datasql_edit_fields[ti].row[i].col[j].type == 'password'}
					
							<div class="form-group">
								<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{$datasql_edit_fields[ti].row[i].col[j].desc}</label>
								<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
									<input type="password" class="form-control" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" placeholder="{$datasql_edit_fields[ti].row[i].col[j].desc}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}"/>
								</div>
							</div>
					
						{elseif $datasql_edit_fields[ti].row[i].col[j].type == 'checkbox'}
					
							<div class="form-group">
								<label class="control-label col-md-{2*$datasql_edit_fields[ti].row[i].col|@count}" for="f_{$datasql_edit_fields[ti].row[i].col[j].field}">{$datasql_edit_fields[ti].row[i].col[j].desc}</label>
								<div class="col-md-{12-(2*$datasql_edit_fields[ti].row[i].col|@count)}">
									<input type="checkbox" id="f_{$datasql_edit_fields[ti].row[i].col[j].field}" name="f_{$datasql_edit_fields[ti].row[i].col[j].field}" value="{$data.{$datasql_edit_fields[ti].row[i].col[j].field}}" {if $data.{$datasql_edit_fields[ti].row[i].col[j].field} == 1}checked{/if}/>
								</div>
							</div>
					
						{/if}

						</div>
							
						{/section}

					</div>

					{/section}
											
  			{if $datasql_edit_fields|@count > 1}
				</div>
				{/if}

				{/section}

  	{if $datasql_edit_fields|@count > 1}
				</div>
			</div>
		</div>
	{/if}
		<div class="form-group">
			<div class="col-md-offset-2 col-md-4">
				<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'save');">Speichern</button>
				&nbsp;
				<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'saveback');">Speichern &amp; zur&uuml;ck</button>
				&nbsp;
			{if $backmod && $backid}
				<a class="btn btn-default" href="{$url}index.php?mod={$backmod}&a=edit&id={$backid}&t={$backt}">Zur&uuml;ck</a>
			{else}
				<a class="btn btn-default" href="{$url}index.php?mod={$module}&p={$page}&s={$search}">Zur&uuml;ck</a>
			{/if}
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
