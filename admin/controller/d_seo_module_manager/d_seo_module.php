<?php
class ControllerDSEOModuleManagerDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'd_seo_module_manager/d_seo_module';
	private $config_file = 'd_seo_module';
	private $error = array();
	
	/*
	*	Functions for SEO Module Manager.
	*/
	public function manager_config($data) {
		$this->load->language($this->route);
		
		$this->config->load($this->config_file);
		$config_setting = ($this->config->get($this->codename . '_manager')) ? $this->config->get($this->codename . '_manager') : array();
		
		foreach ($config_setting['sheet'] as &$sheet) {
			foreach ($sheet['field'] as &$field) {
				if (substr($field['name'], 0, strlen('text_')) == 'text_') {
					$field['name'] = $this->language->get($field['name']);
				}
			}
		}
		
		if (!empty($config_setting['sheet'])) {
			$data['sheet'] = array_replace_recursive($data['sheet'], $config_setting['sheet']);
		}
					
		return $data;
	}
	
	public function manager_list_elements($filter_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_manager_' . $this->codename}->getListElements($filter_data);
	}
	
	public function manager_save_element_field($element_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_manager_' . $this->codename}->saveElementField($element_data);
	}
	
	public function manager_export_elements($export_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_manager_' . $this->codename}->getExportElements($export_data);
	}
	
	public function manager_import_elements($import_data) {	
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_manager_' . $this->codename}->saveImportElements($import_data);
	}	
}
