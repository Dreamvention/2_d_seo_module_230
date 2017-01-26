<?php
class ModelDSEOModuleAdviserDSEOModule extends Model {
	private $codename = 'd_seo_module';
		
	/*
	*	Return Elements for Adviser.
	*/
	public function getAdviserElements($route) {
		$language_id = $this->config->get('config_language_id');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
		} else {
			$server = HTTP_SERVER;
		}
		
		$file_robots = str_replace("system/", "", DIR_SYSTEM) . 'robots.txt';
		if (file_exists($file_robots)) { 
			$robots_txt_parser = new d_robots_txt_parser(file_get_contents($file_robots));
		}
				
		$target_keyword = array();
			
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = '" . $this->db->escape($route) . "' AND language_id = '" . (int)$language_id . "' ORDER BY sort_order");
			
		foreach($query->rows as $result) {
			$target_keyword[$result['sort_order']] = $result['keyword'];
		}
		
		$url = '';
				
		if (strpos($route, 'category_id') !== false) {
			$url = $this->url->link('product/category', str_replace('category_id', 'path', $route));
		}
		
		if (strpos($route, 'product_id') !== false) {
			$url = $this->url->link('product/product', $route);
		}
		
		if (strpos($route, 'manufacturer_id') !== false) {
			$url = $this->url->link('product/manufacturer/info', $route);
		}
		
		if (strpos($route, 'information_id') !== false) {
			$url = $this->url->link('information/information', $route);
		}
		
		$seo_keyword = str_replace($server, '', $url);
										
		$adviser_elements = array();
			
		$target_keyword_duplicate = 0;
			
		if ($target_keyword) {
			foreach ($target_keyword as $keyword) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route NOT LIKE '" . $this->db->escape($route) . "%' AND keyword = '" . $this->db->escape($keyword) . "'");
				
				$target_keyword_duplicate += $query->num_rows;
			}
		}
			
		$robots_empty_rating = 0;
			
		if (isset($robots_txt_parser) && $robots_txt_parser->getRules()) {
			$robots_empty_rating = 1;
		}
			
		$robots_no_index_rating = 1;
		
		if ($seo_keyword && isset($robots_txt_parser) && $robots_txt_parser->isUrlDisallow('/' . $seo_keyword)) {
			$robots_no_index_rating = 0;
		}
						
		$adviser_elements[] = array(
			'module'	=> $this->codename,
			'code'		=> 'target_keyword_empty',
			'rating'	=> $target_keyword ? 1 : 0,
			'weight'	=> 1,
			'text'		=> $this->language->get('text_target_keyword_empty')
		);
			
		$adviser_elements[] = array(
			'module'	=> $this->codename,
			'code'		=> 'target_keyword_duplicate',
			'rating'	=> $target_keyword_duplicate ? (1 / ($target_keyword_duplicate + 1)) : 1,
			'weight'	=> 0.8,
			'text'		=> $this->language->get('text_target_keyword_duplicate')
		);
			
		$adviser_elements[] = array(
			'module'	=> $this->codename,
			'code'		=> 'robots_empty',
			'rating'	=> $robots_empty_rating,
			'weight'	=> 1,
			'text'		=> $this->language->get('text_robots_empty')
		);
			
		$adviser_elements[] = array(
			'module'	=> $this->codename,
			'code'		=> 'robots_no_index',
			'rating'	=> $robots_no_index_rating,
			'weight'	=> 1,
			'text'		=> $this->language->get('text_robots_no_index')
		);
					
		return $adviser_elements;
	}
}
?>