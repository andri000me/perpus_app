<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Baris extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->data['module'] = 'Baris';

    $this->load->model(array('Baris_model'));

    $this->data['company_data']             = $this->Company_model->company_profile();
    $this->data['layout_template']          = $this->Template_model->layout();
    $this->data['skins_template']           = $this->Template_model->skins();
    $this->data['footer']                   = $this->Footer_model->footer();

    $this->data['btn_submit'] = 'Save';
    $this->data['btn_reset']  = 'Reset';
    $this->data['btn_add']    = 'Tambah Data';
    $this->data['add_action'] = base_url('admin/baris/create');

    is_login();

    if (is_admin() and is_pegawai()) {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak masuk ke halaman sebelumnya</div>');
      redirect('admin/dashboard');
    }

    if ($this->uri->segment(2) != NULL) {
      menuaccess_check();
    } elseif ($this->uri->segment(3) != NULL) {
      submenuaccess_check();
    }
  }

  function index()
  {
    is_read();

    $this->data['page_title'] = 'Data ' . $this->data['module'];

    if (is_grandadmin()) {
      $this->data['get_all'] = $this->Baris_model->get_all();
    } elseif (is_masteradmin()) {
      $this->data['get_all'] = $this->Baris_model->get_all_by_instansi();
    } 

    $this->load->view('back/baris/baris_list', $this->data);
  }

  function create()
  {
    is_create();

    $this->data['page_title'] = 'Tambah Data ' . $this->data['module'];
    $this->data['action']     = 'admin/baris/create_action';

    if (is_grandadmin()) {
      $this->data['get_all_combobox_instansi']     = $this->Instansi_model->get_all_combobox();
      $this->data['get_all_combobox_cabang']       = $this->Cabang_model->get_all_combobox();
      $this->data['get_all_combobox_lokasi']       = $this->Lokasi_model->get_all_combobox();
    } elseif (is_masteradmin()) {
      $this->data['get_all_combobox_cabang']       = $this->Cabang_model->get_all_combobox_by_instansi($this->session->instansi_id);
      $this->data['get_all_combobox_lokasi']       = $this->Lokasi_model->get_all_combobox_by_instansi($this->session->instansi_id);
    } 

    $this->data['baris_name'] = [
      'name'          => 'baris_name',
      'id'            => 'baris_name',
      'class'         => 'form-control',
      'autocomplete'  => 'off',
      'required'      => '',
      'value'         => $this->form_validation->set_value('baris_name'),
    ];
    $this->data['instansi_id'] = [
      'name'          => 'instansi_id',
      'id'            => 'instansi_id',
      'class'         => 'form-control',
      'onChange'      => 'tampilLokasi()',
      'required'      => '',
    ];
    $this->data['cabang_id'] = [
      'name'          => 'cabang_id',
      'id'            => 'cabang_id',
      'class'         => 'form-control',
      'onChange'      => 'tampilDivisi()',
      'required'      => '',
    ];
    $this->data['divisi_id'] = [
      'name'          => 'divisi_id',
      'id'            => 'divisi_id',
      'class'         => 'form-control',
      'required'      => '',
    ];
    $this->data['lokasi_id'] = [
      'name'          => 'lokasi_id',
      'id'            => 'lokasi_id',
      'class'         => 'form-control',
      'onChange'      => 'tampilRak()',
      'required'      => '',
    ];
    $this->data['rak_id'] = [
      'name'          => 'rak_id',
      'id'            => 'rak_id',
      'class'         => 'form-control',
      'required'      => '',
    ];

    $this->load->view('back/baris/baris_add', $this->data);
  }

  function create_action()
  {
    $this->form_validation->set_rules('baris_name', 'Nama Baris', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if (is_grandadmin()) {
      $instansi_id  = $this->input->post('instansi_id');
      $lokasi_id  = $this->input->post('lokasi_id');
      $rak_id  = $this->input->post('rak_id');
      $this->data['check_by_name']  = $this->Baris_model->check_by_name_and_rak_and_lokasi_and_instansi($this->input->post('baris_name'), $instansi_id, $lokasi_id, $rak_id);
    } elseif (is_masteradmin()) {
      $instansi_id  = $this->session->instansi_id;
      $lokasi_id  = $this->input->post('lokasi_id');
      $rak_id  = $this->input->post('rak_id');
      $this->data['check_by_name']  = $this->Baris_model->check_by_name_and_rak_and_lokasi_and_instansi($this->input->post('baris_name'), $instansi_id, $lokasi_id, $rak_id);
    } 

    if ($this->form_validation->run() === FALSE) {
      $this->create();
    } elseif ($this->input->post('baris_name') == $this->data['check_by_name']->baris_name) {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Nama ' . $this->data['module'] . ' telah ada, silahkan ganti yang lain</div>');
      $this->create();
    } else {
      $data = array(
        'baris_name'        => $this->input->post('baris_name'),
        'lokasi_id'         => $this->input->post('lokasi_id'),
        'rak_id'            => $this->input->post('rak_id'),
        'instansi_id'       => $instansi_id,
        'cabang_id'         => $cabang_id,
        'divisi_id'         => $divisi_id,
        'created_by'        => $this->session->username,
      );

      $this->Baris_model->insert($data);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan</div>');
      redirect('admin/baris');
    }
  }

  function update($id)
  {
    is_update();

    $this->data['baris']     = $this->Baris_model->get_by_id($id);

    if ($this->data['baris']) {
      $this->data['page_title'] = 'Update Data ' . $this->data['module'];
      $this->data['action']     = 'admin/baris/update_action';

      if (is_grandadmin()) {
        $this->data['get_all_combobox_instansi']     = $this->Instansi_model->get_all_combobox();
        $this->data['get_all_combobox_cabang']       = $this->Cabang_model->get_all_combobox_update($this->data['baris']->instansi_id);
        $this->data['get_all_combobox_divisi']       = $this->Divisi_model->get_all_combobox_update($this->data['baris']->cabang_id);
        $this->data['get_all_combobox_lokasi']       = $this->Lokasi_model->get_all_combobox_update($this->data['baris']->instansi_id);
        $this->data['get_all_combobox_rak']       = $this->Rak_model->get_all_combobox_update_by_lokasi($this->data['baris']->lokasi_id);
      } elseif (is_masteradmin()) {
        $this->data['get_all_combobox_cabang']       = $this->Cabang_model->get_all_combobox_update($this->data['baris']->instansi_id);
        $this->data['get_all_combobox_divisi']       = $this->Divisi_model->get_all_combobox_update($this->data['baris']->cabang_id);
        $this->data['get_all_combobox_lokasi']       = $this->Lokasi_model->get_all_combobox_by_instansi($this->session->instansi_id);
        $this->data['get_all_combobox_rak']       = $this->Rak_model->get_all_combobox_update_by_lokasi($this->data['baris']->lokasi_id);
      } 

      $this->data['id_baris'] = [
        'name'          => 'id_baris',
        'type'          => 'hidden',
      ];
      $this->data['baris_name'] = [
        'name'          => 'baris_name',
        'id'            => 'baris_name',
        'class'         => 'form-control',
        'autocomplete'  => 'off',
        'required'      => '',
      ];
      $this->data['instansi_id'] = [
        'name'          => 'instansi_id',
        'id'            => 'instansi_id',
        'class'         => 'form-control',
        'onChange'      => 'tampilLokasi()',
        'required'      => '',
      ];
      $this->data['cabang_id'] = [
        'name'          => 'cabang_id',
        'id'            => 'cabang_id',
        'class'         => 'form-control',
        'onChange'      => 'tampilDivisi()',
        'required'      => '',
      ];
      $this->data['divisi_id'] = [
        'name'          => 'divisi_id',
        'id'            => 'divisi_id',
        'class'         => 'form-control',
        'required'      => '',
      ];
      $this->data['lokasi_id'] = [
        'name'          => 'lokasi_id',
        'id'            => 'lokasi_id',
        'class'         => 'form-control',
        'onChange'      => 'tampilRak()',
        'required'      => '',
      ];
      $this->data['rak_id'] = [
        'name'          => 'rak_id',
        'id'            => 'rak_id',
        'class'         => 'form-control',
        'required'      => '',
      ];

      $this->load->view('back/baris/baris_edit', $this->data);
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('admin/baris');
    }
  }

  function update_action()
  {
    $this->form_validation->set_rules('baris_name', 'Nama Baris', 'trim|required');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

    if (is_grandadmin()) {
      $instansi_id  = $this->input->post('instansi_id');
      $cabang_id    = $this->input->post('cabang_id');
      $divisi_id    = $this->input->post('divisi_id');
    } elseif (is_masteradmin()) {
      $instansi_id  = $this->session->instansi_id;
      $cabang_id    = $this->input->post('cabang_id');
      $divisi_id    = $this->input->post('divisi_id');
    } 

    if ($this->form_validation->run() === FALSE) {
      $this->update($this->input->post('id_baris'));
    } else {
      $data = array(
        'baris_name'            => $this->input->post('baris_name'),
        'lokasi_id'            => $this->input->post('lokasi_id'),
        'rak_id'              => $this->input->post('rak_id'),
        'instansi_id'         => $instansi_id,
        'cabang_id'           => $cabang_id,
        'divisi_id'           => $divisi_id,
        'modified_by'         => $this->session->username,
      );

      $this->Baris_model->update($this->input->post('id_baris'), $data);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan</div>');
      redirect('admin/baris');
    }
  }

  function delete($id)
  {
    is_delete();

    $delete = $this->Baris_model->get_by_id($id);

    if ($delete) {
      $data = array(
        'is_delete_baris'   => '1',
        'deleted_by'        => $this->session->username,
        'deleted_at'        => date('Y-m-d H:i:a'),
      );

      $this->Baris_model->soft_delete($id, $data);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil dihapus</div>');
      redirect('admin/baris');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('admin/baris');
    }
  }

  function delete_permanent($id)
  {
    is_delete();

    $delete = $this->Baris_model->get_by_id($id);

    if ($delete) {
      $this->Baris_model->delete($id);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil dihapus permanen</div>');
      redirect('admin/baris/deleted_list');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('admin/baris');
    }
  }

  function deleted_list()
  {
    is_restore();

    $this->data['page_title'] = 'Recycle Bin ' . $this->data['module'];

    if (is_grandadmin()) {
      $this->data['get_all_deleted'] = $this->Baris_model->get_all_deleted();
    } elseif (is_masteradmin()) {
      $this->data['get_all_deleted'] = $this->Baris_model->get_all_deleted_by_instansi();
    } 

    $this->load->view('back/baris/baris_deleted_list', $this->data);
  }

  function restore($id)
  {
    is_restore();

    $row = $this->Baris_model->get_by_id($id);

    if ($row) {
      $data = array(
        'is_delete_baris'   => '0',
        'deleted_by'        => NULL,
        'deleted_at'        => NULL,
      );

      $this->Baris_model->update($id, $data);

      write_log();

      $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil dikembalikan</div>');
      redirect('admin/baris/deleted_list');
    } else {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('admin/baris');
    }
  }

  function pilih_baris()
  {
    // $this->data['baris'] = $this->Baris_model->get_baris_by_divisi_combobox($this->uri->segment(4));
    $this->data['baris'] = $this->Baris_model->get_baris_by_rak_combobox($this->uri->segment(4));
    $this->load->view('back/baris/v_baris', $this->data);
  }
}
