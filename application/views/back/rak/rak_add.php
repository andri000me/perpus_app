<?php $this->load->view('back/template/meta'); ?>
<div class="wrapper">

  <?php $this->load->view('back/template/navbar'); ?>
  <?php $this->load->view('back/template/sidebar'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><?php echo $page_title ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><?php echo $module ?></li>
        <li class="active"><?php echo $page_title ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php if ($this->session->flashdata('message')) {
        echo $this->session->flashdata('message');
      } ?>
      <?php echo validation_errors() ?>
      <div class="box box-primary">
        <?php echo form_open($action) ?>
        <div class="box-body">        
          <?php if (is_grandadmin()) { ?>
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group"><label>Perguruan Tinggi (*)</label>
                  <?php echo form_dropdown('', $get_all_combobox_instansi, '', $instansi_id) ?>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group"><label>Fakultas (*)</label>
                  <?php echo form_dropdown('', array('' => '- Pilih Perguruan Tinggi Dulu -'), '', $cabang_id) ?>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group"><label>Program Studi (*)</label>
                  <?php echo form_dropdown('', array('' => '- Pilih Fakultas Dulu -'), '', $divisi_id) ?>
                </div>
              </div>
            </div>
          <?php } elseif (is_masteradmin()) { ?>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group"><label>Fakultas (*)</label>
                  <?php echo form_dropdown('', $get_all_combobox_cabang, '', $cabang_id) ?>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group"><label>Program Studi (*)</label>
                  <?php echo form_dropdown('', array('' => '- Pilih Fakultas Dulu -'), '', $divisi_id) ?>
                </div>
              </div>
            </div>
          <?php } elseif (is_superadmin()) { ?>
            <div class="form-group"><label>Program Studi (*)</label>
            <?php echo form_dropdown('', $get_all_combobox_divisi, '', $divisi_id) ?>
            </div>
          <?php } ?>

          <div class="form-group"><label>Nama Rak</label>
            <?php echo form_input($rak_name) ?>
          </div>
        </div>
        <div class="box-footer">
          <button type="submit" name="button" class="btn btn-success"><i class="fa fa-save"></i> <?php echo $btn_submit ?></button>
          <button type="reset" name="button" class="btn btn-danger"><i class="fa fa-refresh"></i> <?php echo $btn_reset ?></button>
        </div>
        <!-- /.box-body -->
        <?php echo form_close() ?>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('back/template/footer'); ?>
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/') ?>datatables-bs/css/dataTables.bootstrap.min.css">
  <script src="<?php echo base_url('assets/plugins/') ?>datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url('assets/plugins/') ?>datatables-bs/js/dataTables.bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable();
    });

    function tampilCabang() {
      instansi_id = document.getElementById("instansi_id").value;
      $.ajax({
        url: "<?php echo base_url(); ?>admin/cabang/pilih_cabang/" + instansi_id + "",
        success: function(response) {
          $("#cabang_id").html(response);
        },
        dataType: "html"
      });
      return false;
    }

    function tampilDivisi() {
      cabang_id = document.getElementById("cabang_id").value;
      $.ajax({
        url: "<?php echo base_url(); ?>admin/divisi/pilih_divisi/" + cabang_id + "",
        success: function(response) {
          $("#divisi_id").html(response);
        },
        dataType: "html"
      });
      return false;
    }
  </script>

</div>
<!-- ./wrapper -->

</body>

</html>