<?php
class ModelDSEOModuleManagerDSEOModule extends Model {
		
	/*
	*	Return List Elements for Manager.
	*/
	public function getListElements($data) {
		if ($data['sheet_id'] == 'category') {
			$implode = array();
			$implode[] = "c.category_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
					
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('category_id=', c.category_id) AND ut.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "url_target ut2 ON (ut2.route = CONCAT('category_id=', c.category_id))";
			
			$implode = array();
			
			foreach ($data['filter'] as $field_id => $filter) {
				if (!empty($filter)) {
					if ($field_id == 'target_keyword') {
						$implode[] = "(ut2.keyword LIKE '%" . $this->db->escape($filter) . "%')";
					}
				}
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY c.category_id";
			
			$query = $this->db->query($sql);
			
			$categories = array();
			
			foreach ($query->rows as $result) {
				$categories[$result['category_id']] = $result;
			}

			return $categories;	
		}
		
		if ($data['sheet_id'] == 'product') {
			$implode = array();
			$implode[] = "p.product_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('product_id=', p.product_id) AND ut.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "url_target ut2 ON (ut2.route = CONCAT('product_id=', p.product_id))";
		
			$implode = array();
			
			foreach ($data['filter'] as $field_id => $filter) {
				if (!empty($filter)) {
					if ($field_id == 'target_keyword') {
						$implode[] = "(ut2.keyword LIKE '%" . $this->db->escape($filter) . "%')";
					}
				}
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY p.product_id";
			
			$query = $this->db->query($sql);
			
			$products = array();
			
			foreach ($query->rows as $result) {
				$products[$result['product_id']] = $result;
			}

			return $products;	
		}
		
		if ($data['sheet_id'] == 'manufacturer') {
			$implode = array();
			$implode[] = "m.manufacturer_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('manufacturer_id=', m.manufacturer_id) AND ut.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "url_target ut2 ON (ut2.route = CONCAT('manufacturer_id=', m.manufacturer_id))";
			
			$implode = array();
			
			foreach ($data['filter'] as $field_id => $filter) {
				if (!empty($filter)) {
					if ($field_id == 'target_keyword') {
						$implode[] = "(ut2.keyword LIKE '%" . $this->db->escape($filter) . "%')";
					}
				}
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY m.manufacturer_id";
			
			$query = $this->db->query($sql);
			
			$manufacturers = array();
			
			foreach ($query->rows as $result) {
				$manufacturers[$result['manufacturer_id']] = $result;
			}

			return $manufacturers;	
		}
		
		if ($data['sheet_id'] == 'information') {
			$implode = array();
			$implode[] = "i.information_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			$sql = "SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('information_id=', i.information_id) AND ut.language_id = '" . (int)$data['language_id'] . "') LEFT JOIN " . DB_PREFIX . "url_target ut2 ON (ut2.route = CONCAT('information_id=', i.information_id))";
			
			$implode = array();
			
			foreach ($data['filter'] as $field_id => $filter) {
				if (!empty($filter)) {
					if ($field_id == 'target_keyword') {
						$implode[] = "(ut2.keyword LIKE '%" . $this->db->escape($filter) . "%')";
					}
				}
			}
			
			if ($implode) {
				$sql .= " WHERE " . implode(' AND ', $implode);
			}

			$sql .= " GROUP BY i.information_id";
			
			$query = $this->db->query($sql);
			
			$informations = array();
			
			foreach ($query->rows as $result) {
				$informations[$result['information_id']] = $result;
			}

			return $informations;	
		}
		
		return false;
	}
	
	/*
	*	Save Element Field for Manager.
	*/
	public function saveElementField($element) {		
		if ($element['sheet_id'] == 'category') {
			if ($element['field_id'] == 'target_keyword') {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'category_id=" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
				preg_match_all('/\[[^]]+\]/', $element['value'], $keywords);
				
				$sort_order = 1;
				$this->request->post['value'] = '';
				foreach ($keywords[0] as $keyword) {
					$keyword = substr($keyword, 1, strlen($keyword)-2);
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'category_id=" . (int)$element['element_id'] . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
					$this->request->post['value'] .= '[' . $keyword . ']';
				}
			}
		}
		
		if ($element['sheet_id'] == 'product') {
			if ($element['field_id'] == 'target_keyword') {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'product_id=" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
				preg_match_all("/\[[^]]+\]/", $element['value'], $keywords);

				$sort_order = 1;
				$this->request->post['value'] = '';
				foreach ($keywords[0] as $keyword) {
					$keyword = substr($keyword, 1, strlen($keyword)-2);
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'product_id=" . (int)$element['element_id'] . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
					$this->request->post['value'] .= '[' . $keyword . ']';
				}
			}
		}
		
		if ($element['sheet_id'] == 'manufacturer') {
			if ($element['field_id'] == 'target_keyword') {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'manufacturer_id=" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
				preg_match_all("/\[[^]]+\]/", $element['value'], $keywords);

				$sort_order = 1;
				$this->request->post['value'] = '';
				foreach ($keywords[0] as $keyword) {
					$keyword = substr($keyword, 1, strlen($keyword)-2);
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'manufacturer_id=" . (int)$element['element_id'] . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
					$this->request->post['value'] .= '[' . $keyword . ']';
				}
			}
		}
		
		if ($element['sheet_id'] == 'information') {
			if ($element['field_id'] == 'target_keyword') {
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'information_id=" . (int)$element['element_id'] . "' AND language_id = '" . (int)$element['language_id'] . "'");
				
				preg_match_all("/\[[^]]+\]/", $element['value'], $keywords);

				$sort_order = 1;
				$this->request->post['value'] = '';
				foreach ($keywords[0] as $keyword) {
					$keyword = substr($keyword, 1, strlen($keyword)-2);
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'information_id=" . (int)$element['element_id'] . "', language_id = '" . (int)$element['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
					$this->request->post['value'] .= '[' . $keyword . ']';
				}
			}
		}
		
		return true;
	}
	
	/*
	*	Return Export Elements for Manager.
	*/
	public function getExportElements($data) {
		$languages = $this->getLanguages();
				
		if ($data['sheet_id'] == 'category') {
			$categories = array();
			
			$implode = array();
			$implode[] = "c.category_id";
			$implode[] = "ut.language_id";
									
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('category_id=', c.category_id)) GROUP BY c.category_id, ut.language_id");
														
				foreach ($query->rows as $result) {
					$categories[$result['category_id']]['category_id'] = $result['category_id'];
					if (isset($result['target_keyword'])) {
						if (!isset($categories[$result['category_id']]['target_keyword'])) {
							$categories[$result['category_id']]['target_keyword'] = array();
						}
						if ($result['language_id'] && $result['target_keyword']) {
							$categories[$result['category_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
						}
					}
				}
			}

			return $categories;	
		}
		
		if ($data['sheet_id'] == 'product') {
			$products = array();
			
			$implode = array();
			$implode[] = "p.product_id";
			$implode[] = "ut.language_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('product_id=', p.product_id)) GROUP BY p.product_id, ut.language_id");
					
				foreach ($query->rows as $result) {
					$products[$result['product_id']]['product_id'] = $result['product_id'];
					if (isset($result['target_keyword'])) {
						if (!isset($products[$result['product_id']]['target_keyword'])) {
							$products[$result['product_id']]['target_keyword'] = array();
						}
						if ($result['language_id'] && $result['target_keyword']) {
							$products[$result['product_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
						}
					}
				}
			}

			return $products;	
		}
		
		if ($data['sheet_id'] == 'manufacturer') {
			$manufacturers = array();
						
			$implode = array();
			$implode[] = "m.manufacturer_id";
			$implode[] = "ut.language_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('manufacturer_id=', m.manufacturer_id)) GROUP BY m.manufacturer_id, ut.language_id");
					
				foreach ($query->rows as $result) {
					$manufacturers[$result['manufacturer_id']]['manufacturer_id'] = $result['manufacturer_id'];
					if (isset($result['target_keyword'])) {
						if (!isset($manufacturers[$result['manufacturer_id']]['target_keyword'])) {
							$manufacturers[$result['manufacturer_id']]['target_keyword'] = array();
						}
						if ($result['language_id'] && $result['target_keyword']) {
							$manufacturers[$result['manufacturer_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
						}
					}
				}
			}
						
			return $manufacturers;	
		}
		
		if ($data['sheet_id'] == 'information') {
			$informations = array();
			
			$implode = array();
			$implode[] = "i.information_id";
			$implode[] = "ut.language_id";
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT " . implode(', ', $implode) . " FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('information_id=', i.information_id)) GROUP BY i.information_id, ut.language_id");
		
				foreach ($query->rows as $result) {
					$informations[$result['information_id']]['information_id'] = $result['information_id'];
					if (isset($result['target_keyword'])) {
						if (!isset($informations[$result['information_id']]['target_keyword'])) {
							$informations[$result['information_id']]['target_keyword'] = array();
						}
						if ($result['language_id'] && $result['target_keyword']) {
							$informations[$result['information_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
						}
					}
				}
			}
				
			return $informations;	
		}
		
		return false;
	}
	
	/*
	*	Save Import Elements for Manager.
	*/
	public function saveImportElements($data) {
		$languages = $this->getLanguages();
				
		if ($data['sheet_id'] == 'category') {
			$categories = array();
			
			$query = $this->db->query("SELECT c.category_id, ut.language_id, CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('category_id=', c.category_id)) GROUP BY c.category_id, ut.language_id");
									
			foreach ($query->rows as $result) {
				$categories[$result['category_id']]['category_id'] = $result['category_id'];
				if (!isset($categories[$result['category_id']]['target_keyword'])) {
					$categories[$result['category_id']]['target_keyword'] = array();
				}
				if ($result['language_id'] && $result['target_keyword']) {
					$categories[$result['category_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
				}
			}
			
			foreach ($data['elements'] as $element) {
				if (isset($categories[$element['category_id']])) {
					$category = $categories[$element['category_id']];
					foreach ($languages as $language) {
						if (isset($element['target_keyword'][$language['language_id']])) {
							if (!isset($category['target_keyword'][$language['language_id']]) || (isset($category['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $category['target_keyword'][$language['language_id']]))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'category_id=" . (int)$category['category_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									$sort_order = 1;
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword)-2);
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'category_id=" . (int)$category['category_id'] . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										$sort_order++;
									}
								}
							}
						}
					}
				}	
			}
		}
		
		if ($data['sheet_id'] == 'product') {
			$products = array();
			
			$query = $this->db->query("SELECT p.product_id, ut.language_id, CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('product_id=', p.product_id)) GROUP BY p.product_id, ut.language_id");
			
			foreach ($query->rows as $result) {
				$products[$result['product_id']]['product_id'] = $result['product_id'];
				if (!isset($products[$result['product_id']]['target_keyword'])) {
					$products[$result['product_id']]['target_keyword'] = array();
				}
				if ($result['language_id'] && $result['target_keyword']) {
					$products[$result['product_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
				}
			}
			
			foreach ($data['elements'] as $element) {
				if (isset($products[$element['product_id']])) {
					$product = $products[$element['product_id']];
					foreach ($languages as $language) {
						if (isset($element['target_keyword'][$language['language_id']])) {
							if (!isset($product['target_keyword'][$language['language_id']]) || (isset($product['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $product['target_keyword'][$language['language_id']]))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'product_id=" . (int)$product['product_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									$sort_order = 1;
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword)-2);
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'product_id=" . (int)$product['product_id'] . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										$sort_order++;
									}
								}
							}
						}
					}
				}	
			}
		}
		
		if ($data['sheet_id'] == 'manufacturer') {
			$manufacturers = array();
			
			$query = $this->db->query("SELECT m.manufacturer_id, ut.language_id, CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('manufacturer_id=', m.manufacturer_id)) GROUP BY m.manufacturer_id, ut.language_id");
			
			foreach ($query->rows as $result) {
				$manufacturers[$result['manufacturer_id']]['manufacturer_id'] = $result['manufacturer_id'];
				if (!isset($manufacturers[$result['manufacturer_id']]['target_keyword'])) {
					$manufacturers[$result['manufacturer_id']]['target_keyword'] = array();
				}
				if ($result['language_id'] && $result['target_keyword']) {
					$manufacturers[$result['manufacturer_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
				}
			}
			
			foreach ($data['elements'] as $element) {
				if (isset($manufacturers[$element['manufacturer_id']])) {
					$manufacturer = $manufacturers[$element['manufacturer_id']];
					foreach ($languages as $language) {
						if (isset($element['target_keyword'][$language['language_id']])) {
							if (!isset($manufacturer['target_keyword'][$language['language_id']]) || (isset($manufacturer['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $manufacturer['target_keyword'][$language['language_id']]))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									$sort_order = 1;
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword)-2);
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										$sort_order++;
									}
								}
							}
						}
					}
				}	
			}
		}
		
		if ($data['sheet_id']=='information') {
			$informations = array();
			
			$query = $this->db->query("SELECT i.information_id, ut.language_id, CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('information_id=', i.information_id)) GROUP BY i.information_id, ut.language_id");
						
			foreach ($query->rows as $result) {
				$informations[$result['information_id']]['information_id'] = $result['information_id'];
				if (!isset($informations[$result['information_id']]['target_keyword'])) {
					$informations[$result['information_id']]['target_keyword'] = array();
				}
				if ($result['language_id'] && $result['target_keyword']) {
					$informations[$result['information_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
				}
			}
			
			foreach ($data['elements'] as $element) {
				if (isset($informations[$element['information_id']])) {
					$information = $informations[$element['information_id']];
					foreach ($languages as $language) {
						if (isset($element['target_keyword'][$language['language_id']])) {
							if (!isset($information['target_keyword'][$language['language_id']]) || (isset($information['target_keyword'][$language['language_id']]) && ($element['target_keyword'][$language['language_id']] != $information['target_keyword'][$language['language_id']]))) {
								$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'information_id=" . (int)$information['information_id'] . "' AND language_id = '" . (int)$language['language_id'] . "'");
								if ($element['target_keyword'][$language['language_id']]) {
									preg_match_all('/\[[^]]+\]/', $element['target_keyword'][$language['language_id']], $keywords);
									$sort_order = 1;
									foreach ($keywords[0] as $keyword) {
										$keyword = substr($keyword, 1, strlen($keyword)-2);
										$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'information_id=" . (int)$information['information_id'] . "', language_id = '" . (int)$language['language_id'] . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
										$sort_order++;
									}
								}
							}
						}
					}
				}	
			}
		}
		
		return true;
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