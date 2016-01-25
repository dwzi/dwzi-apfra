<?php

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."tcpdf".DS."tcpdf.php");

// extend TCPF with custom functions
class MYPDF extends TCPDF {

	public function DrawHeader($header, $w) {

		// Colors, line width and bold font
		$this->SetFillColor(255, 0, 0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		// Header
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 0, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
	}

	// Colored table
	public function ColoredTable($header, $data) {

		$maxwidth = $this->w - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT);

		/* font size 12..7px */

		$fontsize = 12;
		do {
			$fontsize--;

			$w = array();
			foreach ($header as $value)
				$w[] = ceil($this->GetStringWidth($value, 'helvetica', 'B', $fontsize))+2;

			foreach ($data as $row) {
				for ($i=0; $i<count($w); $i++) {
					$tmp = ceil($this->GetStringWidth($row[$i], 'helvetica', '', $fontsize))+2;
					if ($w[$i] < $tmp) $w[$i] = $tmp;
				}
			}

		} while (array_sum($w) > $maxwidth && $fontsize > 7);

		/* crop text if more than one page in width */

		if (array_sum($w) > $maxwidth) {

			$datawidth = array();
			foreach ($header as $value)
				$datawidth[] = ceil($this->GetStringWidth($value, 'helvetica', 'B', $fontsize))+2;

			foreach ($data as $row) {
				for ($i=0; $i<count($w); $i++) {
					$tmp = ceil($this->GetStringWidth($row[$i], 'helvetica', '', $fontsize))+2;
					if ($datawidth[$i] < $tmp) $datawidth[$i] = $tmp;
				}
			}

			arsort($datawidth);

			foreach ($datawidth as $cropcol => $width) {

				if (array_sum($w) > $maxwidth) {

					/* crop one column */

					for ($i=0; $i<count($data); $i++) {
						$tmp = ceil($this->GetStringWidth($data[$i][$cropcol], 'helvetica', '', $fontsize))+2;
						if ($tmp > 40) {
							$data[$i][$cropcol] = substr($data[$i][$cropcol], 0, 37)."...";
						}
					}

					/* recalc width */

					$fontsize = 12;
					do {
						$fontsize--;

						$w = array();
						foreach ($header as $value)
							$w[] = ceil($this->GetStringWidth($value, 'helvetica', 'B', $fontsize))+2;

						foreach ($data as $row) {
							for ($i=0; $i<count($w); $i++) {
								$tmp = ceil($this->GetStringWidth($row[$i], 'helvetica', '', $fontsize))+2;
								if ($w[$i] < $tmp) $w[$i] = $tmp;
							}
						}

					} while (array_sum($w) > $maxwidth && $fontsize > 7);

				}
			}
		}

		/* too much text in columns ... */

		$pagepart_c = array(0 => array());
		$pagepart_w = array(0 => 0);

		$j = 0;
		for ($i=0; $i<count($w); $i++) {
			if ($pagepart_w[$j]+$w[$i] < $maxwidth) {
				$pagepart_c[$j][] = $i;
				$pagepart_w[$j] += $w[$i];
			} else {
				$j++;
				$pagepart_c[$j][] = $i;
				$pagepart_w[$j] = $w[$i];
			}
		}

		$this->SetFont('helvetica', '', $fontsize, '', false);

		for ($pc=0; $pc<count($pagepart_c); $pc++) {

			$colarr = $pagepart_c[$pc];

			$part_header = array();
			$part_w = array();
			foreach ($colarr as $col) {
				$part_header[] = $header[$col];
				$part_w[] = $w[$col];
			}

			$this->DrawHeader($part_header, $part_w);

			$fill = 0;
			foreach($data as $row) {

				$numpages = $this->getNumPages();
				$this->startTransaction();
				for ($c=0; $c<count($colarr); $c++) {
					$part_lr = "";
					$part_lr .= $pc == 0 || $c > 0 ? "L" : "";
					$part_lr .= $pc == count($pagepart_c)-1 && $c == count($colarr)-1 ? "R" : "";
					$this->Cell($w[$colarr[$c]], 0, $row[$colarr[$c]], $part_lr, 0, 'L', $fill);
				}
				$this->Ln();

				if ($numpages < $this->getNumPages()) {

					$this->rollbackTransaction(true);
	//				$this->Cell(array_sum($w), 0, '', 'T');
					$this->AddPage();

					$this->DrawHeader($part_header, $part_w);

					for ($c=0; $c<count($colarr); $c++) {
						$part_lr = "";
						$part_lr .= $pc == 0 || $c > 0 ? "L" : "";
						$part_lr .= $pc == count($pagepart_c)-1 && $c == count($colarr)-1 ? "R" : "";
						$this->Cell($w[$colarr[$c]], 0, $row[$colarr[$c]], $part_lr, 0, 'L', $fill);
					}
					$this->Ln();
				} else {

					$this->commitTransaction();
				}

				$fill=!$fill;
			}
			$this->Cell(array_sum($part_w), 0, '', 'T');

			if ($pc < count($pagepart_c)-1) {

				$this->AddPage();
			}
		}

	}

	// Colored Data, only one ID
	public function ColoredData($header, $data) {

		$fontsize = 12;
		$maxwidth = $this->w - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT);

		do {
			$fontsize--;

			$w = array("header" => 0, "data" => 0);

			foreach ($header as $value) {
				$tmp = ceil($this->GetStringWidth($value, 'helvetica', 'B', $fontsize))+2;
				if ($w["header"] < $tmp) $w["header"] = $tmp;
			}

			foreach ($data as $value) {
				$tmp = ceil($this->GetStringWidth($value, 'helvetica', 'B', $fontsize))+2;
				if ($w["data"] < $tmp) $w["data"] = $tmp;
			}
		} while (array_sum($w) > $maxwidth && $fontsize > 8);

		$this->SetFont('helvetica', '', $fontsize, '', false);

		$this->SetFillColor(224, 235, 255);
		$fill=0;
		for ($i=0; $i<count($header); $i++) {

			$this->Cell($w["header"], 0, $header[$i], '', 0, 'L', $fill);
			$this->Cell($w["data"], 0, $data[$i], '', 0, 'L', $fill);
			$this->Ln();

			$fill=!$fill;
		}
	}
}

$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(DEF_COMPANY);
$pdf->SetTitle($apfra_db_desc[$datasql_table]);
$pdf->SetSubject('');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData("logo_print.png", 50, DEF_COMPANY, "", array(255,127,0), array(255,127,0));
$pdf->setFooterData(array(0,0,0), array(255,127,0));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set default font subsetting mode
$pdf->setFontSubsetting(false);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$datasql_fields_edit = array();
$datasql_fields_edit_ref1n = array();
$datasql_fields_edit_ref1n_min = array();
$datasql_fields_edit_ref1nsub = array();

foreach ($datasql_edit_fields as $fields_tab) {
	foreach ($fields_tab["row"] as $fields_row) {
		foreach ($fields_row["col"] as $fields_col) {

			if ($fields_col["type"] == "reference1n") {
				$datasql_fields_edit_ref1n[$fields_col["field"]] = $fields_col["field"];
			} elseif ($fields_col["type"] == "reference1n_min") {
				$datasql_fields_edit_ref1n_min[$fields_col["field"]] = $fields_col["field"];
			} elseif ($fields_col["type"] == "reference1nsub") {
				$datasql_fields_edit_ref1nsub[$fields_col["field"]] = $fields_col;
			} else {
				$datasql_fields_edit[] = $fields_col["field"];
			}
		}
	}
}

if ($id) {

	$tmp_sql_fields = array();
	$tmp_other_fields = array();
	for ($i=0; $i<count($datasql_print1_fields); $i++) {
		if (in_array($datasql_print1_fields[$i], $datasql_fields_edit)) {

			$tmp_sql_fields[] = $datasql_print1_fields[$i];
		} else {
			$tmp_other_fields[] = $datasql_print1_fields[$i];
		}
	}

	// column titles
	$tmpdescarr = array();
	foreach ($tmp_sql_fields as $field) {
		$tmpdescarr[] = $apfra_db_desc[$datasql_table.".".$field];
	}
	$header = $tmpdescarr;

	$data = array();
	if ($result = $db->Execute("select ".implode(",",$tmp_sql_fields)." from ".$datasql_table." where id = '".$id."' limit 1")) {

		if (!$result->EOF) {

			$datatmp = array();
			foreach ($tmp_sql_fields as $field) {
				$field = str_replace("`","",$field);
				if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
				if (in_array($field, $datasql_reference11_fields)) {

					if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {
						if (!$result->EOF) {
							$sfield = $datasql_reference11[$field]["field"];
							if (strpos($sfield, " as ")) $sfield = substr($sfield, strpos($sfield, " as ")+4, strlen($sfield));
							$stmp = $sresult->fields[$sfield];
						}
					}
					$datatmp[] = $stmp;
				} else {

					$datatmp[] = $result->fields[$field];
				}
			}

			$data = $datatmp;
		}
	}

	$data_tables = array();
	if (count($tmp_other_fields)) {

		if (count(array_merge($datasql_fields_edit_ref1n, $datasql_fields_edit_ref1n_min))) {

			foreach (array_merge($datasql_fields_edit_ref1n, $datasql_fields_edit_ref1n_min) as $ref1n) {

				$tmp = substr($ref1n, 6, 255);
				$tmparr = explode("_", $tmp);
				$tmpref1 = "refid_".$tmparr[0];
				$tmprefn = "refid_".$tmparr[1];
				$tmptab = $ref1n;

				$refarr = array(
						"table"      => $ref1n,
						"col1_key"   => $tmpref1,
						"coln_key"   => $tmprefn,
						"col1_table" => $datasql_reference11[$tmpref1]["table"],
						"coln_table" => $datasql_reference11[$tmprefn]["table"],
						"coln_valuenorm" => (strpos($datasql_reference11[$tmprefn]["field"], " as ") !== false ? substr($datasql_reference11[$tmprefn]["field"], strpos($datasql_reference11[$tmprefn]["field"], " as ")+4, strlen($datasql_reference11[$tmprefn]["field"])) : $datasql_reference11[$tmprefn]["field"]),
						"coln_value" => array($datasql_reference11[$tmprefn]["field"]),
						"coln_order" => $datasql_reference11[$tmprefn]["orderby"]
				);

				if ($sresult = $db->Execute("select aRefDesc from aRef where aRef = '".$tmprefn."' limit 1")) {

					if (!$sresult->EOF) {

						$apfra_db_desc[$ref1n] = $sresult->fields["aRefDesc"];
					}
				}

				$datatmp = array();
				if ($result = $db->Execute("select ".$refarr["table"].".id as dataid, ".$refarr["coln_table"].".id, ".implode(",", $refarr["coln_value"])." from ".$refarr["col1_table"].", ".$refarr["table"].", ".$refarr["coln_table"]." where ".$refarr["col1_table"].".id = ".$refarr["table"].".".$refarr["col1_key"]." and ".$refarr["coln_table"].".id = ".$refarr["table"].".".$refarr["coln_key"]." and ".$refarr["col1_table"].".id = '".$id."' order by ".implode(",", $refarr["coln_order"]))) {

					while (!$result->EOF) {

						foreach ($refarr["coln_value"] as $value) {
							$datatmp[] = $result->fields[$value];
						}

//						array_push($tmpdata, $datatmp);

						$result->MoveNext();
					}
				}

				// content print

				$header[] = $apfra_db_desc[$ref1n];
				$data[] = implode(",", $datatmp);

			}
		}

		foreach ($tmp_other_fields as $tmpfield) {

			if (array_key_exists($tmpfield, $datasql_fields_edit_ref1nsub)) {

				$fields_col = array();
				foreach ($datasql_fields_edit_ref1nsub as $key => $valuearr) {
					if ($key == $tmpfield) {
						$fields_col = $valuearr;
					}
				}

				$tmpfield = substr($fields_col["field"], 6, 255);
				$tmpdata = array();

				$tmptotal = array();
				if (isset($fields_col["ref_total"]) && count($fields_col["ref_total"])) {
					foreach ($fields_col["ref_total"] as $field) {
						$tmptotal[$field] = 0;
					}
				}

				$tmpdescarr = array();
				foreach ($fields_col["ref_value"] as $field) {
					if (strpos($field," as ") !== false) {
						$field = substr($field, strpos($field, " as ")+4, 255);
					}
					$tmpdescarr[] = $field;
				}
				$tmpheader = $tmpdescarr;

				if ($result = $db->Execute("select id, ".implode(",",$fields_col["ref_value"])." from ".$tmpfield." where refid_".$datasql_table." = '".$id."' order by ".implode(",", $fields_col["ref_order"]))) {

					while (!$result->EOF) {

						$datatmp = array();

						foreach ($fields_col["ref_value"] as $keyfieldcol => $value) {

							if (strpos($value," as ") !== false) {

								$value = substr($value, strpos($value, " as ")+4, 255);
								$datatmp[$value] = $result->fields[$value];

								if (isset($fields_col["ref_total"]) && in_array($value, $fields_col["ref_total"])) {
									$tmptotal[$value] += $result->fields[$value];
								}

							} elseif (count($datasql_reference11)) {

								$datatmp[$value] = $result->fields[$value];

								if (isset($fields_col["ref_total"]) && in_array($value, $fields_col["ref_total"])) {
									$tmptotal[$value] += $result->fields[$value];
								}

								foreach ($datasql_reference11 as $key => $refarr2) {

									if ($key == $value) {
										if ($sresult = $db->Execute("select ".$refarr2["field"]." from ".$refarr2["table"]." where id = '".$result->fields[$value]."' limit 1")) {
											if (!$sresult->EOF) {

												$tmpidx = $refarr2["field"];
												if (strpos($tmpidx," as ") !== false) {
													$tmpidx = substr($tmpidx, strpos($tmpidx, " as ")+4, 255);
												}

												$datatmp[$value] = $sresult->fields[$tmpidx];
											}
										}
									}
								}
							}
						}

						$tmpa = $datatmp;
						$datatmp = array();
						foreach ($tmpa as $key => $value) {
							$datatmp[] = $value;
						}

						array_push($tmpdata, $datatmp);

						$result->MoveNext();
					}
				}

				if (isset($fields_col["ref_total"]) && count($fields_col["ref_total"])) {

					$datatmp = array();

					foreach ($fields_col["ref_value"] as $keyfieldcol => $value) {

						if (strpos($value," as ") !== false) {
							$value = substr($value, strpos($value, " as ")+4, 255);
						}

						$datatmp[$value] = "";
					}

					foreach ($tmptotal as $key => $value) {
						$value = str_replace(",",".",$value);
						$datatmp[$key] = $value;
					}

					$tmpa = $datatmp;
					$datatmp = array();
					foreach ($tmpa as $key => $value) {
						$datatmp[] = $value;
					}

					array_push($tmpdata, $datatmp);
				}

				$data_tables[] = array(
					"header" => $tmpheader,
					"fields" => $tmpdata
				);
			}
		}
	}

	$pdf->ColoredData($header, $data);

	if (count($data_tables)) {

		foreach ($data_tables as $data_table) {

			$pdf->ln();
			$pdf->ColoredTable($data_table["header"], $data_table["fields"]);
		}
	}

	$pdf->Output(date("Y-m-d_H-i-s").'_print_'.$module.'_'.$id.'.pdf', 'I');

} else {

/* no id */

	$datasql_table_fields_ref1n = array();
	$datasql_table_fields_sql = array();
	$datasql_table_fields_tables = array();
	for ($i = 0; $i < count($datasql_printn_fields); $i++) {
		if (strpos($datasql_printn_fields[$i], ".") !== false) {
			$tmparr = explode(".", $datasql_printn_fields[$i]);
			if (!in_array($tmparr[0], $datasql_table_fields_tables)) {
				$datasql_table_fields_tables[] = $tmparr[0];
			}
			$datasql_table_fields_sql[] = $datasql_printn_fields[$i];
			if (strpos($datasql_printn_fields[$i], " as ") !== false) {
				$datasql_printn_fields[$i] = substr($datasql_printn_fields[$i], strpos($datasql_printn_fields[$i], " as ")+4, strlen($datasql_printn_fields[$i]));
			}
		} elseif (substr($datasql_printn_fields[$i],0,6) == "ref1n_" && substr($datasql_printn_fields[$i],6,strrpos($datasql_printn_fields[$i],"_")-6) == $datasql_table) {
			$datasql_table_fields_ref1n[] = $datasql_printn_fields[$i];
			$apfra_db_desc[$datasql_table.".".$datasql_printn_fields[$i]] = $apfra_db_desc[substr($datasql_printn_fields[$i],strrpos($datasql_printn_fields[$i],"_")+1,255)];
		} elseif ($datasql_printn_fields[$i] != "refid_aUser_update") {
			$datasql_table_fields_sql[] = $datasql_printn_fields[$i];
		}
	}

	$where = "";
	if ($search) {
		if (count($datasql_search_fields)) {
			$where .= " where (";
			foreach ($datasql_search_fields as $field) {

				if (strpos($field,".") === false) {
					$tmpsql_sfield = $datasql_table.".".$field;
				} else {
					$tmpsql_sfield = $field;
				}

				if (in_array($field, $datasql_reference11_fields)) {

					$tmpwhere = "";
					foreach ($datasql_reference11[$field]["search"] as $svalue) {
						$tmpwhere .= $tmpsql_sfield." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$search."%') or ";
					}

					$where .= $tmpwhere;

				} elseif (in_array($field, $datasql_table_fields_ref1n)) {

					$tmp_ref1n = substr($field,strrpos($field,"_")+1,255);

					$tmpidarr = array();
					if ($sresult = $db->Execute("select id from ".$tmp_ref1n." where ".$tmp_ref1n." like '%".$search."%'")) {
						while (!$sresult->EOF) {
							$tmpidarr[] = $sresult->fields["id"];
							$sresult->MoveNext();
						}
					}

					if (count($tmpidarr)) {

						$where .= "(select count(*) from ".$field." where ".$field.".refid_".$datasql_table." = ".$datasql_table.".id and ".$field.".refid_".$tmp_ref1n." in ('".implode("','", $tmpidarr)."') > '0') or ";
					}

				} else {

					$where .= $tmpsql_sfield." like '%".$search."%' or ";
				}
			}
			$where = substr($where, 0, strlen($where) - 3);
			$where .= ")";
		}
	}

	if (count($data_filter)) {

		$where .= !$where ? " where (" : " and (";
		foreach ($data_filter as $field => $value) {
			if (in_array($field, $datasql_reference11_fields)) {

				$tmpwhere = "(";
				foreach ($datasql_reference11[$field]["search"] as $svalue) {
					$tmpwhere .= $field." in (select id from ".$datasql_reference11[$field]["table"]." where ".$svalue." like '%".$value."%') or ";
				}
				$tmpwhere = substr($tmpwhere, 0, strlen($tmpwhere) - 3);
				$tmpwhere .= ") and ";
				$where .= $tmpwhere;

			} elseif (in_array($field, $datasql_table_fields_ref1n)) {

				$tmp_ref1n = substr($field,strrpos($field,"_")+1,255);

				$tmpidarr = array();
				if ($sresult = $db->Execute("select id from ".$tmp_ref1n." where ".$tmp_ref1n." like '%".$value."%'")) {
					while (!$sresult->EOF) {
						$tmpidarr[] = $sresult->fields["id"];
						$sresult->MoveNext();
					}
				}

				if (count($tmpidarr)) {

					$where .= "(select count(*) from ".$field." where ".$field.".refid_".$datasql_table." = ".$datasql_table.".id and ".$field.".refid_".$tmp_ref1n." in ('".implode("','", $tmpidarr)."') > '0') and ";
				}

			} else {

				$tmp_field = $field;
				foreach ($datasql_table_fields_sql as $fvalue) {
					if (strpos($fvalue, " as ") !== false) {
						$tmp_field = substr($fvalue, 0, strpos($fvalue, " as "));
					}
				}

				$where .= $tmp_field." like '%".$value."%' and ";
			}
		}
		$where = substr($where, 0, strlen($where) - 4);
		$where .= ")";
	}

	$expw = isset($_SESSION["psd"]["expw"]) ? $_SESSION["psd"]["expw"] : "";
	if ($expw) {

		$where .= !$where ? " where (" : " and (";
		$where .= $expw;
		$where .= ")";
	}

	if (count($datasql_table_fields_tables)) {

		$where .= !$where ? " where (" : " and (";
		foreach ($datasql_table_fields_tables as $tab) {
			$where .= $tab.".id = ".$datasql_table.".refid_".$tab." and ";
		}
		$where = substr($where, 0, strlen($where) - 4);
		$where .= ")";
	}

	$pdf->SetFont('helvetica', 'B', 14, '', false);
	$pdf->Write(0, $apfra_db_desc[$datasql_table], '', false, 'L', true, 0, false, true, 0);
	$pdf->Ln();
	$pdf->SetFont('helvetica', '', 11, '', false);

	// column titles
	$tmpdescarr = array();
	foreach ($datasql_table_fields_sql as $field) {
		if (strpos($field," as ") === false) {
			$tmpdescarr[] = $apfra_db_desc[$datasql_table.".".$field];
		} else {
			$tmpdescarr[] = substr($field,strpos($field," as ")+4,255);
		}
	}
	if (in_array("refid_aUser_update", $datasql_printn_fields)) {
		$tmpdescarr[] = $apfra_db_desc[$datasql_table.".refid_aUser_update"];
	}
	if (count($datasql_table_fields_ref1n)) {
		foreach ($datasql_table_fields_ref1n as $field) {
			$tmpdescarr[] = $apfra_db_desc[$datasql_table.".".$field];
		}
	}
	$header = $tmpdescarr;

	$tmp_sql_fields = array();
	for ($i=0; $i<count($datasql_table_fields_sql);$i++) {
		if (strpos($datasql_table_fields_sql[$i], ".") === false) {
			$tmp_sql_fields[] = $datasql_table.".".$datasql_table_fields_sql[$i];
		} else {
			$tmp_sql_fields[] = $datasql_table_fields_sql[$i];
		}
	}

	$tmp_sql_order = array();
	for ($i=0; $i<count($datasql_printn_orderby);$i++) {
		if (strpos($datasql_printn_orderby[$i], ".") === false) {
			$tmp_sql_order[] = $datasql_table.".".$datasql_printn_orderby[$i];
		} else {
			$tmp_sql_order[] = $datasql_printn_orderby[$i];
		}
	}

	$data = array();
	if ($result = $db->Execute("select (select aUser from aUser where id = ".$datasql_table.".refid_aUser_update) as refid_aUser, ".implode(",",$tmp_sql_fields)." from ".$datasql_table.(count($datasql_table_fields_tables) ? ",".implode(",", $datasql_table_fields_tables) : "")." ".$where." order by ".implode(",",$tmp_sql_order))) {

		while (!$result->EOF) {

			$datatmp = array();
			foreach ($datasql_table_fields_sql as $field) {
				$field = str_replace("`","",$field);
				if (strpos($field, " as ")) $field = substr($field, strpos($field, " as ")+4, strlen($field));
				if (in_array($field, $datasql_reference11_fields)) {

					if ($sresult = $db->Execute("select ".$datasql_reference11[$field]["field"]." from ".$datasql_reference11[$field]["table"]." where id = '".$result->fields[$field]."' limit 1")) {
						if (!$result->EOF) {
							$sfield = $datasql_reference11[$field]["field"];
							if (strpos($sfield, " as ")) $sfield = substr($sfield, strpos($sfield, " as ")+4, strlen($sfield));
							$stmp = $sresult->fields[$sfield];
						}
					}
					$datatmp[] = htmlspecialchars_decode($stmp);

				} else {

					$datatmp[] = htmlspecialchars_decode($result->fields[$field]);
				}
			}

			if (in_array("refid_aUser_update", $datasql_printn_fields)) {
				$datatmp[] = $result->fields["refid_aUser"];
			}

			if (count($datasql_table_fields_ref1n)) {

				foreach ($datasql_table_fields_ref1n as $value) {

					$tmp_ref1n = substr($value,strrpos($value,"_")+1,255);
					$tmpvaluearr = array();

					if ($sresult = $db->Execute("select ".$tmp_ref1n." from ".$tmp_ref1n." where id in (select refid_".$tmp_ref1n." from ".$value." where refid_".$datasql_table." = '".$result->fields["id"]."' group by refid_".$tmp_ref1n.") order by ".$tmp_ref1n)) {
						while (!$sresult->EOF) {
							$tmpvaluearr[] = htmlspecialchars_decode($sresult->fields[$tmp_ref1n]);
							$sresult->MoveNext();
						}
					}

					$datatmp[] = implode(", ", $tmpvaluearr);
				}
			}

			$data[] = $datatmp;

			$result->MoveNext();
		}
	}

	$pdf->ColoredTable($header, $data);

	$pdf->Output(date("Y-m-d_H-i-s").'_print_'.$module.'.pdf', 'I');
}


require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
die();

?>
