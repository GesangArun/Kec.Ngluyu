# 📄 Instalasi Fitur SKTM DOCX

## **Fitur yang Tersedia:**

✅ **Generate Dokumen SKTM** - Setelah pengajuan berhasil, sistem akan otomatis membuat dokumen SKTM
✅ **Download Dokumen** - Pemohon dapat langsung download dokumen SKTM yang telah dibuat
✅ **Format Dokumen** - Dokumen dibuat dalam format HTML yang bisa dikonversi ke DOCX

## **Cara Kerja:**

1. **User mengisi form SKTM** di `sktm.html`
2. **Data disimpan** ke file JSON atau database
3. **Sistem generate dokumen** SKTM otomatis
4. **User dapat download** dokumen yang telah dibuat

## **File yang Dibuat:**

- `data/generate_sktm_docx.php` - File untuk generate dokumen DOCX (menggunakan PhpWord)
- `data/sktm_data.php` - Update untuk integrasi generate DOCX
- `sktm.html` - Update form untuk menampilkan link download
- `uploads/sktm_docx/` - Folder untuk menyimpan dokumen SKTM

## **Instalasi Library PhpWord (Opsional):**

Jika ingin menggunakan library PhpWord untuk format DOCX asli:

```bash
# Install Composer (jika belum ada)
# Download dari: https://getcomposer.org/

# Install dependencies
composer install

# Atau install manual
composer require phpoffice/phpword
```

## **Alternatif Tanpa Library:**

Sistem sudah dibuat dengan alternatif generate dokumen HTML yang bisa:
- Dibuka di browser
- Dikonversi ke PDF menggunakan browser (Print > Save as PDF)
- Dikonversi ke DOCX menggunakan online converter

## **Struktur Dokumen SKTM:**

- **Header** - Kop surat resmi Kecamatan Ngluyu
- **Nomor Surat** - Otomatis generate dengan format tahun/bulan/nomor
- **Data Pemohon** - Nama, NIK, Alamat, dll
- **Keterangan** - Penjelasan status tidak mampu
- **Masa Berlaku** - 6 bulan dari tanggal pembuatan
- **Tanda Tangan** - Camat Ngluyu

## **Testing:**

1. **Buka `sktm.html`**
2. **Isi form dengan data lengkap**
3. **Submit form**
4. **Lihat pesan sukses dengan tombol download**
5. **Klik tombol "Download SKTM"**
6. **Dokumen akan terdownload**

## **Troubleshooting:**

### **Dokumen tidak terdownload:**
- Pastikan folder `uploads/sktm_docx/` sudah dibuat
- Periksa permission folder (777 untuk development)
- Periksa console browser untuk error

### **Format dokumen tidak sesuai:**
- Dokumen dibuat dalam format HTML
- Bisa dibuka di browser untuk preview
- Gunakan browser untuk konversi ke PDF

## **Keamanan:**

- Folder uploads tidak boleh diakses langsung dari web
- Tambahkan `.htaccess` untuk protect folder uploads
- Validasi file yang diupload
- Sanitasi input user

## **Pengembangan Lanjutan:**

- Integrasi dengan library PhpWord untuk format DOCX asli
- Template dokumen yang bisa dikustomisasi
- Watermark dan digital signature
- Email otomatis dokumen SKTM
- QR Code untuk verifikasi dokumen
