<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) exit;

// Process pending transfers automatically
include __DIR__ . "/process-transfers.php";

$user_id = $_SESSION['user_id'];

// Fetch last 150 transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 150");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($transactions)) {
    echo '<table>
            <tr>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount (AUD)</th>
                <th>Balance (AUD)</th>
                <th>Status</th>
            </tr>';
    foreach ($transactions as $tx) {
        $balance = isset($tx['balance']) ? number_format($tx['balance'], 2) : number_format($_SESSION['balance'] ?? 0, 2);
        $status = isset($tx['status']) ? htmlspecialchars($tx['status']) : 'Completed';
        echo '<tr>
                <td>'.htmlspecialchars($tx['created_at']).'</td>
                <td class="'.htmlspecialchars($tx['type']).'">'.ucfirst(htmlspecialchars($tx['type'])).'</td>
                <td>'.htmlspecialchars($tx['description']).'</td>
                <td class="'.htmlspecialchars($tx['type']).'">'.number_format($tx['amount'],2).'</td>
                <td>'.$balance.'</td>
                <td>'.$status.'</td>
              </tr>';
    }
    echo '</table>';
} else {
    echo '<p>No recent transactions.</p>';
}
?>
