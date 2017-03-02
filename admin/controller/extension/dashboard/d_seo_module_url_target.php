<?php
class ControllerExtensionDashboardDSEOModuleURLTarget extends Controller {
	private $codename = 'd_seo_module_url_target';
	private $main_codename = 'd_seo_module';
	private $route = 'extension/dashboard/d_seo_module_url_target';
	private $config_file = 'd_seo_module_url_target';
	private $extension = array();
	private $error = array(); 
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->d_shopunity = (file_exists(DIR_SYSTEM . 'mbooth/extension/d_shopunity.json'));
		$this->extension = json_decode(file_get_contents(DIR_SYSTEM . 'mbooth/extension/' . $this->main_codename . '.json'), true);
	}
	
	public function required(){
		$this->load->language($this->route);

		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['text_not_found'] = $this->language->get('text_not_found');
		$data['breadcrumbs'] = array();

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
	}

	public function index() {
		$this->load->language($this->route);
		
		$this->load->model('setting/setting');

		if (!$this->d_shopunity) {
			$this->response->redirect($this->url->link($this->route . '/required', 'codename=d_shopunity&token=' . $this->session->data['token'], true));
		}
		
		$this->load->model('d_shopunity/mbooth');
				
		$this->model_d_shopunity_mbooth->validateDependencies($this->main_codename);

		// Styles and Scripts
		$this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
						
		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		
		// Variable
		$data['codename'] = $this->codename;
		$data['route'] = $this->route;
		$data['version'] = $this->extension['version'];
		$data['config'] = $this->config_file;
		$data['d_shopunity'] = $this->d_shopunity;
		$data['token'] =  $this->session->data['token'];
										
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		// Action
		$data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], true);
		$data['action'] = $this->url->link($this->route . '/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);
		
		// Button
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');	
		
		// Entry
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_list_limit'] = $this->language->get('entry_list_limit');
		$data['entry_duplicate_status'] = $this->language->get('entry_duplicate_status');
		$data['entry_empty_status'] = $this->language->get('entry_empty_status');
		
		// Text		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		// Notification
		foreach($this->error as $key => $error){
			$data['error'][$key] = $error;
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		// Breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], true)
		);
		
		// Setting 	
		$this->config->load($this->config_file);
		$data['setting'] = ($this->config->get($this->codename)) ? $this->config->get($this->codename) : array();
		
		$setting = $this->model_setting_setting->getSetting('dashboard_' . $this->codename);	
		$status = isset($setting['dashboard_' . $this->codename . '_status']) ? $setting['dashboard_' . $this->codename . '_status'] : false;
		$width = isset($setting['dashboard_' . $this->codename . '_width']) ? $setting['dashboard_' . $this->codename . '_width'] : 12;
		$sort_order = isset($setting['dashboard_' . $this->codename . '_sort_order']) ? $setting['dashboard_' . $this->codename . '_sort_order'] : 20;
		$setting = isset($setting['dashboard_' . $this->codename . '_setting']) ? $setting['dashboard_' . $this->codename . '_setting'] : array();
		
		$data['status'] = $status;
		$data['width'] = $width;
		$data['sort_order'] = $sort_order;
								
		if (!empty($setting)) {
			$data['setting'] = array_replace_recursive($data['setting'], $setting);
		}
		
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->route, $data));
	}
	
	public function save() {
		$this->load->language($this->route);
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_' . $this->codename, $this->request->post);
						
			$this->session->data['success'] = $this->language->get('text_success_save');
		}
						
		$data['error'] = $this->error;
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$this->response->setOutput(json_encode($data));
	}
	
	public function dashboard() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		// Heading
		$data['heading_title'] = $this->language->get('heading_title_main');
		
		// Variable
		$data['codename'] = $this->codename;
		$data['route'] = $this->route;
		$data['token'] =  $this->session->data['token'];
		$data['stores'] = $this->{'model_extension_dashboard_' . $this->codename}->getStores();
		$data['languages'] = $this->{'model_extension_dashboard_' . $this->codename}->getLanguages();
		
		// Column
		$data['column_route'] = $this->language->get('column_route');
		$data['column_target_keyword'] = $this->language->get('column_target_keyword');
		
		// Text
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		// Setting
		$this->config->load($this->config_file);
		$config_setting = ($this->config->get($this->codename)) ? $this->config->get($this->codename) : array();
		
		$setting = $this->model_setting_setting->getSetting('dashboard_' . $this->codename);	
		$setting = isset($setting['dashboard_' . $this->codename . '_setting']) ? $setting['dashboard_' . $this->codename . '_setting'] : array();
		
		if (!empty($setting)) {
			$config_setting = array_replace_recursive($config_setting, $setting);
		}
		
		$setting = $config_setting;
		
		$seo_url_target_extensions = $this->{'model_extension_dashboard_' . $this->codename}->getSEOURLTargetExtensions();
		
		$targets = array();
		$implode = array();
				
		if ($setting['duplicate_status']) {
			$duplicate_targets = array();
			
			foreach ($seo_url_target_extensions as $seo_url_target_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_url_target_extension . '/duplicate_targets');
				if ($info) $duplicate_targets = array_replace_recursive($duplicate_targets, $info);
			}
			
			if ($duplicate_targets) $targets = array_replace_recursive($targets, $duplicate_targets);
						
			$implode[] = sprintf($this->language->get('text_heading_info_duplicate'), count($duplicate_targets));
		}
		
		if ($setting['empty_status']) {
			$empty_targets = array();
			
			foreach ($seo_url_target_extensions as $seo_url_target_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_url_target_extension . '/empty_targets');
				if ($info) $empty_targets = array_replace_recursive($empty_targets, $info);
			}
			
			if ($empty_targets) $targets = array_replace_recursive($targets, $empty_targets);
						
			$implode[] = sprintf($this->language->get('text_heading_info_empty'), count($empty_targets));
		}
		
		if ($implode) {
			$data['heading_title'] .= ' ' . $this->language->get('text_found') . ' ' . implode(' ' . $this->language->get('text_and') . ' ', $implode);
		}
						
		$data['targets'] = array();
		
		$i = 0;
		foreach ($targets as $target) {
			$data['targets'][] = $target;
			$i++;
			if ($i==$setting['list_limit']) break;
		}
		
		foreach ($seo_url_target_extensions as $seo_url_target_extension) {
			$targets = $this->load->controller($this->codename . '/' . $seo_url_target_extension . '/targets_links', $data['targets']);
			if ($targets) $data['targets'] = $targets;
		}

		return $this->load->view($this->route . '_info', $data);
	}
	
	public function refresh() {
		$this->response->setOutput($this->dashboard());
	}
	
	public function editTarget() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		
		if (isset($this->request->post['route']) && isset($this->request->post['language_id']) && isset($this->request->post['target_keyword']) && $this->validate()) {
			$target_data = array(
				'route'				=> $this->request->post['route'],
				'language_id'		=> $this->request->post['language_id'],
				'target_keyword'	=> $this->request->post['target_keyword']
			);
		
			$this->{'model_extension_dashboard_' . $this->codename}->editTarget($target_data);
		}
			
		$data['error'] = $this->error;
		
		$this->response->setOutput(json_encode($data));
	}
	
	/*
	*	Validator Functions.
	*/		
	private function validate($permission = 'modify') {
		if (isset($this->request->post['config'])) {
			return false;
		}
				
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}
		
		return true;
	}
}
