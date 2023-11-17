<?php
include 'function.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Prepare the SQL statement to get all records from our contacts table
$stmt = $pdo->prepare('SELECT * FROM presensi ORDER BY nim');
$stmt->execute();

// Fetch all records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_contacts = $pdo->query('SELECT COUNT(*) FROM presensi')->fetchColumn();

?>

<?= template_header('Admin', 'read.css') ?>
<br>
<div class="content read">
    <h2>Read Presensi</h2>
    <a href="index.php" class="create-contact">Create Presensi</a>
    <table>
        <thead>
            <tr>
                <td>NIM</td>
                <td>Name</td>
                <td>Email</td>
                <td>Kehadiran</td>
                <td>Bukti Kehadiran</td>
                <td>Waktu Kehadiran</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td><?= $contact['nim'] ?></td>
                    <td><?= $contact['nama'] ?></td>
                    <td><?= $contact['email'] ?></td>
                    <td><?= $contact['kehadiran'] ?></td>
                    <td><button id="tampilkan" onclick="tampilkangambar(`<?php echo 'uploads/' . $contact['bukti_kehadiran']?>`)">tampilkan <i class="fa-solid fa-arrow-up-right-from-square"></i></button></td>
                    <td><?= $contact['waktu_ditambahkan'] ?></td>
                    <td class="actions">
                        <a href="update.php?nim=<?= $contact['nim'] ?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                        <a onclick="document.getElementById('id01').style.display='block'" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                        <div id="id01" class="modal">
                            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
                            <form class="modal-content" action="delete.php" method="get">
                                <input type="hidden" name="nim" value="<?= $contact['nim'] ?>">
                                <input type="hidden" name="command" value="delete">
                                <div class="container">
                                    <h1>Delete Data #<?= $contact['nim'] ?></h1>
                                    <p>Apa anda yakin menghapus ingin menghapus data tersebut?</p>

                                    <div class="clearfix">
                                        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                                        <button type="submit" onclick="document.getElementById('id01').style.display='none'" class="deletebtn">Delete</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    let container = document.createElement("div");
    function tampilkangambar(imageUrl) {
        let body = document.getElementsByTagName("body")[0];
        container.id = "container-image";
        container.onclick = removeimage;
        body.appendChild(container);
        container.innerHTML = "<img class='pop-image' src='" + imageUrl + "' alt='Gambar' />";
    }

    function removeimage() {
        container.remove();
        
    }
    // Get the modal
    var modal = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

<?= template_footer() ?>