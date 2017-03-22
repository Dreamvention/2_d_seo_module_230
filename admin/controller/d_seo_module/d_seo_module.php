<?php
class ControllerDSEOModuleDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'd_seo_module/d_seo_module';
	private $config_file = 'd_seo_module';
	private $error = array();
		
	/*
	*	Functions for SEO Module.
	*/
	public function menu() {
		$this->load->language($this->route);
		
		$menu = array();

		if ($this->user->hasPermission('access', 'extension/module/' . $this->codename)) {
			$menu[] = array(
				'name'	   		=> $this->language->get('heading_title_main'),
				'href'     		=> $this->url->link('extension/module/' . $this->codename, 'token=' . $this->session->data['token'], true),
				'sort_order' 	=> 1,
				'children' 		=> array()
			);
		}

		return $menu;
	}

	public function language_add($data) {
		$this->load->model($this->route);

		$this->{'model_d_seo_module_' . $this->codename}->addLanguage($data);
	}
	
	public function language_delete($data) {
		$this->load->model($this->route);

		$this->{'model_d_seo_module_' . $this->codename}->deleteLanguage($data);
	}
	
	public function setting_script() {	
		$data['route'] = $this->route;
				
		return $this->load->view($this->route . '/setting_script.tpl');
	}
	
	public function category_form_tab_general_language() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('extension/module/d_seo_module');
		
		$languages = $this->{'model_d_seo_module_' . $this->codename}->getLanguages();
		
		$data['entry_target_keyword'] = $this->language->get('entry_target_keyword');
		
		$data['help_target_keyword'] = $this->language->get('help_target_keyword');
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['category_id'])) {	
			$data['target_keyword'] = $this->{'model_d_seo_module_' . $this->codename}->getCategoryTargetKeyword($this->request->get['category_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		$html_tab_general_language = array();
		
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$language['language_id']])) {
				foreach ($data['target_keyword'][$language['language_id']] as $sort_order => $keyword) {
					$target_keywords = $this->{'model_extension_module_' . $this->codename}->getTargetKeywords(array('filter_keyword' => $keyword));
					
					if ((count($target_keywords) > 1) || (count(reset($target_keywords)) > 1)) {
						$data['target_keyword_duplicate'][$language['language_id']][$sort_order] = 1;
					}
				}
			}
			
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/category_form_tab_general_language.tpl', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function category_form_style() {		
		return $this->load->view($this->route . '/category_form_style.tpl');
	}
	
	public function category_form_script() {	
		$data['route'] = $this->route;
		$data['token'] = $this->session->data['token'];
		
		return $this->load->view($this->route . '/category_form_script.tpl', $data);
	}
	
	public function category_form_add($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveCategoryTargetKeyword($data);
	}
	
	public function category_form_edit($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveCategoryTargetKeyword($data);
	}
	
	public function product_form_tab_general_language() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('extension/module/d_seo_module');
		
		$languages = $this->{'model_d_seo_module_' . $this->codename}->getLanguages();
		
		$data['entry_target_keyword'] = $this->language->get('entry_target_keyword');
				
		$data['help_target_keyword'] = $this->language->get('help_target_keyword');
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['product_id'])) {	
			$data['target_keyword'] = $this->{'model_d_seo_module_' . $this->codename}->getProductTargetKeyword($this->request->get['product_id']);
		} else {
			$data['target_keyword'] = array();
		}
		
		$html_tab_general_language = array();
		
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$language['language_id']])) {
				foreach ($data['target_keyword'][$language['language_id']] as $sort_order => $keyword) {
					$target_keywords = $this->{'model_extension_module_' . $this->codename}->getTargetKeywords(array('filter_keyword' => $keyword));
					
					if ((count($target_keywords) > 1) || (count(reset($target_keywords)) > 1)) {
						$data['target_keyword_duplicate'][$language['language_id']][$sort_order] = 1;
					}
				}
			}
			
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/product_form_tab_general_language.tpl', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function product_form_style() {		
		return $this->load->view($this->route . '/product_form_style.tpl');
	}
	
	public function product_form_script() {	
		$data['route'] = $this->route;
		$data['token'] = $this->session->data['token'];
		
		return $this->load->view($this->route . '/product_form_script.tpl', $data);
	}
	
	public function product_form_add($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveProductTargetKeyword($data);
	}
	
	public function product_form_edit($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveProductTargetKeyword($data);
	}
	
	public function manufacturer_form_tab_general_language() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('extension/module/d_seo_module');
		
		$languages = $this->{'model_d_seo_module_' . $this->codename}->getLanguages();
		
		$data['entry_target_keyword'] = $this->language->get('entry_target_keyword');
				
		$data['help_target_keyword'] = $this->language->get('help_target_keyword');
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$data['target_keyword'] = $this->{'model_d_seo_module_' . $this->codename}->getManufacturerTargetKeyword($this->request->get['manufacturer_id']);
		} else {
			$data['target_keyword'] = array();
		}
				
		$html_tab_general_language = array();
		
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$language['language_id']])) {
				foreach ($data['target_keyword'][$language['language_id']] as $sort_order => $keyword) {
					$target_keywords = $this->{'model_extension_module_' . $this->codename}->getTargetKeywords(array('filter_keyword' => $keyword));
					
					if ((count($target_keywords) > 1) || (count(reset($target_keywords)) > 1)) {
						$data['target_keyword_duplicate'][$language['language_id']][$sort_order] = 1;
					}
				}
			}
			
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/manufacturer_form_tab_general_language.tpl', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function manufacturer_form_style() {		
		return $this->load->view($this->route . '/manufacturer_form_style.tpl');
	}
	
	public function manufacturer_form_script() {	
		$data['route'] = $this->route;
		$data['token'] = $this->session->data['token'];
		
		return $this->load->view($this->route . '/manufacturer_form_script.tpl', $data);
	}
	
	public function manufacturer_form_add($data) {
		$this->load->model($this->route);

		$this->{'model_d_seo_module_' . $this->codename}->addManufacturerDescription($data);
		$this->{'model_d_seo_module_' . $this->codename}->saveManufacturerTargetKeyword($data);
	}
	
	public function manufacturer_form_edit($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveManufacturerTargetKeyword($data);
	}
	
	public function information_form_tab_general_language() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('extension/module/d_seo_module');
		
		$languages = $this->{'model_d_seo_module_' . $this->codename}->getLanguages();
		
		$data['entry_target_keyword'] = $this->language->get('entry_target_keyword');
				
		$data['help_target_keyword'] = $this->language->get('help_target_keyword');
		
		if (isset($this->request->post['target_keyword'])) {
			$data['target_keyword'] = $this->request->post['target_keyword'];
		} elseif (isset($this->request->get['information_id'])) {
			$data['target_keyword'] = $this->{'model_d_seo_module_' . $this->codename}->getInformationTargetKeyword($this->request->get['information_id']);
		} else {
			$data['target_keyword'] = array();
		}
			
		$html_tab_general_language = array();
		
		foreach ($languages as $language) {
			$data['language_id'] = $language['language_id'];
			
			if (isset($data['target_keyword'][$language['language_id']])) {
				foreach ($data['target_keyword'][$language['language_id']] as $sort_order => $keyword) {
					$target_keywords = $this->{'model_extension_module_' . $this->codename}->getTargetKeywords(array('filter_keyword' => $keyword));
					
					if ((count($target_keywords) > 1) || (count(reset($target_keywords)) > 1)) {
						$data['target_keyword_duplicate'][$language['language_id']][$sort_order] = 1;
					}
				}
			}
			
			$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/information_form_tab_general_language.tpl', $data);
		}
				
		return $html_tab_general_language;
	}
	
	public function information_form_style() {		
		return $this->load->view($this->route . '/information_form_style.tpl');
	}
	
	public function information_form_script() {	
		$data['route'] = $this->route;
		$data['token'] = $this->session->data['token'];
		
		return $this->load->view($this->route . '/information_form_script.tpl', $data);
	}
	
	public function information_form_add($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveInformationTargetKeyword($data);
	}
	
	public function information_form_edit($data) {
		$this->load->model($this->route);
		
		$this->{'model_d_seo_module_' . $this->codename}->saveInformationTargetKeyword($data);
	}
}
