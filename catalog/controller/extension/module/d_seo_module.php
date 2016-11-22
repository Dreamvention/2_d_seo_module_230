<?php
class ControllerExtensionModuleDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'extension/module/d_seo_module';
	
	public function home_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/home_data', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function home_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/home_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function category_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/category_data', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function category_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/category_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function product_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/product_data', $data);
				if ($info) $data = $info;
			}
		}			
	}
			
	public function product_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/product_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function manufacturer_info_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
			
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/manufacturer_info_data', $data);
				if ($info) $data = $info;
			}	
		}
	}
			
	public function manufacturer_info_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/manufacturer_info_html', $output);
				if ($info) $output = $info;
			}
		}
	}
	
	public function information_before($route, &$data, $output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/information_data', $data);
				if ($info) $data = $info;
			}
		}
	}
			
	public function information_after($route, $data, &$output) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		
		//setting 
		$setting = $this->model_setting_setting->getSetting($this->codename);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		
		if ($status) {
			$seo_extensions = $this->{'model_extension_module_' . $this->codename}->getSEOExtensions();
		
			foreach ($seo_extensions as $seo_extension) {
				$info = $this->load->controller('extension/module/' . $seo_extension . '/information_html', $output);
				if ($info) $output = $info;
			}
		}
	}
		
}