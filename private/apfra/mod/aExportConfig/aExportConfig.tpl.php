{strip}

<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

	<input type="hidden" id="fa" name="fa" value="save">	
	
	<div class="collapsible">
		<div class="row">

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><input type="checkbox" onClick="setcheck_all(this, 'exp')" checked> Allgemeines</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
						<input type="checkbox" id="f_exp_data" name="f_exp_data" value="1" checked> inkl. Daten<br>
						<input type="checkbox" id="f_exp_history" name="f_exp_history" value="1" checked> inkl. historischen Daten<br>
						<input type="checkbox" id="f_exp_aReport" name="f_exp_aReport" value="1" checked> inkl. Berichte<br>
						<input type="checkbox" id="f_exp_aRight" name="f_exp_aRight" value="1" checked> inkl. Rechte<br>
						<input type="checkbox" id="f_exp_aRole" name="f_exp_aRole" value="1" checked> inkl. Rollen<br>
						<input type="checkbox" id="f_exp_aUser" name="f_exp_aUser" value="1" checked> inkl. Benutzer
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><input type="checkbox" onClick="setcheck_all(this, 'tab')" checked> Tabellen</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-hover">
							<tbody>
							{section name=i loop=$data_table}
								<tr> 
									<td><input type="checkbox" id="f_tab_{$data_table[i].id}" name="f_tab_{$data_table[i].id}" value="1" checked></td>
									<td>{$data_table[i].aTable} ({$data_table[i].aTableDesc})</td>
								</tr>
							{/section}
							</tbody>
						</table>					
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><input type="checkbox" onClick="setcheck_all(this, 'mod')" checked> Module</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-hover">
							<tbody>
							{section name=i loop=$data_mod}
								<tr> 
									<td><input type="checkbox" id="f_mod_{$data_mod[i].id}" name="f_mod_{$data_mod[i].id}" value="1" checked></td>
									<td>{$data_mod[i].aModule} ({$data_mod[i].aModuleDesc})</td>
								</tr>
							{/section}
							</tbody>
						</table>					
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><input type="checkbox" onClick="setcheck_all(this, 'men')" checked> Men&uuml;</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-hover">
							<tbody>
							{section name=i loop=$data_menu}
								<tr> 
									<td><input type="checkbox" id="f_men_{$data_menu[i].id}" name="f_men_{$data_menu[i].id}" value="1" checked></td>
									<td>{$data_menu[i].aMenu}{if $data_menu[i].aModule} ({$data_menu[i].aModule}){/if}</td>
								</tr>
							{section name=j loop=$data_menu[i].submenu}
								<tr> 
									<td><input type="checkbox" id="f_men_{$data_menu[i].submenu[j].id}" name="f_men_{$data_menu[i].submenu[j].id}" value="1" checked></td>
									<td>|-- {$data_menu[i].submenu[j].aMenu}{if $data_menu[i].submenu[j].aModule} ({$data_menu[i].submenu[j].aModule}){/if}</td>
								</tr>
							{/section}								
							{/section}
							</tbody>
						</table>					
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><input type="checkbox" onClick="setcheck_all(this, 'doc')" checked> Dokumente</h3>
						<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-hover">
							<tbody>
							{section name=i loop=$data_doc}
								<tr> 
									<td><input type="checkbox" id="f_doc_{$smarty.section.i.index}" name="f_doc_{$smarty.section.i.index}" value="{$data_doc[i]}" checked></td>
									<td>{$data_doc[i]}</td>
								</tr>
							{/section}
							</tbody>
						</table>					
					</div>
				</div>
			</div>			

		</div>	
	</div>
	
	<div class="form-group">
		<div class="col-md-8">
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'save');">Exportieren</button>
		</div>
	</div>
	
</form>

{/strip}
<script type="text/javascript">
<!--
function setcheck_all(elem, group) {

	$("input[name^='f_"+group+"_']").prop('checked', elem.checked);
}
//-->
</script>

{strip}
{/strip}
