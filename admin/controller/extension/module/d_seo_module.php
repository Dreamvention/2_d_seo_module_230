<?php
class ControllerExtensionModuleDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'extension/module/d_seo_module';
	private $config_file = 'd_seo_module';
	private $extension = array();
	private $error = array();

	public function __construct($registry) {
		parent::__construct($registry);

		$this->d_shopunity = (file_exists(DIR_SYSTEM . 'mbooth/extension/d_shopunity.json'));
		$this->extension = json_decode(file_get_contents(DIR_SYSTEM . 'mbooth/extension/' . $this->codename . '.json'), true);
	}

	public function required() {
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
		$this->setting();
	}

	public function setting() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if (!$this->d_shopunity) {
			$this->response->redirect($this->url->link($this->route . '/required', 'codename=d_shopunity&token=' . $this->session->data['token'], true));
		}
		
		$this->load->model('d_shopunity/mbooth');

		$this->model_d_shopunity_mbooth->validateDependencies($this->codename);

		// Styles and Scripts
		$this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
		$this->document->addStyle('view/stylesheet/' . $this->codename . '.css');

		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');

		// Variable
		$data['codename'] = $this->codename;
		$data['route'] = $this->route;
		$data['version'] = $this->extension['version'];
		$data['config'] = $this->config_file;
		$data['d_shopunity'] = $this->d_shopunity;
		$data['token'] = $this->session->data['token'];
		$data['stores'] = $this->{'model_extension_module_' . $this->codename}->getStores();
		$data['languages'] = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$data['installed'] = in_array($this->codename, $seo_extensions) ? true : false;

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$data['catalog_parse'] = parse_url($data['catalog']);
		
		// Action
		$data['href_setting'] = $this->url->link($this->route . '/setting', 'token=' . $this->session->data['token'], true);
		$data['href_custom_page'] = $this->url->link($this->route . '/custom_page', 'token=' . $this->session->data['token'], true);
		$data['href_export_import'] = $this->url->link($this->route . '/export_import', 'token=' . $this->session->data['token'], true);
		$data['href_instruction'] = $this->url->link($this->route . '/instruction', 'token=' . $this->session->data['token'], true);
		
		$data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], true);
		$data['action'] = $this->url->link($this->route . '/save', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		$data['install'] = $this->url->link($this->route . '/installModule', 'token=' . $this->session->data['token'], true);
		$data['uninstall'] = $this->url->link($this->route . '/uninstallModule', 'token=' . $this->session->data['token'], true);
		$data['store_setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);

		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_custom_pages'] = $this->language->get('text_custom_pages');
		$data['text_export_import'] = $this->language->get('text_export_import');
		$data['text_instructions'] = $this->language->get('text_instructions');
		$data['text_basic_settings'] = $this->language->get('text_basic_settings');
		$data['text_htaccess'] = $this->language->get('text_htaccess');
		$data['text_robots'] = $this->language->get('text_robots');

		// Button
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		$data['button_edit_store_setting'] = $this->language->get('button_edit_store_setting');

		// Entry
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_list_limit'] = $this->language->get('entry_list_limit');
		$data['entry_uninstall'] = $this->language->get('entry_uninstall');
		$data['entry_text'] = $this->language->get('entry_text');

		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_install'] = $this->language->get('text_install');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_uninstall_confirm'] = $this->language->get('text_uninstall_confirm');
		
		// Help
		$data['help_install'] = $this->language->get('help_install');
		$data['help_htaccess_setting'] = $this->language->get('help_htaccess_setting');
		$data['help_htaccess_subfolder'] = $this->language->get('help_htaccess_subfolder');
		$data['help_robots'] = sprintf($this->language->get('help_robots'), $data['catalog'], $data['catalog_parse']['host']);
		
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
			'text' => $this->language->get('text_modules'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], true)
		);

		// Setting
		$this->config->load($this->config_file);
		$data['setting'] = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
		
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		$setting = isset($setting[$this->codename . '_setting']) ? $setting[$this->codename . '_setting'] : array();
		
		$data['status'] = $status;
		
		if (!empty($setting)) {
			$data['setting'] = array_replace_recursive($data['setting'], $setting);
		}
		
		$data['htaccess'] = $this->{'model_extension_module_' . $this->codename}->getFileData('htaccess');
		$data['robots'] = $this->{'model_extension_module_' . $this->codename}->getFileData('robots');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($data['installed']) {
			$this->response->setOutput($this->load->view($this->route . '/setting.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view($this->route . '/install.tpl', $data));
		}
	}
	
	public function custom_page() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if (!$this->d_shopunity) {
			$this->response->redirect($this->url->link($this->route . '/required', 'codename=d_shopunity&token=' . $this->session->data['token'], true));
		}
		
		$this->load->model('d_shopunity/mbooth');
				
		$this->model_d_shopunity_mbooth->validateDependencies($this->codename);

		// Styles and Scripts
		$this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
		$this->document->addStyle('view/stylesheet/' . $this->codename . '.css');
	
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
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
		$data['stores'] = $this->{'model_extension_module_' . $this->codename}->getStores();
		$data['languages'] = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$data['installed'] = in_array($this->codename, $seo_extensions) ? true : false;
						
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
				
		// Action
		$data['href_setting'] = $this->url->link($this->route . '/setting', 'token=' . $this->session->data['token'], true);
		$data['href_custom_page'] = $this->url->link($this->route . '/custom_page', 'token=' . $this->session->data['token'], true);
		$data['href_export_import'] = $this->url->link($this->route . '/export_import', 'token=' . $this->session->data['token'], true);
		$data['href_instruction'] = $this->url->link($this->route . '/instruction', 'token=' . $this->session->data['token'], true);
		
		$data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		$data['install'] = $this->url->link($this->route . '/installModule', 'token=' . $this->session->data['token'], true);
				
		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_custom_pages'] = $this->language->get('text_custom_pages');
		$data['text_export_import'] = $this->language->get('text_export_import');
		$data['text_instructions'] = $this->language->get('text_instructions');
						
		// Button
		$data['button_save'] = $this->language->get('button_save');
		$data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');	
		$data['button_install'] = $this->language->get('button_install');
		$data['button_create_default_custom_page'] = $this->language->get('button_create_default_custom_page');
		$data['button_add_custom_page'] = $this->language->get('button_add_custom_page');
		$data['button_delete_custom_page'] = $this->language->get('button_delete_custom_page');	
						
		// Column
		$data['column_route'] = $this->language->get('column_route');
		$data['column_target_keyword'] = $this->language->get('column_target_keyword');
		
		// Entry
		$data['entry_route'] = $this->language->get('entry_route');
		$data['entry_target_keyword'] = $this->language->get('entry_target_keyword');
				
		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_install'] = $this->language->get('text_install');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_add_custom_page'] = $this->language->get('text_add_custom_page');
		$data['text_delete_custom_pages_confirm'] = $this->language->get('text_delete_custom_pages_confirm');
		$data['text_create_default_custom_pages_confirm'] = $this->language->get('text_create_default_custom_pages_confirm');
		
		// Help
		$data['help_install'] = $this->language->get('help_install');
		
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
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		// Breadcrumbs
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_modules'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], true)
		);
				
		// Setting 	
		$this->config->load($this->config_file);
		$data['setting'] = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();
		
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$setting = isset($setting[$this->codename . '_setting']) ? $setting[$this->codename . '_setting'] : array();
										
		if (!empty($setting)) {
			$data['setting'] = array_replace_recursive($data['setting'], $setting);
		}
		
		$custom_pages = $this->{'model_extension_module_' . $this->codename}->getCustomPages();
		
		$data['custom_pages'] = array();
		
		$i = 0;
		foreach ($custom_pages as $custom_page) {
			if (isset($custom_page['target_keyword'])) {
				foreach ($custom_page['target_keyword'] as $language_id => $target_keyword) {
					foreach ($target_keyword as $sort_order => $keyword) {
						if (count($this->{'model_extension_module_' . $this->codename}->getTargetKeywords(array('filter_keyword' => $keyword)))>1) {
							$custom_page['target_keyword_duplicate'][$language_id][$sort_order] = 1;
						}
					}
				}
			}
			
			if (($i>=(($page-1)*$data['setting']['list_limit'])) && ($i<((($page-1)*$data['setting']['list_limit'])+$data['setting']['list_limit']))) {
				$data['custom_pages'][] = $custom_page;
			}
			$i++;
			if ($i == ((($page-1)*$data['setting']['list_limit']) + $data['setting']['list_limit'])) break;
		}
						
		$pagination = new Pagination();
		$pagination->total = count($custom_pages);
		$pagination->page = $page;
		$pagination->limit = $data['setting']['list_limit'];
		$pagination->url = $this->url->link($this->route . '/custom_page', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), (count($custom_pages)) ? (($page - 1) * $data['setting']['list_limit']) + 1 : 0, ((($page - 1) * $data['setting']['list_limit']) > (count($custom_pages) - $data['setting']['list_limit'])) ? count($custom_pages) : ((($page - 1) * $data['setting']['list_limit']) + $data['setting']['list_limit']), count($custom_pages), ceil(count($custom_pages) / $data['setting']['list_limit']));
		
		$data['page'] = $page;		
						
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($data['installed']) {
			$this->response->setOutput($this->load->view($this->route . '/custom_page.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view($this->route . '/install.tpl', $data));
		}
	}
	
	public function export_import() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if (!$this->d_shopunity) {
			$this->response->redirect($this->url->link($this->route . '/required', 'codename=d_shopunity&token=' . $this->session->data['token'], true));
		}
		
		$this->load->model('d_shopunity/mbooth');
				
		$this->model_d_shopunity_mbooth->validateDependencies($this->codename);

		// Styles and Scripts
		$this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
		$this->document->addStyle('view/stylesheet/' . $this->codename . '.css');
				
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
		$data['stores'] = $this->{'model_extension_module_' . $this->codename}->getStores();
				
		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$data['installed'] = in_array($this->codename, $seo_extensions) ? true : false;
						
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
				
		// Action
		$data['href_setting'] = $this->url->link($this->route . '/setting', 'token=' . $this->session->data['token'], true);
		$data['href_custom_page'] = $this->url->link($this->route . '/custom_page', 'token=' . $this->session->data['token'], true);
		$data['href_export_import'] = $this->url->link($this->route . '/export_import', 'token=' . $this->session->data['token'], true);
		$data['href_instruction'] = $this->url->link($this->route . '/instruction', 'token=' . $this->session->data['token'], true);
		
		$data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		$data['install'] = $this->url->link($this->route . '/installModule', 'token=' . $this->session->data['token'], true);
		$data['export'] = $this->url->link($this->route . '/export', 'token=' . $this->session->data['token'], true);
		$data['import'] = $this->url->link($this->route . '/import', 'token=' . $this->session->data['token'], true);
		
		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_custom_url'] = $this->language->get('text_custom_url');
		$data['text_export_import'] = $this->language->get('text_export_import');
		$data['text_instructions'] = $this->language->get('text_instructions');
		
		$data['text_export'] = $this->language->get('text_export');
		$data['text_import'] = $this->language->get('text_import');
				
		// Button
		$data['button_cancel'] = $this->language->get('button_cancel');	
		$data['button_install'] = $this->language->get('button_install');
		$data['button_export'] = $this->language->get('button_export');
		$data['button_import'] = $this->language->get('button_import');
				
		// Entry
		$data['entry_sheet'] = $this->language->get('entry_sheet');
		$data['entry_export'] = $this->language->get('entry_export');
		$data['entry_upload'] = $this->language->get('entry_upload');
		$data['entry_import'] = $this->language->get('entry_import');
				
		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_install'] = $this->language->get('text_install');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['text_custom_pages'] = $this->language->get('text_custom_pages');
		
		// Help
		$data['help_install'] = $this->language->get('help_install');
		
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
			'text' => $this->language->get('text_modules'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], true)
		);
				
		// Setting 		
		$this->config->load($this->config_file);
		$data['setting'] = ($this->config->get($this->codename)) ? $this->config->get($this->codename) : array();
		
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$setting = isset($setting[$this->codename . '_setting']) ? $setting[$this->codename . '_setting'] : array();
		
		if (!empty($setting)) {
			$data['setting'] = array_replace_recursive($data['setting'], $setting);
		}
						
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($data['installed']) {
			$this->response->setOutput($this->load->view($this->route . '/export_import.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view($this->route . '/install.tpl', $data));
		}
	}
	
	public function instruction() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if (!$this->d_shopunity) {
			$this->response->redirect($this->url->link($this->route . '/required', 'codename=d_shopunity&token=' . $this->session->data['token'], true));
		}
		
		$this->load->model('d_shopunity/mbooth');
				
		$this->model_d_shopunity_mbooth->validateDependencies($this->codename);

		// Styles and Scripts
		$this->document->addLink('//fonts.googleapis.com/css?family=PT+Sans:400,700,700italic,400italic&subset=latin,cyrillic-ext,latin-ext,cyrillic', "stylesheet");
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');
		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
		$this->document->addStyle('view/stylesheet/' . $this->codename . '.css');
				
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
				
		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$data['installed'] = in_array($this->codename, $seo_extensions) ? true : false;
						
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
				
		// Action
		$data['href_setting'] = $this->url->link($this->route . '/setting', 'token=' . $this->session->data['token'], true);
		$data['href_custom_page'] = $this->url->link($this->route . '/custom_page', 'token=' . $this->session->data['token'], true);
		$data['href_export_import'] = $this->url->link($this->route . '/export_import', 'token=' . $this->session->data['token'], true);
		$data['href_instruction'] = $this->url->link($this->route . '/instruction', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		$data['install'] = $this->url->link($this->route . '/installModule', 'token=' . $this->session->data['token'], true);
				
		// Tab
		$data['text_settings'] = $this->language->get('text_settings');
		$data['text_custom_pages'] = $this->language->get('text_custom_pages');
		$data['text_export_import'] = $this->language->get('text_export_import');
		$data['text_instructions'] = $this->language->get('text_instructions');
						
		// Button
		$data['button_cancel'] = $this->language->get('button_cancel');	
		$data['button_install'] = $this->language->get('button_install');
										
		// Text
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_install'] = $this->language->get('text_install');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_instructions_full'] = $this->language->get('text_instructions_full');
		
		// Help
		$data['help_install'] = $this->language->get('help_install');
		
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
			'text' => $this->language->get('text_modules'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], true)
		);
								
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($data['installed']) {
			$this->response->setOutput($this->load->view($this->route . '/instruction.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view($this->route . '/install.tpl', $data));
		}
	}

	public function save() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->codename, $this->request->post);

			if (isset($this->request->post['htaccess'])) {
				$this->{'model_extension_module_' . $this->codename}->saveFileData('htaccess', $this->request->post['htaccess']);
			}
			if (isset($this->request->post['robots'])) {
				$this->{'model_extension_module_' . $this->codename}->saveFileData('robots', $this->request->post['robots']);
			}

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
	
	public function createDefaultCustomPage() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
				
		if ($this->validate()) {
			$this->{'model_extension_module_' . $this->codename}->createDefaultCustomPage();
						
			$this->session->data['success'] = $this->language->get('text_success_create_default_custom_pages');
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
	
	public function addCustomPage() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
				
		if (isset($this->request->post['custom_page']) && $this->validateAddCustomPage()) {
			$this->{'model_extension_module_' . $this->codename}->addCustomPage($this->request->post['custom_page']);
						
			$this->session->data['success'] = $this->language->get('text_success_add_custom_page');
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
	
	public function editCustomPage() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		
		if (isset($this->request->post['route']) && isset($this->request->post['language_id']) && isset($this->request->post['target_keyword']) && $this->validateEditCustomPage()) {
			$custom_page_data = array(
				'route'				=> $this->request->post['route'],
				'language_id'		=> $this->request->post['language_id'],
				'target_keyword'	=> $this->request->post['target_keyword']
			);
		
			$this->{'model_extension_module_' . $this->codename}->editCustomPage($custom_page_data);
		}
			
		$data['error'] = $this->error;
		
		$this->response->setOutput(json_encode($data));
	}
	
	public function deleteCustomPage() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
				
		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $route) {
				$this->{'model_extension_module_' . $this->codename}->deleteCustomPage($route);
			}
			
			$this->session->data['success'] = $this->language->get('text_success_delete_custom_pages');
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
	
	public function export() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		
		if (isset($this->request->post['sheet_codes'])) { 
			$sheet_codes = $this->request->post['sheet_codes']; 
		} else {  
			$sheet_codes = array();
		}
		
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		require_once(DIR_SYSTEM . 'library/d_phpexcel.php');
				
		// create a new workbook
		$workbook = new PHPExcel();

		// set some default styles
		$workbook->getDefaultStyle()->getFont()->setName('Arial');
		$workbook->getDefaultStyle()->getFont()->setSize(10);
		$workbook->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$workbook->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$workbook->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		$worksheet_index = 0;
		foreach ($sheet_codes as $sheet_code) {
			if ($worksheet_index > 0) $workbook->createSheet();
			$workbook->setActiveSheetIndex($worksheet_index);
			$worksheet = $workbook->getActiveSheet();
			
			if ($sheet_code == 'custom_page') {
				$worksheet->setTitle($sheet_code);
				
				// Set the column widths
				$j = 0;
				$worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('route')+4,30)+1);
				foreach ($languages as $language) {
					$worksheet->getColumnDimensionByColumn($j++)->setWidth(max(strlen('target_keyword')+4,30)+1);
				}
		
				// The heading row and column styles
				$data = array();
				$i = 1;
				$j = 0;
				
				$data[$j++] = 'route';
				foreach ($languages as $language) {
					$data[$j++] = 'target_keyword' . '('.$language['code'].')';
				}
				$worksheet->getRowDimension($i)->setRowHeight(30);
				$worksheet->fromArray($data, null, 'A' . $i, true);
						
				// The actual custom pages data
				$i += 1;
				$j = 0;
				
				$custom_pages = $this->{'model_extension_module_' . $this->codename}->getCustomPages();
				
				foreach ($custom_pages as $custom_page) {
					$worksheet->getRowDimension($i)->setRowHeight(26);
					$data = array();
					$data[$j++] = html_entity_decode($custom_page['route'], ENT_QUOTES,'UTF-8');
					foreach ($languages as $language) {
						if (isset($custom_page['target_keyword'][$language['language_id']])) {
							$target_keyword_text = '';
							foreach ($custom_page['target_keyword'][$language['language_id']] as $sort_order => $keyword) {
								$target_keyword_text .= '[' . $keyword . ']';
							}
							$data[$j++] = html_entity_decode($target_keyword_text, ENT_QUOTES,'UTF-8');
						} else {
							$data[$j++] = '';
						}
					}	
					$worksheet->fromArray($data, null, 'A' . $i, true);
					$i += 1;
					$j = 0;
				}
				
				$worksheet->freezePaneByColumnAndRow(1, 2);
				
				$worksheet_index++;
			}
		}
				
		$filename = $this->codename . ' ' . date('Y-m-d') . '.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
	}
	
	public function import() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
				
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateImport())) {
			if ((isset($this->request->files['upload'])) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
				$filename = $this->request->files['upload']['tmp_name'];
				
				require_once(DIR_SYSTEM . 'library/d_phpexcel.php');
				
				// parse uploaded spreadsheet file
				$inputFileType = PHPExcel_IOFactory::identify($filename);
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				$reader = $objReader->load($filename);
				
				// get worksheet if there
				$worksheet = $reader->getSheetByName('custom_page');
				if ($worksheet != null) {
					$elements = array();
					$first_row = array();
					$i = 0;
					$max_row = $worksheet->getHighestRow();
					$max_col = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
						
					for ($i=0; $i<$max_row; $i++) {
						if ($i == 0) {
							for ($j=1; $j<=$max_col; $j++) {
								$first_row[] = ($worksheet->cellExistsByColumnAndRow($j-1, $i+1)) ? $worksheet->getCellByColumnAndRow($j-1, $i+1)->getValue() : '';
							}
							continue;
						}
						for ($j=1; $j<=$max_col; $j++) {
							$cell = ($worksheet->cellExistsByColumnAndRow($j-1, $i+1)) ?  $worksheet->getCellByColumnAndRow($j-1, $i+1)->getValue() : '';
							$elements[$i][$first_row[$j-1]] = htmlspecialchars($cell);
						}
					}
					
					$custom_pages = array();
					foreach ($elements as $element) {
						$custom_page = array();
						if (isset($element['route']) && $element['route']) {
							$custom_page['route'] = $element['route'];
						} else {
							continue;
						}
						$custom_page['target_keyword'] = array();
						foreach ($languages as $language) {
							if (isset($element['target_keyword' . '(' . $language['code'] . ')'])) {
								$custom_page['target_keyword'][$language['language_id']] = $element['target_keyword' . '(' . $language['code'] . ')'];
							}
						}
						$custom_pages[] = $custom_page;
										
						$this->{'model_extension_module_' . $this->codename}->saveCustomPages($custom_pages);
					}
				}
			}
		}
						
		$data['error'] = $this->error;
		
		if (!$this->error) $this->session->data['success'] = $this->language->get('text_success_import');
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$this->response->setOutput(json_encode($data));
	}
	
	public function installModule() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('extension/event');
		$this->load->model('extension/extension');
		$this->load->model('user/user_group');

		if ($this->validateInstall()) {
			$this->model_extension_event->deleteEvent($this->codename);
			$this->model_extension_event->addEvent($this->codename, 'admin/view/common/column_left/before', 'extension/module/d_seo_module/column_left_before');
			$this->model_extension_event->addEvent($this->codename, 'admin/view/setting/setting/after', 'extension/module/d_seo_module/setting_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/view/setting/store_form/after', 'extension/module/d_seo_module/setting_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/localisation/language/addLanguage/after', 'extension/module/d_seo_module/language_add_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/localisation/language/deleteLanguage/after', 'extension/module/d_seo_module/language_delete_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/view/catalog/category_form/after', 'extension/module/d_seo_module/category_form_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/category/addCategory/after', 'extension/module/d_seo_module/category_add_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/category/editCategory/after', 'extension/module/d_seo_module/category_edit_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/view/catalog/product_form/after', 'extension/module/d_seo_module/product_form_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/product/addProduct/after', 'extension/module/d_seo_module/product_add_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/product/editProduct/after', 'extension/module/d_seo_module/product_edit_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/view/catalog/manufacturer_form/after', 'extension/module/d_seo_module/manufacturer_form_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/manufacturer/addManufacturer/after', 'extension/module/d_seo_module/manufacturer_add_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/manufacturer/editManufacturer/after', 'extension/module/d_seo_module/manufacturer_edit_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/view/catalog/information_form/after', 'extension/module/d_seo_module/information_form_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/information/addInformation/after', 'extension/module/d_seo_module/information_add_after');
			$this->model_extension_event->addEvent($this->codename, 'admin/model/catalog/information/editInformation/after', 'extension/module/d_seo_module/information_edit_after');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/common/home/before', 'extension/module/d_seo_module/home_before');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/*/template/common/home/after', 'extension/module/d_seo_module/home_after');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/product/category/before', 'extension/module/d_seo_module/category_before');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/*/template/product/category/after', 'extension/module/d_seo_module/category_after');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/product/product/before', 'extension/module/d_seo_module/product_before');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/*/template/product/product/after', 'extension/module/d_seo_module/product_after');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/product/manufacturer_info/before', 'extension/module/d_seo_module/manufacturer_info_before');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/*/template/product/manufacturer_info/after', 'extension/module/d_seo_module/manufacturer_info_after');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/information/information/before', 'extension/module/d_seo_module/information_before');
			$this->model_extension_event->addEvent($this->codename, 'catalog/view/*/template/information/information/after', 'extension/module/d_seo_module/information_after');

			$this->{'model_extension_module_' . $this->codename}->installModule();
			
			// Install SEO Module URL Target
			$this->model_extension_extension->install('dashboard', 'd_seo_module_url_target');
			
			$setting = array(
				'dashboard_d_seo_module_url_target_status' => true,
				'dashboard_d_seo_module_url_target_width' => 12,
				'dashboard_d_seo_module_url_target_sort_order' => 20,
			);
			
			$this->model_setting_setting->editSetting('dashboard_d_seo_module_url_target', $setting);
			
			$this->model_user_user_group->addPermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'access', 'extension/dashboard/d_seo_module_url_target');
			$this->model_user_user_group->addPermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'modify', 'extension/dashboard/d_seo_module_url_target');
			
			$this->session->data['success'] = $this->language->get('text_success_install');
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

	public function uninstallModule() {
		$this->load->language($this->route);

		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('extension/event');
		$this->load->model('extension/extension');
		$this->load->model('user/user_group');

		if ($this->validateUninstall()) {
			$this->model_extension_event->deleteEvent($this->codename);

			$this->{'model_extension_module_' . $this->codename}->uninstallModule();
			
			// Uninstall SEO Module URL Target
			$this->model_extension_extension->uninstall('dashboard', 'd_seo_module_url_target');
			$this->model_setting_setting->deleteSetting('dashboard_d_seo_module_url_target');
			$this->model_user_user_group->removePermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'access', 'extension/dashboard/d_seo_module_url_target');
			$this->model_user_user_group->removePermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'modify', 'extension/dashboard/d_seo_module_url_target');
			
			$this->model_user_user_group->removePermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'access', $this->codename . '/' . $this->codename);
			$this->model_user_user_group->removePermission($this->{'model_extension_module_' . $this->codename}->getGroupId(), 'modify', $this->codename . '/' . $this->codename);	

			$this->session->data['success'] = $this->language->get('text_success_uninstall');
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
	
	public function install() {
		if ($this->d_shopunity) {
			$this->load->model('d_shopunity/mbooth');
			$this->model_d_shopunity_mbooth->installDependencies($this->codename);
		}
	}

	public function column_left_before($route, &$data, $output) {
		$this->load->model($this->route);

		$menu_data = array();

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$info = $this->load->controller($this->codename . '/' . $seo_extension . '/menu', $menu_data);
			if ($info) $menu_data = $info;
		}

		if ($menu_data) {
			$data['menus'][] = array(
				'id'       => 'menu-seo',
				'icon'	   => 'fa-signal',
				'name'	   => $this->language->get('text_seo'),
				'href'     => '',
				'children' => $menu_data
			);
		}
	}

	public function language_add_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['language_id'] = $output;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/language_add', $data);
		}
	}

	public function language_delete_after($route, $data, $output) {
		$this->load->model($this->route);

		$language_id = $data[0];
		$data = $data[1];
		$data['language_id'] = $language_id;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/language_delete', $data);
		}
	}

	public function setting_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		$html_dom = new d_simple_html_dom();
		$html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

		$html_tab_general = '';
		$html_tab_general_language = array();
		$html_tab_store = '';
		$html_tab_local = '';
		$html_tab_option = '';
		$html_tab_seo = '';
		$html_style = '';
		$html_script = '';

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		foreach ($seo_extensions as $seo_extension) {
			$html_tab_general .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_tab_general');
			$info = $this->load->controller($this->codename . '/' . $seo_extension . '/setting_tab_general_language');
			foreach ($languages as $language) {
				if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
				if (isset($info[$language['language_id']])) {
					$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
				}
			}
			$html_tab_store .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_tab_store');
			$html_tab_local .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_tab_local');
			$html_tab_option .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_tab_option');
			$html_tab_seo .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_tab_seo');
			$html_style .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_style');
			$html_script .= $this->load->controller($this->codename . '/' . $seo_extension . '/setting_script');
		}

		if ($html_tab_general) {
			$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
		}
		if (reset($html_tab_general_language)) {
			$html_languages = '<ul class="nav nav-tabs" id="language">';
			foreach ($languages as $language) {
				$html_languages .= '<li' . (($language == reset($languages)) ? ' class="active"' : '') . '><a href="#language' . $language['language_id'] . '" data-toggle="tab"><img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" /> ' . $language['name'] . '</a></li>';
            }
            $html_languages .= '</ul>';
			$html_languages .= '<div class="tab-language tab-content">';
			foreach ($languages as $language) {
				$html_languages .= '<div class="tab-pane' . (($language == reset($languages)) ? ' active' : '') . '" id="language' . $language['language_id'] . '"></div>';
            }
			$html_languages .= '</div>';

			$html_dom->find('#tab-general', 0)->innertext = $html_languages . $html_dom->find('#tab-general', 0)->innertext;
			$html_dom->load((string)$html_dom, $lowercase=true, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT);

			foreach ($languages as $language) {
				$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
			}
		}
		if ($html_tab_store) {
			$html_dom->find('#tab-store', 0)->innertext .= $html_tab_store;
		}
		if ($html_tab_local) {
			$html_dom->find('#tab-local', 0)->innertext .= $html_tab_local;
		}
		if ($html_tab_option) {
			$html_dom->find('#tab-option', 0)->innertext .= $html_tab_option;
		}
		if ($html_tab_seo) {
			$html_dom->find('[href="#tab-server"]', 0)->innertext .= '<li><a href="#tab-seo" data-toggle="tab">' . $this->language->get('text_seo') . '</a></li>';
			$html_dom->find('#tab-server', 0)->outertext .= '<div class="tab-pane" id="tab-seo">' . $html_tab_seo . '</div>';
		}
		if ($html_style) {
			$html_dom->find('#content', 0)->innertext .= $html_style;
		}
		if ($html_script) {
			$html_dom->find('#content', 0)->innertext .= $html_script;
		}

		$output = $html_dom;
	}

	public function category_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		$html_dom = new d_simple_html_dom();
		$html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

		$html_tab_general = '';
		$html_tab_general_language = array();
		$html_tab_data = '';
		$html_tab_seo = '';
		$html_style = '';
		$html_script = '';

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		foreach ($seo_extensions as $seo_extension) {
			$html_tab_general .= $this->load->controller($this->codename . '/' . $seo_extension . '/category_form_tab_general');
			$info = $this->load->controller($this->codename . '/' . $seo_extension . '/category_form_tab_general_language');
			foreach ($languages as $language) {
				if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
				if (isset($info[$language['language_id']])) {
					$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
				}
			}
			$html_tab_data .= $this->load->controller($this->codename . '/' . $seo_extension . '/category_form_tab_data');
			$html_tab_seo .= $this->load->controller($this->codename . '/' . $seo_extension . '/category_form_tab_seo');
			$html_style .= $this->load->controller($this->codename . '/' . $seo_extension . '/category_form_style');
			$html_script .= $this->load->controller($this->codename . '/' . $seo_extension . '/category_form_script');
		}
		if ($html_tab_general) {
			$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
		}
		if (reset($html_tab_general_language)) {
			foreach ($languages as $language) {
				$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
			}
		}
		if ($html_tab_data) {
			$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
		}
		if ($html_tab_seo) {
			$html_dom->find('[href="#tab-design"]', 0)->innertext .= '<li><a href="#tab-seo" data-toggle="tab">' . $this->language->get('text_seo') . '</a></li>';
			$html_dom->find('#tab-design', 0)->outertext .= '<div class="tab-pane" id="tab-seo">' . $html_tab_seo . '</div>';
		}
		if ($html_style) {
			$html_dom->find('#content', 0)->innertext .= $html_style;
		}
		if ($html_script) {
			$html_dom->find('#content', 0)->innertext .= $html_script;
		}

		$output = $html_dom;
	}

	public function category_add_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['category_id'] = $output;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/category_form_add', $data);
		}
	}

	public function category_edit_after($route, $data, $output) {
		$this->load->model($this->route);

		$category_id = $data[0];
		$data = $data[1];
		$data['category_id'] = $category_id;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/category_form_edit', $data);
		}
	}

	public function product_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		$html_dom = new d_simple_html_dom();
		$html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

		$html_tab_general = '';
		$html_tab_general_language = array();
		$html_tab_data = '';
		$html_tab_links = '';
		$html_tab_seo = '';
		$html_style = '';
		$html_script = '';

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		foreach ($seo_extensions as $seo_extension) {
			$html_tab_general .= $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_tab_general');
			$info = $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_tab_general_language');
			foreach ($languages as $language) {
				if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
				if (isset($info[$language['language_id']])) {
					$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
				}
			}
			$html_tab_data .= $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_tab_data');
			$html_tab_links .= $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_tab_links');
			$html_tab_seo .= $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_tab_seo');
			$html_style .= $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_style');
			$html_script .= $this->load->controller($this->codename . '/' . $seo_extension . '/product_form_script');
		}
		if ($html_tab_general) {
			$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
		}
		if (reset($html_tab_general_language)) {
			foreach ($languages as $language) {
				$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
			}
		}
		if ($html_tab_data) {
			$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
		}
		if ($html_tab_links) {
			$html_dom->find('#tab-links', 0)->innertext .= $html_tab_links;
		}
		if ($html_tab_seo) {
			$html_dom->find('[href="#tab-design"]', 0)->innertext .= '<li><a href="#tab-seo" data-toggle="tab">' . $this->language->get('text_seo') . '</a></li>';
			$html_dom->find('#tab-design', 0)->outertext .= '<div class="tab-pane" id="tab-seo">' . $html_tab_seo . '</div>';
		}
		if ($html_style) {
			$html_dom->find('#content', 0)->innertext .= $html_style;
		}
		if ($html_script) {
			$html_dom->find('#content', 0)->innertext .= $html_script;
		}

		$output = $html_dom;
	}

	public function product_add_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['product_id'] = $output;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/product_form_add', $data);
		}
	}

	public function product_edit_after($route, $data, $output) {
		$this->load->model($this->route);

		$product_id = $data[0];
		$data = $data[1];
		$data['product_id'] = $product_id;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/product_form_edit', $data);
		}
	}

	public function manufacturer_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		$html_tab_general = '';
		$html_tab_general_language = array();
		$html_tab_data = '';
		$html_tab_seo = '';
		$html_style = '';
		$html_script = '';

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		foreach ($seo_extensions as $seo_extension) {
			$html_tab_general .= $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_tab_general');
			$info = $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_tab_general_language');
			foreach ($languages as $language) {
				if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
				if (isset($info[$language['language_id']])) {
					$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
				}
			}
			$html_tab_data .= $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_tab_data');
			$html_tab_seo .= $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_tab_seo');
			$html_style .= $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_style');
			$html_script .= $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_script');
		}

		$html_dom = new d_simple_html_dom();
		$html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
		$html_manufacturer_name = $html_dom->find('#form-manufacturer .form-group', 0)->outertext;
		$html_dom->find('#form-manufacturer .form-group', 0)->outertext = '';
		$html_manufacturer_data = $html_dom->find('#form-manufacturer', 0)->innertext;
		$html_dom->find('#form-manufacturer', 0)->innertext = '<ul class="nav nav-tabs"><li class="active"><a href="#tab-general" data-toggle="tab">' . $this->language->get('text_general') . '</a></li><li><a href="#tab-data" data-toggle="tab">' . $this->language->get('text_data') . '</a></li></ul><div class="tab-main tab-content"><div class="tab-pane active" id="tab-general">' . $html_manufacturer_name . '</div><div class="tab-pane" id="tab-data">' . $html_manufacturer_data . '</div></div>';
		$html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

		if ($html_tab_general) {
			$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
		}
		if (reset($html_tab_general_language)) {
			$html_languages = '<ul class="nav nav-tabs" id="language">';
			foreach ($languages as $language) {
				$html_languages .= '<li' . (($language == reset($languages)) ? ' class="active"' : '') . '><a href="#language' . $language['language_id'] . '" data-toggle="tab"><img src="language/' . $language['code'] . '/' . $language['code'] . '.png" title="' . $language['name'] . '" /> ' . $language['name'] . '</a></li>';
            }
            $html_languages .= '</ul>';
			$html_languages .= '<div class="tab-language tab-content">';
			foreach ($languages as $language) {
				$html_languages .= '<div class="tab-pane' . (($language == reset($languages)) ? ' active' : '') . '" id="language' . $language['language_id'] . '"></div>';
            }
			$html_languages .= '</div>';

			$html_dom->find('#tab-general', 0)->innertext .= $html_languages;
			$html_dom->load((string)$html_dom, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

			foreach ($languages as $language) {
				$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
			}
		}
		if ($html_tab_data) {
			$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
		}
		if ($html_tab_seo) {
			$html_dom->find('[href="#tab-data"]', 0)->innertext .= '<li><a href="#tab-seo" data-toggle="tab">' . $this->language->get('text_seo') . '</a></li>';
			$html_dom->find('#tab-data', 0)->outertext .= '<div class="tab-pane" id="tab-seo">' . $html_tab_seo . '</div>';
		}
		if ($html_style) {
			$html_dom->find('#content', 0)->innertext .= $html_style;
		}
		if ($html_script) {
			$html_dom->find('#content', 0)->innertext .= $html_script;
		}

		$output = $html_dom;
	}

	public function manufacturer_add_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['manufacturer_id'] = $output;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_add', $data);
		}
	}

	public function manufacturer_edit_after($route, $data, $output) {
		$this->load->model($this->route);

		$manufacturer_id = $data[0];
		$data = $data[1];
		$data['manufacturer_id'] = $manufacturer_id;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_form_edit', $data);
		}
	}

	public function information_form_after($route, $data, &$output) {
		$this->load->language($this->route);

		$this->load->model($this->route);

		$html_dom = new d_simple_html_dom();
		$html_dom->load($output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

		$html_tab_general = '';
		$html_tab_general_language = array();
		$html_tab_data = '';
		$html_tab_seo = '';
		$html_style = '';
		$html_script = '';

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$languages = $this->{'model_extension_module_' . $this->codename}->getLanguages();

		foreach ($seo_extensions as $seo_extension) {
			$html_tab_general .= $this->load->controller($this->codename . '/' . $seo_extension . '/information_form_tab_general');
			$info = $this->load->controller($this->codename . '/' . $seo_extension . '/information_form_tab_general_language');
			foreach ($languages as $language) {
				if (!isset($html_tab_general_language[$language['language_id']])) $html_tab_general_language[$language['language_id']] = '';
				if (isset($info[$language['language_id']])) {
					$html_tab_general_language[$language['language_id']] .= $info[$language['language_id']];
				}
			}
			$html_tab_data .= $this->load->controller($this->codename . '/' . $seo_extension . '/information_form_tab_data');
			$html_tab_seo .= $this->load->controller($this->codename . '/' . $seo_extension . '/information_form_tab_seo');
			$html_style .= $this->load->controller($this->codename . '/' . $seo_extension . '/information_form_style');
			$html_script .= $this->load->controller($this->codename . '/' . $seo_extension . '/information_form_script');
		}
		if ($html_tab_general) {
			$html_dom->find('#tab-general', 0)->innertext .= $html_tab_general;
		}
		if (reset($html_tab_general_language)) {
			foreach ($languages as $language) {
				$html_dom->find('#tab-general #language' . $language['language_id'], 0)->innertext .= $html_tab_general_language[$language['language_id']];
			}
		}
		if ($html_tab_data) {
			$html_dom->find('#tab-data', 0)->innertext .= $html_tab_data;
		}
		if ($html_tab_seo) {
			$html_dom->find('[href="#tab-design"]', 0)->innertext .= '<li><a href="#tab-seo" data-toggle="tab">' . $this->language->get('text_seo') . '</a></li>';
			$html_dom->find('#tab-design', 0)->outertext .= '<div class="tab-pane" id="tab-seo">' . $html_tab_seo . '</div>';
		}
		if ($html_style) {
			$html_dom->find('#content', 0)->innertext .= $html_style;
		}
		if ($html_script) {
			$html_dom->find('#content', 0)->innertext .= $html_script;
		}

		$output = $html_dom;
	}

	public function information_add_after($route, $data, $output) {
		$this->load->model($this->route);

		$data = $data[0];
		$data['information_id'] = $output;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/information_form_add', $data);
		}
	}

	public function information_edit_after($route, $data, $output) {
		$this->load->model($this->route);

		$information_id = $data[0];
		$data = $data[1];
		$data['information_id'] = $information_id;

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();

		foreach ($seo_extensions as $seo_extension) {
			$this->load->controller($this->codename . '/' . $seo_extension . '/information_form_edit', $data);
		}
	}
	
	/*
	*	Return Target Keywords.
	*/	
	public function getTargetKeywords() {
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		
		$filter_data = array();
		
		if (isset($this->request->post['route'])) {
			$filter_data['filter_route'] = $this->request->post['route'];
		}
		
		if (isset($this->request->post['language_id'])) {
			$filter_data['filter_language_id'] = $this->request->post['language_id'];
		}
		
		if (isset($this->request->post['sort_order'])) {
			$filter_data['filter_sort_order'] = $this->request->post['sort_order'];
		}
		
		if (isset($this->request->post['keyword'])) {
			$filter_data['filter_keyword'] = $this->request->post['keyword'];
		}
		
		$data['target_keywords'] = $this->{'model_extension_module_' . $this->codename}->getTargetKeywords($filter_data);
		
		$data['error'] = $this->error;
		
		$this->response->setOutput(json_encode($data));
	}
	
	/*
	*	Validator Functions.
	*/		
	private function validate($permission = 'modify') {
		$this->load->model($this->route);

		if (isset($this->request->post['config'])) {
			return false;
		}

		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		return true;
	}
	
	private function validateAddCustomPage($permission = 'modify') {
		$this->load->model($this->route);
		
		if (isset($this->request->post['config'])) {
			return false;
		}
				
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}
		
		if (!preg_match('/[A-Za-z0-9]+\/[A-Za-z0-9]+/i', $this->request->post['custom_page']['route'])) {
			$this->error['warning'] = $this->language->get('error_route');
			return false;
		}
				
		if ($this->{'model_extension_module_' . $this->codename}->getTargetKeywords(array('filter_route' => $this->request->post['custom_page']['route']))) {
			$this->error['warning'] = sprintf($this->language->get('error_route_exists'), $this->request->post['custom_page']['route']);
			return false;
		}
		
		foreach ($this->request->post['custom_page']['target_keyword'] as $language_id => $target_keyword) {
			preg_match_all('/\[[^]]+\]/', $target_keyword, $keywords);
				
			if (!$keywords[0]) {
				$this->error['warning'] = sprintf($this->language->get('error_target_keyword'), $target_keyword);
				return false;
			}	
		}	
				
		return true;
	}
	
	private function validateEditCustomPage($permission = 'modify') {
		if (isset($this->request->post['config'])) {
			return false;
		}
				
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}
		
		preg_match_all('/\[[^]]+\]/', $this->request->post['target_keyword'], $keywords);
				
		if (!$keywords[0]) {
			$this->error['warning'] = sprintf($this->language->get('error_target_keyword'), $this->request->post['target_keyword']);
			return false;
		}	
						
		return true;
	}
		
	private function validateImport($permission = 'modify') {
		if (isset($this->request->post['config'])) {
			return false;
		}
				
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}
		
		if (!isset($this->request->files['upload']['name']) || !$this->request->files['upload']['name']) {
			$this->error['warning'] = $this->language->get('error_upload_name');
			return false;
		}
		
		$ext = strtolower(pathinfo($this->request->files['upload']['name'], PATHINFO_EXTENSION));
		if (($ext != 'xls') && ($ext != 'xlsx') && ($ext != 'ods')) {
			$this->error['warning'] = $this->language->get('error_upload_ext');
			return false;
		}

		return true;
	}

	private function validateInstall($permission = 'modify') {
		$this->load->model($this->route);

		if (isset($this->request->post['config'])) {
			return false;
		}

		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		$seo_extensions[] = $this->codename;
		$this->{'model_extension_module_' . $this->codename}->saveSEOExtensions($seo_extensions);

		return true;
	}

	private function validateUninstall($permission = 'modify') {
		$this->load->model($this->route);

		if (isset($this->request->post['config'])) {
			return false;
		}

		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		if (count($seo_extensions)>1) {
			$this->error['warning'] = $this->language->get('error_dependencies');
			return false;
		} else {
			$key = array_search($this->codename, $seo_extensions);
			if ($key !== false) unset($seo_extensions[$key]);
		}
		$this->{'model_extension_module_' . $this->codename}->saveSEOExtensions($seo_extensions);

		return true;
	}
}
