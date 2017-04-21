<?php
class ControllerDSEOModuleDSEOModule extends Controller {
	private $codename = 'd_seo_module';
	private $route = 'd_seo_module/d_seo_module';
	private $config_file = 'd_seo_module';
	
	/*
	*	Functions for SEO Module.
	*/		
	public function product_html($html) {
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->controller('product/product/review');
		
		$review = $this->response->getOutput();
				
		$html_dom = new d_simple_html_dom();
		$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
		
		foreach ($html_dom->find('#review') as $element) {
			$element->innertext = $review;
		}
				
		return (string)$html_dom;
	}
	
	public function search_data($data) {
		if (isset($this->request->get['tag']) && !$data['search']) {
			$this->load->language('product/search');
			
			$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag'];
			$data['search'] = $this->request->get['tag'];
		}
				
		return $data;
	}
}