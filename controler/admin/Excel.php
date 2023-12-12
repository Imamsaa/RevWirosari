<?php

namespace App\Controllers\Admin;
require 'public/vendor/autoload.php';
use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\RakModel;
use App\Models\JenisModel;
use App\Models\BukuModel;
use App\Models\TahunModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory; 

class Excel extends BaseController
{
    protected $kelasModel;
    protected $siswaModel;
    protected $rakModel;
    protected $jenisModel;
    protected $bukuModel;
    protected $tahunModel;

    function __construct()
    {
        $this->siswaModel = new SiswaModel();   
        $this->kelasModel = new KelasModel(); 
        $this->rakModel = new RakModel();
        $this->jenisModel = new JenisModel();
        $this->bukuModel = new BukuModel();
        $this->tahunModel = new TahunModel(); 
    }

    public function kelas()
    {
        session();
        $this->kelasModel->disableForeignKeyChecks();
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $validate = [
            'kelas' => [
                'rules' => 'ext_in[kelas,xls,xlsx]',
                'errors' => [
                    'ext_in' => 'Hohon Masukan File Impor Yang Sesuai'
                ],
            ],
        ];

        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Perhatian',
                'message' => $this->validator->getError('kelas')
            ]);
            return redirect()->to(base_url('pustakawan/kelas'));
        }

        $upload = $this->request->getFile('kelas');

        if ($upload->isValid() && !$upload->hasMoved()) {
            $newName = $upload->getRandomName();
            $upload->move(ROOTPATH . 'public/uploads', $newName);

            $file_path = ROOTPATH . 'public/uploads/' . $newName;

            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = true;
            $tampil = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }


                if (!isset($data[1]) OR !isset($data[1])) {
                    continue;
                }

                if ($this->kelasModel->where('kode_kelas',$data[1])->countAllResults() > 0) {
                    $tampil[] = $data[1];
                    continue;
                }else{
                    $insertData = [
                        'kode_kelas' => $data[1],
                        'nama_kelas' => $data[2]
                    ];
                }
                $this->kelasModel->save($insertData);
            }
            unlink('public/uploads/' . $newName);   
            if(is_array($tampil) && count($tampil) > 0)
            {
                $result = implode(" , ",$tampil);
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Kelas Telah Di Import Kecuali Data Kelas Duplikat : $result"
                ]);
                return redirect()->to(base_url('pustakawan/kelas'));
            }else{
                session()->setFlashdata('kotakok',[
                    'status' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Impor Data Kelas Berhasil'
                ]);
                return redirect()->to(base_url('pustakawan/kelas'));
            }
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Impor Data Kelas Gagal'
            ]);
            return redirect()->to(base_url('pustakawan/kelas'));
        }
    }
    
    public function siswa()
    {
        session();
        $this->siswaModel->disableForeignKeyChecks();
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $validate = [
            'siswa' => [
                'rules' => 'ext_in[siswa,xls,xlsx]',
                'errors' => [
                    'ext_in' => 'Hohon Masukan File Impor Yang Sesuai'
                ],
            ],
        ];

        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Perhatian',
                'message' => $this->validator->getError('siswa')
            ]);
            return redirect()->to(base_url('pustakawan/siswa'));
        }

        $upload = $this->request->getFile('siswa');

        if ($upload->isValid() && !$upload->hasMoved()) {
            $newName = $upload->getRandomName();
            $upload->move(ROOTPATH . 'public/uploads', $newName);

            $file_path = ROOTPATH . 'public/uploads/' . $newName;

            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = true;
            $tampil = [];
            $tampiltahun = [];
            $tampilkelas = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                if (!isset($data[1]) OR !isset($data[2]) OR !isset($data[3]) OR !isset($data[4]) OR !isset($data[5]) OR !isset($data[6]) OR !isset($data[7]) OR !isset($data[8])) {
                    continue;
                }

                if ($this->tahunModel->where('kode_tahun',$data[5])->countAllResults() == 0) {
                    $tampiltahun[] = $data[5];
                    continue;
                }

                if ($this->kelasModel->where('kode_kelas',$data[4])->countAllResults() == 0) {
                    $tampilkelas[] = $data[4];
                    continue;
                }

                if ($this->siswaModel->where('nis',$data[1])->countAllResults() > 0) {
                    $tampil[] = $data[1];
                    continue;
                }

                $barcodeImage = $generator->getBarcode($data[1], $generator::TYPE_CODE_128);
                // Sesuaikan dengan struktur tabel Anda
                $insertData = [
                    'nis' => $data[1],
                    'nisn' => $data[2],
                    'nama_siswa' => $data[3],
                    'kode_kelas' => $data[4],
                    'kode_tahun' => $data[5],
                    'wa' => $data[6],
                    'email' => $data[7],
                    'alamat_siswa' => $data[8],
                    'foto' => 'siswa_default.jpg',
                    'barcode_siswa' => $barcodeImage
                ];

                $this->siswaModel->save($insertData);
            }
            unlink(ROOTPATH . 'public/uploads/' . $newName);
            if(is_array($tampil) && count($tampil) > 0)
            {
                $result = implode(" , ",$tampil);
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Siswa Telah Di Import Kecuali Dengan NIS Duplikat : $result"
                ]);
                return redirect()->to(base_url('pustakawan/siswa'));
            }
            elseif(is_array($tampiltahun) && count($tampiltahun) > 0)
            {
                $result = implode(" , ",array_unique($tampiltahun));
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Siswa Telah Di Import Kecuali Tahun Tidak Terdaftar : $result"
                ]);
                return redirect()->to(base_url('pustakawan/siswa'));
            }elseif(is_array($tampilkelas) && count($tampilkelas) > 0)
            {
                $result = implode(" , ",array_unique($tampilkelas));
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Siswa Telah Di Import Kecuali Kelas Tidak Terdaftar : $result"
                ]);
                return redirect()->to(base_url('pustakawan/siswa'));
            }else{
                session()->setFlashdata('kotakok',[
                    'status' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Impor Data Siswa Berhasil'
                ]);
                return redirect()->to(base_url('pustakawan/siswa'));
            }
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Impor Data Siswa Gagal'
            ]);
            return redirect()->to(base_url('pustakawan/siswa'));
        }
    }

    public function alumni()
    {
        session();
        $this->siswaModel->disableForeignKeyChecks();
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $validate = [
            'siswa' => [
                'rules' => 'ext_in[siswa,xls,xlsx]',
                'errors' => [
                    'ext_in' => 'Hohon Masukan File Impor Yang Sesuai'
                ],
            ],
        ];

        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Perhatian',
                'message' => $this->validator->getError('siswa')
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }

        $upload = $this->request->getFile('siswa');

        if ($upload->isValid() && !$upload->hasMoved()) {
            $newName = $upload->getRandomName();
            $upload->move(ROOTPATH . 'public/uploads', $newName);

            $file_path = ROOTPATH . 'public/uploads/' . $newName;

            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = true;
            $tampil = [];
            $tampiltahun = [];
            $tampilkelas = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                if (!isset($data[1]) OR !isset($data[2]) OR !isset($data[3]) OR !isset($data[4]) OR !isset($data[5]) OR !isset($data[6]) OR !isset($data[7]) OR !isset($data[8])) {
                    continue;
                }

                if ($this->tahunModel->where('kode_tahun',$data[5])->countAllResults() == 0) {
                    $tampiltahun[] = $data[5];
                    continue;
                }

                if ($this->kelasModel->where('kode_kelas',$data[4])->countAllResults() == 0) {
                    $tampilkelas[] = $data[4];
                    continue;
                }

                if ($this->siswaModel->where('nis',$data[1])->countAllResults() > 0) {
                    $tampil[] = $data[1];
                    continue;
                }

                $barcodeImage = $generator->getBarcode($data[1], $generator::TYPE_CODE_128);
                // Sesuaikan dengan struktur tabel Anda
                $insertData = [
                    'nis' => $data[1],
                    'nisn' => $data[2],
                    'nama_siswa' => $data[3],
                    'kode_kelas' => $data[4],
                    'kode_tahun' => $data[5],
                    'wa' => $data[6],
                    'email' => $data[7],
                    'alamat_siswa' => $data[8],
                    'foto' => 'siswa_default.jpg',
                    'barcode_siswa' => $barcodeImage
                ];

                $this->siswaModel->save($insertData);
            }
            unlink(ROOTPATH . 'public/uploads/' . $newName);
            if(is_array($tampil) && count($tampil) > 0)
            {
                $result = implode(" , ",$tampil);
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Alumni Telah Di Import Kecuali Dengan NIS Duplikat : $result"
                ]);
                return redirect()->to(base_url('pustakawan/alumni'));
            }
            elseif(is_array($tampiltahun) && count($tampiltahun) > 0)
            {
                $result = implode(" , ",array_unique($tampiltahun));
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Alumni Telah Di Import Kecuali Tahun Tidak Terdaftar : $result"
                ]);
                return redirect()->to(base_url('pustakawan/alumni'));
            }elseif(is_array($tampilkelas) && count($tampilkelas) > 0)
            {
                $result = implode(" , ",array_unique($tampilkelas));
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Alumni Telah Di Import Kecuali Kelas Tidak Terdaftar : $result"
                ]);
                return redirect()->to(base_url('pustakawan/alumni'));
            }else{
                session()->setFlashdata('kotakok',[
                    'status' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Impor Data Alumni Berhasil'
                ]);
                return redirect()->to(base_url('pustakawan/alumni'));
            }
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Impor Data Alumni Gagal'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }
    }

    public function rak()
    {
        session();
        
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $validate = [
            'rak' => [
                'rules' => 'ext_in[rak,xls,xlsx]',
                'errors' => [
                    'ext_in' => 'Hohon Masukan File Impor Yang Sesuai'
                ],
            ],
        ];

        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Perhatian',
                'message' => $this->validator->getError('rak')
            ]);
            return redirect()->to(base_url('pustakawan/rak'));
        }

        $upload = $this->request->getFile('rak');

        if ($upload->isValid() && !$upload->hasMoved()) {
            $newName = $upload->getRandomName();
            $upload->move('public/uploads', $newName);

            $file_path = ROOTPATH . 'public/uploads/' . $newName;

            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = true;
            $tampil = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                if (!isset($data[1]) OR !isset($data[2])) {
                    continue;
                }

                if ($this->rakModel->where('kode_rak',$data[1])->countAllResults() > 0) {
                    $tampil[] = $data[1];
                    continue;
                }
                // Sesuaikan dengan struktur tabel Anda
                $insertData = [
                    'kode_rak' => $data[1],
                    'nama_rak' => $data[2]
                ];

                $this->rakModel->save($insertData);
            }
            unlink('public/uploads/' . $newName);
            if(is_array($tampil) && count($tampil) > 0)
            {
                $result = implode(" , ",$tampil);
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Rak Telah Di Import Kecuali Data Kode Rak Duplikat : $result"
                ]);
                return redirect()->to(base_url('pustakawan/rak'));
            }else{
                session()->setFlashdata('kotakok',[
                    'status' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Impor Data Rak Berhasil'
                ]);
                return redirect()->to(base_url('pustakawan/rak'));
            }
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Impor Data Rak Buku Gagal'
            ]);
            return redirect()->to(base_url('pustakawan/rak'));
        }
    }

    public function jenis()
    {
        session();
        
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $validate = [
            'jenis' => [
                'rules' => 'ext_in[jenis,xls,xlsx]',
                'errors' => [
                    'ext_in' => 'Hohon Masukan File Impor Yang Sesuai'
                ],
            ],
        ];

        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Perhatian',
                'message' => $this->validator->getError('jenis')
            ]);
            return redirect()->to(base_url('pustakawan/jenis'));
        }

        $upload = $this->request->getFile('jenis');

        if ($upload->isValid() && !$upload->hasMoved()) {
            $newName = $upload->getRandomName();
            $upload->move(ROOTPATH . 'public/uploads', $newName);

            $file_path = ROOTPATH . 'public/uploads/' . $newName;

            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = true;
            $tampil = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                if (!isset($data[1]) OR !isset($data[2])) {
                    continue;
                }

                if ($this->jenisModel->where('kode_jenis',$data[1])->countAllResults() > 0) {
                    $tampil[] = $data[1];
                    continue;
                }

                // Sesuaikan dengan struktur tabel Anda
                $insertData = [
                    'kode_jenis' => $data[1],
                    'nama_jenis' => $data[2],
                    'kode_warna' => '#000000'
                ];

                $this->jenisModel->save($insertData);
            }
            unlink('public/uploads/' . $newName);
            if(is_array($tampil) && count($tampil) > 0)
            {
                $result = implode(" , ",$tampil);
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Data Jenis Telah Di Import Kecuali Data Kode Jenis Duplikat : $result"
                ]);
                return redirect()->to(base_url('pustakawan/jenis'));
            }else{
                session()->setFlashdata('kotakok',[
                    'status' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Impor Data Jenis Berhasil'
                ]);
                return redirect()->to(base_url('pustakawan/jenis'));
            }
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Impor Data Jenis Buku Gagal'
            ]);
            return redirect()->to(base_url('pustakawan/jenis'));
        }
    }

    public function buku()
    {
        session();
        
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $validate = [
            'buku' => [
                'rules' => 'ext_in[buku,xls,xlsx]',
                'errors' => [
                    'ext_in' => 'Hohon Masukan File Impor Yang Sesuai'
                ],
            ],
        ];

        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Perhatian',
                'message' => $this->validator->getError('buku')
            ]);
            return redirect()->to(base_url('pustakawan/buku'));
        }

        $upload = $this->request->getFile('buku');

        require 'public/vendor/autoload.php';
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();

        if ($upload->isValid() && !$upload->hasMoved()) {
            $newName = $upload->getRandomName();
            $upload->move(ROOTPATH . 'public/uploads', $newName);

            $file_path = ROOTPATH . 'public/uploads/' . $newName;

            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = true;
            $tampil = [];
            foreach ($worksheet->getRowIterator() as $row) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $data = [];

                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }
                
                if (!isset($data[1]) OR !isset($data[2]) OR !isset($data[3]) OR !isset($data[4]) OR !isset($data[5]) OR !isset($data[6]) OR !isset($data[7])) {
                    continue;
                }

                if ($this->bukuModel->where('judul_buku',$data[1])->countAllResults() > 0) {
                    $tampil[] = $data[1];
                    continue;
                }
                $kode = $this->bukuModel->selectMax('kode_buku', 'max_buku')->first();
                // apabila belum ada data buku maka kita set nomornya jadi 0
                $urutan = (int) substr($kode['max_buku'], 1, 4);
                if ($kode == null) {
                    $kode = 'B000000';
                }
                // $pertambahan = 0;
                for ($i=1; $i <= $data[8] ; $i++) { 
                    // kita ambil nomor urutnya dan mengubbahnya menjadi integer
                    
                    // Kita kombinasikan nomor buku dengan kode bawaan kita
                    $urutan++;
                    // $urutanAkhir = $urutan + $pertambahan;
                    $kode_buku = 'B'. sprintf("%04s", $urutan);

                    $barcodeImage = $generator->getBarcode($kode_buku, $generator::TYPE_CODE_128);
                    // Sesuaikan dengan struktur tabel Anda
                    $insertData = [
                        'kode_buku' => $kode_buku,
                        'judul_buku' => $data[1],
                        'slug' => url_title($data[1]),
                        'isbn'  => $data[2],
                        'tahun_buku' => $data[3],
                        'kode_penerbit' => $data[4],
                        'kode_rak' => $data[5],
                        'kode_jenis' => $data[6],
                        'halaman' => $data[7],
                        'sampul' => 'cover_default.png',
                        'barcode_buku' => $barcodeImage
                    ];
                    $this->bukuModel->save($insertData);
                    // $pertambahan++;
                }
            }
            unlink('public/uploads/' . $newName);
            if(is_array($tampil) && count($tampil) > 0)
            {
                $result = implode(" , ",$tampil);
                session()->setFlashdata('kotakok',[
                    'status' => 'warning',
                    'title' => 'Duplikat',
                    'message' => "Jika Anda Menginginkan Penambahan Stok Buku Silahkan Menggunakan Menu edit Buku, Data Jenis Telah Di Import Kecuali : $result"
                ]);
                return redirect()->to(base_url('pustakawan/buku'));
            }else{
                session()->setFlashdata('kotakok',[
                    'status' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Impor Data Buku Berhasil'
                ]);
                return redirect()->to(base_url('pustakawan/buku'));
            }
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Impor Data Buku Gagal'
            ]);
            return redirect()->to(base_url('pustakawan/buku'));
        }
    }
}
