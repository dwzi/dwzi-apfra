{strip}

<div class="form-group">
<div class="col-md-6">

	<form id="form" role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

		<input type="hidden" id="fa" name="fa" value="save">

	<fieldset>
		<legend>Menü</legend>

		<ol id="sort_menu" class="sortable">
	{section name=i loop=$data}
			<li data-value="{if isset($data[i].module)}{$data_modid[$data[i].module]}{else}0{/if}">
				<input name="f_menu_0_previd_0_id_0" type="text" value="{$data[i].desc}" class="form-control" data-toggle="tooltip" data-placement="top" title="{if isset($data[i].module)}Modul: {$data[i].module}{/if}" />
				<ol>
			{if $menu[i].type == "submenu"}
				{section name=si loop=$data[i].submenu}
					{if $data[i].submenu[si].type == "divider"}
						<li data-value="0" class="divider">
							<input name="f_menu_0_previd_0_id_0" type="hidden" value="#divider" />
							--- Trennlinie ---
						</li>
					{else}
						<li data-value="{if isset($data[i].submenu[si].module)}{$data_modid[$data[i].submenu[si].module]}{else}0{/if}">
							<input name="f_menu_0_previd_0_id_0" type="text" value="{$data[i].submenu[si].desc}" class="form-control" data-toggle="tooltip" data-placement="top" title="{if isset({$data[i].submenu[si].module})}Modul: {$data[i].submenu[si].module}{/if}" />
							<ol></ol>
						</li>
					{/if}
				{/section}
			{/if}
				</ol>
			</li>
	{/section}
		</ol>

	</fieldset>

	<div class="form-group">
		<div class="col-md-6">
			<button class="btn btn-success" type="submit" onClick="$('#fa').attr('value', 'save');">speichern</button>
		</div>
	</div>

	</form>

</div>

<div class="col-md-6">

	<fieldset>
		<legend>verf&uuml;gbare Module / Zus&auml;tze</legend>

			<ol id="sort_mod" class="sortable">
			{section name=z loop=$data_mod}
				<li data-value="{$data_mod[z].id}"><input name="f_menu_0_previd_0_id_0" type="text" value="{$data_mod[z].desc}" class="form-control" data-toggle="tooltip" data-placement="top" title="{if isset({$data_mod[z].module})}Modul: {$data_mod[z].module}{/if}" /></li>
			{/section}
			</ol>

			<ol id="sort_add" class="sortable">
				<li data-value="0" class="divider">
					<input name="f_menu_0_previd_0_id_0" type="hidden" value="#divider" />
					--- Trennlinie ---
				</li>
			</ol>

			<input type="text" class="form-control" placeholder="neuer Text" onChange="$('#sort_add').append('<li data-value=\'0\'><input name=\'f_menu_0_previd_0_id_0\' type=\'text\' value=\''+$(this).val()+'\' class=\'form-control\' data-toggle=\'tooltip\' data-placement=\'top\' title=\'\' /><ol></ol></li>'); $(this).val(''); return false;">

	</fieldset>

</div>
</div>

{/strip}
<script type="text/javascript">
<!--

$('[data-toggle="tooltip"]').tooltip();

$.fn.sort_menu_reorder = function(id = 1) {
		var previd = id>0 ? id-1 : 0;
		this.each(function() {
				$(this).children().each(function(index, li){
					if ($(this)[0].childNodes[0].tagName == "INPUT") {
						$(this)[0].childNodes[0].name = "f_menu_"+$(this).attr("data-value")+"_previd_"+previd+"_id_"+id;
						id++;
					}
						$(this).children().each(function(){
								if(this.tagName == 'UL' || this.tagName == 'OL'){
										id = $(this).sort_menu_reorder(id);
								}
						});
				});
		});
		return id;
};

$("#sort_menu").sort_menu_reorder();

$("#sort_menu").sortable({
  group: 'nav',
  nested: true,
  vertical: true,
	pullPlaceholder: true,
	onDrop: function ($item, container, _super, event) {
		$("#sort_menu").sort_menu_reorder();
		_super($item, container);
	},
	onDragStart: function($item, container, _super) {
		if ($item.hasClass('divider') && container.el[0].id == "sort_add") {
			$("#sort_add").append("<li data-value=\"0\" class=\"divider\"><input name=\"f_menu_0_previd_0_id_0\" type=\"hidden\" value=\"#divider\" />--- Trennlinie ---</li>");
		}
    _super($item, container);
  }
});

$("#sort_menu ol.dropdown-menu").sortable({
  group: 'nav'
});

$("#sort_mod").sortable({
	group: 'nav',
	pullPlaceholder: true
});

$("#sort_add").sortable({
	group: 'nav',
	pullPlaceholder: true
});

//-->
</script>
{strip}

{/strip}
