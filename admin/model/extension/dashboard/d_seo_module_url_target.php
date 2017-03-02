<?php
class ModelExtensionDashboardDSEOModuleURLTarget extends Model {
	private $codename = 'd_seo_module_url_target';
	
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
	*	Return list of SEO URL Target extensions.
	*/
	public function getSEOURLTargetExtensions() {
		$this->load->model('setting/setting');
				
		$installed_extensions = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension ORDER BY code");
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}
				
		$seo_url_target_extensions = array();
		$files = glob(DIR_APPLICATION . 'controller/' . $this->codename . '/*.php');
		if ($files) {
			foreach ($files as $file) {
				$seo_url_target_extension = basename($file, '.php');
				if (in_array($seo_url_target_extension, $installed_extensions)) {
					$seo_url_target_extensions[] = $seo_url_target_extension;
				}
			}
		}
		
		return $seo_url_target_extensions;
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