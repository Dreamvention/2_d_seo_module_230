<?php
class ControllerDSEOModuleAdviserDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'd_seo_module_adviser/d_seo_module';
	private $config_file = 'd_seo_module';
	private $error = array();
	
	/*
	*	Functions for SEO Module Adviser.
	*/
	public function adviser_elements($route) {	
		$this->load->language($this->route);
		
		$this->load->model($this->route);
		
		return $this->{'model_d_seo_module_adviser_' . $this->codename}->getAdviserElements($route);
	}
}
