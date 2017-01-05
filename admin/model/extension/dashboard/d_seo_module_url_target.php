<?php
class ModelExtensionDashboardDSEOModuleURLTarget extends Model {
	
	/*
	*	Edit Target.
	*/
	public function editTarget($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = '" . $this->db->escape($data['route']) . "' AND language_id = '" . (int)$data['language_id'] . "'");
				
		preg_match_all('/\[[^]]+\]/', $data['target_keyword'], $keywords);
				
		$sort_order = 1;
		foreach ($keywords[0] as $keyword) {
			$keyword = substr($keyword, 1, strlen($keyword)-2);
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = '" . $this->db->escape($data['route']) . "', language_id = '" . (int)$data['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
			$sort_order++;
		}
	}
		
	/*
	*	Return Duplicate Targets.
	*/
	public function getDuplicateTargets() {
		$targets = array();
				
		$languages = $this->getLanguages();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target");
		
		foreach ($query->rows as $result) {
			$targets[$result['route']]['route'] = $result['route'];
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$targets[$result['route']]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
				
		$routes = array();
		
		foreach ($targets as $target) {
			foreach($target['target_keyword'] as $target_keyword) {
				foreach($target_keyword as $keyword) {
					if ($keyword) {
						if (isset($targets[$keyword])) {
							if (!in_array($target['route'], $routes[$keyword])) {
								$routes[$keyword][] = $target['route'];
							}
						} else {
							$routes[$keyword][] = $target['route'];			
						}
					}
				}
			}
		}
		
		$duplicate_targets = array();
		
		foreach ($targets as $target) {
			foreach($target['target_keyword'] as $language_id => $target_keyword) {
				foreach($target_keyword as $sort_order => $keyword) {
					if ((isset($routes[$keyword]) && count($routes[$keyword])>1)) {
						if (!isset($duplicate_targets[$target['route']])) {
							$duplicate_targets[$target['route']] = $target;
						
							if (strpos($target['route'], 'category_id')===0) {
								$duplicate_targets[$target['route']]['link'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
							} elseif (strpos($target['route'], 'product_id')===0) {
								$duplicate_targets[$target['route']]['link'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
							} elseif (strpos($target['route'], 'manufacturer_id')===0) {
								$duplicate_targets[$target['route']]['link'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
							} elseif (strpos($target['route'], 'information_id')===0) {
								$duplicate_targets[$target['route']]['link'] = $this->url->link('catalog/information/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
							}
						}
						
						$duplicate_targets[$target['route']]['duplicate'][$language_id][$sort_order] = 1;
					}
				}
			}
		}
			
		return $duplicate_targets;
	}
	
	/*
	*	Return Empty Targets.
	*/
	public function getEmptyTargets() {
		$targets = array();
				
		$languages = $this->getLanguages();
		
		$query = $this->db->query("SELECT c.category_id, ut.language_id, ut.sort_order, ut.keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('category_id=', c.category_id))");
														
		foreach ($query->rows as $result) {
			$route = 'category_id=' . $result['category_id'];
			$targets[$route]['route'] = $route;
			if (!isset($targets[$route]['target_keyword'])) {
				$targets[$route]['target_keyword'] = array();
			}
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$targets[$route]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
						
		$query = $this->db->query("SELECT p.product_id, ut.language_id, ut.sort_order, ut.keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('product_id=', p.product_id)) GROUP BY p.product_id, ut.language_id");
		
		foreach ($query->rows as $result) {
			$route = 'product_id=' . $result['product_id'];
			$targets[$route]['route'] = $route;
			if (!isset($targets[$route]['target_keyword'])) {
				$targets[$route]['target_keyword'] = array();
			}
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$targets[$route]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
		
		$query = $this->db->query("SELECT m.manufacturer_id, ut.language_id, ut.sort_order, ut.keyword FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('manufacturer_id=', m.manufacturer_id)) GROUP BY m.manufacturer_id, ut.language_id");
		
		foreach ($query->rows as $result) {
			$route = 'manufacturer_id=' . $result['manufacturer_id'];
			$targets[$route]['route'] = $route;
			if (!isset($targets[$route]['target_keyword'])) {
				$targets[$route]['target_keyword'] = array();
			}
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$targets[$route]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
		
		$query = $this->db->query("SELECT i.information_id, ut.language_id, ut.sort_order, ut.keyword FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('information_id=', i.information_id)) GROUP BY i.information_id, ut.language_id");
		
		foreach ($query->rows as $result) {
			$route = 'information_id=' . $result['information_id'];
			$targets[$route]['route'] = $route;
			if (!isset($targets[$route]['target_keyword'])) {
				$targets[$route]['target_keyword'] = array();
			}
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$targets[$route]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
		
		$empty_targets = array();
		
		foreach ($targets as $target) {
			foreach ($languages as $language) {
				if (!isset($target['target_keyword'][$language['language_id']]) || (isset($target['target_keyword'][$language['language_id']]) && !$target['target_keyword'][$language['language_id']])) {
					if (!isset($empty_targets[$target['route']])) {
						$empty_targets[$target['route']] = $target;
						
						if (strpos($target['route'], 'category_id')===0) {
							$empty_targets[$target['route']]['link'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
						} elseif (strpos($target['route'], 'product_id')===0) {
							$empty_targets[$target['route']]['link'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
						} elseif (strpos($target['route'], 'manufacturer_id')===0) {
							$empty_targets[$target['route']]['link'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
						} elseif (strpos($target['route'], 'information_id')===0) {
							$empty_targets[$target['route']]['link'] = $this->url->link('catalog/information/edit', 'token=' . $this->session->data['token'] . '&' . $target['route'], true);
						}
					}
				}
			}
		}
			
		return $empty_targets;
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
	
	/*
	*	Return list of stores.
	*/
	public function getStores() {
		$this->load->model('setting/store');
		
		$stores = $this->model_setting_store->getStores();
		$result = array();
		if ($stores) {
			$result[] = array(
				'store_id' => 0, 
				'name' => $this->config->get('config_name')
			);
			foreach ($stores as $store) {
				$result[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name']	
				);
			}	
		}
		
		return $result;
	}
}
?>