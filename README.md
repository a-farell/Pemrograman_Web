# Sistem Manajemen Dokumen 

Proyek ini adalah implementasi antarmuka Sistem Informasi Manajemen Dokumen dengan desain monokrom yang compact. Sistem ini dirancang untuk mendigitalisasi proses pengajuan, pelaporan, dan pengesahan dokumen secara internal guna menggantikan proses manual berbasis kertas. Dibangun menggunakan PHP Native, MySQL, Bootstrap 5, dan DataTables.

## 🚀 Status Implementasi Fitur 
- **Login:** Autentikasi sesi (Session-based) untuk membatasi akses pengguna.
- **CRUD + Upload Multiple File:** Fungsionalitas penambahan dokumen dengan unggahan lampiran jamak. File disimpan ke direktori lokal dan direlasikan pada database MySQL. Fitur Update dan Delete berjalan sempurna.
- **Pencarian Data & Datatable:** Integrasi DataTables untuk fitur Search instan dan tombol Export (Excel, PDF, Print).
- **Canvas untuk TTD Digital:** Penggunaan SignaturePad.js untuk menangkap pengesahan dokumen.
- **Video / Animasi dan Audio:** Menerapkan elemen `<audio>` berulang (loop) untuk latar, serta animasi CSS3 Keyframes (Fade-Slide-Up & Pulse) guna meningkatkan UX.
- **Penggunaan Modal:** Manipulasi form Create dan Update menggunakan Bootstrap Modal tanpa page reload.

## 🛠️ Teknologi Pendukung
* **Back-End:** PHP 8+, Database MySQL
* **Front-End:** HTML5, CSS3, JavaScript, UI Framework Bootstrap 5