<?= $this->extend('admin/template/template'); ?>
 <!-- Content Wrapper. Contains page content -->
 <?= $this->section('content'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Ubah Alumni</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('pustakawan'); ?>">Pustakawan</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('pustakawan/alumni'); ?>">Alumni</a></li>
              <li class="breadcrumb-item">Ubah</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- ROW -->
        <div class="row">
            <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <div class="card-title">
                    <h3>Ubah Data Alumni</h3>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="<?= base_url('pustakawan/alumni/update'); ?>" method="post" enctype="multipart/form-data" class="formconfirm">
              <?= csrf_field(); ?>
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-md-3 my-2 col-sm-12">
                      <img src="<?= base_url('public/admin/img/siswa/'.$siswa['foto']); ?>" alt="Foto Siswa" class="img-thumbnail">
                    </div>
                    <div class="col-md-9 col-sm-12">
                      <div class="form-group">
                        <label for="foto">Unggah Foto Alumni</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input name="foto" type="file" class="custom-file-input" id="foto">
                            <label class="custom-file-label" for="foto">Pilih file gambar</label>
                          </div>
                          <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="nis">NIS Alumni</label>
                      <input type="text" value="<?= (old('nis')) ? old('nis') : $siswa['nis']; ?>" name="nis" class="angka form-control" id="nis" placeholder="" readonly>
                  </div>
                  <div class="form-group">
                    <label for="nisn">NISN Alumni</label>
                    <input type="text" value="<?= (old('nisn')) ? old('nisn') : $siswa['nisn']; ?>" name="nisn" class="angka form-control" id="nisn" placeholder="" required>
                  </div>
                  <div class="form-group">
                    <label for="nama_siswa">Nama Alumni</label>
                    <input type="text" value="<?= (old('nama_siswa')) ? old('nama_siswa') : $siswa['nama_siswa']; ?>" name="nama_siswa" class="form-control" id="nama_siswa" placeholder="" required>
                  </div>
                  <div class="form-group">
                      <label for="kode_kelas">Pilih Kelas</label>
                      <select id="kode_kelas" name="kode_kelas" class="form-control" required>
                        <option></option>
                        <?php foreach($kelas as $k) : ?>
                        <option value="<?= $k['kode_kelas']; ?>" <?= (old('kode_kelas') == $siswa['kode_kelas'] || $k['kode_kelas'] == $siswa['kode_kelas']) ? 'selected' : ''; ?> ><?= $k['nama_kelas']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="kode_tahun">Pilih Tahun Ajaran</label>
                      <select id="kode_tahun" name="kode_tahun" class="form-control" required>
                        <option></option>
                        <?php foreach($tahun as $t) : ?>
                        <option value="<?= $t['kode_tahun'] ?>" <?= ($t['kode_tahun'] == $siswa['kode_tahun'] OR old('kode_tahun') == $siswa['kode_tahun']) ? 'selected' : ''; ?> ><?= $t['nama_tahun']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  <div class="form-group">
                    <label for="wa">Nomor WhastApp</label>
                    <input type="text" value="<?= (old('wa')) ? old('wa') : $siswa['wa']; ?>" name="wa" class="angka form-control" id="wa" placeholder="" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email Alumni</label>
                    <input type="email" value="<?= (old('email')) ? old('email') : $siswa['email']; ?>" name="email" class="form-control" id="email" placeholder="" required>
                  </div>
                  <div class="form-group">
                    <label for="alamat_siswa">Alamat Alumni</label>
                    <textarea name="alamat_siswa" class="form-control" id="alamat_siswa" rows="3"><?= (old('alamat_siswa')) ? old('alamat_siswa') : $siswa['alamat_siswa']; ?></textarea>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" id="submitconfirm" class="btn btn-primary my-1"><i class="fas fa-solid fa-pen"></i> Ubah Alumni</button>
                  <a href="<?= base_url('pustakawan/alumni'); ?>" class="btn btn-danger my-1"><i class="fas fa-solid fa-ban"></i> Batal</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?= $this->endSection(); ?>