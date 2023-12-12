<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\TahunModel;
use App\Models\TransaksiModel;

class Alumni extends BaseController
{
    protected $siswaModel;
    protected $trans;
    protected $kelasModel;
    protected $tahunModel;
    protected $db;

    function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->tahunModel = new TahunModel();
        $this->trans = new TransaksiModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        session();
        // require 'vendor/autoload.php';
        // echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $builder = $this->db->table('siswa');
        $tanggal = date('Y-m-d');
        $siswa = $builder->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
        ->join('tahun_ajaran','tahun_ajaran.kode_tahun = siswa.kode_tahun')
        ->where('tahun_ajaran.kadaluarsa <=',$tanggal)
        ->get();
        $data = [
            'title' => 'Daftar Alumni',
            'siswa' => $siswa->getResultArray(),
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];
        return view('admin/alumni/tablealumni', $data);
    }

    public function tambah()
    {
        session();

        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $kelas = $this->kelasModel->findAll();
        $tanggal = date('Y-m-d');
        $tahun = $this->tahunModel->where('kadaluarsa <=',$tanggal)->findAll();
        $data = [
            'title' => 'Tambah Alumni',
            'kelas' => $kelas,
            'tahun' => $tahun,
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];
        return view('admin/alumni/addalumni', $data);
    }

    function save()
    {
        $validate = [
            'nis' => [
                'rules' => 'is_unique[siswa.nis]',
                'errors' => [
                    'is_unique' => 'NIS Sudah Digunakan'
                ],
            ],
            'nisn' => [
                'rules' => 'is_unique[siswa.nisn]',
                'errors' => [
                    'is_unique' => 'NISN Sudah Digunakan'
                ],
            ],
            'foto' => [
                'rules' => 'max_size[foto,1024]|ext_in[foto,png,jpg,jpeg]|is_image[foto]',
                'errors' => [
                    'max_size' => 'Ukuran file terlalu besar',
                    'ext_in' => 'Moson Masukan file Gambar',
                    'is_image' => 'Moson Masukan file Gambar Yang benar'
                ],
            ],
        ];
        
        if (!$this->validate($validate)) {
            if ($this->validator->hasError('nis')) {
                $message = $this->validator->getError('nis');
            }elseif ($this->validator->hasError('nisn')) {
                $message = $this->validator->getError('nisn');
            }elseif ($this->validator->hasError('foto')) {
                $message = $this->validator->getError('foto');
            }

            session()->setFlashdata('kotakok',[

                'status' => 'warning',
                'title' => 'Gagal',
                'message' => $message
            ]);
            return redirect()->to(base_url('pustakawan/alumni/tambah'))->withInput();
        }
        
        require 'vendor/autoload.php';
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $foto = $this->request->getFile('foto');

        if ($foto->getError() == 4 ) {
            $name = "siswa_default.jpg";
        }else{
            $name = $foto->getRandomName();
        }
        $siswa = $this->request->getvar();

        if ($foto->isvalid() && !$foto->hasMoved()) {
            $foto->move('public/admin/img/siswa/',$name);
        }

        $barcodeImage = $generator->getBarcode($siswa['nis'], $generator::TYPE_CODE_128);

        if ($this->siswaModel->save([
            'nis' => $siswa['nis'],
            'nisn' => $siswa['nisn'],
            'nama_siswa' => $siswa['nama_siswa'],
            'kode_kelas' => $siswa['kode_kelas'],
            'kode_tahun' => $siswa['kode_tahun'],
            'wa' => $siswa['wa'],
            'email' => $siswa['email'],
            'alamat_siswa' => $siswa['alamat_siswa'],
            'foto' => $name,
            'barcode_siswa' => $barcodeImage
        ]) == true
        ) {
            session()->setFlashdata('pojokatas',[
                'status' => 'success',
                'message' => 'Data Alumni Berhasil disimpan'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }else{
            session()->setFlashdata('pojokatas',[
                'status' => 'error',
                'message' => 'Data Alumni Gagal disimpan'
            ]);
            return redirect()->to(base_url('pustakawan/alumni/tambah'))->withInput();
        }
    }

    public function ubah($nis)
    {
        session();

        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $kelas = $this->kelasModel->findAll();
        $tanggal = date('Y-m-d');
        $tahun = $this->tahunModel->where('kadaluarsa <=',$tanggal)->findAll();
        $siswa = $this->siswaModel->where('nis',$nis)->first();
        $data = [
            'title' => 'Ubah Data Alumni',
            'siswa' => $siswa,
            'kelas' => $kelas,
            'tahun' => $tahun,
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];
        return view('admin/alumni/editalumni', $data);
    }

    function update()
    {
        $validate = [
            'foto' => [
                'rules' => 'max_size[foto,1024]|ext_in[foto,png,jpg,jpeg]|is_image[foto]',
                'errors' => [
                    'max_size' => 'Ukuran file terlalu besar',
                    'ext_in' => 'Mohon Masukan file Gambar',
                    'is_image' => 'Mohon Masukan file Gambar Yang benar'
                ],
            ],
        ];

        $siswa = $this->request->getvar();
        if (!$this->validate($validate)) {
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Gagal',
                'message' => $this->validator->getError('foto')
            ]);
            return redirect()->to(base_url('pustakawan/alumni/ubah/'.$siswa['nis']))->withInput();
        }

        $setsiswa = [
            'nis' => $siswa['nis'],
            'nama_siswa' => $siswa['nama_siswa'],
            'kode_kelas' => $siswa['kode_kelas'],
            'kode_tahun' => $siswa['kode_tahun'],
            'wa' => $siswa['wa'],
            'email' => $siswa['email'],
            'alamat_siswa' => $siswa['alamat_siswa']
        ];

        $foto = $this->request->getFile('foto');

        if ($foto->getError() == 4 ) {
            
        }else{
            $name = $foto->getRandomName();
            $setsiswa['foto'] = $name;
        }
        
        $cek = $this->siswaModel->where('nis',$siswa['nis'])->first();

        if ($cek['nisn'] == $siswa['nisn']) {
            $setsiswa['nisn'] = $siswa['nisn'];
        }elseif($this->siswaModel->where('nisn',$siswa['nisn'])->countAllResults() > 0){
            session()->setFlashdata('kotakok',[
                'status' => 'warning',
                'title' => 'Gagal',
                'message' => 'NISN Sudah Digunakan Digunakan'
            ]);
            return redirect()->to(base_url('pustakawan/alumni/ubah/'.$siswa['nis']))->withInput();
        }else{
            $setsiswa['nisn'] = $siswa['nisn'];
        }

        if ($foto->isvalid() && !$foto->hasMoved()) {
            $foto->move('public/admin/img/siswa',$name);
            if ($cek['foto'] != 'siswa_default.jpg') {
                unlink('public/admin/img/siswa/'.$cek['foto']);
            }
        }
        if ($this->siswaModel->where('nis',$siswa['nis'])->set($setsiswa)->update() == true) 
        {
            session()->setFlashdata('pojokatas',[
                'status' => 'success',
                'message' => 'Data Alumni Berhasil disimpan'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }else{
            session()->setFlashdata('pojokatas',[
                'status' => 'error',
                'message' => 'Data Alumni Gagal disimpan'
            ]);
            return redirect()->to(base_url('pustakawan/alumni/ubah/'.$siswa['nis']))->withInput();
        }
    }

    public function delete($nis)
    {
        $this->siswaModel->disableForeignKeyChecks();

        if ($this->trans->where('status','pinjam')->where('nis',$nis)->countAllResults() > 0) {
            session()->setFlashdata('kotakok',[
                'status'    => 'warning',
                'title' => 'Perhatian',
                'message'   => 'Alumni Masih Memiliki Tanggungan Peminjaman'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }

        $nama = $this->siswaModel->select('foto')->where('nis',$nis)->first('foto');
        if ($this->siswaModel->where('nis',$nis)->delete() == true ) {
            if($nama['foto'] != 'siswa_default.jpg'){
                unlink('public/admin/img/siswa/'.$nama['foto']);
            }
            session()->setFlashdata('pojokatas',[
                'status'    => 'success',
                'message'   => 'Data Alumni Berhasil Dihapus'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }else{
            session()->setFlashdata('pojokatas',[
                'status'    => 'error',
                'message'   => 'Data Alumni Gagal Dihapus'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }
    }

    public function delAll()
    {
        $this->siswaModel->disableForeignKeyChecks();
        $tanggal = date('Y-m-d');
        $siswa = $this->siswaModel->join('tahun_ajaran', 'tahun_ajaran.kode_tahun = siswa.kode_tahun')->where('tahun_ajaran.kadaluarsa <=',$tanggal)->findAll();
        if (is_array($siswa)) {
            foreach ($siswa as $value) {
                if (!$this->siswaModel->where('id',$value['id'])->delete()) {
                     continue;
                }
            }
            session()->setFlashdata('kotakok',[
                'status' => 'success',
                'title' => 'Berhasil',
                'message' => 'Seluruh data berhasil dihapus'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }else{
            session()->setFlashdata('kotakok',[
                'status' => 'error',
                'title' => 'Gagal',
                'message' => 'Data gagal dihapus'
            ]);
            return redirect()->to(base_url('pustakawan/alumni'));
        }
    }
}
