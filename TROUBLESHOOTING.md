# 🔧 **Troubleshooting Guide - Data Storage Issues**

## 🚨 **Masalah: Data Akun Tidak Tersimpan di Admin Panel**

### **🔍 Penyebab Umum:**

1. **PHP Handler tidak memiliki GET action**
2. **Database tidak tersedia dan JSON fallback tidak bekerja**
3. **File permissions tidak tepat**
4. **Path file tidak sesuai**
5. **Form field names tidak cocok**

---

## ✅ **Solusi yang Telah Diterapkan:**

### **1. PHP Handler GET Action**
Semua file PHP handler sudah ditambahkan dengan GET action untuk admin panel:

```php
// Handle GET request untuk admin panel
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    // Ambil data dari database atau JSON
}
```

### **2. Database + JSON Fallback**
Setiap handler memiliki fallback ke file JSON jika database tidak tersedia:

```php
try {
    // Coba database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch(PDOException $e) {
    // Fallback ke JSON
    $data = json_decode(file_get_contents('registration.json'), true);
}
```

### **3. File Structure yang Benar**
```
data/
├── laporan_data.php      ✅ GET + POST
├── pengaduan_data.php    ✅ GET + POST  
├── foto_data.php         ✅ GET + POST
├── registration_data.php  ✅ GET + POST
├── laporan.json          📄 Data storage
├── pengaduan.json        📄 Data storage
├── foto.json            📄 Data storage
└── registration.json     📄 Data storage

uploads/
├── laporan/             📁 File uploads
├── pengaduan/           📁 File uploads
├── foto/                📁 File uploads
└── ktp/                 📁 File uploads
```

---

## 🧪 **Cara Test & Debug:**

### **1. Buka Test Page**
```
http://localhost/kecamatan.html/test-data.html
```

### **2. Test Data Retrieval**
- Klik **"Test Registration Data"**
- Klik **"Test All Data"**
- Lihat hasil di console

### **3. Check File Structure**
- Klik **"Check File Structure"**
- Pastikan semua file ada

### **4. Test Database Connection**
- Klik **"Test Database"**
- Lihat apakah database atau JSON yang aktif

---

## 🔧 **Langkah Perbaikan Manual:**

### **Langkah 1: Check File Permissions**
```bash
# Pastikan folder data bisa diakses
chmod 755 data/
chmod 644 data/*.php
chmod 666 data/*.json

# Pastikan folder uploads bisa ditulis
chmod 755 uploads/
chmod 755 uploads/*/
```

### **Langkah 2: Check PHP Error Log**
```bash
# Buka file error log
tail -f /var/log/apache2/error.log
# atau
tail -f /var/log/nginx/error.log
```

### **Langkah 3: Test PHP Handler Langsung**
```bash
# Test GET action
curl "http://localhost/kecamatan.html/data/registration_data.php?action=get"

# Test POST action
curl -X POST "http://localhost/kecamatan.html/data/registration_data.php" \
  -d "nama_lengkap=Test&nik=1234567890123456&email=test@test.com"
```

---

## 📋 **Checklist Troubleshooting:**

### **✅ Form Submission:**
- [ ] Form action mengarah ke `data/registration_data.php`
- [ ] Method = POST
- [ ] Enctype = `multipart/form-data` (jika ada file upload)
- [ ] Semua field required terisi

### **✅ PHP Handler:**
- [ ] File `data/registration_data.php` ada
- [ ] GET action untuk admin panel
- [ ] POST action untuk form submission
- [ ] Database connection + JSON fallback

### **✅ File Storage:**
- [ ] Folder `data/` ada dan bisa diakses
- [ ] File `registration.json` bisa dibuat/ditulis
- [ ] Folder `uploads/ktp/` ada dan bisa ditulis

### **✅ Admin Panel:**
- [ ] Admin panel bisa login (password: `Admin123`)
- [ ] JavaScript fetch ke `?action=get`
- [ ] Response format sesuai (`success: true, data: [...]`)

---

## 🚀 **Quick Fix Commands:**

### **1. Buat Folder yang Diperlukan**
```bash
mkdir -p uploads/laporan uploads/pengaduan uploads/foto uploads/ktp
chmod 755 uploads uploads/*
```

### **2. Buat File JSON Kosong**
```bash
echo '[]' > data/registration.json
echo '[]' > data/laporan.json
echo '[]' > data/pengaduan.json
echo '[]' > data/foto.json
chmod 666 data/*.json
```

### **3. Test PHP Handler**
```bash
# Test registration data
php -f data/registration_data.php

# Test dengan parameter
php -f data/registration_data.php action=get
```

---

## 📱 **Test di Browser:**

### **1. Buka Developer Tools**
- **F12** → **Console**
- **F12** → **Network**

### **2. Submit Form**
- Isi form pendaftaran
- Submit dan lihat di Network tab
- Pastikan response success

### **3. Buka Admin Panel**
- Login dengan password `Admin123`
- Lihat di Console apakah ada error
- Check Network tab untuk fetch requests

---

## 🔍 **Debugging Tips:**

### **1. Console Logs**
```javascript
// Tambahkan di admin.html
console.log('Loading registration data...');
console.log('Response:', data);
```

### **2. PHP Debug**
```php
// Tambahkan di registration_data.php
error_log('POST data: ' . print_r($_POST, true));
error_log('Files: ' . print_r($_FILES, true));
```

### **3. File Check**
```bash
# Check file size
ls -la data/*.json

# Check file content
cat data/registration.json

# Check permissions
ls -la data/
```

---

## 📞 **Jika Masih Bermasalah:**

### **1. Check Server Requirements**
- PHP 7.4+ terinstall
- Apache/Nginx berjalan
- File permissions benar

### **2. Test dengan File Sederhana**
- Buat file test sederhana
- Test basic PHP functionality
- Test file read/write

### **3. Compare dengan Working Example**
- Download fresh copy
- Compare file contents
- Check for differences

---

## 🎯 **Expected Result:**

Setelah semua perbaikan, admin panel seharusnya:

✅ **Bisa login** dengan password `Admin123`  
✅ **Menampilkan data** dari semua form  
✅ **Update statistics** real-time  
✅ **Show tables** dengan data lengkap  
✅ **Handle file uploads** dengan benar  

---

**🔧 Jika masih ada masalah, gunakan test-data.html untuk debug step by step!**

