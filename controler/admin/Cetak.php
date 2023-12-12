<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BukuModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\RakModel;
use App\Models\TransaksiModel;

class Cetak extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $bukuModel;
    protected $rakModel;
    protected $trans;

    function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();   
        $this->bukuModel = new BukuModel();
        $this->rakModel = new RakModel();
        $this->trans = new TransaksiModel(); 
    }

    public function index($kode_kelas = null)
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }
        $data = [
            'title' => 'Cetak Kartu Siswa',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];

        if ($kode_kelas == null) {
            $siswa = $this->siswaModel
            ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
            ->join('tahun_ajaran', 'tahun_ajaran.kode_tahun = siswa.kode_tahun')
            ->findAll();
            $data['siswa'] = $siswa;
        }else{
            $siswa = $this->siswaModel
            ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
            ->join('tahun_ajaran', 'tahun_ajaran.kode_tahun = siswa.kode_tahun')
            ->where('siswa.kode_kelas', $kode_kelas)
            ->findAll();
            $data['siswa'] = $siswa;
        }
        return view('admin/cetak/tampilsemua', $data);
    }

    function cetakperkelas($kode_kelas = null)
    {
        $siswa = $this->siswaModel
        ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
        ->join('tahun_ajaran', 'tahun_ajaran.kode_tahun = siswa.kode_tahun')
        ->where('siswa.kode_kelas',$kode_kelas)
        ->findAll();

        $data = [
            'title' => 'Cetak Kartu Siswa',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku,
            'siswa' => $siswa
        ];
        return view('admin/cetak/tampilsemua', $data);
    }

    function kelas()
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        $kelas = $this->kelasModel
        ->findAll();

        $data = [
            'title' => 'Cetak Kartu Siswa',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku,
            'kelas' => $kelas
        ];
        return view('admin/cetak/tampilkelas', $data);
    }

    function cetakkelas($kode_kelas = null)
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }
        $data = [
            'title' => 'Cetak Kartu Siswa',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];

        if ($kode_kelas == null) {
            return view('admin/cetak/tampilkelas', $data);
        }else{
            $data['cetak'] = $this->siswaModel
            ->join('kelas', 'siswa.kode_kelas = kelas.kode_kelas')
            ->join('tahun_ajaran', 'tahun_ajaran.kode_tahun = siswa.kode_tahun')
            ->where('siswa.kode_kelas', $kode_kelas)
            ->findAll();
            return view('admin/cetak/cetakkartu', $data);
        }
    }

    function cetaksiswa($nis = null)
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }
        $data = [
            'title' => 'Cetak Kartu Siswa',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];

        if ($nis == null) {
            $data['cetak'] = $this->siswaModel
            ->join('kelas','siswa.kode_kelas = kelas.kode_kelas')
            ->join('tahun_ajaran', 'siswa.kode_tahun = tahun_ajaran.kode_tahun')
            ->findAll();
            return view('admin/cetak/cetakkartu', $data);
        }else{
            $data['cetak'] = $this->siswaModel
            ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
            ->join('tahun_ajaran', 'siswa.kode_tahun = tahun_ajaran.kode_tahun')
            ->where('nis', $nis)->findAll();
            return view('admin/cetak/cetakkartu', $data);
        }
    }



    // CETAK BUKU


    public function buku($kode_rak = null)
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }

        if ($kode_rak == null) {
            $buku = $this->bukuModel
            ->join('penerbit','penerbit.kode_penerbit = buku.kode_penerbit')
            ->join('rak', 'rak.kode_rak = buku.kode_rak')
            ->join('jenis_buku', 'jenis_buku.kode_jenis = buku.kode_jenis')
            ->findAll();   
        }else{
            $buku = $this->bukuModel
            ->join('penerbit','penerbit.kode_penerbit = buku.kode_penerbit')
            ->join('rak', 'rak.kode_rak = buku.kode_rak')
            ->join('jenis_buku', 'jenis_buku.kode_jenis = buku.kode_jenis')
            ->where('buku.kode_rak',$kode_rak)
            ->findAll();
        }

        $data = [
            'title' => 'Daftar Barcode Buku',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku,
            'buku' => $buku
        ];
        return view('admin/cetak/tablebarcode', $data);
    }

    function rak()
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }
        
        $rak = $this->rakModel->findAll();
        
        $data = [
            'title' => 'Daftar Barcode Buku',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku,
            'rak' => $rak
        ];
        return view('admin/cetak/tablerak', $data);
    }

    function cetakbuku($kode_buku = null)
    { 
        if ($kode_buku == null) {
            $buku = $this->bukuModel
            ->join('penerbit','penerbit.kode_penerbit = buku.kode_penerbit')
            ->join('rak', 'rak.kode_rak = buku.kode_rak')
            ->join('jenis_buku', 'jenis_buku.kode_jenis = buku.kode_jenis')
            ->findAll();
        }else{
            $buku = $this->bukuModel
            ->join('penerbit','penerbit.kode_penerbit = buku.kode_penerbit')
            ->join('rak', 'rak.kode_rak = buku.kode_rak')
            ->join('jenis_buku', 'jenis_buku.kode_jenis = buku.kode_jenis')
            ->where('buku.kode_buku',$kode_buku)
            ->findAll();
        }
        $data = [
            'title' => 'Daftar Barcode Buku',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku,
            'buku'=> $buku
        ];
        return view('admin/cetak/cetakbarcode', $data);
    }

    function cetakrak($kode_rak)
    {
        $buku = $this->bukuModel
        ->join('penerbit','penerbit.kode_penerbit = buku.kode_penerbit')
        ->join('rak', 'rak.kode_rak = buku.kode_rak')
        ->join('jenis_buku', 'jenis_buku.kode_jenis = buku.kode_jenis')
        ->where('buku.kode_rak',$kode_rak)
        ->findAll();

        $data = [
            'title' => 'Daftar Barcode Buku',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku,
            'buku'=> $buku
        ];
        return view('admin/cetak/cetakbarcode', $data);
    }

    public function bebas()
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }
        $data = [
            'title' => 'Surat Bebas Peminjaman',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];

        $siswa = $this->siswaModel
        ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
        ->findAll();

        $siswaresult = [];
        foreach ($siswa as  $s) {
            if ($this->trans->where('nis',$s['nis'])->where('status','pinjam')->countAllResults() > 0) {
                continue;
            }else{
                $siswaresult[] = $this->siswaModel
                ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
                ->join('tahun_ajaran','tahun_ajaran.kode_tahun = siswa.kode_tahun')
                ->where('nis',$s['nis'])
                ->first();
            }
        }

        $data['siswa'] = $siswaresult;
        return view('admin/cetak/tampilbebas', $data);
    }

    function cetakbebas($nis = null)
    {
        if (session()->get('login') == null) {
            return redirect()->to(base_url('login'));
        }
        $data = [
            'title' => 'Surat Bebas Peminjaman',
            'sekolah' => $this->sekolah,
            'perpus' => $this->perpus,
            'aku' => $this->aku
        ];

        if ($nis == null) {
            $data['cetak'] = $this->siswaModel
            ->join('kelas','siswa.kode_kelas = kelas.kode_kelas')
            ->join('tahun_ajaran', 'siswa.kode_tahun = tahun_ajaran.kode_tahun')
            ->findAll();
            return view('admin/cetak/cetakbebas', $data);
        }else{
            $data['cetak'] = $this->siswaModel
            ->join('kelas','kelas.kode_kelas = siswa.kode_kelas')
            ->join('tahun_ajaran', 'siswa.kode_tahun = tahun_ajaran.kode_tahun')
            ->where('nis', $nis)->findAll();
            return view('admin/cetak/cetakbebas', $data);
        }
    }
}