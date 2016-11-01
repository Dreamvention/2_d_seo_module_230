<?php
class ModelExtensionModuleDSEOModule extends Model {
	private $route = 'd_seo_module';
		
	/*
	*	Return list of seo extensions.
	*/
	public function getSEOExtensions() {
		$installed_extensions = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE type = 'module' ORDER BY code");
		foreach ($query->rows as $result) {
			$installed_extensions[] = $result['code'];
		}		
		$seo_extensions = array();
		$files = glob(DIR_APPLICATION . 'controller/extension/module/' . $this->id . '*.php');
		if ($files) {
			foreach ($files as $file) {
				$seo_extension = basename($file, '.php');
				if (in_array($seo_extension, $installed_extensions)) {
					$seo_extensions[] = $seo_extension;
				}
			}
		}
		
		return $seo_extensions;
	}
	
}