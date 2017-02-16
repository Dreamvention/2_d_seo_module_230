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
	*	Create Default Custom Page.
	*/
	public function createDefaultCustomPage() {
		$languages = $this->getLanguages();
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route LIKE '%/%'");
		
		foreach ($languages as $language) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_target (route, language_id, sort_order, keyword) VALUES
				('account/account', " . $language['language_id'] . ", '1', 'my-account'),
				('account/address', " . $language['language_id'] . ", '1', 'address-book'),
				('account/download', " . $language['language_id'] . ", '1', 'downloads'),
				('account/edit', " . $language['language_id'] . ", '1', 'edit-account'),
				('account/forgotten', " . $language['language_id'] . ", '1', 'forgot-password'),
				('account/login', " . $language['language_id'] . ", '1', 'login'),
				('account/logout', " . $language['language_id'] . ", '1', 'logout'),
				('account/newsletter', " . $language['language_id'] . ", '1', 'newsletter'),
				('account/order', " . $language['language_id'] . ", '1', 'order-history'),
				('account/password', " . $language['language_id'] . ", '1', 'change-password'),
				('account/register', " . $language['language_id'] . ", '1', 'create-account'),
				('account/return', " . $language['language_id'] . ", '1', 'returns'),
				('account/return/insert', " . $language['language_id'] . ", '1', 'request-return'),
				('account/reward', " . $language['language_id'] . ", '1', 'reward-points'),
				('account/transaction', " . $language['language_id'] . ", '1', 'transactions'),
				('account/voucher', " . $language['language_id'] . ", '1', 'account-voucher'),
				('account/wishlist', " . $language['language_id'] . ", '1', 'wishlist'),
				('affiliate/account', " . $language['language_id'] . ", '1', 'affiliates'),
				('affiliate/edit', " . $language['language_id'] . ", '1', 'edit-affiliate-account'),
				('affiliate/forgotten', " . $language['language_id'] . ", '1', 'affiliate-forgot-password'),
				('affiliate/login', " . $language['language_id'] . ", '1', 'affiliate-login'),
				('affiliate/logout', " . $language['language_id'] . ", '1', 'affiliate-logout'),
				('affiliate/password', " . $language['language_id'] . ", '1', 'change-affiliate-password'),
				('affiliate/payment', " . $language['language_id'] . ", '1', 'affiliate-payment-options'),
				('affiliate/register', " . $language['language_id'] . ", '1', 'create-affiliate-account'),
				('affiliate/tracking', " . $language['language_id'] . ", '1', 'affiliate-tracking-code'),
				('affiliate/transaction', " . $language['language_id'] . ", '1', 'affiliate-transactions'),
				('checkout/cart', " . $language['language_id'] . ", '1', 'cart'),
				('checkout/checkout', " . $language['language_id'] . ", '1', 'checkout'),
				('checkout/success', " . $language['language_id'] . ", '1', 'checkout-success'),
				('checkout/voucher', " . $language['language_id'] . ", '1', 'gift-vouchers'),
				('common/home', " . $language['language_id'] . ", '1', ''),
				('product/compare', " . $language['language_id'] . ", '1', 'compare-products'),
				('product/manufacturer', " . $language['language_id'] . ", '1', 'brands'),
				('product/search', " . $language['language_id'] . ", '1', 'search'),
				('product/special', " . $language['language_id'] . ", '1', 'specials'),
				('information/contact', " . $language['language_id'] . ", '1', 'contact-us'),
				('information/sitemap', " . $language['language_id'] . ", '1', 'sitemap')
			");
		}
	}
	
	/*
	*	Save Custom Pages.
	*/
	public function saveCustomPages($custom_pages) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route LIKE '%/%'");
		
		foreach ($custom_pages as $custom_page) {
			foreach ($custom_page['target_keyword'] as $language_id => $target_keyword) {
				preg_match_all('/\[[^]]+\]/', $target_keyword, $keywords);
				
				$sort_order = 1;
				
				foreach ($keywords[0] as $keyword) {
					$keyword = substr($keyword, 1, strlen($keyword)-2);
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = '" . $this->db->escape($custom_page['route']) . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
					$sort_order++;
				}
			}
		}
	}
			
	/*
	*	Add Custom Page.
	*/
	public function addCustomPage($data) {
		foreach ($data['target_keyword'] as $language_id => $target_keyword) {
			preg_match_all('/\[[^]]+\]/', $target_keyword, $keywords);
				
			$sort_order = 1;
				
			foreach ($keywords[0] as $keyword) {
				$keyword = substr($keyword, 1, strlen($keyword)-2);
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_target SET route = '" . $this->db->escape($data['route']) . "', language_id = '" . (int)$language_id . "', sort_order = '" . $sort_order . "', keyword = '" .  $this->db->escape($keyword) . "'");
					
				$sort_order++;
			}
		}
	}
	
	/*
	*	Edit Custom Page.
	*/
	public function editCustomPage($data) {
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
	*	Delete Custom Page.
	*/
	public function deleteCustomPage($route) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_target WHERE route = '" . $this->db->escape($route) . "'");
	}
	
	/*
	*	Return Custom Pages.
	*/
	public function getCustomPages($data = array()) {
		$custom_pages = array();
		
		$languages = $this->getLanguages();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "url_target WHERE route LIKE '%/%'";
		
		$implode = array();
		
		if (!empty($data['filter_route'])) {
			$implode[] = "route = '" . $this->db->escape($data['filter_route']) . "'";
		}
		
		if (!empty($data['filter_keyword'])) {
			$implode[] = "keyword = '" . $this->db->escape($data['filter_keyword']) . "'";
		}
		
		if ($implode) {
			$sql .= " AND " . implode(' AND ', $implode);
		}
		
		$sql .= " ORDER BY route, sort_order";
		
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$custom_pages[$result['route']]['route'] = $result['route'];
			if ($result['language_id'] && $result['sort_order'] && $result['keyword']) {
				$custom_pages[$result['route']]['target_keyword'][$result['language_id']][$result['sort_order']] = $result['keyword'];
			}
		}
					
		return $custom_pages;
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
	*	Save SEO extensions.
	*/
	public function saveSEOExtensions($seo_extensions) {
		$this->load->model('setting/setting');
		
		$setting['d_seo_extension_install'] = $seo_extensions;
		$this->model_setting_setting->editSetting('d_seo_extension', $setting);
	}
	
	/*
	*	Return list of SEO extensions.
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
		$this->db->query("ALTER TABLE " . DB_PREFIX . "setting MODIFY code VARCHAR(64) NOT NULL");
		
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