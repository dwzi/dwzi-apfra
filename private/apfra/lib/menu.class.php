<?php

class apfra_menu {

	private $adodb;
	private $rights;
	private $dbtable_menu;
	private $dbtable_module;
	
	function apfra_menu($adodb, $rights, $dbtable_menu = 'aMenu', $dbtable_module = 'aModule') {

		$this->adodb = $adodb;
		$this->rights = $rights;
		$this->dbtable_menu = $dbtable_menu;		
		$this->dbtable_module = $dbtable_module;
	}

	function readmenu() {

		$tmpmenu = array();
		if ($m0result = $this->adodb->Execute("select ".$this->dbtable_menu.".id, ".$this->dbtable_menu.".aMenu, (select aModule from ".$this->dbtable_module." where ".$this->dbtable_module.".id = ".$this->dbtable_menu.".refid_aModule) as aModule from ".$this->dbtable_menu." where (".$this->dbtable_menu.".refid_aMenu_parent = 0 or ".$this->dbtable_menu.".refid_aMenu_parent is null) order by ".$this->dbtable_menu.".pos")) {
		
			while (!$m0result->EOF) {
		
				$tmpsubmenu = array();
				if ($m1result = $this->adodb->Execute("select ".$this->dbtable_menu.".aMenu, (select aModule from ".$this->dbtable_module." where ".$this->dbtable_module.".id = ".$this->dbtable_menu.".refid_aModule) as aModule from ".$this->dbtable_menu." where ".$this->dbtable_menu.".refid_aMenu_parent = '".$m0result->fields["id"]."' order by ".$this->dbtable_menu.".pos")) {
		
					while (!$m1result->EOF) {
		
						if ($m1result->fields["aMenu"] == "#divider") {
								
							$tmpsubmenu[] = $m1result->fields["aMenu"];

						} else {
								
							if ($this->rights[$m1result->fields["aModule"]]["sum"]) {
		
								$tmpsubmenu[] = array(
										"module" => $m1result->fields["aModule"],
										"desc" => $m1result->fields["aMenu"]
								);
							}
						}
		
						$m1result->MoveNext();
					}
				}
		
				if (count($tmpsubmenu)) {
						
					$tmpmenu[] = array(
							"module" => $m0result->fields["aModule"],
							"desc" => $m0result->fields["aMenu"],
							"submenu" => $tmpsubmenu
					);
		
				} else {
		
					if ($m0result->fields["aModule"] && $this->rights[$m0result->fields["aModule"]]["sum"]) {
		
						$tmpmenu[] = array(
								"module" => $m0result->fields["aModule"],
								"desc" => $m0result->fields["aMenu"]
						);
					}
				}
		
				$m0result->MoveNext();
			}
		}

		/* remove multiple #divider due to missing rights */
		$tmpmenu2 = $tmpmenu;
		$tmpmenu = array();
		foreach ($tmpmenu2 as $elem) {
			if (isset($elem["submenu"]) && count($elem["submenu"])) {
				$tmpsubmenu2 = array();
				foreach ($elem["submenu"] as $subelem) {
					if (!($subelem == "#divider" && count($tmpsubmenu2) && $tmpsubmenu2[count($tmpsubmenu2)-1] == "#divider")) {
						$tmpsubmenu2[] = $subelem;
					}
				}
				if ($tmpsubmenu2 == array("#divider")) {
					$tmpsubmenu2 = array();
				}
				if (count($tmpsubmenu2) && $tmpsubmenu2[0] == "#divider") {
					$tmpsubmenu2 = array_slice($tmpsubmenu2, 1, count($tmpsubmenu2) - 1);
				}
				if (count($tmpsubmenu2) && $tmpsubmenu2[count($tmpsubmenu2)-1] == "#divider") {
					$tmpsubmenu2 = array_slice($tmpsubmenu2, 0, count($tmpsubmenu2) - 1);
				}
				if (count($tmpsubmenu2)) {
					$elem["submenu"] = $tmpsubmenu2;
					$tmpmenu[] = $elem;
				}
			} else {
				$tmpmenu[] = $elem;
			}
		}

		return $tmpmenu;
	}
}

?>