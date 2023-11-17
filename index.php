<?php
include("function.php");

$pdo = pdo_connect_mysql();
$msg = '';

// Check if POST data is not empty
if (!empty($_POST)) {
    // Set-up the variables that are going to be inserted
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
    // Insert new record into the presensi table
    $stmt = $pdo->prepare('INSERT INTO presensi VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$nim, $name, $email, $kehadiran, $buktiKehadiran, $created->format("Y-m-d H:i:s")]);
    // Output message
    $msg = 'Created Successfully!';
}
?>


<?php template_header('create','style.css'); ?>

    <div class="container">
      <header>
        <h1 id="title">ABSENSI WEBINAR</h1>
        <p id="description">
          Terimakasih telah meluangkan waktu untuk mengikuti webinar yang kami
          berikan
        </p>
      </header>

      <form id="survey-form" action="index.php" enctype="multipart/form-data" method="post">
        <div class="form-group">
          <label id="name-label" for="name">Nama</label>
          <input id="name" name="nama" type="text" placeholder="Masukkan nama anda" required />
        </div>
        <div class="form-group">
          <label id="email-label" for="email">Email</label>
          <input id="email" name="email" type="email" placeholder="Masukkan email anda" required />
        </div>
        <div class="form-group">
          <label id="number-label" for="nim">NIM</label>
          <input id="nim" name="nim" type="number" placeholder="NIM" required />
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
            <input id="bukti-kehadiran" name="bukti-kehadiran" type="file" accept=".jpg, .jpeg, .png" required />
          </div>
          <div>
            <button type="submit">Submit</button>
          </div>

          <script>
            navbaranimate();
          </script>
      </form>
    </div>

    <?php template_footer() ?>
