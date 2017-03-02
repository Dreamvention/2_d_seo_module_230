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
		
		$target_keyword = $this->{'model_extension_module_' . $this->codename}->getTargetKeyword($route);	
		$seo_keyword = $this->getSEOKeyword($route);
												
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
			'module'		=> $this->codename,
			'code'			=> 'target_keyword_empty',
			'name'			=> $this->language->get('text_target_keyword_empty'),
			'description'	=> $this->language->get('help_target_keyword_empty'),
			'rating'		=> $target_keyword ? 1 : 0,
			'weight'		=> 1
		);
			
		$adviser_elements[] = array(
			'module'		=> $this->codename,
			'code'			=> 'target_keyword_duplicate',
			'name'			=> $this->language->get('text_target_keyword_duplicate'),
			'description'	=> $this->language->get('help_target_keyword_duplicate'),
			'rating'		=> $target_keyword_duplicate ? (1 / ($target_keyword_duplicate + 1)) : 1,
			'weight'		=> 0.8
		);
			
		$adviser_elements[] = array(
			'module'		=> $this->codename,
			'code'			=> 'robots_empty',
			'name'			=> $this->language->get('text_robots_empty'),
			'description'	=> $this->language->get('help_robots_empty'),
			'rating'		=> $robots_empty_rating,
			'weight'		=> 1
		);
			
		$adviser_elements[] = array(
			'module'		=> $this->codename,
			'code'			=> 'robots_no_index',
			'name'			=> $this->language->get('text_robots_no_index'),
			'description'	=> $this->language->get('help_robots_no_index'),
			'rating'		=> $robots_no_index_rating,
			'weight'		=> 1
		);
					
		return $adviser_elements;
	}
	
	/*
	*	Return Target Keyword.
	*/
	public function getTargetKeyword($route, $language_id) {
		$target_keyword = array();
			
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = '" . $this->db->escape($route) . "' AND language_id = '" . (int)$language_id . "' ORDER BY sort_order");
			
		foreach($query->rows as $result) {
			$target_keyword[$result['sort_order']] = $result['keyword'];
		}
		
		return $target_keyword;
	}
	
	/*
	*	Return SEO Keyword.
	*/
	public function getSEOKeyword($route) {
		$seo_keyword = '';
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query LIKE '" . $this->db->escape($route) . "%'");
		foreach ($query->rows as $result) {
			$query_arr = explode("&language_id=", $result['query']);
			if (!isset($query_arr[1])) {
				$seo_keyword = $result['keyword'];
			}
		}				
		foreach ($query->rows as $result) {
			$query_arr = explode("&language_id=", $result['query']);
			if (isset($query_arr[1]) && $query_arr[1] == $this->config->get('config_language_id')) {
				$seo_keyword = $result['keyword'];
			}
		}
		
		return $seo_keyword;
	}
}
?>