<?php 
include("read.php");

$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['nim'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM presensi WHERE nim = ?');
    $stmt->execute([$_GET['nim']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Presensi doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['command'])) {
        if ($_GET['command'] === 'delete') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM presensi WHERE nim = ?');
            $stmt->execute([$_GET['nim']]);
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<script>
    window.location = 'read.php';
</script>


