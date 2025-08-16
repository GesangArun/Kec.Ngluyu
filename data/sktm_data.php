<?php
// File untuk menangani data SKTM - Tanpa Database
header('Content-Type: application/json');

// Handle POST request untuk SKTM baru
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
        $jenis_sktm = $_POST['jenis_sktm'] ?? 'Tidak Diketahui';
        $alasan = $_POST['alasan'] ?? 'Tidak Diketahui';
        $penghasilan = $_POST['penghasilan'] ?? '0';
        $jumlah_tanggungan = $_POST['jumlah_tanggungan'] ?? '0';
        $telepon = $_POST['telepon'] ?? 'Tidak Diketahui';
        
        // Generate nomor surat
        $nomor_surat = date('Y') . '/' . date('m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        // Generate dokumen SKTM
        $docContent = generateSktmDocument([
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
            'jenis_sktm' => $jenis_sktm,
            'alasan' => $alasan,
            'penghasilan' => $penghasilan,
            'jumlah_tanggungan' => $jumlah_tanggungan,
            'telepon' => $telepon,
            'nomor_surat' => $nomor_surat
        ]);
        
        // Buat folder untuk menyimpan dokumen
        $uploadDir = 'uploads/sktm_docx/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Generate nama file
        $fileName = 'SKTM_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nama) . '_' . date('Y-m-d_H-i-s') . '.html';
        $filePath = $uploadDir . $fileName;
        
        // Simpan dokumen
        if (file_put_contents($filePath, $docContent)) {
            $response = [
                'status' => 'success',
                'message' => 'SKTM berhasil dibuat!',
                'data' => [
                    'id' => uniqid(),
                    'nama' => $nama,
                    'nomor_surat' => $nomor_surat,
                    'docx_file' => $filePath,
                    'docx_download' => $filePath
                ]
            ];
        } else {
            throw new Exception('Gagal menyimpan file dokumen');
        }
        
        // Simpan data ke file JSON untuk admin panel
        $sktmData = [];
        if (file_exists('sktm.json')) {
            $sktmData = json_decode(file_get_contents('sktm.json'), true);
        }
        
        $sktmData[] = [
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
            'jenis_sktm' => $jenis_sktm,
            'alasan' => $alasan,
            'penghasilan' => $penghasilan,
            'jumlah_tanggungan' => $jumlah_tanggungan,
            'telepon' => $telepon,
            'tanggal' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ];
        
        file_put_contents('sktm.json', json_encode($sktmData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Gagal membuat SKTM: ' . $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle GET request untuk admin panel
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    $jsonFile = 'sktm.json';
    
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

// Fungsi untuk generate dokumen SKTM
function generateSktmDocument($data) {
    $tanggal = date('d F Y');
    $jenis_kelamin_text = $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan';
    $tanggal_lahir_text = date('d F Y', strtotime($data['tanggal_lahir']));
    $berlaku_hingga_text = date('d F Y', strtotime($data['berlaku_hingga']));
    $penghasilan_text = number_format($data['penghasilan'], 0, ',', '.');
    
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>SKTM - ' . $data['nama'] . '</title>
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
            .nomor-surat { text-align: center; margin: 20px 0; font-weight: bold; }
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
        </style>
    </head>
    <body>
        <div class="header">
            <div class="title">PEMERINTAH KABUPATEN NGANJUK</div>
            <div class="title">KECAMATAN NGLUYU</div>
            <div class="subtitle">Jl. Raya Ngluyu No. 123, Ngluyu, Nganjuk</div>
            <div class="subtitle">Telp: (0354) 123456 | Email: kecamatan.ngluyu@nganjukkab.go.id</div>
        </div>
        
        <div class="nomor-surat">
            <div class="title">SURAT KETERANGAN TIDAK MAMPU (SKTM)</div>
            <div class="subtitle">Nomor: ' . $data['nomor_surat'] . '</div>
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
            
            <p>Dengan ini menerangkan bahwa:</p>
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
                    <tr><td>Jenis SKTM</td><td>: ' . $data['jenis_sktm'] . '</td></tr>
                    <tr><td>Penghasilan per bulan</td><td>: Rp ' . $penghasilan_text . '</td></tr>
                    <tr><td>Jumlah tanggungan</td><td>: ' . $data['jumlah_tanggungan'] . ' orang</td></tr>
                </table>
            </div>
            
            <p>Berdasarkan keterangan di atas, yang bersangkutan termasuk dalam kategori keluarga tidak mampu dan berhak mendapatkan bantuan sesuai dengan ketentuan yang berlaku.</p>
            
            <p>Surat keterangan ini berlaku sampai dengan tanggal <strong>' . $berlaku_hingga_text . '</strong></p>
            
            <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>
        
        <div class="barcode-container">
            <div class="barcode">*' . $data['nomor_surat'] . '*</div>
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
        
        <div class="stamp">LUNAS</div>
    </body>
    </html>';
}
?>
