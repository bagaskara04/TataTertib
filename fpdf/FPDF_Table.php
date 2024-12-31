<?php
require_once('fpdf.php');

class FPDF_Table extends FPDF
{
    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        // Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        // Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        // Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = 6 * $nb;
        $this->CheckPageBreak($h);

        // Output the data
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
            // Use MultiCell for long text in specific columns
            if ($this->isLongText($data[$i])) {
                $this->MultiCell($w, $h, $data[$i], 1, $a);
            } else {
                $this->Cell($w, $h, $data[$i], 1, 0, $a);
            }
        }
        $this->Ln();
    }

    function CheckPageBreak($h)
    {
        // If the height of the row is greater than the space available, add a new page
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage();
        }
    }

    function NbLines($w, $txt)
    {
        // Calculate the number of lines needed to display a cell with a given width and text
        $cw = $this->GetStringWidth($txt);
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $n = ceil($cw / $w);
        return $n;
    }

    function isLongText($text)
    {
        // Define which columns should use MultiCell based on their length or specific columns
        // Example: If the text length exceeds 30 characters, we use MultiCell
        return strlen($text) > 30;
    }
}
?>
