<?php
// File untuk generate dokumen DOCX SKTM
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ambil data dari form
        $nama = $_POST['nama'] ?? '';
        $nik = $_POST['nik'] ?? '';
        $alamat = $_POST['alamat'] ?? '';
        $jenis_sktm = $_POST['jenis_sktm'] ?? '';
        $alasan = $_POST['alasan'] ?? '';
        $penghasilan = $_POST['penghasilan'] ?? '';
        $jumlah_tanggungan = $_POST['jumlah_tanggungan'] ?? '';
        
        // Buat dokumen Word baru
        $phpWord = new PhpWord();
        
        // Tambahkan style untuk dokumen
        $phpWord->addTitleStyle(1, array('bold' => true, 'size' => 16), array('spaceAfter' => 240));
        $phpWord->addTitleStyle(2, array('bold' => true, 'size' => 14), array('spaceAfter' => 120));
        
        // Style untuk paragraf
        $paragraphStyle = array(
            'spacing' => 120,
            'spaceAfter' => 120,
            'spaceBefore' => 120,
        );
        
        $paragraphStyleCenter = array(
            'spacing' => 120,
            'spaceAfter' => 120,
            'spaceBefore' => 120,
            'align' => Jc::CENTER,
        );
        
        // Style untuk text
        $textStyle = array('size' => 12);
        $textStyleBold = array('size' => 12, 'bold' => true);
        
        // Buat section
        $section = $phpWord->addSection();
        
        // Header dokumen
        $section->addText('PEMERINTAH KABUPATEN NGANJUK', $textStyleBold, $paragraphStyleCenter);
        $section->addText('KECAMATAN NGLUYU', $textStyleBold, $paragraphStyleCenter);
        $section->addText('Jl. Raya Ngluyu No. 123, Ngluyu, Nganjuk', $textStyle, $paragraphStyleCenter);
        $section->addText('Telp: (0354) 123456', $textStyle, $paragraphStyleCenter);
        
        // Garis pemisah
        $section->addText('', $textStyle, array('spaceAfter' => 60));
        $section->addText('_________________________________________________________________', $textStyle, $paragraphStyleCenter);
        $section->addText('', $textStyle, array('spaceAfter' => 60));
        
        // Judul dokumen
        $section->addText('SURAT KETERANGAN TIDAK MAMPU (SKTM)', $textStyleBold, $paragraphStyleCenter);
        $section->addText('Nomor: ' . date('Y') . '/' . date('m') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT), $textStyle, $paragraphStyleCenter);
        $section->addText('', $textStyle, array('spaceAfter' => 120));
        
        // Isi dokumen
        $section->addText('Yang bertanda tangan di bawah ini:', $textStyle, $paragraphStyle);
        $section->addText('Nama    : Drs. Ahmad Supriyadi, M.Si', $textStyle, $paragraphStyle);
        $section->addText('NIP     : 196512341987031001', $textStyle, $paragraphStyle);
        $section->addText('Jabatan : Camat Ngluyu', $textStyle, $paragraphStyle);
        $section->addText('', $textStyle, array('spaceAfter' => 120));
        
        $section->addText('Dengan ini menerangkan bahwa:', $textStyle, $paragraphStyle);
        $section->addText('Nama                    : ' . $nama, $textStyle, $paragraphStyle);
        $section->addText('NIK                     : ' . $nik, $textStyle, $paragraphStyle);
        $section->addText('Alamat                  : ' . $alamat, $textStyle, $paragraphStyle);
        $section->addText('Jenis SKTM              : ' . ucfirst(str_replace('_', ' ', $jenis_sktm)), $textStyle, $paragraphStyle);
        $section->addText('Penghasilan per bulan   : Rp ' . number_format($penghasilan, 0, ',', '.'), $textStyle, $paragraphStyle);
        $section->addText('Jumlah tanggungan       : ' . $jumlah_tanggungan . ' orang', $textStyle, $paragraphStyle);
        $section->addText('', $textStyle, array('spaceAfter' => 120));
        
        $section->addText('Berdasarkan keterangan di atas, yang bersangkutan termasuk dalam kategori keluarga tidak mampu dan berhak mendapatkan bantuan sesuai dengan ketentuan yang berlaku.', $textStyle, $paragraphStyle);
        $section->addText('', $textStyle, array('spaceAfter' => 120));
        
        $section->addText('Surat keterangan ini berlaku sampai dengan tanggal ' . date('d F Y', strtotime('+6 months')), $textStyle, $paragraphStyle);
        $section->addText('', $textStyle, array('spaceAfter' => 120));
        
        $section->addText('Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', $textStyle, $paragraphStyle);
        $section->addText('', $textStyle, array('spaceAfter' => 120));
        
        // Tanda tangan
        $section->addText('Ngluyu, ' . date('d F Y'), $textStyle, $paragraphStyle);
        $section->addText('Camat Ngluyu,', $textStyle, $paragraphStyle);
        $section->addText('', $textStyle, array('spaceAfter' => 240));
        $section->addText('Drs. Ahmad Supriyadi, M.Si', $textStyleBold, $paragraphStyle);
        $section->addText('NIP. 196512341987031001', $textStyle, $paragraphStyle);
        
        // Buat folder untuk menyimpan dokumen
        $uploadDir = '../uploads/sktm_docx/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Generate nama file
        $fileName = 'SKTM_' . $nama . '_' . date('Y-m-d_H-i-s') . '.docx';
        $filePath = $uploadDir . $fileName;
        
        // Simpan dokumen
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);
        
        // Response sukses
        $response = [
            'status' => 'success',
            'message' => 'Dokumen SKTM berhasil dibuat',
            'file_name' => $fileName,
            'file_path' => 'uploads/sktm_docx/' . $fileName,
            'download_url' => 'uploads/sktm_docx/' . $fileName
        ];
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'Gagal membuat dokumen SKTM: ' . $e->getMessage()
        ];
        
        echo json_encode($response);
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Method tidak diizinkan'
    ];
    
    echo json_encode($response);
}
?>
