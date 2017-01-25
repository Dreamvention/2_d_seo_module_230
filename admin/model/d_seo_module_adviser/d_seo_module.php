<?php
class ModelDSEOModuleAdviserDSEOModule extends Model {
	private $codename = 'd_seo_module';
		
	/*
	*	Return Elements for Adviser.
	*/
	public function getAdviserElements($route) {
		$languages = $this->getLanguages();
		
		$file_robots = str_replace("system/", "", DIR_SYSTEM) . 'robots.txt';
		if (file_exists($file_robots)) { 
			$robots_txt_parser = new d_robots_txt_parser(file_get_contents($file_robots));
		}
				
		$target_keyword = array();
			
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = '" . $this->db->escape($route) . "' ORDER BY sort_order");
			
		foreach($query->rows as $result) {
			$target_keyword[$result['language_id']][$result['sort_order']] = $result['keyword'];
		}
		
		$seo_keyword = array();
				
		if (strpos($route, 'category_id') !== false) {
			$route_arr = explode("category_id=", $route);
			if (isset($route_arr[1])) {
				$category_id = $route_arr[1];
				if (file_exists(DIR_APPLICATION . 'model/d_seo_module/d_seo_module_url.php')) {
					$this->load->model('d_seo_module/d_seo_module_url');
					$seo_keyword = $this->model_d_seo_module_d_seo_module_url->getCategorySEOKeyword($category_id);
				} else {
					$category_info = $this->model_catalog_category->getCategory($category_id);
					$seo_keyword = $category_info['keyword'];
				}
			}
		}
		
		if (strpos($route, 'product_id') !== false) {
			$route_arr = explode("product_id=", $route);
			if (isset($route_arr[1])) {
				$product_id = $route_arr[1];
				if (file_exists(DIR_APPLICATION . 'model/d_seo_module/d_seo_module_url.php')) {
					$this->load->model('d_seo_module/d_seo_module_url');
					$seo_keyword = $this->model_d_seo_module_d_seo_module_url->getProductSEOKeyword($product_id);
				} else {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					$seo_keyword = $product_info['keyword'];
				}
			}
		}
		
		if (strpos($route, 'manufacturer_id') !== false) {
			$route_arr = explode("manufacturer_id=", $route);
			if (isset($route_arr[1])) {
				$manufacturer_id = $route_arr[1];
				if (file_exists(DIR_APPLICATION . 'model/d_seo_module/d_seo_module_url.php')) {
					$this->load->model('d_seo_module/d_seo_module_url');
					$seo_keyword = $this->model_d_seo_module_d_seo_module_url->getManufacturerSEOKeyword($manufacturer_id);
				} else {
					$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
					$seo_keyword = $manufacturer_info['keyword'];
				}
			}
		}
		
		if (strpos($route, 'information_id') !== false) {
			$route_arr = explode("information_id=", $route);
			if (isset($route_arr[1])) {
				$information_id = $route_arr[1];
				if (file_exists(DIR_APPLICATION . 'model/d_seo_module/d_seo_module_url.php')) {
					$this->load->model('d_seo_module/d_seo_module_url');
					$seo_keyword = $this->model_d_seo_module_d_seo_module_url->getInformationSEOKeyword($information_id);
				} else {
					$information_info = $this->model_catalog_information->getInformation($information_id);
					$seo_keyword = $information_info['keyword'];
				}
			}
		}
												
		$adviser_elements = array();
			
		foreach($languages as $language) {
			$target_keyword_duplicate = 0;
			
			if (isset($target_keyword[$language['language_id']])) {
				foreach ($target_keyword[$language['language_id']] as $keyword) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route NOT LIKE '" . $this->db->escape($route) . "%' AND keyword = '" . $this->db->escape($keyword) . "'");
				
					$target_keyword_duplicate += $query->num_rows;
				}
			}
			
			$robots_empty_rating = 0;
			
			if (isset($robots_txt_parser) && $robots_txt_parser->getRules()) {
				$robots_empty_rating = 1;
			}
			
			$robots_no_index_rating = 1;
			
			if (is_array($seo_keyword) && isset($seo_keyword[$language['language_id']]) && $seo_keyword[$language['language_id']]) {
				if (isset($robots_txt_parser) && $robots_txt_parser->isUrlDisallow('/' . $seo_keyword[$language['language_id']])) {
					$robots_no_index_rating = 0;
				}
			}	
			if (!is_array($seo_keyword) && $seo_keyword) {
				if (isset($robots_txt_parser) && $robots_txt_parser->isUrlDisallow('/' . $seo_keyword)) {
					$robots_no_index_rating = 0;
				}
			}
												
			$adviser_elements[$language['language_id']][] = array(
				'module'	=> $this->codename,
				'code'		=> 'target_keyword_empty',
				'rating'	=> isset($target_keyword[$language['language_id']]) ? 1 : 0,
				'weight'	=> 1,
				'text'		=> $this->language->get('text_target_keyword_empty')
			);
			
			$adviser_elements[$language['language_id']][] = array(
				'module'	=> $this->codename,
				'code'		=> 'target_keyword_duplicate',
				'rating'	=> $target_keyword_duplicate ? (1 / ($target_keyword_duplicate + 1)) : 1,
				'weight'	=> 0.8,
				'text'		=> $this->language->get('text_target_keyword_duplicate')
			);
			
			$adviser_elements[$language['language_id']][] = array(
				'module'	=> $this->codename,
				'code'		=> 'robots_empty',
				'rating'	=> $robots_empty_rating,
				'weight'	=> 1,
				'text'		=> $this->language->get('text_robots_empty')
			);
			
			$adviser_elements[$language['language_id']][] = array(
				'module'	=> $this->codename,
				'code'		=> 'robots_no_index',
				'rating'	=> $robots_no_index_rating,
				'weight'	=> 1,
				'text'		=> $this->language->get('text_robots_no_index')
			);
		}
			
		return $adviser_elements;
	}
			
	/*
	*	Return list of languages.
	*/
	public function getLanguages() {
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		foreach ($languages as $key => $language) {
            $languages[$key]['flag'] = 'language/' . $language['code'] . '/' . $language['code'] . '.png';
        }
		
		return $languages;
	}
}
?>