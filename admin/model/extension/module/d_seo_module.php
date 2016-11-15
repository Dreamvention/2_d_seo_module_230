<?php
class ModelExtensionModuleDSEOModule extends Model {
	private $codename = 'd_seo_module';
	
	/*
	*	Save File Manager.
	*/
	public function saveFileData($file, $data) {
		$dir =  str_replace("system/", "", DIR_SYSTEM);
		if ($file=='htaccess') {
			$file_on = $dir . '.htaccess';
			$file_off = $dir . '.htaccess.txt'; 
		}
		if ($file=='robots') {
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
		$dir =  str_replace("system/", "", DIR_SYSTEM);
		if ($file=='htaccess') {
			$file_on = $dir . '.htaccess';
			$file_off = $dir . '.htaccess.txt'; 
		}
		if ($file=='robots') {
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
	*	Save Manufacturer Description.
	*/
	public function saveManufacturerDescription($data) {
		foreach ($data['manufacturer_description'] as $language_id => $manufacturer_description) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$data['manufacturer_id'] . "', language_id = '" . (int)$language_id . "'");
		}
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
	*	Install.
	*/		
	public function installModule() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "manufacturer_description");
		$this->db->query("CREATE TABLE " . DB_PREFIX . "manufacturer_description (manufacturer_id int(11) NOT NULL, language_id int(11) NOT NULL, PRIMARY KEY (manufacturer_id, language_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		
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
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "manufacturer_description");
	}
}
?>