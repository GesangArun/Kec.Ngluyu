<?php
// File untuk menangani data pengaduan - Tanpa Database
header('Content-Type: application/json');

// Handle POST request untuk pengaduan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ambil data dari form
        $nama = $_POST['nama'] ?? 'Tidak Diketahui';
        $nik = $_POST['nik'] ?? 'Tidak Diketahui';
        $tempat_lahir = $_POST['tempat_lahir'] ?? 'Tidak Diketahui';
        $tanggal_lahir = $_POST['tanggal_lahir'] ?? 'Tidak Diketahui';
        $jenis_kelamin = $_POST['jenis_kelamin'] ?? 'Tidak Diketahui';
        $agama = $_POST['agama'] ?? 'Tidak Diketahui';
        $status_perkawinan = $_POST['status_perkawinan'] ?? 'Tidak Diketahui';
        $pekerjaan = $_POST['pekerjaan'] ?? 'Tidak Diketahui';
        $kewarganegaraan = $_POST['kewarganegaraan'] ?? 'Tidak Diketahui';
        $berlaku_hingga = $_POST['berlaku_hingga'] ?? 'Tidak Diketahui';
        $alamat = $_POST['alamat'] ?? 'Tidak Diketahui';
        $rt = $_POST['rt'] ?? 'Tidak Diketahui';
        $rw = $_POST['rw'] ?? 'Tidak Diketahui';
        $kelurahan = $_POST['kelurahan'] ?? 'Tidak Diketahui';
        $jenis_pengaduan = $_POST['jenis_pengaduan'] ?? 'Tidak Diketahui';
        $prioritas = $_POST['prioritas'] ?? 'Tidak Diketahui';
        $judul = $_POST['judul'] ?? 'Tidak Diketahui';
        $deskripsi = $_POST['deskripsi'] ?? 'Tidak Diketahui';
        $lokasi = $_POST['lokasi'] ?? 'Tidak Diketahui';
        $tanggal_kejadian = $_POST['tanggal_kejadian'] ?? 'Tidak Diketahui';
        $telepon = $_POST['telepon'] ?? 'Tidak Diketahui';
        
        // Generate nomor pengaduan
        $nomor_pengaduan = 'P-' . date('Y') . '/' . date('m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        // Generate dokumen pengaduan
        $docContent = generatePengaduanDocument([
            'nama' => $nama,
            'nik' => $nik,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'jenis_kelamin' => $jenis_kelamin,
            'agama' => $agama,
            'status_perkawinan' => $status_perkawinan,
            'pekerjaan' => $pekerjaan,
            'kewarganegaraan' => $kewarganegaraan,
            'berlaku_hingga' => $berlaku_hingga,
            'alamat' => $alamat,
            'rt' => $rt,
            'rw' => $rw,
            'kelurahan' => $kelurahan,
            'jenis_pengaduan' => $jenis_pengaduan,
            'prioritas' => $prioritas,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'lokasi' => $lokasi,
            'tanggal_kejadian' => $tanggal_kejadian,
            'telepon' => $telepon,
            'nomor_pengaduan' => $nomor_pengaduan
        ]);
        
        // Buat folder untuk menyimpan dokumen
        $uploadDir = 'uploads/pengaduan_doc/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Generate nama file
        $fileName = 'Pengaduan_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nama) . '_' . date('Y-m-d_H-i-s') . '.html';
        $filePath = $uploadDir . $fileName;
        
        // Simpan dokumen
        if (file_put_contents($filePath, $docContent)) {
            $response = [
                'status' => 'success',
                'message' => 'Pengaduan berhasil dibuat!',
                'data' => [
                    'id' => uniqid(),
                    'nama' => $nama,
                    'nomor_pengaduan' => $nomor_pengaduan,
                    'doc_file' => $filePath,
                    'doc_download' => $filePath
                ]
            ];
        } else {
            throw new Exception('Gagal menyimpan file dokumen');
        }
        
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Gagal membuat pengaduan: ' . $e->getMessage()
        ];
    }
    
    // Simpan data ke file JSON untuk admin panel
    $pengaduanData = [];
    if (file_exists('pengaduan.json')) {
        $pengaduanData = json_decode(file_get_contents('pengaduan.json'), true);
    }
    
    $pengaduanData[] = [
        'id' => uniqid(),
        'nama' => $nama,
        'nik' => $nik,
        'tempat_lahir' => $tempat_lahir,
        'tanggal_lahir' => $tanggal_lahir,
        'jenis_kelamin' => $jenis_kelamin,
        'agama' => $agama,
        'status_perkawinan' => $status_perkawinan,
        'pekerjaan' => $pekerjaan,
        'kewarganegaraan' => $kewarganegaraan,
        'berlaku_hingga' => $berlaku_hingga,
        'alamat' => $alamat,
        'rt' => $rt,
        'rw' => $rw,
        'kelurahan' => $kelurahan,
        'jenis_pengaduan' => $jenis_pengaduan,
        'prioritas' => $prioritas,
        'judul' => $judul,
        'deskripsi' => $deskripsi,
        'lokasi' => $lokasi,
        'tanggal_kejadian' => $tanggal_kejadian,
        'telepon' => $telepon,
        'nomor_pengaduan' => $nomor_pengaduan,
        'tanggal' => date('Y-m-d H:i:s'),
        'status' => 'pending'
    ];
    
    file_put_contents('pengaduan.json', json_encode($pengaduanData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode($response);
    exit;
}

// Handle GET request untuk admin panel
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    $jsonFile = 'pengaduan.json';
    
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
    
    echo json_encode($response);
    exit;
}

// Fungsi untuk generate dokumen pengaduan
function generatePengaduanDocument($data) {
    $tanggal = date('d F Y');
    $jenis_kelamin_text = $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan';
    $tanggal_lahir_text = date('d F Y', strtotime($data['tanggal_lahir']));
    $berlaku_hingga_text = date('d F Y', strtotime($data['berlaku_hingga']));
    $tanggal_kejadian_text = date('d F Y', strtotime($data['tanggal_kejadian']));
    
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Pengaduan - ' . $data['nama'] . '</title>
        <style>
            @page { size: A4; margin: 2cm; }
            body { 
                font-family: "Times New Roman", serif; 
                margin: 0; padding: 0; 
                line-height: 1.6; font-size: 12pt;
            }
            .header { 
                text-align: center; margin-bottom: 30px; 
                border-bottom: 2px solid #000; padding-bottom: 20px;
            }
            .title { 
                font-size: 16pt; font-weight: bold; 
                margin-bottom: 5px; text-transform: uppercase;
            }
            .subtitle { font-size: 12pt; margin-bottom: 3px; }
            .content { margin: 20px 0; text-align: justify; }
            .nomor-pengaduan { text-align: center; margin: 20px 0; font-weight: bold; }
            .data-table { width: 100%; margin: 20px 0; }
            .data-table td { padding: 5px 10px; vertical-align: top; }
            .data-table td:first-child { width: 200px; font-weight: bold; }
            .barcode-container { 
                text-align: center; margin: 20px 0; 
                padding: 20px; border: 2px solid #000; border-radius: 10px;
            }
            .barcode { 
                font-family: "Courier New", monospace; font-size: 14pt; 
                letter-spacing: 2px; margin: 10px 0;
            }
            .ttd-container { margin-top: 50px; text-align: right; }
            .ttd-box { 
                display: inline-block; width: 200px; text-align: center; 
                border: 1px solid #000; padding: 10px; margin-top: 20px;
            }
            .ttd-image { 
                width: 80px; height: 80px; border: 1px solid #000; 
                margin: 10px auto; display: block; background: #f0f0f0; 
                text-align: center; line-height: 80px; font-size: 10pt; color: #666;
            }
            .stamp { 
                position: absolute; top: 50%; right: 50px; 
                transform: rotate(15deg); opacity: 0.3; 
                font-size: 24pt; color: #ff0000; font-weight: bold;
            }
            .prioritas-badge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 10pt;
                font-weight: bold;
                color: white;
            }
            .prioritas-tinggi { background: #dc3545; }
            .prioritas-sedang { background: #ffc107; color: #000; }
            .prioritas-rendah { background: #28a745; }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="title">PEMERINTAH KABUPATEN NGANJUK</div>
            <div class="title">KECAMATAN NGLUYU</div>
            <div class="subtitle">Jl. Raya Ngluyu No. 123, Ngluyu, Nganjuk</div>
            <div class="subtitle">Telp: (0354) 123456 | Email: kecamatan.ngluyu@nganjukkab.go.id</div>
        </div>
        
        <div class="nomor-pengaduan">
            <div class="title">BUKTI PENGADUAN MASYARAKAT</div>
            <div class="subtitle">Nomor: ' . $data['nomor_pengaduan'] . '</div>
        </div>
        
        <div class="content">
            <p>Yang bertanda tangan di bawah ini:</p>
            <div class="data-table">
                <table>
                    <tr><td>Nama</td><td>: Drs. Ahmad Supriyadi, M.Si</td></tr>
                    <tr><td>NIP</td><td>: 196512341987031001</td></tr>
                    <tr><td>Jabatan</td><td>: Camat Ngluyu</td></tr>
                </table>
            </div>
            
            <p>Dengan ini menerima pengaduan dari:</p>
            <div class="data-table">
                <table>
                    <tr><td>Nama</td><td>: ' . $data['nama'] . '</td></tr>
                    <tr><td>NIK</td><td>: ' . $data['nik'] . '</td></tr>
                    <tr><td>Tempat/Tanggal Lahir</td><td>: ' . $data['tempat_lahir'] . ', ' . $tanggal_lahir_text . '</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: ' . $jenis_kelamin_text . '</td></tr>
                    <tr><td>Agama</td><td>: ' . $data['agama'] . '</td></tr>
                    <tr><td>Status Perkawinan</td><td>: ' . $data['status_perkawinan'] . '</td></tr>
                    <tr><td>Pekerjaan</td><td>: ' . $data['pekerjaan'] . '</td></tr>
                    <tr><td>Kewarganegaraan</td><td>: ' . $data['kewarganegaraan'] . '</td></tr>
                    <tr><td>Alamat</td><td>: ' . $data['alamat'] . '</td></tr>
                    <tr><td>RT/RW</td><td>: ' . $data['rt'] . '/' . $data['rw'] . '</td></tr>
                    <tr><td>Kelurahan/Desa</td><td>: ' . $data['kelurahan'] . '</td></tr>
                </table>
            </div>
            
            <p>Detail Pengaduan:</p>
            <div class="data-table">
                <table>
                    <tr><td>Jenis Pengaduan</td><td>: ' . $data['jenis_pengaduan'] . '</td></tr>
                    <tr><td>Prioritas</td><td>: <span class="prioritas-badge prioritas-' . strtolower($data['prioritas']) . '">' . $data['prioritas'] . '</span></td></tr>
                    <tr><td>Judul</td><td>: ' . $data['judul'] . '</td></tr>
                    <tr><td>Lokasi</td><td>: ' . $data['lokasi'] . '</td></tr>
                    <tr><td>Tanggal Kejadian</td><td>: ' . $tanggal_kejadian_text . '</td></tr>
                    <tr><td>Deskripsi</td><td>: ' . $data['deskripsi'] . '</td></tr>
                </table>
            </div>
            
            <p>Pengaduan ini telah diterima dan akan diproses sesuai dengan prioritas dan ketentuan yang berlaku.</p>
        </div>
        
        <div class="barcode-container">
            <div class="barcode">*' . $data['nomor_pengaduan'] . '*</div>
            <div style="font-size: 10pt; color: #666;">Scan barcode untuk verifikasi</div>
        </div>
        
        <div class="ttd-container">
            <p>Ngluyu, ' . $tanggal . '</p>
            <p>Camat Ngluyu,</p>
            <div class="ttd-box">
                <div class="ttd-image">TTD</div>
                <p><strong>Drs. Ahmad Supriyadi, M.Si</strong></p>
                <p>NIP. 196512341987031001</p>
            </div>
        </div>
        
        <div class="stamp">DITERIMA</div>
    </body>
    </html>';
}
?>
