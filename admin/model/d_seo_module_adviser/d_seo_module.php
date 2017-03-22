<?php
class ModelDSEOModuleAdviserDSEOModule extends Model {
	private $codename = 'd_seo_module';
		
	/*
	*	Return Elements for Adviser.
	*/
	public function getAdviserElements($route) {
		$languages = $this->getLanguages();
		
		$file_robots = str_replace("system/", "", DIR_SYSTEM) . 'robots.txt';
		
		if (file_exists($file_robots) && file_exists(DIR_SYSTEM . 'library/d_robots_txt_parser.php')) { 
			$robots_txt_parser = new d_robots_txt_parser(file_get_contents($file_robots));
		}
				
		$target_keyword = $this->{'model_extension_module_' . $this->codename}->getTargetKeyword($route);	
		$seo_keyword = $this->getSEOKeyword($route);
																
		$adviser_elements = array();
			
		foreach($languages as $language) {
			$target_keyword_duplicate = 0;
			
			if (isset($target_keyword[$language['language_id']])) {
				foreach ($target_keyword[$language['language_id']] as $keyword) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE (route <> '" . $this->db->escape($route) . "' OR language_id <> '" . (int)$language['language_id'] . "') AND keyword = '" . $this->db->escape($keyword) . "'");
				
					$target_keyword_duplicate += $query->num_rows;
				}
			}
			
			$robots_empty_rating = 0;
			
			if (isset($robots_txt_parser) && $robots_txt_parser->getRules()) {
				$robots_empty_rating = 1;
			}
			
			$robots_no_index_rating = 1;
			
			if (isset($seo_keyword[$language['language_id']]) && $seo_keyword[$language['language_id']]) {
				if (isset($robots_txt_parser) && $robots_txt_parser->isUrlDisallow('/' . $seo_keyword[$language['language_id']])) {
					$robots_no_index_rating = 0;
				}
			}	
															
			$adviser_elements[$language['language_id']][] = array(
				'module'		=> $this->codename,
				'code'			=> 'target_keyword_empty',
				'name'			=> $this->language->get('text_target_keyword_empty'),
				'description'	=> $this->language->get('help_target_keyword_empty'),
				'rating'		=> isset($target_keyword[$language['language_id']]) ? 1 : 0,
				'weight'		=> 1
			);
			
			$adviser_elements[$language['language_id']][] = array(
				'module'		=> $this->codename,
				'code'			=> 'target_keyword_duplicate',
				'name'			=> $this->language->get('text_target_keyword_duplicate'),
				'description'	=> $this->language->get('help_target_keyword_duplicate'),
				'rating'		=> $target_keyword_duplicate ? (1 / ($target_keyword_duplicate + 1)) : 1,
				'weight'		=> 0.8
			);
			
			$adviser_elements[$language['language_id']][] = array(
				'module'		=> $this->codename,
				'code'			=> 'robots_empty',
				'name'			=> $this->language->get('text_robots_empty'),
				'description'	=> $this->language->get('help_robots_empty'),
				'rating'		=> $robots_empty_rating,
				'weight'		=> 1
			);
			
			$adviser_elements[$language['language_id']][] = array(
				'module'		=> $this->codename,
				'code'			=> 'robots_no_index',
				'name'			=> $this->language->get('text_robots_no_index'),
				'description'	=> $this->language->get('help_robots_no_index'),
				'rating'		=> $robots_no_index_rating,
				'weight'		=> 1
			);
		}
			
		return $adviser_elements;
	}
		
	/*
	*	Return SEO Keyword.
	*/
	public function getSEOKeyword($route) {
		$seo_keyword = array();
		
		$languages = $this->getLanguages();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE query LIKE '" . $this->db->escape($route) . "%'");
		
		foreach ($query->rows as $result) {
			$query_arr = explode("&language_id=", $result['query']);
			
			if (!isset($query_arr[1])) {
				foreach($languages as $language) {
					$seo_keyword[$language['language_id']] = $result['keyword'];
				}
			}
		}
		
		foreach ($query->rows as $result) {
			$query_arr = explode("&language_id=", $result['query']);
			
			if (isset($query_arr[1])) {
				$seo_keyword[$query_arr[1]] = $result['keyword'];
			}
		}
		
		return $seo_keyword;
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