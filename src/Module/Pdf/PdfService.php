<?php /** @noinspection ALL */
declare(strict_types=1);

namespace Project\Module\Pdf;

use InvalidArgumentException;
use Project\Configuration;
use Project\Module\Competition\CompetitionService;
use Project\Module\Competition\CompetitionType;
use Project\Module\CompetitionData\CompetitionData;
use Project\Module\CompetitionData\CompetitionDataCollection;
use Project\Module\CompetitionData\CompetitionDataService;
use Project\Module\DefaultService;
use Project\Module\GenericValueObject\Date;
use Project\Module\GenericValueObject\ShortCode;
use Project\Module\MultipleRegister\MultipleRegister;
use Project\Module\MultipleRegister\MultipleRegisterCollection;
use Project\Module\MultipleRegister\MultipleRegisterService;
use Project\Module\PermanentStarter\PermanentStarter;
use Project\Module\QrCode\QrCode;
use Project\Module\Runner\Runner;
use Project\Module\StartNumber\StartNumber;
use Project\Module\StartNumber\StartNumberCollection;
use Project\Module\Voucher\Voucher;
use Project\Utilities\Converter;

/**
 * Class PdfService
 * @package     Project\Module\Pdf
 */
class PdfService extends DefaultService
{
    protected const DEFAULT_FORMAT = 'DL';

    protected const DEFAULT_FILE_ENDING = '.pdf';

    protected $pdfService;

    public function __construct(string $format = self::DEFAULT_FORMAT)
    {
        parent::__construct();

        $this->pdfService = new Mypdf('L', 'mm', $format);

        $this->pdfService->SetCreator('Martin Springer');
        $this->pdfService->SetAuthor('Heidelaufserie');
        $this->pdfService->SetTitle('Gutschein Dauerstarter 2019');

        $this->pdfService->setPrintHeader(false);
        $this->pdfService->setPrintFooter(false);
    }

    /**
     * @param Voucher $voucher
     * @param QrCode  $qrCode
     *
     * @return MYPDF
     */
    public function getPdf(Voucher $voucher, QrCode $qrCode): MYPDF
    {
        // create new PDF document
        $pdf = new MYPDF('L', PDF_UNIT, 'DL', false, 'ISO-8859-1', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 051');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set background image
        $img_file = $voucher->getVoucherTyp()->getVoucherTemplate()->getVoucherTemplate();

        $pdf->Image($img_file, 0, 0, 220, 110);

        // set the starting point for the page content
        $pdf->setPageMark();


        // Print a text
        $html = '<div><span style="color: #464a4d; font-size: 1.5em; font-weight: bold;">Für:</span> <span style="color: #464a4d; font-size: 2em;">' . utf8_decode($voucher->getRunnerFirstname()->getName()) . ' ' . utf8_decode($voucher->getRunnerSurname()->getName()) . '</span></div>';
        $pdf->writeHTMLCell($html, 0, 71, 90, $html, 0, 1, 0, true, '', false);

        $pdf->Image($qrCode->getQrCodePath(), 187, 10, 18, 18);

        $html = '<div style="color: #464a4d; font-size: 2em; ">' . $voucher->getShortCode()->getShortCode() . '</div>';
        $pdf->writeHTMLCell($html, 0, 13, 95, $html, 0, 1, 0, true, '', false);

        // ---------------------------------------------------------

        return $pdf;
    }

    /**
     * @param string $text
     *
     * @return MYPDF
     */
    public function getSilvesterPdf(string $text): MYPDF
    {
        // create new PDF document
        $pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Einladung Silvesterlauf');
        $pdf->SetSubject('Silvesterlauf');
        $pdf->SetKeywords('Heidelaufserie, Silvesterlauf');

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set background image
        $img_file = 'data/silvester_background.jpg';

        $pdf->Image($img_file, 0, 0, 210, 297);

        // set the starting point for the page content
        $pdf->setPageMark();


        // Print a text
        $pdf->writeHTMLCell($text, 0, 100, 160, $text, 0, 1, 0, true, '', false);

        return $pdf;
    }

    /**
     * @param MYPDF $pdf
     *
     * @param ShortCode $shortCode
     * @param string $suffix
     *
     * @param bool $overwrite
     *
     * @return bool
     */
    public function savePdf(MYPDF $pdf, ShortCode $shortCode, string $suffix = '', bool $overwrite = false): bool
    {
        try {
            $path = $this->getPdfPath($shortCode, $suffix);

            if ($overwrite === true && file_exists($path) === true) {
                unlink($path);
            }

            $pdf->Output($path, 'F');

            return file_exists($path);
        } catch (InvalidArgumentException $exception) {
            return false;
        }
    }

    /**
     * @param ShortCode $shortCode
     * @param string $suffix
     *
     * @return string
     */
    public function getPdfPath(ShortCode $shortCode, string $suffix = ''): string
    {
        if ($suffix !== '') {
            $suffix = '-' . $suffix;
        }
        return getcwd() . '/' . $this->configuration->getEntryByName('pdfPath') . $shortCode->getShortCode() . $suffix . self::DEFAULT_FILE_ENDING;
    }

    /**
     * @param ShortCode     $shortCode
     * @param Configuration $configuration
     *
     * @param string        $suffix
     *
     * @return string
     */
    public function getRelativePdfPath(ShortCode $shortCode, Configuration $configuration, string $suffix = ''): string
    {
        return $configuration->getEntryByName('pdfPath') . $shortCode->getShortCode() . $suffix . self::DEFAULT_FILE_ENDING;
    }

    /**
     * @param PermanentStarter $permanentStarter
     *
     * @return MYPDF
     */
    public function getReceiptByPermanentStarter(PermanentStarter $permanentStarter): ?MYPDF
    {
        $runner = $permanentStarter->getRunner();
        if ($runner === null) {
            return null;
        }

        // create new PDF document
        // $pdf = new MYPDF('L', PDF_UNIT, 'A5', false, 'ISO-8859-1', false);
        $pdf = new MYPDF('L', PDF_UNIT, 'A5', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Quittung');
        $pdf->SetSubject('Dauerstarter');
        $pdf->SetKeywords('Heidelaufserie, Quittung, Dauerstarter');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set bacground image
        $img_file = 'data/receipt_background.jpg';

        $pdf->Image($img_file, 0, 0, 210, 148);

        // set the starting point for the page content
        $pdf->setPageMark();


        $html = '<div style="font-size: 1.5em;">' . $permanentStarter->getToPay() . '</div>';
        $pdf->writeHTMLCell($html, 0, 40, 27, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . utf8_decode(Converter::num2text($permanentStarter->getToPay())) . ' Euro</div>';
        $pdf->writeHTMLCell($html, 0, 60, 39, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . utf8_decode($runner->getFirstname() . ' ' . $runner->getSurname()) . '</div>';
        $pdf->writeHTMLCell($html, 0, 30, 52, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">Heidelaufverein Halle/Saale e.V.</div>';
        $pdf->writeHTMLCell($html, 0, 30, 64, $html, 0, 1, 0, true, '', false);

        $certificate = '';
        if ($permanentStarter->isCertificate() === true) {
            $certificate = ' mit Urkunde';
        }

        $html = '<div style="font-size: 1.5em;">Dauerstartnummer f�r Heidelaufserie 2019' . $certificate . '</div>';
        $pdf->writeHTMLCell($html, 0, 30, 75, $html, 0, 1, 0, true, '', false);

        $date = Date::fromValue('now');

        $html = '<div style="font-size: 1.5em;">' . $date . '</div>';
        $pdf->writeHTMLCell($html, 0, 32, 86.5, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">Halle (Saale)</div>';
        $pdf->writeHTMLCell($html, 0, 32, 97.5, $html, 0, 1, 0, true, '', false);

        return $pdf;
    }

    /**
     * @param CompetitionData $competitionData
     *
     * @return MYPDF|null
     */
    public function getReceiptByCompetitionData(CompetitionData $competitionData): ?MYPDF
    {
        $runner = $competitionData->getRunner();
        $competition = $competitionData->getCompetition();

        if ($runner === null || $competition === null || $competition->getCompetitionDay() === null || $competitionData->getStartNumber() === null) {
            return null;
        }

        // create new PDF document
        // $pdf = new MYPDF('L', PDF_UNIT, 'A5', false, 'ISO-8859-1', false);
        $pdf = new MYPDF('L', PDF_UNIT, 'A5', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Quittung');
        $pdf->SetSubject('Läufer');
        $pdf->SetKeywords('Heidelaufserie, Quittung, Läufer');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set background image
        $img_file = 'data/receipt_background.jpg';

        $pdf->Image($img_file, 0, 0, 210, 148);

        // set the starting point for the page content
        $pdf->setPageMark();

        $html = '<div style="font-size: 1.5em;">' . $competitionData->getToPay() . '</div>';
        $pdf->writeHTMLCell($html, 0, 40, 27, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . utf8_decode(Converter::num2text($competitionData->getToPay())) . ' Euro</div>';
        $pdf->writeHTMLCell($html, 0, 60, 39, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . utf8_decode($runner->getFirstname() . ' ' . $runner->getSurname() . ' (Startnummer ' . $competitionData->getStartNumber()->getStartNumber() . ')') . '</div>';
        $pdf->writeHTMLCell($html, 0, 30, 52, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">Heidelaufverein Halle/Saale e.V.</div>';
        $pdf->writeHTMLCell($html, 0, 30, 64, $html, 0, 1, 0, true, '', false);

        $certificate = '';
        if ($competitionData->isCertificate() === true) {
            $certificate = ' mit Urkunde';
        }

        $status = 'Voranmeldung';
        if ($competitionData->isLateRegistration() === true) {
            $status = 'Nachmeldung';
        }

        $html = '<div style="font-size: 1.5em;">' . utf8_decode('Startgebühr ' . $competition->getCompetitionDay()->getTitle() . ' (' . $status . ')' . $certificate . '</div>');
        $pdf->writeHTMLCell($html, 0, 30, 75, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . $competition->getCompetitionDay()->getDate() . '</div>';
        $pdf->writeHTMLCell($html, 0, 32, 86.5, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">Halle (Saale)</div>';
        $pdf->writeHTMLCell($html, 0, 32, 97.5, $html, 0, 1, 0, true, '', false);

        return $pdf;
    }

    /**
     * @param MultipleRegister $multipleRegister
     *
     * @return null|MYPDF
     */
    public function getReceiptByMultipleRegister(MultipleRegister $multipleRegister): ?MYPDF
    {
        $competition = $multipleRegister->getCompetition();
        if ($competition === null || $competition->getCompetitionDay() === null) {
            return null;
        }

        // create new PDF document
        $pdf = new MYPDF('P', PDF_UNIT, 'A4', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Quittung');
        $pdf->SetSubject('Sammelmeldungen');
        $pdf->SetKeywords('Heidelaufserie, Quittung, Sammelmeldungen');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set background image
        $img_file = 'data/receipt_background.jpg';

        $pdf->Image($img_file, 0, 0, 210, 148);

        // set the starting point for the page content
        $pdf->setPageMark();

        $html = '<div style="font-size: 1.5em;">' . $multipleRegister->getPayed() . '</div>';
        $pdf->writeHTMLCell($html, 0, 40, 27, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . utf8_decode(Converter::num2text($multipleRegister->getPayed())) . ' Euro</div>';
        $pdf->writeHTMLCell($html, 0, 60, 39, $html, 0, 1, 0, true, '', false);

        $contactPerson = ' - ';
        if ($multipleRegister->getContactPerson() !== null) {
            $contactPerson = $multipleRegister->getContactPerson()->getContactPerson();
        }
        $html = '<div style="font-size: 1.5em;">' . utf8_decode($contactPerson) . '</div>';
        $pdf->writeHTMLCell($html, 0, 30, 52, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">Heidelaufverein Halle/Saale e.V.</div>';
        $pdf->writeHTMLCell($html, 0, 30, 64, $html, 0, 1, 0, true, '', false);

        $forString = $this->getForString($multipleRegister->getFinishedCompetitionDataList());

        $html = '<div style="font-size: 1em;">' . utf8_decode('Startgebühren ' . $competition->getCompetitionDay()->getTitle() . ' (' . $forString . ')</div>');
        $pdf->writeHTMLCell($html, 0, 30, 76, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">' . $competition->getCompetitionDay()->getDate() . '</div>';
        $pdf->writeHTMLCell($html, 0, 32, 86.5, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.5em;">Halle (Saale)</div>';
        $pdf->writeHTMLCell($html, 0, 32, 97.5, $html, 0, 1, 0, true, '', false);

        $html = '<div style="font-size: 1.3em; font-weight: bold;">Zusammenfassung:</div>';
        $pdf->writeHTMLCell($html, 0, 11, 155, $html, 0, 1, 0, true, '', false);

        $competitionDataList = $multipleRegister->getFinishedCompetitionDataList();

        $firstCompetitionDataList = $competitionDataList;
        $moreCompetitionDataList = [];

        if (count($competitionDataList) >= 10) {
            $dividedCompetitionDataList = array_chunk($competitionDataList, 12);
            $firstCompetitionDataList = $dividedCompetitionDataList[0];

            $counter = 0;
            foreach ($dividedCompetitionDataList as $competitionDataList) {
                if ($counter !== 0) {
                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $moreCompetitionDataList = array_merge($moreCompetitionDataList, $competitionDataList);
                }

                $counter++;
            }
        }

        $tbl = $this->getTableForReceipt($firstCompetitionDataList);
        $pdf->writeHTMLCell($tbl, 0, 11, 165, $tbl, 0, 1, 0, true, '', false);

        $html = '<div style="border-top: 1px solid black; width: 100%; height: 2px;"></div>';
        $pdf->writeHTMLCell($html, 0, 11, 250, $html, 0, 1, 0, true, '', false);

        $html = '<div style="width: 300px;"><div ><a href="https://www.heidelaufserie.de">www.heidelaufserie.de</a></div><div><a href="https://www.facebook.com/heidelaufserie">www.facebook.com/heidelaufserie</a></div></div>';
        $pdf->writeHTMLCell($html, 0, 11, 250, $html, 0, 1, 0, true, '', false);

        $html = '<div><p><b>Heidelaufverein Halle/Saale e.V.</b><br/>Am Heidebad 10<br/>06126 Halle<br/>Tel.: 0345 / 13 50 68 64<br/>Vereinsregisternr. Stendal: 4441<br/>Email: <a href="mailto:info@heidelaufserie.de">info@heidelaufserie.de</a></p></div>';
        $pdf->writeHTMLCell($html, 0, 120, 245, $html, 0, 1, 0, true, '', false);

        if (empty($moreCompetitionDataList) === false) {
            $pdf->AddPage();
            $pdf->setPage($pdf->getPage());

            $tbl = $this->getTableForReceipt($moreCompetitionDataList);
            $pdf->writeHTMLCell($tbl, 0, 11, 11, $tbl, 0, 1, 0, true, '', false);

            $html = '<div style="border-top: 1px solid black; width: 100%; height: 2px;"></div>';
            $pdf->writeHTMLCell($html, 0, 11, 250, $html, 0, 1, 0, true, '', false);

            $html = '<div style="width: 300px;"><div ><a href="https://www.heidelaufserie.de">www.heidelaufserie.de</a></div><div><a href="https://www.facebook.com/heidelaufserie">www.facebook.com/heidelaufserie</a></div></div>';
            $pdf->writeHTMLCell($html, 0, 11, 250, $html, 0, 1, 0, true, '', false);

            $html = '<div><p><b>Heidelaufverein Halle/Saale e.V.</b><br/>Am Heidebad 10<br/>06126 Halle<br/>Tel.: 0345 / 13 50 68 64<br/>Vereinsregisternr. Stendal: 4441<br/>Email: <a href="mailto:info@heidelaufserie.de">info@heidelaufserie.de</a></p></div>';
            $pdf->writeHTMLCell($html, 0, 120, 245, $html, 0, 1, 0, true, '', false);

            $pdf->lastPage();
        }

        return $pdf;
    }


    /**
     * @param CompetitionDataCollection $competitionDataCollection
     * @param CompetitionService        $competitionService
     * @param Date                      $date
     * @param CompetitionDataService    $competitionDataService
     *
     * @return MYPDF|null
     */
    public function getMemberListPdf(CompetitionDataCollection $competitionDataCollection, CompetitionService $competitionService, Date $date, CompetitionDataService $competitionDataService, MultipleRegisterService $multipleRegisterService): ?MYPDF
    {
        $allCompetitionTypes = $competitionService->getAllCompetitionTypes();

        // create new PDF document
        $pdf = new MYPDF('P', PDF_UNIT, 'A4', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Meldezettel');
        $pdf->SetSubject('Heidelauf Meldezettel');
        $pdf->SetKeywords('Heidelaufserie, Meldezettel');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set the starting point for the page content
        $pdf->setPageMark();

        // Deckblatt mit allen Startnummernbereichen
        $html = '<div style="font-size: 3em; text-align: center; font-weight: bold;">Startnummernbereiche</div>';
        $pdf->writeHTMLCell($html, 0, 5, 10, $html, 0, 1, 0, true, '', false);

        $html = '';

        /** @var MultipleRegisterCollection $multipleRegisterCollection */
        $multipleRegisterCollection = $multipleRegisterService->getActiveMultipleRegistersByDate($date, true);
        $maxMultipleRegisterStartNumber = $multipleRegisterCollection->getMaximalStartNumber();
        if ($maxMultipleRegisterStartNumber !== null) {
            $html .= '<div style="font-size: 2em; text-align: left; margin-bottom: 8em;">Sammelmeldungen bis Stnr. ' . $maxMultipleRegisterStartNumber . '</div>';
        }

        /** @var CompetitionType $competitionType */
        foreach ($allCompetitionTypes as $competitionType) {
            $competition = $competitionService->getCompetitionByDateAndCompetitionTypeId($date, $competitionType->getCompetitionTypeId());

            if ($competition === null) {
                continue;
            }

            $minStartNumber = $competition->getMinimalStartNumber();
            $maxStartNumber = $competition->getMaximalStartNumber();

            if ($minStartNumber === null || $maxStartNumber === null) {
                continue;
            }

            $html .= '<div style="font-size: 2em; text-align: left; margin-bottom: 8em;">' . $competition->getCompetitionType()->getCompetitionName() . ' von Stnr. ' . $minStartNumber . ' bis ' . $maxStartNumber . '</div>';
        }

        $nextFreeStartNumber = $competitionDataService->getNextFreeStartNumberByDate($date);

        if ($nextFreeStartNumber !== null) {
            $html .= '<div style="font-size: 2em; text-align: left; margin-bottom: 8em;">Nachmeldungen ab ' . $nextFreeStartNumber->getStartNumber() . '</div>';
        }
        $pdf->writeHTMLCell($html, 0, 20, 55, $html, 0, 1, 0, true, '', false);

        $pdf->AddPage();
        $pdf->setPage($pdf->getPage());

        $firstPage = true;

        /** @var CompetitionType $competitionType */
        foreach ($allCompetitionTypes as $competitionType) {
            $html = '<div style="font-size: 1.5em; text-align: center">Startnummerliste ' . $competitionType->getCompetitionName() . '</div>';
            $pdf->writeHTMLCell($html, 0, 5, 10, $html, 0, 1, 0, true, '', false);

            $competitionDataArray = $competitionDataCollection->getNameSortedNonPermanentStarterCompetitionDataByCompetitionTypeId($competitionType->getCompetitionTypeId());
            $chunk = array_chunk($competitionDataArray, 24);

            foreach ($chunk as $arrayChunk) {
                $tbl = '<table cellspacing="0" cellpadding="1" border="0">';
                $tbl .= '<tr>';
                $tbl .= '<td width="50" height="36"><span style="font-weight: bold;">Stnr.</span></td>';
                $tbl .= '<td width="120" style="font-weight: bold;">Name</td>';
                $tbl .= '<td width="120" style="font-weight: bold;">Vorname</td>';
                $tbl .= '<td width="150" style="font-weight: bold;">Verein/Ort</td>';
                $tbl .= '<td width="75" style="font-weight: bold;">AK</td>';
                $tbl .= '<td width="50" style="font-weight: bold;">JG</td>';
                $tbl .= '<td width="75" style="font-weight: bold;" align="center">Urkunde</td>';
                $tbl .= '<td width="75" style="font-weight: bold;" align="center">Kosten</td>';
                $tbl .= '</tr>';

                $background = '';
                /** @var CompetitionData $competitionData */
                foreach ($arrayChunk as $competitionData) {
                    $runner = $competitionData->getRunner();

                    $clubName = '';
                    if ($competitionData->getClub() !== null) {
                        $clubName = $competitionData->getClub()->getClubName()->getClubName();
                    }

                    $certificate = '';
                    if ($competitionData->isCertificate() === true) {
                        $certificate = 'x';
                    }

                    $costs = $competitionData->getToPay()->toString() . 'EUR';
                    if ($competitionData->getMultipleRegisterId() !== null) {
                        $costs = 'S';
                    }

                    if ($background === '') {
                        $background = 'background-color: grey;';
                    } else {
                        $background = '';
                    }

                    $tbl .= '<tr style="' . $background . ' vertical-align: bottom;">';

                    $tbl .= '<td height="36" style="vertical-align: bottom;">' . $competitionData->getStartNumber() . '</td>';
                    $tbl .= '<td style="vertical-align: bottom;">' . utf8_decode($runner->getSurname()->getName()) . '</td>';
                    $tbl .= '<td style="vertical-align: bottom;">' . utf8_decode($runner->getFirstname()->getName()) . '</td>';
                    $tbl .= '<td style="vertical-align: bottom;">' . utf8_decode($clubName) . '</td>';
                    $tbl .= '<td style="vertical-align: bottom;">' . $runner->getAgeGroup()->getAgeGroup() . '</td>';
                    $tbl .= '<td style="vertical-align: bottom;">' . $runner->getAgeGroup()->getBirthYear() . '</td>';
                    $tbl .= '<td align="center" style="vertical-align: bottom;">' . $certificate . '</td>';
                    $tbl .= '<td align="center" style="vertical-align: bottom;">' . $costs . '</td>';

                    $tbl .= '</tr>';
                }


                if ($firstPage === true) {
                    $firstPage = false;
                }

                $tbl .= '</table>';

                $pdf->writeHTMLCell($tbl, 0, 5, 20, $tbl, 0, 1, 0, true, '', false);

                $pdf->AddPage();
                $pdf->setPage($pdf->getPage());
            }
        }

        $lastPage = $pdf->getPage();
        $pdf->deletePage($lastPage);

        return $pdf;
    }

    /**
     * @param MultipleRegisterCollection $multipleRegisterCollection
     * @return null|MYPDF
     */
    public function getMultipleRegisterStarterListPdf(MultipleRegisterCollection $multipleRegisterCollection): ?MYPDF
    {
        // create new PDF document
        // $pdf = new MYPDF('L', PDF_UNIT, 'A5', false, 'ISO-8859-1', false);
        $pdf = new MYPDF('L', PDF_UNIT, 'A4', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Meldezettel');
        $pdf->SetSubject('Heidelauf Meldezettel');
        $pdf->SetKeywords('Heidelaufserie, Meldezettel');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 2, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(true, 2);

        // set the starting point for the page content
        $pdf->setPageMark();

        $tbl = '<table cellspacing="0" cellpadding="2" border="1" style="border-collapse:collapse">';
        $tbl .= '<tr>';
        $tbl .= '<td align="center" width="50" style="font-weight: bold;">Geld</td>';
        $tbl .= '<td align="center" width="50" style="font-weight: bold;">Stnr.</td>';
        $tbl .= '<td width="200" style="font-weight: bold;">Name</td>';
        $tbl .= '<td width="230" style="font-weight: bold;">Verein</td>';
        $tbl .= '<td align="center" width="40" style="font-weight: bold;">JG</td>';
        $tbl .= '<td width="150" style="font-weight: bold;">Strecke</td>';
        $tbl .= '<td width="200" style="font-weight: bold;">Sammelanmeldung</td>';
        $tbl .= '<td align="center" width="50" style="font-weight: bold;">Urk.</td>';
        $tbl .= '<td align="center" width="50" style="font-weight: bold;">Ges.</td>';
        $tbl .= '</tr>';

        /** @var MultipleRegister $multipleRegister */
        foreach ($multipleRegisterCollection->getNameSortedMultipleRegisterArray() as $multipleRegister) {
            if ($multipleRegister->getContactPerson() === null || $multipleRegister->getToPay() === null) {
                continue;
            }

            $competitionDataList = $multipleRegister->getCompetitionDataList();
            $countList = count($competitionDataList);
            $counter = 0;

            /** @var CompetitionData $competitionData */
            foreach ($competitionDataList as $competitionData) {
                $lastRowStyle = '';
                $counter++;
                if ($counter === $countList) {
                    $lastRowStyle = 'border-bottom: 3px solid black; border-right: 1px solid black;';
                }
                $runner = $competitionData->getRunner();

                $startNumber = '';
                if ($competitionData->getStartNumber() !== null) {
                    $startNumber = $competitionData->getStartNumber()->getStartNumber();
                }

                $club = '';
                if ($competitionData->getClub() !== null) {
                    $club = substr($competitionData->getClub()->getClubName()->getClubName(), 0, 30);
                }

                $certificate = 'nein';
                if ($competitionData->isCertificate() === true) {
                    $certificate = 'ja';
                }

                $tbl .= '<tr>';
                $tbl .= '<td align="center" style="' . $lastRowStyle . '">' . $competitionData->getToPay()->toString() . '</td>';
                $tbl .= '<td align="center" style="font-weight: bold; ' . $lastRowStyle . '">' . $startNumber . '</td>';
                $tbl .= '<td style="' . $lastRowStyle . '">' . utf8_decode($runner->getSurname()->getName()) . ', ' . utf8_decode($runner->getFirstname()->getName()) . '</td>';
                $tbl .= '<td style="' . $lastRowStyle . '">' . utf8_decode($club) . '</td>';
                $tbl .= '<td align="center" style="' . $lastRowStyle . '">' . $runner->getAgeGroup()->getBirthYear()->getShortBirthYear() . '</td>';
                $tbl .= '<td style="' . $lastRowStyle . '">' . utf8_decode($competitionData->getCompetition()->getCompetitionType()->getCompetitionName()->getCompetitionName()) . '</td>';
                $tbl .= '<td style="' . $lastRowStyle . '">' . utf8_decode($multipleRegister->getContactPerson()->getReverseName(true)) . '</td>';
                $tbl .= '<td align="center" style="' . $lastRowStyle . '">' . $certificate . '</td>';
                $tbl .= '<td align="center" style="font-weight: bold; ' . $lastRowStyle . '">' . $multipleRegister->getToPay()->toString() . '</td>';
                $tbl .= '</tr > ';
            }
        }

        $tbl .= '</table>';

        $pdf->writeHTMLCell($tbl, 0, 3, 2, $tbl, 0, 1, 0, true, '', false);

        return $pdf;
    }

    /**
     * @param MultipleRegisterCollection $multipleRegisterCollection
     *
     * @return null|MYPDF
     */
    public function getMultipleRegisterPickUpPdf(MultipleRegisterCollection $multipleRegisterCollection): ?MYPDF
    {
        // create new PDF document
        // $pdf = new MYPDF('L', PDF_UNIT, 'A5', false, 'ISO-8859-1', false);
        $pdf = new MYPDF('P', PDF_UNIT, 'A4', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Meldezettel');
        $pdf->SetSubject('Heidelauf Meldezettel');
        $pdf->SetKeywords('Heidelaufserie, Meldezettel');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        // disable auto-page-break
        $pdf->SetAutoPageBreak(false);

        // set the starting point for the page content
        $pdf->setPageMark();

        $firstPage = true;
        /** @var MultipleRegister $multipleRegister */
        foreach ($multipleRegisterCollection->getNameSortedMultipleRegisterArray() as $multipleRegister) {
            if ($multipleRegister->isActive() === false || $multipleRegister->getContactPerson() === null || $multipleRegister->getToPay() === null) {
                continue;
            }

            if ($firstPage === false) {
                $pdf->AddPage();
                $pdf->setPage($pdf->getPage());
            } else {
                $firstPage = false;
            }

            $html = '<h3>Abholer Sammelmeldung:</h3>';
            $pdf->writeHTMLCell($html, 0, 10, 10, $html, 0, 1, 0, true, '', false);

            $html = '<h3>' . utf8_decode($multipleRegister->getContactPerson()->getContactPerson()) . '</h3>';
            $pdf->writeHTMLCell($html, 0, 80, 10, $html, 0, 1, 0, true, '', false);

            $html = '<h3>Gesamtbetrag:</h3>';
            $pdf->writeHTMLCell($html, 0, 36.5, 20, $html, 0, 1, 0, true, '', false);

            $html = '<h3>' . $multipleRegister->getToPay()->toString() . ' EUR</h3>';
            $pdf->writeHTMLCell($html, 0, 80, 20, $html, 0, 1, 0, true, '', false);

            $tbl = '<table cellspacing="0" cellpadding="1" border="0">';
            $tbl .= '<tr>';
            $tbl .= '<td width="50" height="25" style="font-weight: bold;">Stnr.</td>';
            $tbl .= '<td width="180" style="font-weight: bold;">Name</td>';
            $tbl .= '<td width="75" style="font-weight: bold;" align="center">Startgeld</td>';
            $tbl .= '<td width="150"></td>';
            $tbl .= '</tr>';

            /** @var CompetitionData $competitionData */
            foreach ($multipleRegister->getCompetitionDataList() as $competitionData) {
                $startNumber = '';
                if ($competitionData->getStartNumber() !== null) {
                    $startNumber = $competitionData->getStartNumber()->getStartNumber();
                }

                $certificate = '';
                if ($competitionData->isCertificate() === true) {
                    $certificate = '(einschl. Urkunde)';
                }

            $tbl .= '<tr>';
            $tbl .= '<td>' . $startNumber . '</td >';
                $tbl .= '<td>' . utf8_decode($competitionData->getRunner()->getFullName()) . '</td>';
                $tbl .= '<td align="center">' . $competitionData->getToPay()->toString() . ' EUR</td>';
                $tbl .= '<td>' . $certificate . '</td>';
                $tbl .= '</tr >';
            }

            $tbl .= '</table > ';

            $pdf->writeHTMLCell($tbl, 0, 10, 30, $tbl, 0, 1, 0, true, '', false);
        }

        return $pdf;
    }

    /**
     * @param StartNumberCollection $startNumberCollection
     *
     * @return MYPDF|null
     */
    public function getAvailableStartNumberList(StartNumberCollection $startNumberCollection): ?MYPDF
    {
        // create new PDF document
        $pdf = new MYPDF('P', PDF_UNIT, 'A4', false, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Heidelaufserie');
        $pdf->SetTitle('Quittung');
        $pdf->SetSubject('Sammelmeldungen');
        $pdf->SetKeywords('Heidelaufserie, Quittung, Sammelmeldungen');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // add a page
        $pdf->AddPage();

        // -- set new background ---

        $html = '<table><tr>';

        $counter = 0;
        /** @var StartNumber $startNumber */
        foreach ($startNumberCollection->getStartNumberArray() as $startNumber) {
            $html .= '<td style="border: 1px solid black; width: 40px; height: 24px; text-align: center; padding: 3px;">' . $startNumber->getStartNumber()->getStartNumber() . '</td><td style="border: 1px solid black; width: 40px; height: 24px; padding: 3px;"> </td>';
            $counter++;

            if ($counter % 8 === 0) {
                $counter = 0;
                $html .= '</tr> <tr>';
            }
        }

        $html .= '</tr></table>';

        $pdf->writeHTMLCell(10, 10, 10, 6, $html, 0, 0, 0, true, '', false);

        return $pdf;
    }

    /**
     * @param array $competitionDataList
     *
     * @return string
     */
    protected function getTableForReceipt(array $competitionDataList): string
    {
        $tbl = '<table cellspacing="0" cellpadding="1" border="0">';

        /** @var CompetitionData $competitionData */
        foreach ($competitionDataList as $competitionData) {
            $runner = $competitionData->getRunner();
            if ($runner === null) {
                continue;
            }

            $certificate = '';
            if ($competitionData->isCertificate() === true) {
                $certificate = '(inkl. 1,00 EUR für Urkunde)';
            }

            if ($competitionData->isFinished() === true) {
                $startNumber = ' - ';
                if ($competitionData->getStartNumber() !== null) {
                    $startNumber = $competitionData->getStartNumber()->getStartNumber();
                }
                $tbl .= '<tr>
                    <td width="75">Stnr. ' . $startNumber . '</td>
                    <td width="175">' . utf8_decode($runner->getSurname()->getName()) . ', ' . utf8_decode($runner->getFirstname()->getName()) . '</td>
                    <td width="75">' . $runner->getAgeGroup()->getAgeGroup() . '</td>
                    <td width="75">' . $competitionData->getToPay()->getPrice() . ' EUR</td>                   
                    <td width="250">' . utf8_decode($certificate) . '</td>
                </tr>';
            }
        }

        $tbl .= '</table>';

        return $tbl;
    }

    /**
     * @param array $competitionDataList
     *
     * @return string
     */
    protected function getForString(array $competitionDataList): string
    {
        $categories = [
            'K' => 0,
            'U16' => 0,
            'U20' => 0,
            'adult' => 0,
            'certificate' => 0
        ];

        $forString = '';

        /** @var CompetitionData $competitionData */
        foreach ($competitionDataList as $competitionData) {
            /** @var Runner $runner */
            $runner = $competitionData->getRunner();

            if ($runner === null || $competitionData->isFinished() === false) {
                continue;
            }

            if ($competitionData->isCertificate() === true) {
                $categories['certificate']++;
            }

            switch ($runner->getAgeGroup()->getAgeGroup()) {
                case 'K':
                    $categories['K']++;
                    break;
                case 'WJ U16':
                case 'MJ U16':
                    $categories['U16']++;
                    break;
                case 'WJ U20':
                case 'MJ U20':
                    $categories['U20']++;
                    break;
                default:
                    $categories['adult']++;
                    break;
            }
        }

        foreach ($categories as $key => $category) {
            if ($category === 0) {
                continue;
            }

            if (empty($forString) === false) {
                $forString .= ' / ';
            }

            if ($key === 'K') {
                $forString .= $category . 'x Kdr.';
            } elseif ($key === 'U16') {
                $forString .= $category . 'x Jgd. U16';
            } elseif ($key === 'U20') {
                $forString .= $category . 'x Jgd. U20';
            } elseif ($key === 'adult') {
                $forString .= $category . 'x Erw.';
            } elseif ($key === 'certificate') {
                if ($category === 1) {
                    $forString .= $category . 'x Urkunde';
                } else {
                    $forString .= $category . 'x Urkunden';
                }
            }
        }

        return $forString;
    }
}