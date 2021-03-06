<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class aktifitas_staff extends Admin_Controller {
	public function __construct() {
		parent::__construct ();
		
		/* Load Library */
		$this->load->library('form_validation');
		$this->load->library('session');
		
		/* Title Page :: Common */
		$this->page_title->push(lang('menu_penilaian_kinerja'));
		$this->data['pagetitle'] = $this->page_title->show();
		
		/* Breadcrumbs :: Common */
		$this->breadcrumbs->unshift(1, lang('menu_users'), 'admin/users');
		$this->load->helper(array('form', 'url'));
		$this->load->model ('member/penilaian_kinerja_model' );
		
	}
	
	public function validationData(){
		
		$this->form_validation->set_rules('kd_penilaian_kinerja','lang:penilaian_kinerja_kd_penilaian_kinerja','max_length[50]');
		$this->form_validation->set_rules('nama', 'lang:pegawai_nama','max_length[2000]');
		
		return $this->form_validation->run();
	}
	
	/* member Property column */
	public function inputSetting($data){
		$this->data['nip'] = array(
				'name'  => 'nip',
				'id'    => 'nip',
				'type'  => 'text',
				'class' => 'form-control',
				'required'=> 'required',
				'placeholder'=>'nip',
				'value' => $data['nip'],
		);
		$this->data['tgl_kegiatan'] = array(
				'name'  => 'tgl_kegiatan',
				'id'    => 'tgl_kegiatan',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'nama penilaian_kinerja',
				'value' => $data['tgl_kegiatan'],
		);
		$this->data['id_tupoksi'] = array(
				'name'  => 'id_tupoksi',
				'id'    => 'id_tupoksi',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'kd tupoksi',
				'value' => $data['id_tupoksi'],
		);
		$this->data['tupoksi'] = array(
				'name'  => 'tupoksi',
				'id'    => 'tupoksi',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'tupoksi',
				'value' => $data['tupoksi'],
		);
		$this->data['id_uraian'] = array(
				'name'  => 'id_uraian',
				'id'    => 'id_uraian',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'kd uraian',
				'value' => $data['id_uraian'],
		);
		$this->data['uraian_tupoksi'] = array(
				'name'  => 'uraian_tupoksi',
				'id'    => 'uraian_tupoksi',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'uraian tupoksi',
				'value' => $data['uraian_tupoksi'],
		);
		$this->data['id_aktifitas'] = array(
				'name'  => 'id_aktifitas',
				'id'    => 'id_aktifitas',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'id aktifitas',
				'value' => $data['id_aktifitas'],
		);
		$this->data['nama_aktifitas'] = array(
				'name'  => 'nama_aktifitas',
				'id'    => 'nama_aktifitas',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'nama aktifitas',
				'value' => $data['nama_aktifitas'],
		);
		$this->data['nilai'] = array(
				'name'  => 'nilai',
				'id'    => 'nilai',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder'=>'nilai aktifitas',
				'value' => $data['nilai'],
		);
		return $this->data;
	}
	
	public function index() {
		
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth/login', 'refresh');
		}
		else
		{
			/* Breadcrumbs */
			$this->data['breadcrumb'] = $this->breadcrumbs->show();
		
			/* Get all users */

			$config = array ();
			$config ["base_url"] = base_url () . "member/penilaian_kinerja/index";
			$config ["total_rows"] = $this->penilaian_kinerja_model->record_count ();
			$config ["per_page"] = 25;
			$config ["uri_segment"] = 4;
			$choice = $config ["total_rows"] / $config ["per_page"];
			$config ["num_links"] = 5;
			
			// config css for pagination
			$config ['full_tag_open'] = '<ul class="pagination">';
			$config ['full_tag_close'] = '</ul>';
			$config ['first_link'] = 'First';
			$config ['last_link'] = 'Last';
			$config ['first_tag_open'] = '<li>';
			$config ['first_tag_close'] = '</li>';
			$config ['prev_link'] = 'Previous';
			$config ['prev_tag_open'] = '<li class="prev">';
			$config ['prev_tag_close'] = '</li>';
			$config ['next_link'] = 'Next';
			$config ['next_tag_open'] = '<li>';
			$config ['next_tag_close'] = '</li>';
			$config ['last_tag_open'] = '<li>';
			$config ['last_tag_close'] = '</li>';
			$config ['cur_tag_open'] = '<li class="active"><a href="#">';
			$config ['cur_tag_close'] = '</a></li>';
			$config ['num_tag_open'] = '<li>';
			$config ['num_tag_close'] = '</li>';
			
			if ($this->uri->segment ( 4 ) == "") {
				$data ['number'] = 0;
			} else {
				$data ['number'] = $this->uri->segment ( 4 );
			}
			
			$this->pagination->initialize ( $config );
			$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
			
			$this->data ['penilaian_kinerja'] = $this->penilaian_kinerja_model->fetchAll($config ["per_page"], $page);
			$this->data ['links'] = $this->pagination->create_links ();
			$this->template->admin_render('member/penilaian_kinerja/index', $this->data);
		}
	}
	
	public function add(){
		if($this->input->post('submit')){
			if($this->validationData()==TRUE){
				$data = array(
						'kd_penilaian_kinerja'=>$this->input->post('kd_penilaian_kinerja'),
						'nama'=>$this->input->post ('nama')
				);
				$this->penilaian_kinerja_model->create($data);
				redirect('member/penilaian_kinerja','refresh');
			}else{
				$this->session->set_flashdata('message', validation_errors());
				redirect('member/penilaian_kinerja/add', 'refresh');
			}
			
		} else {
			$data = array(
					'kd_penilaian_kinerja'=>null,
					'nama'=>null
			);
			$this->template->admin_render('member/penilaian_kinerja/form',$this->inputSetting($data));
		}
	}
	
	public function modify($id=null) {
		if($this->input->post('submit')){
			if($this->validationData()==TRUE){
				$data = array(
					'kd_penilaian_kinerja'=>$this->input->post('kd_penilaian_kinerja'),
					'nama'=>$this->input->post ('nama')
				);
			}
			$this->penilaian_kinerja_model->update($data);
			redirect('member/penilaian_kinerja','refresh');
		} else {
			$query = $this->penilaian_kinerja_model->fetchById($id);
			foreach ($query as $row){
				$this->template->admin_render('member/penilaian_kinerja/form',$this->inputSetting($row));
			}
		}
	}
	
	public function remove($id=null) {
		$this->penilaian_kinerja_model->delete($id);
		redirect ('member/penilaian_kinerja/index','refresh');
	}
	
	public function find(){
		
		
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth/login', 'refresh');
		}
		else {
			if($this->input->post('submit')){
				$column = $this->input->post('column');
				$query = $this->input->post('data');
				
				$option = array(
					'user_column'=>$column,
					'user_data'=>$query
				);
				$this->session->set_userdata($option);
			}else{
			   $column = $this->session->has_userdata('user_column');
			   $query = $this->session->has_userdata('user_data');
			}
			
			/* Breadcrumbs */
			$this->data['breadcrumb'] = $this->breadcrumbs->show();
		
			/* Get all users */
		
			$config = array ();
			$config ["base_url"] = base_url () . "member/penilaian_kinerja/find";
			$config ["total_rows"] = $this->penilaian_kinerja_model->search_count($column,$query);
			$config ["per_page"] = 25;
			$config ["uri_segment"] = 4;
			$choice = $config ["total_rows"] / $config ["per_page"];
			$config ["num_links"] = 5;
				
			// config css for pagination
			$config ['full_tag_open'] = '<ul class="pagination">';
			$config ['full_tag_close'] = '</ul>';
			$config ['first_link'] = 'First';
			$config ['last_link'] = 'Last';
			$config ['first_tag_open'] = '<li>';
			$config ['first_tag_close'] = '</li>';
			$config ['prev_link'] = 'Previous';
			$config ['prev_tag_open'] = '<li class="prev">';
			$config ['prev_tag_close'] = '</li>';
			$config ['next_link'] = 'Next';
			$config ['next_tag_open'] = '<li>';
			$config ['next_tag_close'] = '</li>';
			$config ['last_tag_open'] = '<li>';
			$config ['last_tag_close'] = '</li>';
			$config ['cur_tag_open'] = '<li class="active"><a href="#">';
			$config ['cur_tag_close'] = '</a></li>';
			$config ['num_tag_open'] = '<li>';
			$config ['num_tag_close'] = '</li>';
				
			if ($this->uri->segment ( 4 ) == "") {
				$data ['number'] = 0;
			} else {
				$data ['number'] = $this->uri->segment ( 4 );
			}
				
			$this->pagination->initialize ( $config );
			$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
				
			$this->data ['pegawai'] = $this->penilaian_kinerja_model->search($column,$query,$config ["per_page"], $page);
			$this->data ['links'] = $this->pagination->create_links ();
			$this->template->admin_render('member/penilaian_kinerja/index', $this->data);
		}
	}
	
	public function uploadImgae(){
		
	}
	
}