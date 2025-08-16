<?php
// File untuk menangani data foto
header('Content-Type: application/json');

// Konfigurasi database (sesuaikan dengan kebutuhan)
$host = 'localhost';
$dbname = 'kecamatan_ngluyu_db';
$username = 'root';
$password = '';

// Handle GET request untuk admin panel
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    try {
        // Coba koneksi ke database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES utf8");
        
        // Ambil data dari database
        $stmt = $pdo->query("SELECT * FROM foto ORDER BY tanggal DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $response = [
            'success' => true,
            'data' => $data
        ];
        
    } catch(PDOException $e) {
        // Jika database tidak tersedia, ambil dari file JSON
        $jsonFile = 'foto.json';
        
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true);
            $response = [
                'success' => true,
                'data' => $data ?: []
            ];
        } else {
            $response = [
                'success' => true,
                'data' => []
            ];
        }
    }
    
    echo json_encode($response);
    exit;
}

// Handle POST request untuk foto baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Koneksi ke database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Set charset
        $pdo->exec("SET NAMES utf8");
        
    } catch(PDOException $e) {
        // Jika database tidak tersedia, simpan ke file JSON
        $response = [
            'status' => 'success',
            'message' => 'Foto berhasil disimpan ke file lokal',
            'data' => []
        ];
        
        // Simpan data ke file JSON
        $fotoData = [];
        
        if (file_exists('foto.json')) {
            $fotoData = json_decode(file_get_contents('foto.json'), true);
        }
        
        // Ambil data dari form
        $newFoto = [
            'id' => uniqid(),
            'nama' => $_POST['nama'] ?? '',
            'nik' => $_POST['nik'] ?? '',
            'alamat' => $_POST['alamat'] ?? '',
            'telepon' => $_POST['telepon'] ?? '',
            'judul' => $_POST['judul'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'foto_path' => '',
            'tanggal' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];
        
        // Handle file upload foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/foto/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid() . '_' . $_FILES['foto']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                $newFoto['foto_path'] = $fileName;
            }
        }
        
        // Tambahkan ke array data
        $fotoData[] = $newFoto;
        
        // Simpan ke file JSON
        file_put_contents('foto.json', json_encode($fotoData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $response['data'] = [
            'id' => $newFoto['id'],
            'nama' => $newFoto['nama'],
            'judul' => $newFoto['judul'],
            'tanggal' => $newFoto['tanggal']
        ];
        
        echo json_encode($response);
        exit;
    }
}

// Jika database tersedia, simpan ke database
try {
    // Buat tabel jika belum ada
    $sql = "CREATE TABLE IF NOT EXISTS foto (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        nik VARCHAR(16) NOT NULL,
        alamat TEXT NOT NULL,
        telepon VARCHAR(20) NOT NULL,
        judul VARCHAR(255) NOT NULL,
        deskripsi TEXT NOT NULL,
        foto_path VARCHAR(255) NOT NULL,
        tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Prepare statement untuk insert
    $stmt = $pdo->prepare("INSERT INTO foto (nama, nik, alamat, telepon, judul, deskripsi, foto_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $fotoPath = '';
    
    // Handle file upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/foto/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . $_FILES['foto']['name'];
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
            $fotoPath = $fileName;
        }
    }
    
    // Execute statement
    $stmt->execute([
        $_POST['nama'],
        $_POST['nik'],
        $_POST['alamat'],
        $_POST['telepon'],
        $_POST['judul'],
        $_POST['deskripsi'],
        $fotoPath
    ]);
    
    $response = [
        'status' => 'success',
        'message' => 'Foto berhasil disimpan ke database',
        'data' => [
            'id' => $pdo->lastInsertId(),
            'nama' => $_POST['nama'],
            'judul' => $_POST['judul'],
            'tanggal' => date('Y-m-d H:i:s')
        ]
    ];
    
    echo json_encode($response);
    
} catch(PDOException $e) {
    $response = [
        'status' => 'error',
        'message' => 'Gagal menyimpan foto: ' . $e->getMessage()
    ];
    
    echo json_encode($response);
}
?>
