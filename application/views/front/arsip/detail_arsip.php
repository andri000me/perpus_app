<?php $this->load->view('front/template/meta'); ?>

<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">

    <?php $this->load->view('front/template/navbar'); ?>

    <div class="content-wrapper">
      <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Detail Buku</h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url() ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"> Detail Buku</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="box box-primary box-solid">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group"><label>No/Label Buku</label>
                    <p><?php echo $detail_arsip->no_arsip ?></p>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group"><label>Judul Buku</label>
                    <p><?php echo $detail_arsip->arsip_name ?></p>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group"><label>Perguruan Tinggi</label>
                    <p><?php echo $detail_arsip->instansi_name ?></p>
                  </div>
                </div>
              </div>
              
              <div class="form-group"><label>Deskripsi Buku</label>
                <p><?php echo $detail_arsip->deskripsi_arsip ?></p>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group"><label>Lokasi Buku</label>
                    <p><?php echo $detail_arsip->lokasi_name ?></p>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group"><label>Nomor Rak</label>
                    <p><?php echo $detail_arsip->rak_name ?></p>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group"><label>Nomor Baris</label>
                    <p><?php echo $detail_arsip->baris_name ?></p>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group"><label>Jumlah/Stok Buku</label>
                    <p><?php echo $detail_arsip->qty ?></p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group"><label>Dibuat Pada</label>
                    <p><?php echo datetime_indo($detail_arsip->waktu_dibuat) ?></p>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group"><label>Dibuat Oleh</label>
                    <p><?php echo $detail_arsip->dibuat_oleh ?></p>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group"><label>Cover Buku</label><br>
                    <?php if ($detail_arsip->cover_buku_thumb != NULL) { ?>
                      <img src="<?php echo base_url('assets/images/cover_buku/'.$detail_arsip->cover_buku_thumb) ?>" width="100px" height="120px">
                    <?php } else { ?>
                      <img src="<?php echo base_url('assets/images/noimage.jpg') ?>" width="100px" height="120px">
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <?php
            // Jika grand/masteradmin, tampilkan semua file
            if (is_grandadmin() or is_masteradmin()) {
            ?>
              <div class="box-footer">
                <div class="form-group"><label>File</label>
                  <br>
                  <?php if ($file_upload == NULL) {
                    echo "<button class='btn btn-sm btn-danger'><i class='fa fa-remove'></i> Belum ada data</button>";
                  } ?>
                  <ol>
                    <?php foreach ($file_upload as $files) { ?>
                      <li>
                        <b>FileName:</b> <?php echo $files->file_upload ?><br>
                        <a href="<?php echo base_url('assets/file_arsip/' . $instansiName . '/') . $files->file_upload ?>" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-download"></i> Download/Lihat</a>
                      </li><br>
                    <?php } ?>
                  </ol>
                </div>
              </div>

            <?php } ?>
          </div>

          <a href="<?php echo base_url('auth/book_searching') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali ke halaman sebelumnya</a>

        </section>
        <!-- /.content -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->

    <?php $this->load->view('front/template/footer'); ?>

</body>

</html>