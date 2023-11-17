<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['nim'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $nim = isset($_POST['nim']) ? $_POST['nim'] : NULL;
        $name = isset($_POST['nama']) ? $_POST['nama'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $kehadiran = isset($_POST['kehadiran']) ? $_POST['kehadiran'] : '';

        $uploadDir = 'uploads/'; // Direktori tempat menyimpan gambar
        $uploadFile = $uploadDir . basename($_FILES['bukti-kehadiran']['name']);
    
        // Memeriksa apakah file gambar yang diunggah adalah gambar yang valid
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png');
    
        if (!in_array($imageFileType, $allowedExtensions)) {
            echo "Hanya gambar dengan format JPG, JPEG, PNG, dan GIF yang diizinkan.";
        } else {
            if (move_uploaded_file($_FILES['bukti-kehadiran']['tmp_name'], $uploadFile)) {
                // Selanjutnya, Anda dapat menyimpan $uploadFile ke database atau melakukan tindakan lain yang diperlukan.
            } else {
                echo "Gagal mengunggah gambar.";
            }
        }
    
        $buktiKehadiran = isset($_FILES['bukti-kehadiran']['name']) ? $_FILES['bukti-kehadiran']['name'] : '';
        date_default_timezone_set('Asia/Jakarta');
        $created = new DateTime();
        // Update the record
        $stmt = $pdo->prepare('UPDATE presensi SET nim = ?, nama = ?, email = ?, kehadiran = ?, bukti_kehadiran = ?, waktu_ditambahkan = ? WHERE nim = ?');
        if ($stmt->execute([$nim, $name, $email, $kehadiran, $buktiKehadiran, $created->format("Y-m-d H:i:s"), $_GET['nim']])) {
            $msg = 'Updated Successfully!';
        } else {
            $msg = 'Failed to update data.';
            // Jika ingin mengetahui pesan kesalahan SQL, Anda dapat menggunakan $stmt->errorInfo()
            // echo 'SQL Error: ' . $stmt->errorInfo()[2];
        }
        
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM presensi WHERE nim = ?');
    $stmt->execute([$_GET['nim']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?php template_header('create', 'style.css'); ?>

<div class="container">
    <header>
        <h1 id="title">ABSENSI WEBINAR</h1>
        <p id="description">
            mohon absen sebelum tenggatnya
        </p>
        <h2>Update #<?= $contact['nim'] ?></h2>
    </header>

    <form id="survey-form" action="update.php?nim=<?= $contact['nim'] ?>" enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label id="name-label" for="name">Nama</label>
            <input id="name" name="nama" type="text" placeholder="Masukkan nama anda" value="<?= $contact['nama'] ?>" required />
        </div>
        <div class="form-group">
            <label id="email-label" for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="Masukkan email anda" value="<?= $contact['email'] ?>" required />
        </div>
        <div class="form-group">
            <label id="number-label" for="nim">NIM</label>
            <input id="nim" name="nim" type="number" placeholder="NIM" value="<?= $contact['nim'] ?>" required />
            <div class="form-group">
                <p>Kehadiran</p>
                <label for="hadir">
                    <input type="radio" id="hadir" name="kehadiran" value="hadir"> Hadir
                </label>
                <label for="izin">
                    <input type="radio" id="izin" name="kehadiran" value="izin"> izin
                </label>
            </div>
            <div class="form-group">
                <label id="bukti-kehadiran-label" for="nim">Bukti Kehadiran</label>
                <input id="bukti-kehadiran" name="bukti-kehadiran" type="file" accept=".jpg, .jpeg, .png" value="<?= $contact['bukti_kehadiran']; ?>" required />
            </div>
            <div>
                <button type="submit">Update</button>
            </div>

            <script>
                navbaranimate();
                var kehadiran = document.querySelectorAll('input[name="kehadiran"]');
                kehadiran.forEach(function(kh) {
                    if (kh.value == '<?= $contact['kehadiran'] ?>') {
                        kh.checked = true;
                    }
                });
            </script>
        </div>
    </form>
</div>

<?php template_footer() ?>