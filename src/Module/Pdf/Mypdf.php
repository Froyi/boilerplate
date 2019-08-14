<?php
declare(strict_types=1);

namespace Project\Module\Pdf;

use TCPDF;

/**
 * Class Mypdf
 * @package     Project\Module\Pdf
 */
class Mypdf extends TCPDF
{
    //Page header
    public function Header(): void
    {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}