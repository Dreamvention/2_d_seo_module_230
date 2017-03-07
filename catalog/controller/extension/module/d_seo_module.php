<?php
class ControllerExtensionModuleDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'extension/module/d_seo_module';
	
	public function header_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/header_data', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function header_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/header_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function footer_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/footer_data', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function footer_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/footer_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function home_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/home_data', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function home_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/home_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function category_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/category_data', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function category_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
			
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/category_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function product_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/product_data', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function product_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/product_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function manufacturer_list_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
			
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_list_data', $data);
				if ($info) $data = $info;
			}	
		}
	}
			
	public function manufacturer_list_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_list_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function manufacturer_info_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
			
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_info_data', $data);
				if ($info) $data = $info;
			}	
		}
	}
			
	public function manufacturer_info_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/manufacturer_info_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function information_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/information_data', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function information_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/information_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function search_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/search_data', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function search_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/search_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function special_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/special_data', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function special_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller($this->codename . '/' . $seo_extension . '/special_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function seo_url() {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		unset($this->session->data[$this->codename]);
		
		if ($status) {
			$this->session->data[$this->codename] = true;
			
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$this->load->controller($this->codename . '/' . $seo_extension . '/seo_url');
			}
			
			foreach ($seo_extensions as $seo_extension) {
				$this->load->controller($this->codename . '/' . $seo_extension . '/seo_url_check');
			}
		}
	}
	
	public function seo_url_rewrite($link) {
		$this->load->model($this->route);
				
		if (isset($this->session->data[$this->codename])) {			
			$cache = md5($link);
			
			$language_id = $this->config->get('config_language_id');
		
			$rewrite_link = false;
		
			$rewrite_link = $this->cache->get('url_rewrite.' . $cache . '.' . $language_id);
		
			if (!$rewrite_link) {
				$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
				foreach ($seo_extensions as $seo_extension) {
					$info = $this->load->controller($this->codename . '/' . $seo_extension . '/seo_url_rewrite', $link);
					if ($info) $link = $info;
				}
				
				$this->cache->set('url_rewrite.' . $cache . '.' . $language_id, $link);
			} else {
				$link = $rewrite_link;
			}
		}
				
		return $link;
	}
	
	public function seo_url_language() {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		// Setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$this->load->controller($this->codename . '/' . $seo_extension . '/seo_url_language');
			}
		}
	}
}