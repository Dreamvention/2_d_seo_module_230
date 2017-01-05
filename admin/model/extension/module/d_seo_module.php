<?php
class ModelExtensionModuleDSEOModule extends Model {
	private $codename = 'd_seo_module';
	
	/*
	*	Save File Manager.
	*/
	public function saveFileData($file, $data) {
		$dir = str_replace("system/", "", DIR_SYSTEM);
		if ($file == 'htaccess') {
			$file_on = $dir . '.htaccess';
			$file_off = $dir . '.htaccess.txt'; 
		}
		if ($file == 'robots') {
			$file_on = $dir . 'robots.txt';
			$file_off = $dir . '_robots.txt'; 
		}
		
		if ($data['status']) {
			if (file_exists($file_off)) unlink($file_off);
			$fh = fopen($file_on, "w");
			fwrite($fh, html_entity_decode($data['text']));
			fclose($fh);
		} else {
			if (file_exists($file_on)) unlink($file_on);
			$fh = fopen($file_off, "w");
			fwrite($fh, html_entity_decode($data['text']));
			fclose($fh);
		}
	}
	
	/*
	*	Return htaccess.
	*/
	public function getFileData($file) {
		$dir = str_replace("system/", "", DIR_SYSTEM);
		if ($file == 'htaccess') {
			$file_on = $dir . '.htaccess';
			$file_off = $dir . '.htaccess.txt'; 
		}
		if ($file == 'robots') {
			$file_on = $dir . 'robots.txt';
			$file_off = $dir . '_robots.txt'; 
		}
		
		$data = array();
		if (file_exists($file_on)) { 
			$data['status'] = true;
			$fh = fopen($file_on, "r");
			$data['text'] = fread($fh, filesize($file_on)+1);
			fclose($fh);
		} else {
			if (file_exists($file_off)) {
				$data['status'] = false;
				$fh = fopen($file_off, "r");
				$data['text'] = fread($fh, filesize($file_off)+1);
				fclose($fh);
			} else {
				$data['status'] = false;
				$data['text'] = '';
			}
		}
				
		return $data;
	}
	
	/*
	*	Add Language.
	*/
	public function addLanguage($data) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $manufacturer) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "', language_id = '" . (int)$data['language_id'] . "'");
		}

		$this->cache->delete('manufacturer');
	}
	
	/*
	*	Delete Language.
	*/
	public function deleteLanguage($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE language_id = '" . (int)$data['language_id'] . "'");

		$this->cache->delete('manufacturer');
	}
	
	/*
	*	Add Manufacturer Description.
	*/
	public function addManufacturerDescription($data) {
		foreach ($data['manufacturer_description'] as $language_id => $manufacturer_description) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$data['manufacturer_id'] . "', language_id = '" . (int)$language_id . "'");
		}
	}
	
	/*
	*	Save Category Target Keyword.
	*/
	public function saveCategoryTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'category_id=" . (int)$data['category_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $language_id => $keywords) {
				$sort_order = 1;
				foreach ($keywords as $keyword) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'category_id=" . (int)$data['category_id'] . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
				}
			}
		}
	}
	
	/*
	*	Save Category Target Keyword.
	*/
	public function saveProductTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'product_id=" . (int)$data['product_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $language_id => $keywords) {
				$sort_order = 1;
				foreach ($keywords as $keyword) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'product_id=" . (int)$data['product_id'] . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
				}
			}
		}
	}
	
	/*
	*	Save Manufacturer Target Keyword.
	*/
	public function saveManufacturerTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'manufacturer_id=" . (int)$data['manufacturer_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $language_id => $keywords) {
				$sort_order = 1;
				foreach ($keywords as $keyword) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'manufacturer_id=" . (int)$data['manufacturer_id'] . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
				}
			}
		}
	}
	
	/*
	*	Save Information Target Keyword.
	*/
	public function saveInformationTargetKeyword($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = 'information_id=" . (int)$data['information_id'] . "'");
						
		if (isset($data['target_keyword'])) {
			foreach ($data['target_keyword'] as $language_id => $keywords) {
				$sort_order = 1;
				foreach ($keywords as $keyword) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = 'information_id=" . (int)$data['information_id'] . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
				}
			}
		}
	}	
		
	/*
	*	Return Target Keywords.
	*/
	public function getTargetKeywords($data = array()) {
		$target_keywords = array();
		
		$languages = $this->getLanguages();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "url_target";
		
		$implode = array();
		
		if (!empty($data['filter_route'])) {
			$implode[] = "route = '" . $this->db->escape($data['filter_route']) . "'";
		}
		
		if (!empty($data['filter_language_id'])) {
			$implode[] = "language_id = '" . (int)$data['filter_language_id'] . "'";
		}
		
		if (!empty($data['filter_sort_order'])) {
			$implode[] = "sort_order = '" . (int)$data['filter_sort_order'] . "'";
		}
		
		if (!empty($data['filter_keyword'])) {
			$implode[] = "keyword = '" . $this->db->escape($data['filter_keyword']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(' AND ', $implode);
		}
		
		$sql .= " ORDER BY sort_order";
				
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$target_keywords[$result['route']][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
								
		return $target_keywords;
	}
	
	/*
	*	Return Category Target Keyword.
	*/
	public function getCategoryTargetKeyword($category_id, $language_id) {
		$category_target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = 'category_id=" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$category_target_keyword[$result['sort_order']] = $result;
			if (count($this->getTargetKeywords(array('filter_keyword' => $result['keyword'])))>1) {
				$category_target_keyword[$result['sort_order']]['duplicate'] = 1;
			}
		}
		
		return $category_target_keyword;
	}
	
	/*
	*	Return Product Target Keyword.
	*/
	public function getProductTargetKeyword($product_id, $language_id) {
		$product_target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = 'product_id=" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$product_target_keyword[$result['sort_order']] = $result;
			if (count($this->getTargetKeywords(array('filter_keyword' => $result['keyword'])))>1) {
				$product_target_keyword[$result['sort_order']]['duplicate'] = 1;
			}
		}
		
		return $product_target_keyword;
	}
	
	/*
	*	Return Manufacturer Target Keyword.
	*/
	public function getManufacturerTargetKeyword($manufacturer_id, $language_id) {
		$manufacturer_target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = 'manufacturer_id=" . (int)$manufacturer_id . "' AND language_id = '" . (int)$language_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$manufacturer_target_keyword[$result['sort_order']] = $result;
			if (count($this->getTargetKeywords(array('filter_keyword' => $result['keyword'])))>1) {
				$manufacturer_target_keyword[$result['sort_order']]['duplicate'] = 1;
			}
		}
		
		return $manufacturer_target_keyword;
	}
	
	/*
	*	Return Information Target Keyword.
	*/
	public function getInformationTargetKeyword($information_id, $language_id) {
		$information_target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = 'information_id=" . (int)$information_id . "' AND language_id = '" . (int)$language_id . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$information_target_keyword[$result['sort_order']] = $result;
			if (count($this->getTargetKeywords(array('filter_keyword' => $result['keyword'])))>1) {
				$information_target_keyword[$result['sort_order']]['duplicate'] = 1;
			}
		}
		
		return $information_target_keyword;
	}
	
	/*
	*	Return List Elements for Manager.
	*/
	public function getListElements($data) {
				
		if ($data['sheet_id'] == 'category') {
			$implode = array();
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'category_id') {
					$implode[] = "c.category_id";
				}
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
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'product_id') {
					$implode[] = "p.product_id";
				}
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
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'manufacturer_id') {
					$implode[] = "m.manufacturer_id";
				}
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
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'information_id') {
					$implode[] = "i.information_id";
				}
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
									
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT c.category_id, ut.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('category_id=', c.category_id)) GROUP BY c.category_id, ut.language_id");
														
				foreach ($query->rows as $result) {
					$categories[$result['category_id']]['category_id'] = $result['category_id'];
					if (!isset($categories[$result['category_id']]['target_keyword'])) {
						$categories[$result['category_id']]['target_keyword'] = array();
					}
					if ($result['language_id'] && $result['target_keyword']) {
						$categories[$result['category_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
					}
				}
			}

			return $categories;	
		}
		
		if ($data['sheet_id'] == 'product') {
			$products = array();
			
			$implode = array();
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT p.product_id, ut.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('product_id=', p.product_id)) GROUP BY p.product_id, ut.language_id");
					
				foreach ($query->rows as $result) {
					$products[$result['product_id']]['product_id'] = $result['product_id'];
					if (!isset($products[$result['product_id']]['target_keyword'])) {
						$products[$result['product_id']]['target_keyword'] = array();
					}
					if ($result['language_id'] && $result['target_keyword']) {
						$products[$result['product_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
					}
				}
			}

			return $products;	
		}
		
		if ($data['sheet_id'] == 'manufacturer') {
			$manufacturers = array();
						
			$implode = array();
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT m.manufacturer_id, ut.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('manufacturer_id=', m.manufacturer_id)) GROUP BY m.manufacturer_id, ut.language_id");
					
				foreach ($query->rows as $result) {
					$manufacturers[$result['manufacturer_id']]['manufacturer_id'] = $result['manufacturer_id'];
					if (!isset($manufacturers[$result['manufacturer_id']]['target_keyword'])) {
						$manufacturers[$result['manufacturer_id']]['target_keyword'] = array();
					}
					if ($result['language_id'] && $result['target_keyword']) {
						$manufacturers[$result['manufacturer_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
					}
				}
			}
						
			return $manufacturers;	
		}
		
		if ($data['sheet_id'] == 'information') {
			$informations = array();
			
			$implode = array();
			
			foreach ($data['fields'] as $field) {
				if ($field['id'] == 'target_keyword') {
					$implode[] = "CONCAT('[', GROUP_CONCAT(DISTINCT ut.keyword ORDER BY ut.sort_order SEPARATOR ']['), ']') as target_keyword";
				}
			}
			
			if ($implode) {
				$query = $this->db->query("SELECT i.information_id, ut.language_id, " . implode(', ', $implode) . " FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "url_target ut ON (ut.route = CONCAT('information_id=', i.information_id)) GROUP BY i.information_id, ut.language_id");
		
				foreach ($query->rows as $result) {
					$informations[$result['information_id']]['information_id'] = $result['information_id'];
					if (!isset($informations[$result['information_id']]['target_keyword'])) {
						$informations[$result['information_id']]['target_keyword'] = array();
					}
					if ($result['language_id'] && $result['target_keyword']) {
						$informations[$result['information_id']]['target_keyword'][$result['language_id']] = $result['target_keyword'];
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
	*	Save seo extensions.
	*/
	public function saveSEOExtensions($seo_extensions) {
		$this->load->model('setting/setting');
		
		$setting['d_seo_extension_install'] = $seo_extensions;
		$this->model_setting_setting->editSetting('d_seo_extension', $setting);
	}
	
	/*
	*	Return list of seo extensions.
	*/
	public function getSEOExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' ORDER BY code");
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
		
		$installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
		$installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
		
		$seo_extensions = array();
		$files = glob(DIR_APPLICATION . 'controller/extension/module/' . $this->codename . '*.php');
		if ($files) {
			foreach ($files as $file) {
				$seo_extension = basename($file, '.php');
				if (in_array($seo_extension, $installed_extensions) && in_array($seo_extension, $installed_seo_extensions)) {
					$seo_extensions[] = $seo_extension;
				}
			}
		}
		
		return $seo_extensions;
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
	
	/*
	*	Return Group ID.
	*/
	public function getGroupId() {
        $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . $this->user->getId() . "'");
        
		$user_group_id = (int)$user_query->row['user_group_id'];
        
        return $user_group_id;
    }
				
	/*
	*	Install.
	*/		
	public function installModule() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "url_target");
		$this->db->query("CREATE TABLE " . DB_PREFIX . "url_target (route VARCHAR(255) NOT NULL, language_id INT(11) NOT NULL, sort_order INT(3) NOT NULL, keyword VARCHAR(255) NOT NULL, PRIMARY KEY (route, language_id, sort_order), KEY keyword (keyword)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "manufacturer_description");
		$this->db->query("CREATE TABLE " . DB_PREFIX . "manufacturer_description (manufacturer_id INT(11) NOT NULL, language_id INT(11) NOT NULL, PRIMARY KEY (manufacturer_id, language_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer");
		$manufacturers = $query->rows;
		$languages = $this->getLanguages();
		foreach ($manufacturers as $manufacturer) {
			foreach ($languages as $language) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "', language_id = '" . (int)$language['language_id'] . "'");
			}
		}
	}
	
	/*
	*	Uninstall.
	*/		
	public function uninstallModule() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "url_target");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "manufacturer_description");
	}
}
?>