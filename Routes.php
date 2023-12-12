<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/siswa', 'Home::siswa');

$routes->get('/login', 'Login::index');
$routes->post('/setlogin', 'Login::setLogin');
$routes->get('/logout', 'Logout::index');

// $routes->get('/register', 'Register::index');
$routes->get('/peminjaman', 'Peminjaman::index');
$routes->post('/setpeminjaman', 'Peminjaman::siswa');

$routes->get('/pengembalian', 'Pengembalian::index');

// DASHBOARD

$routes->get('/pustakawan', 'Admin\Dashboard::index');

// KELAS

$routes->get('/pustakawan/kelas', 'Admin\Kelas::index');
$routes->get('/pustakawan/kelas/delall', 'Admin\Kelas::delAll');
$routes->get('/pustakawan/kelas/tambah', 'Admin\Kelas::tambah');
$routes->get('/pustakawan/kelas/ubah/(:any)', 'Admin\Kelas::ubah/$1');
$routes->post('/pustakawan/kelas/save', 'Admin\Kelas::save');
$routes->post('/pustakawan/kelas/update', 'Admin\Kelas::update');
$routes->DELETE('/pustakawan/kelas/delete/(:any)', 'Admin\Kelas::delete/$1');

// SISWA

$routes->get('/pustakawan/siswa', 'Admin\Siswa::index');
$routes->get('/pustakawan/siswa/delall', 'Admin\Siswa::delAll');
$routes->get('/pustakawan/siswa/tambah', 'Admin\Siswa::tambah');
$routes->get('/pustakawan/siswa/ubah/(:any)', 'Admin\Siswa::ubah/$1');
$routes->post('/pustakawan/siswa/save', 'Admin\Siswa::save');
$routes->post('/pustakawan/siswa/update', 'Admin\Siswa::update');
$routes->DELETE('/pustakawan/siswa/delete/(:any)', 'Admin\Siswa::delete/$1');

// TAHUN

$routes->get('/pustakawan/tahun', 'Admin\Tahun::index');
$routes->get('/pustakawan/tahun/tambah', 'Admin\Tahun::tambah');
$routes->get('/pustakawan/tahun/ubah/(:any)', 'Admin\Tahun::ubah/$1');
$routes->post('/pustakawan/tahun/update', 'Admin\Tahun::update');
$routes->post('/pustakawan/tahun/save', 'Admin\Tahun::save');
$routes->DELETE('/pustakawan/tahun/delete/(:any)', 'Admin\Tahun::delete/$1');

// CETAK SISWA

$routes->get('/pustakawan/siswa/cetaksiswa', 'Admin\Cetak::index');
$routes->get('/pustakawan/siswa/cetaksiswa/(:any)', 'Admin\Cetak::index/$1');
$routes->get('/pustakawan/siswa/cetakkelas', 'Admin\Cetak::kelas');
$routes->get('/pustakawan/siswa/cetakkelas/(:any)', 'Admin\Cetak::cetakperkelas/$1');
$routes->get('/pustakawan/cetak/siswa', 'Admin\Cetak::cetaksiswa');
$routes->get('/pustakawan/cetak/siswa/(:any)', 'Admin\Cetak::cetaksiswa/$1');
$routes->get('/pustakawan/cetak/kelas/(:any)', 'Admin\Cetak::cetakkelas/$1');


// CETAK BUKU
$routes->get('/pustakawan/buku/cetakbuku', 'Admin\Cetak::buku');
$routes->get('/pustakawan/buku/cetakbuku/(:any)', 'Admin\Cetak::buku/$1');
$routes->get('/pustakawan/buku/cetakrak', 'Admin\Cetak::rak');
// $routes->get('/pustakawan/buku/cetakbuku/(:any)', 'Admin\Cetak::buku/$1');
$routes->get('/pustakawan/cetak/buku', 'Admin\Cetak::cetakbuku');
$routes->get('/pustakawan/cetak/buku/(:any)', 'Admin\Cetak::cetakbuku/$1');
$routes->get('/pustakawan/cetak/rak/(:any)', 'Admin\Cetak::cetakrak/$1');

// PENERBIT

$routes->get('/pustakawan/penerbit', 'Admin\Penerbit::index');
$routes->get('/pustakawan/penerbit/tambah', 'Admin\Penerbit::tambah');
$routes->get('/pustakawan/penerbit/ubah/(:any)', 'Admin\Penerbit::ubah/$1');
$routes->post('/pustakawan/penerbit/save', 'Admin\Penerbit::save');
$routes->post('/pustakawan/penerbit/update', 'Admin\Penerbit::update');
$routes->DELETE('/pustakawan/penerbit/delete/(:any)', 'Admin\Penerbit::delete/$1');

// Rak Buku

$routes->get('/pustakawan/rak', 'Admin\Rak::index');
$routes->get('/pustakawan/rak/delall', 'Admin\Rak::delAll');
$routes->get('/pustakawan/rak/tambah', 'Admin\Rak::tambah');
$routes->get('/pustakawan/rak/ubah/(:any)', 'Admin\Rak::ubah/$1');
$routes->post('/pustakawan/rak/save', 'Admin\Rak::save');
$routes->post('/pustakawan/rak/update', 'Admin\Rak::update');
$routes->DELETE('/pustakawan/rak/delete/(:any)', 'Admin\Rak::delete/$1');

// Jenis Buku

$routes->get('/pustakawan/jenis', 'Admin\Jenis::index');
$routes->get('/pustakawan/jenis/delall', 'Admin\Jenis::delAll');
$routes->get('/pustakawan/jenis/tambah', 'Admin\Jenis::tambah');
$routes->get('/pustakawan/jenis/ubah/(:any)', 'Admin\Jenis::ubah/$1');
$routes->post('/pustakawan/jenis/save', 'Admin\Jenis::save');
$routes->post('/pustakawan/jenis/update', 'Admin\Jenis::update');
$routes->DELETE('/pustakawan/jenis/delete/(:any)', 'Admin\Jenis::delete/$1');

// Buku

$routes->get('/pustakawan/buku', 'Admin\Buku::index');
$routes->get('/pustakawan/buku/delall', 'Admin\Buku::delAll');
$routes->get('/pustakawan/buku/tambah', 'Admin\Buku::tambah');
$routes->get('/pustakawan/buku/ubah/(:any)', 'Admin\Buku::ubah/$1');
$routes->post('/pustakawan/buku/save', 'Admin\Buku::save');
$routes->get('/pustakawan/buku/deletebuku/(:any)/(:any)', 'Admin\Buku::deleteBuku/$1/$2');
$routes->post('/pustakawan/buku/update', 'Admin\Buku::update');
$routes->post('/pustakawan/buku/stok', 'Admin\Buku::stok');
$routes->DELETE('/pustakawan/buku/delete/(:any)', 'Admin\Buku::delete/$1');

// Peminjaman

$routes->get('/pustakawan/peminjaman', 'Admin\Peminjaman::index');
$routes->get('/pustakawan/pengembalian', 'Admin\Pengembalian::index');
$routes->get('/pustakawan/pengembalian/(:any)', 'Admin\Pengembalian::index/$1');
$routes->post('/pustakawan/pengembalian/update', 'Admin\Pengembalian::update');
$routes->get('/pustakawan/transaksi', 'Admin\Transaksi::index');
$routes->post('/pustakawan/transaksi/update', 'Admin\Transaksi::update');
$routes->post('/pustakawan/transaksi/pdf', 'Admin\Transaksi::pdf');
$routes->post('/pustakawan/transaksi/excel', 'Admin\Transaksi::excel');
$routes->post('/pustakawan/peminjaman/save', 'Admin\Peminjaman::save');
$routes->DELETE('/pustakawan/peminjaman/delete/(:any)', 'Admin\Peminjaman::delete/$1');
// $routes->get('/pustakawan/buku/tambah', 'Admin\Buku::tambah');
// $routes->get('/pustakawan/buku/ubah', 'Admin\Buku::ubah');


// LAPORAN

$routes->get('/pustakawan/laporan', 'Admin\Laporan::index');
$routes->post('/pustakawan/laporan', 'Admin\Laporan::index');
$routes->get('/pustakawan/laporan/reset', 'Admin\Laporan::reset');
// $routes->get('/pustakawan/denda/tambah', 'Admin\Denda::tambah');
// $routes->get('/pustakawan/denda/ubah', 'Admin\Denda::ubah');

// PENGUNJUNG
$routes->get('/pustakawan/pengunjung', 'Admin\Pengunjung::index');
$routes->post('/pustakawan/pengunjung', 'Admin\Pengunjung::index');
$routes->post('/pustakawan/pengunjung/pdf', 'Admin\Pengunjung::pdf');
$routes->post('/pustakawan/pengunjung/excel', 'Admin\Pengunjung::excel');

// BACKUP
$routes->get('/pustakawan/backup', 'Admin\Backup::index');

// SEKOLAH

$routes->get('/pustakawan/sekolah', 'Admin\Sekolah::index');
// $routes->get('/pustakawan/sekolah/laporan', 'Admin\Sekolah::laporan');
$routes->post('/pustakawan/sekolah/update', 'Admin\Sekolah::update');

// PERPUSTAKAAN

$routes->get('/pustakawan/perpustakaan', 'Admin\Perpustakaan::index');
$routes->post('/pustakawan/perpustakaan/save', 'Admin\Perpustakaan::save');

// KIRIM
$routes->get('/pustakawan/kirimpesan/whastapp/(:any)', 'Admin\Pesan::whastapp/$1');
$routes->get('/pustakawan/kirimpesan/email/(:any)', 'Admin\Pesan::email/$1');


// WHASTAPP
$routes->get('/pustakawan/kirimpesan', 'Admin\Pesan::index');
$routes->get('/pustakawan/whastapp', 'Admin\Whastapp::index');
$routes->post('/pustakawan/whastapp/save', 'Admin\Whastapp::save');

// EMAIL

$routes->get('/pustakawan/email', 'Admin\Email::index');
$routes->post('/pustakawan/email/save', 'Admin\Email::save');

// EXCEL
$routes->post('/pustakawan/excel/kelas', 'Admin\Excel::kelas');
$routes->post('/pustakawan/excel/siswa', 'Admin\Excel::siswa');
$routes->post('/pustakawan/excel/alumni', 'Admin\Excel::alumni');
$routes->post('/pustakawan/excel/rak', 'Admin\Excel::rak');
$routes->post('/pustakawan/excel/jenis', 'Admin\Excel::jenis');
$routes->post('/pustakawan/excel/buku', 'Admin\Excel::buku');

// PROFIL

$routes->get('/pustakawan/profil', 'Admin\Profil::index');
$routes->post('/pustakawan/profil/update', 'Admin\Profil::update');
$routes->get('/pustakawan/password', 'Admin\Profil::password');
$routes->post('/pustakawan/password/update', 'Admin\Profil::repassword');

// USERS

$routes->get('/pustakawan/user', 'Admin\Users::index');
$routes->get('/pustakawan/user/tambah', 'Admin\Users::tambah');
$routes->post('/pustakawan/user/tambah/save', 'Admin\Users::save');
$routes->get('/pustakawan/user/ubah/(:any)', 'Admin\Users::ubah/$1');
$routes->post('/pustakawan/user/ubah/update', 'Admin\Users::update');
$routes->DELETE('/pustakawan/user/delete/(:any)', 'Admin\Users::delete/$1');

// USERS

$routes->get('/pustakawan/pengajuan', 'Admin\Pengajuan::index');
// $routes->get('/pustakawan/user/tambah', 'Admin\Users::tambah');
// $routes->get('/pustakawan/user/ubah', 'Admin\Users::ubah');

$routes->get('/pustakawan/alumni', 'Admin\Alumni::index');
$routes->get('/pustakawan/alumni/delall', 'Admin\Alumni::delAll');
$routes->get('/pustakawan/alumni/tambah', 'Admin\Alumni::tambah');
$routes->get('/pustakawan/alumni/ubah/(:any)', 'Admin\Alumni::ubah/$1');
$routes->post('/pustakawan/alumni/save', 'Admin\Alumni::save');
$routes->post('/pustakawan/alumni/update', 'Admin\Alumni::update');
$routes->DELETE('/pustakawan/alumni/delete/(:any)', 'Admin\Alumni::delete/$1');

$routes->get('/pustakawan/bebaspinjam', 'Admin\Cetak::bebas');
$routes->get('/pustakawan/cetak/bebas', 'Admin\Cetak::cetakbebas');
$routes->get('/pustakawan/cetak/bebas/(:any)', 'Admin\Cetak::cetakbebas/$1');