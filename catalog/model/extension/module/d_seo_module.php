<?php
class ModelExtensionModuleDSEOModule extends Model {
	private $codename = 'd_seo_module';
	
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
	*	Return Target Keyword.
	*/
	public function getTargetKeyword($route) {
		$target_keyword = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_target WHERE route = '" . $this->db->escape($route) . "' ORDER BY sort_order");
		
		foreach($query->rows as $result) {
			$target_keyword[$result['language_id']][$result['sort_order']] = $result['keyword'];
		}
		
		return $target_keyword;
	}
		
	/*
	*	Return list of seo extensions.
	*/
	public function getSEOExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
		
		$installed_seo_extensions = $this->model_setting_setting->getSetting('d_seo_extension');
		$installed_seo_extensions = isset($installed_seo_extensions['d_seo_extension_install']) ? $installed_seo_extensions['d_seo_extension_install'] : array();
		
		$seo_extensions = array();
		$files = glob(DIR_APPLICATION . 'controller/' . $this->codename . '/*.php');
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
}