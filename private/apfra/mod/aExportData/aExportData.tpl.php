{strip}

<form id="form" role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

	<input type="hidden" id="fa" name="fa" value="">
	<input type="hidden" id="step" name="step" value="{$step}">	

	{if $step != 1}<input type="hidden" id="f_table" name="f_table" value="{$f_table}">{/if}	
	{if $step != 2}<input type="hidden" id="f_fields" name="f_fields" value="{$f_fields}">{/if}	
	{if $step != 3}<input type="hidden" id="f_filter" name="f_filter" value="{$f_filter}">{/if}
	{if $step != 4}<input type="hidden" id="f_order" name="f_order" value="{$f_order}">{/if}
	
	<div class="stepwizard">
		<div class="stepwizard-row">
		{section name=i loop=$steps}
			<div class="stepwizard-step" style="width: {round(100/$steps|@count)}%;">
				<button type="submit" id="btn_{$steps[i].step}" class="btn {if $steps[i].step == $step}btn-primary{else}btn-{if $steps[i].ok}success{else}default{/if}{/if}"{if $steps[i].step == 99} disabled="disabled"{/if} onClick="$('#fa').attr('value', 'direct'); $('#step').attr('value', '{$steps[i].step}');">{$steps[i].step}</button>
				<p>{$steps[i].desc}</p>
			</div>
		{/section}
		</div>
	</div>	

{if $step == 1}

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4>Bitte w&auml;hlen Sie eine Tabelle aus</h4>
		</div>
	</div>
	
	<div class="funkyradio">
	{section name=i loop=$data}
		<div class="funkyradio-default col-md-offset-2 col-md-8">
			<input class="form-control" type="radio" name="f_table" id="f_table_{$data[i].id}" value="{$data[i].aTable}" {if $data[i].aTable == $f_table}checked="checked" {/if} onChange="$('#f_fields').attr('value', ''); $('#f_filter').attr('value', ''); $('#f_order').attr('value', ''); $('#btn_2').removeClass('btn-success');$('#btn_2').addClass('btn-default'); $('#btn_3').removeClass('btn-success');$('#btn_3').addClass('btn-default'); $('#btn_4').removeClass('btn-success');$('#btn_4').addClass('btn-default');" />
			<label for="f_table_{$data[i].id}">{$data[i].aTable} ({$data[i].aTableDesc})</label>
		</div>			
	{/section}
	</div> 
	
{elseif $step == 2}

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4>Bitte ziehen Sie die gew&uuml;nschten Felder nach rechts</h4>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-4 col-md-offset-2">
			<input type="hidden" name="f_fields" id="f_fields" value="{$f_fields}">
			<ol id="fs_fields" class="sortable">
			{section name=i loop=$data}
				<li data-value="{$data[i].aField}"><span class="glyphicon glyphicon-move text-success" aria-hidden="true"></span> {$data[i].aField} ({$data[i].aFieldDesc})<span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span><span style="display: none;" class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></li>
			{/section}
			</ol>
		</div>
		<div class="col-md-4">
			<ol id="fs_fields2" class="sortable">
			{section name=i loop=$data2}
				<li data-value="{$data2[i].aField}"><span class="glyphicon glyphicon-move text-success" aria-hidden="true"></span> {$data2[i].aField} ({$data2[i].aFieldDesc})<span style="display: none;" class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></li>
			{/section}
			</ol>
		</div>
	</div>

{/strip}
<script type="text/javascript">
<!--

$(".glyphicon-chevron-right").click(function() {
	$(this).hide();
	$(this).next().show();
	$(this).parent().detach().appendTo($('#fs_fields2'));
	$('#f_fields').val($("#fs_fields2").serializelist());
});

$(".glyphicon-remove").click(function() {
	$(this).hide();
	$(this).prev().show();
	$(this).parent().detach().appendTo($('#fs_fields'));
	$('#f_fields').val($("#fs_fields2").serializelist());
});

$("ol.sortable").sortable({
	group: 'fs_fields',
	pullPlaceholder: true,
	
	onDrop: function ($item, container, _super, event) {
		$('#f_fields').val($("#fs_fields2").serializelist());
		_super($item, container);
	}
});
	
//-->
</script>

{strip}
	
{elseif $step == 4}

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4>Bitte ziehen Sie die gew&uuml;nschten Felder f&uuml;r die Sortierung nach rechts</h4>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-4 col-md-offset-2">
			<input type="hidden" name="f_order" id="f_order" value="{$f_order}">
			<ol id="fs_order" class="sortable">
			{section name=i loop=$data}
				<li data-value="{$data[i].aField}"><span class="glyphicon glyphicon-move text-success" aria-hidden="true"></span> {$data[i].aField} ({$data[i].aFieldDesc})<span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span><span style="display: none;" class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></li>
			{/section}
			</ol>
		</div>
		<div class="col-md-4">
			<ol id="fs_order2" class="sortable">
			{section name=i loop=$data2}
				<li data-value="{$data2[i].aField}"><span style="display: none;" class="glyphicon glyphicon-move text-success" aria-hidden="true"></span> {$data2[i].aField} ({$data2[i].aFieldDesc})<span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></li>
			{/section}
			</ol>
		</div>
	</div>

{/strip}
<script type="text/javascript">
<!--


$(".glyphicon-chevron-right").click(function() {
	$(this).hide();
	$(this).next().show();
	$(this).parent().detach().appendTo($('#fs_order2'));
	$('#f_order').val($("#fs_order2").serializelist());
});

$(".glyphicon-remove").click(function() {
	$(this).hide();
	$(this).prev().show();
	$(this).parent().detach().appendTo($('#fs_order'));
	$('#f_order').val($("#fs_order2").serializelist());
});

$("ol.sortable").sortable({
	group: 'fs_order',
	pullPlaceholder: true,
	
	onDrop: function ($item, container, _super, event) {
		$('#f_order').val($("#fs_order2").serializelist());
		_super($item, container);
	}
});
	
//-->
</script>

{strip}
	
{elseif $step == 5}

	<div class="row">
		<div class="col-md-12">
			<h4>Vorschau</h4>
		</div>
	</div>

	
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
		{section name=i loop=$data_header}
			<th>{$data_header[i]}</th>
		{/section}
		</tr>
		</thead>
		<tbody>		
	{section name=i loop=$data}
		<tr>
		{foreach $data[i] as $key => $value}
			<td>{$value}</td>
		{/foreach}
		</tr>
	{/section}
		</tbody>	
	</table>

{/if}

	<div class="row"><div class="col-md-12"><p>&nbsp;</p></div></div>	

	<div class="row">
		<div class="col-md-2 col-md-offset-4 text-center">
		{if $step > 1}
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'prev'); $('#step').attr('value', '{$step-1}');">&laquo; zur&uuml;ck</button>
		{/if}
		</div>
		<div class="col-md-2 text-center">
		{if $step < $steps|@count}	
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'next'); $('#step').attr('value', '{$step+1}');">weiter &raquo;</button>
		{elseif $step == $steps|@count}
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'export');">Export</button>
		{/if}
		</div>
	</div>
	
</form>

{/strip}
<script type="text/javascript">
<!--

$('#form').validator();

function setcheck_all(elem, group) {

	$("input[name^='f_"+group+"_']").prop('checked', elem.checked);
}
//-->
</script>

{strip}
{/strip}
