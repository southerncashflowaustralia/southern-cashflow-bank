<?php
require_once __DIR__ . "/../vendor/tcpdf/tcpdf.php";

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Southern Cashflow Finance');
$pdf->SetAuthor('Southern Cashflow Finance');
$pdf->SetTitle('Test PDF');
$pdf->SetSubject('PDF Generation Test');
$pdf->SetKeywords('TCPDF, PDF, test');

// Set default header data
$pdf->SetHeaderData('', 0, 'Southern Cashflow Finance', 'Test PDF Generation');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Add a page
$pdf->AddPage();

// Set content
$html = '<h1>Welcome to Southern Cashflow Finance</h1>';
$html .= '<p>This is a test PDF generated using TCPDF.</p>';

// Output HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF
$pdf->Output('test.pdf', 'I'); // I = Inline in browser
?>