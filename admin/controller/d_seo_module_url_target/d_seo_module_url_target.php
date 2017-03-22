<?php
class ControllerDSEOModuleURLTargetDSEOModuleURLTarget extends Controller {
	private $codename = 'd_seo_module_url_target';
	private $route = 'd_seo_module_url_target/d_seo_module_url_target';
	private $config_file = 'd_seo_module_url_target';
	private $error = array();
	
	/*
	*	Functions for SEO Module URL Target.
	*/
	public function duplicate_targets() {	
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_url_target_' . $this->codename}->getDuplicateTargets();
	}
	
	public function empty_targets() {	
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_url_target_' . $this->codename}->getEmptyTargets();
	}
	
	public function targets_links($targets) {	
		foreach ($targets as &$target) {
			if (strpos($target['route'], 'category_id') === 0) {
				$route_arr = explode("category_id=", $target['route']);
				
				if (isset($route_arr[1])) {
					$category_id = $route_arr[1];
					$target['link'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $category_id, true);
				}
			} elseif (strpos($target['route'], 'product_id') === 0) {
				$route_arr = explode("product_id=", $target['route']);
				
				if (isset($route_arr[1])) {
					$product_id = $route_arr[1];
					$target['link'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id, true);
				}
			} elseif (strpos($target['route'], 'manufacturer_id') === 0) {
				$route_arr = explode("manufacturer_id=", $target['route']);
				
				if (isset($route_arr[1])) {
					$manufacturer_id = $route_arr[1];
					$target['link'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $manufacturer_id, true);
				}
			} elseif (strpos($target['route'], 'information_id') === 0) {
				$route_arr = explode("information_id=", $target['route']);
				
				if (isset($route_arr[1])) {
					$information_id = $route_arr[1];
					$target['link'] = $this->url->link('catalog/information/edit', 'token=' . $this->session->data['token'] . '&information_id=' . $information_id, true);
				}
			}
		}
		
		return $targets;
	}
}
