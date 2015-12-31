<?php

class apfra_rights {

	private $adodb;

	function apfra_rights($adodb) {

		$this->adodb = $adodb;
	}

	function readrights() {

		$tmp_is_admin = $_SESSION["psu"]["id"] == 1 ? 1 : 0;
				
		$tmprights = array();
		
		if ($rresult = $this->adodb->Execute("select aModule from aModule")) {
		
			while (!$rresult->EOF) {
		
				$tmprights[$rresult->fields["aModule"]] = array(
						"sel" => $tmp_is_admin,
						"ins" => $tmp_is_admin,
						"upd" => $tmp_is_admin,
						"del" => $tmp_is_admin
				);
				$tmprights[$rresult->fields["aModule"]]["sum"] = array_sum($tmprights[$rresult->fields["aModule"]]);
		
				$rresult->MoveNext();
			}
		}
		
		if ($rresult = $this->adodb->Execute("select aModule.aModule, aRight.aselect, aRight.ainsert, aRight.aupdate, aRight.adelete from aRight, ref1n_aUser_aRole, aModule where ref1n_aUser_aRole.refid_aUser = '".$_SESSION["psu"]["id"]."' and ref1n_aUser_aRole.refid_aRole = aRight.refid_aRole and aRight.refid_aModule = aModule.id")) {
		
			while (!$rresult->EOF) {
		
				$tmprights[$rresult->fields["aModule"]] = array(
						"sel" => $rresult->fields["aselect"],
						"ins" => $rresult->fields["ainsert"],
						"upd" => $rresult->fields["aupdate"],
						"del" => $rresult->fields["adelete"]
				);
				$tmprights[$rresult->fields["aModule"]]["sum"] = array_sum($tmprights[$rresult->fields["aModule"]]);
		
				$rresult->MoveNext();
			}
		}
		
		return $tmprights;
	}
}

?>