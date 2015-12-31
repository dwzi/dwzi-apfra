{strip}

<form role="form" class="form-horizontal" action="{$url}index.php?mod={$module}" method="post">

	<input type="hidden" id="fa" name="fa" value="save">	
	
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Modul/Tabelle</th>
				<th>&nbsp;</th>
			{section name=j loop=$data_role}
				<th style="text-align: center;">{$data_role[j].aRole}</th>
			{/section}
			</tr>
			<tr>
				<th>
					alle Tabellen und Rollen <input type="checkbox" onClick="setcheck_all(this)">
				</th>
				<th bgcolor="#eeeeee">
					<table class="table table-condensed table-centered">
					<tr>
						<td>all</td>
						<td>sel</td>
						<td>ins</td>
						<td>upd</td>
						<td>del</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					</table>					
				</th>
			{section name=j loop=$data_role}
				<th bgcolor="#eeeeee">
					<table class="table table-condensed table-centered">
					<tr>
						<td>all</td>
						<td>sel</td>
						<td>ins</td>
						<td>upd</td>
						<td>del</td>
					</tr>
					<tr>
						<td><input type="checkbox" onClick="setcheck_role('{$data_role[j].id}', '*', this)"></td>
						<td><input type="checkbox" onClick="setcheck_role('{$data_role[j].id}', 'sel', this)"></td>
						<td><input type="checkbox" onClick="setcheck_role('{$data_role[j].id}', 'ins', this)"></td>
						<td><input type="checkbox" onClick="setcheck_role('{$data_role[j].id}', 'upd', this)"></td>
						<td><input type="checkbox" onClick="setcheck_role('{$data_role[j].id}', 'del', this)"></td>
					</tr>
					</table>					
				</th>
			{/section}
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$data_mod}
			<tr> 
				<td>{$data_mod[i].aModule} ({$data_mod[i].aModuleDesc})</td>
				<td bgcolor="#eeeeee">
					<table class="table table-condensed table-centered">
					<tr>
						<td><input type="checkbox" onClick="setcheck_mod('{$data_mod[i].id}', '*', this)"></td>
						<td><input type="checkbox" onClick="setcheck_mod('{$data_mod[i].id}', 'sel', this)"></td>
						<td><input type="checkbox" onClick="setcheck_mod('{$data_mod[i].id}', 'ins', this)"></td>
						<td><input type="checkbox" onClick="setcheck_mod('{$data_mod[i].id}', 'upd', this)"></td>
						<td><input type="checkbox" onClick="setcheck_mod('{$data_mod[i].id}', 'del', this)"></td>
					</tr>
					</table>
				</td>
			{section name=j loop=$data_role}
				<td>
					<table class="table table-condensed table-centered">
					<tr>
						<td><input type="checkbox" onClick="setcheck_role_mod('{$data_role[j].id}', '{$data_mod[i].id}', this)"></td>
						<td><input type="checkbox" id="f_{$data_role[j].id}_{$data_mod[i].id}_sel" name="f_{$data_role[j].id}_{$data_mod[i].id}_sel" value="1"{if $data[$data_role[j].id][$data_mod[i].id].sel|default:0 == 1} checked{/if}></td>
						<td><input type="checkbox" id="f_{$data_role[j].id}_{$data_mod[i].id}_ins" name="f_{$data_role[j].id}_{$data_mod[i].id}_ins" value="1"{if $data[$data_role[j].id][$data_mod[i].id].ins|default:0 == 1} checked{/if}></td>
						<td><input type="checkbox" id="f_{$data_role[j].id}_{$data_mod[i].id}_upd" name="f_{$data_role[j].id}_{$data_mod[i].id}_upd" value="1"{if $data[$data_role[j].id][$data_mod[i].id].upd|default:0 == 1} checked{/if}></td>
						<td><input type="checkbox" id="f_{$data_role[j].id}_{$data_mod[i].id}_del" name="f_{$data_role[j].id}_{$data_mod[i].id}_del" value="1"{if $data[$data_role[j].id][$data_mod[i].id].del|default:0 == 1} checked{/if}></td>
					</tr>
					</table>
				</td>
			{/section}
			</tr>
		{/section}
		</tbody>
	</table>

	<div class="form-group">
		<div class="col-md-8">
			<button type="submit" class="btn btn-success" onClick="$('#fa').attr('value', 'save');">Speichern</button>
		</div>
	</div>
	
</form>

{/strip}
<script type="text/javascript">
<!--
function setcheck_all(elem) {

	$("input[name^='f_']").prop('checked', elem.checked);
}

function setcheck_role_mod(roleid, modid, elem) {

	$("input[name^='f_"+roleid+"_"+modid+"_']").prop('checked', elem.checked);
}

function setcheck_role(id, op, elem) {

	if (op == '*') {
		$("input[name^='f_"+id+"_']").prop('checked', elem.checked);
	} else {
		$("input[name^='f_"+id+"_'][name$='_"+op+"']").prop('checked', elem.checked);
	}
}

function setcheck_mod(id, op, elem) {

	if (op == '*') {
		$("input[name$='_"+id+"_sel']").prop('checked', elem.checked);
		$("input[name$='_"+id+"_ins']").prop('checked', elem.checked);
		$("input[name$='_"+id+"_upd']").prop('checked', elem.checked);
		$("input[name$='_"+id+"_del']").prop('checked', elem.checked);
	} else {
		$("input[name$='_"+id+"_"+op+"']").prop('checked', elem.checked);
	}
}
//-->
</script>

{strip}
{/strip}
