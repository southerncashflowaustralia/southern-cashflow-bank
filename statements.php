<?php
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../vendor/tcpdf/tcpdf.php";

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Southern Cashflow Finance');
$pdf->SetTitle('Account Statement');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(15, 20, 15);

// Add page
$pdf->AddPage();

// Bank statement content
$html = '
<h2 style="text-align:center;">Southern Cashflow Finance</h2>
<p style="text-align:center;">Official Bank Account Statement</p>
<hr>

<p><strong>Account Holder:</strong> Demo Customer</p>
<p><strong>Account Number:</strong> 1234567890</p>
<p><strong>Statement Period:</strong> Last 30 Days</p>

<br>

<table border="1" cellpadding="6" width="100%">
<tr style="background-color:#f2f2f2;">
<th>Date</th>
<th>Description</th>
<th>Debit</th>
<th>Credit</th>
<th>Balance</th>
</tr>

<tr>
<td>2026-01-01</td>
<td>Opening Balance</td>
<td>-</td>
<td>AUD 0.00</td>
<td>AUD 0.00</td>
</tr>

<tr>
<td>2026-01-05</td>
<td>Cash Deposit</td>
<td>-</td>
<td>AUD 1,000.00</td>
<td>AUD 1,000.00</td>
</tr>

<tr>
<td>2026-01-12</td>
<td>Online Transfer</td>
<td>AUD 250.00</td>
<td>-</td>
<td>AUD 750.00</td>
</tr>

</table>

<br><br>
<p style="font-size:11px;">
This statement is generated electronically by Southern Cashflow Finance and is valid without a signature.
</p>
';

// Write content
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('Southern_Cashflow_Statement.pdf', 'I');
exit;