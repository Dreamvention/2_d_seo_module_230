<?php
class ModelExtensionModuleDSEOModule extends Model {
	private $codename = 'd_seo_module';
		
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
	
}