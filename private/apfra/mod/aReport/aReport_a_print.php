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

		$fontsize = 12;
		$maxwidth = $this->w - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT);

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

		} while (array_sum($w) > $maxwidth && $fontsize > 8);

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
}

$data = array();
if ($result = $db->Execute("select aReport, felder, filter, sortierung from ".$datasql_table." where id = '".$id."' limit 1")) {

	if (!$result->EOF) {

		$data["aReport"] = $result->fields["aReport"];
		$data["felder"] = $result->fields["felder"];
		$data["filter"] = $result->fields["filter"];
		$data["sortierung"] = $result->fields["sortierung"];
	}
}

$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(DEF_COMPANY);
$pdf->SetTitle($data["aReport"]);
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

$pdf->SetFont('helvetica', 'B', 14, '', false);
$pdf->Write(0, $data["aReport"], '', false, 'L', true, 0, false, true, 0);
$pdf->Ln();
$pdf->SetFont('helvetica', '', 11, '', false);

$data_tables = array();
$data_fields = explode(",", $data["felder"]);
$data_filters = $data["filter"] ? unserialize($data["filter"]) : array();

if (count($data_fields)) {
	foreach ($data_fields as $data_field) {
		$tmparr = explode(".", $data_field);
		$tmptable = isset($tmparr[0]) && $tmparr[0] ? $tmparr[0] : "";
		if (!in_array($tmptable, $data_tables) && $tmptable) {
			$data_tables[] = $tmptable;
		}
	}
}

if (count($data_filters)) {

	foreach ($data_filters as $dfarr) {

		$tmparr = explode(".", $dfarr["col"]);
		$tmptable = isset($tmparr[0]) && $tmparr[0] ? $tmparr[0] : "";

		if (!in_array($tmptable, $data_tables) && $tmptable) {
			$data_tables[] = $tmptable;
		}
	}
}

$wherearr = array();

if (count($datasql_reference1n)) {

	foreach ($datasql_reference1n as $ref1n) {

		$tmparr = explode("_", substr($ref1n, 6, 255));
		$tmptable1 = $tmparr[0];
		$tmptablen = $tmparr[1];

		if (in_array($tmptable1, $data_tables) && in_array($tmptablen, $data_tables) && !in_array($ref1n, $data_tables)) {
			$data_tables[] = $ref1n;
			$wherearr[] = $ref1n.".refid_".$tmptable1." = ".$tmptable1.".id";
			$wherearr[] = $ref1n.".refid_".$tmptablen." = ".$tmptablen.".id";
		}
	}
}

if (count($data_tables)) {
	foreach ($data_tables as $data_table) {
		foreach ($datareport_ref as $refkey => $refvalue) {
			if ($refkey == $data_table) {
				foreach ($refvalue as $subkey => $subvalue) {
					if (in_array($subkey, $data_tables)) {
						$wherearr[] = $subkey.".id = ".$refkey.".".$subvalue;
					}
				}
			}
		}
	}
}

if (count($data_filters)) {
	$tmpwhere = "";

	foreach ($data_filters as $dfarr) {

		if (isset($dfarr["junc"]) && $dfarr["junc"]) $tmpwhere .= " ".$dfarr["junc"];
		$tmpwhere .= " ".$dfarr["col"]." ".$dfarr["op"]." '".$dfarr["val"]."'";
	}
	if ($tmpwhere) {
		$wherearr[] = "(".$tmpwhere.")";
	}
}

if ($wherearr) {
	$where = "where ".implode(" and ", $wherearr);
} else {
	$where = "";
}

// column titles
$header = array();
foreach ($data_fields as $data_field) {
	$header[] = $apfra_db_desc[$data_field];
}

$orderby = $data["sortierung"] ? "order by ".$data["sortierung"] : "";

$query = "select ".$data["felder"]." from ".implode(",", $data_tables)." ".$where." ".$orderby;

$data = array();
if ($result = $db->Execute($query)) {

	while (!$result->EOF) {

		$datatmp = array();
		foreach ($data_fields as $data_field) {
			$tmparr = explode(".", $data_field);
			$tmptable = isset($tmparr[0]) && $tmparr[0] ? $tmparr[0] : "";
			$tmpfield = isset($tmparr[1]) && $tmparr[1] ? $tmparr[1] : "";
			$datatmp[] = $result->fields[$tmpfield];
		}

		$data[] = $datatmp;

		$result->MoveNext();
	}
}

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output(date("Y-m-d_H-i-s").'_report.pdf', 'I');

require(DEF_PATH_PRIVATE."apfra".DS."lib".DS."exit.inc.php");
die();

?>
