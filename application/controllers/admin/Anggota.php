<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends CI_Controller
{
    //FUNCTION CONSTRUCTOR
    public function __construct()
    {
        parent::__construct();

        $this->data['module'] = 'Anggota';

        $this->load->model(array('Anggota_model'));

        $this->data['company_data']             = $this->Company_model->company_profile();
        $this->data['layout_template']          = $this->Template_model->layout();
        $this->data['skins_template']           = $this->Template_model->skins();
        $this->data['footer']                   = $this->Footer_model->footer();

        $this->data['btn_submit'] = 'Save';
        $this->data['btn_reset']  = 'Reset';
        $this->data['btn_add']    = 'Tambah Data';
        $this->data['add_action'] = base_url('admin/anggota/create');

        is_login();

        if ($this->uri->segment(2) != NULL) {
            menuaccess_check();
        } elseif ($this->uri->segment(3) != NULL) {
            submenuaccess_check();
        }
    }

    //MENAMPILKAN DATA ANGGOTA PADA INTERFACE (GET DATA)
    function index() 
    {

    }

    //FITUR TAMBAH DATA ANGGOTA (MENAMPILKAN FORM)
    function create()
    {
        is_create();

        $this->data['page_title'] = 'Tambah Data ' . $this->data['module'];
        $this->data['action'] = 'admin/anggota/create_action';

        if (is_grandadmin()) {
            $this->data['get_all_combobox_instansi']  = $this->Instansi_model->get_all_combobox();
        }

        $this->data['instansi_id'] = [
            'name'          => 'instansi_id',
            'id'            => 'instansi_id',
            'class'         => 'form-control',
            'autocomplete'  => 'off',
            'required'      => '',
        ];
        $this->data['no_induk'] = [
            'name'          => 'no_induk',
            'id'            => 'no_induk',
            'class'         => 'form-control',
            'autocomplete'  => 'off',
            'required'      => '',
        ];
        $this->data['anggota_name'] = [
            'name'          => 'anggota_name',
            'id'            => 'anggota_name',
            'class'         => 'form-control',
            'autocomplete'  => 'off',
            'required'      => '',
        ];
        $this->data['gender'] = [
            'name'          => 'gender',
            'id'            => 'gender',
            'class'         => 'form-control',
        ];
        $this->data['gender_value'] = [
            '1'             => 'Laki-Laki',
            '2'             => 'Perempuan',
        ];
        $this->data['angkatan'] = [
            'name'          => 'angkatan',
            'id'            => 'angkatan',
            'class'         => 'form-control',
            'autocomplete'  => 'off',
        ];
        $this->data['address'] = [
            'name'          => 'address',
            'id'            => 'address',
            'class'         => 'form-control',
            'autocomplete'  => 'off',
            'required'      => '',
            'rows'          => '5',
        ];

        $this->load->view('back/anggota/anggota_add', $this->data);
    }

    //FUNCTION UNTUK MENJALANKAN FITUR SIMPAN DATA ANGGOTA BARU
    function create_action()
    {
        if (is_grandadmin()) {
            $this->form_validation->set_rules('instansi_id', 'Instansi', 'required');
        }
        $this->form_validation->set_rules('no_induk', 'No Induk Mahasiswa', 'trim|required|is_unique[anggota.no_induk]');
        $this->form_validation->set_rules('anggota_name', 'Nama Anggota', 'trim|required|is_unique[anggota.anggota_name]');
        $this->form_validation->set_rules('angkatan', 'Angkatan', 'required');
        $this->form_validation->set_rules('address', 'Alamat', 'trim|required');

        $this->form_validation->set_message('required', '{field} wajib diisi');
        $this->form_validation->set_message('is_unique', '{field} sudah ada, silahkan ganti yang lain');

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

        if (is_grandadmin()) {
            $instansi_id = $this->input->post('instansi_id');
        } elseif (is_masteradmin()){
            $instansi_id = $this->session->instansi_id;
        }

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = array(
                'no_induk'      => $this->input->post('no_induk'),
                'anggota_name'  => $this->input->post('anggota_name'),
                'instansi_id'   => $instansi_id,
                'gender'        => $this->input->post('gender'),
                'angkatan'      => $this->input->post('angkatan'),
                'address'       => $this->input->post('address'), 
            );

            $this->Anggota_model->insert($data);

            write_log();

            $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan</div>');
            redirect('admin/anggota');
        }
    }
}

?>