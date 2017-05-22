Seo Module
==========
The first professional SEO extension for opencart 2
Opencart version supported 2.3.0 (other versions are in developement. Comming soon...)

##### Table of content
1. [Installation & Update](#installation-and-update)
2. [API](#api)
	1. [Admin events](#admin-list-of-events-and-their-methods)
		* [common](#admin-common)
		* [setting](#setting)
		* [localisation](#localisation)
		* [catalog](#catalog)
	2. [Catalog events](#catalog-list-of-events-and-their-methods)
		* [common](#catalog-common)
		* [startup](#startup)
		* [product](#product)
		* [information](#information)


Installation and Update
=======================
The easiest way is to use Shopunity.net extension to install the module.

###[Shopunity](https://shopunity.net) (recomended)
1. In Shopunty module search for SEO module and click install
2. After installation is complete click Admin
3. Click install inside the SEO module to complete the installation.

###Extension Installer (shopunity module required)
1. Go to Admin / Extensions / Extension Installer
2. Upload zip archive
3. Go to Admin / Extensions / Modules
4. Next to SEO module Click install
5. Edit SEO module
6. Click install to complete the installation process.

###FTP (shopunity module required)
1. Upload all the files from the folder upload
2. Go to Admin / Extensions / Modules
3. Next to SEO module Click install
4. Edit SEO module
5. Click install to complete the installation process.


###Update
You can update practically the same way as you have install the module. Only you will not need to click the final install inside the module since the module has already been installed. Though if the new version of the module locates missing parts, it will display an update button.

API
===
You can extend the SEO Module functionality by using the built-in API. The SEO module will look inside the ```admin/controller/d_seo_module/``` and if your extension was found, will call specially named methods. The result will be used to modify the output using Opencart Event Methods.

####For the API to work you will need
1. Install your extension in Opencart (table `oc_extension`).
2. Add your extension in the list ```d_seo_extension_install``` in the Opencart table `oc_setting`.
3. Add method, that corresponds to the event you want to subscribe to.

Here is an example of adding a new item to the SEO Module Menu in admin panel:

```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function menu() {
	$this->load->language($this->route);
		
	$menu = array();
		
	if ($this->user->hasPermission('access', 'extension/module/' . $this->codename)) {
		$menu[] = array(
			'name'	   		=> $this->language->get('heading_title_main'),
			'href'     		=> $this->url->link('extension/module/' . $this->codename, 'token=' . $this->session->data['token'], 'SSL'),
			'sort_order' 	=> 10,
			'children' 		=> array()
		);
	}

	return $menu;
}
```

---

##Admin list of events and their methods
> ####How to use it?
> This is how you should understand the following events:

>`admin/view/common/column_left/before` is called before the `column_left.tpl` is rendered to the screen.

>To subsribe you will need to add the method `public function menu()` to your controller file `admin/controller/d_seo_module/d_seo_module_myfeature.php`.

>You will populate `$menu` with your menu item(s) `array('name' => ..., 'href' => ..., 'sort_order' => ..., 'children' => ...)` and `return $menu;`


###common
####1. admin/view/common/column_left/before
#####menu()
_Add an item(s) in admin to seo menu. You will add your menu item(s) and return the menu array._

* **method:** `public function menu()`
* **parameters:** `$menu[] = array('name' => ..., 'href' => ..., 'sort_order' => ..., 'children' => ...);`
* **return:** `$menu = array()`

Example
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function menu() {
	$this->load->language($this->route);
		
	$menu = array();
		
	if ($this->user->hasPermission('access', 'extension/module/' . $this->codename)) {
		$menu[] = array(
			'name'	   		=> $this->language->get('heading_title_main'),
			'href'     		=> $this->url->link('extension/module/' . $this->codename, 'token=' . $this->session->data['token'], 'SSL'),
			'sort_order' 	=> 10,
			'children' 		=> array()
		);
	}

	return $menu;
}
```

---

###setting
####1. admin/view/setting/setting/after & admin/view/setting/store_form/after
#####setting_tab_general()
_Modify the output of store setting form and new store create form. You simply return an HTML of the input or anything else that you want to place into the form and tab._

* **method:** `public function setting_tab_general()`
* **parameters:** `none`
* **output:** `html`

Example
**admin/controller/d_seo_module/d_seo_module_myfeature.php**
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function setting_tab_general() {
	//load models and language files
	$this->load->language($this->route);
	
	$this->load->model($this->route);
	
	//get language data
	$data['entry_myfeature'] = $this->language->get('entry_myfeature');
	$data['help_myfeature'] = $this->language->get('help_myfeature');

	//load config file for module d_seo_module_myfeature and fetch default config values.
	$this->config->load('d_seo_module_myfeature');
	$data['setting'] = ($this->config->get('d_seo_module_myfeature_setting')) ? $this->config->get('d_seo_module_myfeature_setting') : array();

	//add config_myfeature value to the $data for settings general tab
	if (isset($this->request->post['config_myfeature'])) {
		$data['config_myfeature'] = $this->request->post['config_myfeature'];
	} else {
		$data['config_myfeature'] = $this->config->get('config_myfeature');
	}

	//render the $data with the setting_tab_general.tpl. the HTML will be returned and added to the final HTML inside the Store Setting General tab.						
	return $this->load->view($this->route . '/setting_tab_general.tpl', $data);
}
```

#####setting_tab_general_language()
_You can add html to the language tabs._

* **method:** `public function setting_tab_general_language()`
* **parameters:** `none`
* **output:** `$html_tab_general_language = array()`

Example
**admin/controller/extension/module/d_seo_module_myfeature.php**
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function setting_tab_general_language() {
	//load models and language files
	$this->load->language($this->route);
	
	$this->load->model($this->route);
	
	//get languages
	$languages = $this->{'model_d_seo_module_d_seo_module'}->getLanguages();
	
	//get language data
	$data['entry_myfeature'] = $this->language->get('entry_myfeature');
	$data['help_myfeature'] = $this->language->get('help_myfeature');

	//load config file for module d_seo_module_myfeature and fetch default config values.
	$this->config->load('d_seo_module_myfeature');
	$data['setting'] = ($this->config->get('d_seo_module_myfeature_setting')) ? $this->config->get('d_seo_module_myfeature_setting') : array();

	//add config_myfeature value to the $data for settings general tab
	if (isset($this->request->post['config_myfeature'])) {
		$data['config_myfeature'] = $this->request->post['config_myfeature'];
	} elseif ($this->config->get('config_myfeature')) {
		$data['config_myfeature'] = $this->config->get('config_myfeature');
	} else {
		foreach ($languages as $language) {
			$data['config_myfeature'][$language['language_id']]['myfeature_title'] = $this->config->get('config_myfeature_title');
		}
	}

	//render the $data with the setting_tab_general_language.tpl. the HTML will be returned and added to the final HTML inside the Store Setting General tab.
	$html_tab_general_language = array();
		
	foreach ($languages as $language) {
		$data['language_id'] = $language['language_id'];
		
		$html_tab_general_language[$data['language_id']] = $this->load->view($this->route . '/setting_tab_general_language.tpl', $data);
	}
				
	return $html_tab_general_language;
}
```

#####setting_tab_store()
* **method:** `public function setting_tab_store()`
* **parameters:** `none`
* **output:** `html`

#####setting_tab_local()
* **method:** `public function setting_tab_local()`
* **parameters:** `none`
* **output:** `html`

#####setting_tab_option()
* **method:** `public function setting_tab_option()`
* **parameters:** `none`
* **output:** `html`

#####setting_tab_seo()
_This is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function setting_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####setting_style()
_This is a style input. You can use this for adding CSS to the form. Yet we recommend using he default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function setting_style()`
* **parameters:** `none`
* **output:** `html`

#####setting_script()
_Add js scripts to the form._

* **method:** `public function setting_script()`
* **parameters:** `none`
* **output:** `html`

###localisation
####1. admin/model/localisation/language/addLanguage/after
#####language_add()
_After a new language has been added, you can preform your own actions like add a new column to a table._

* **method:** ```public function language_add($data)```
* **parameters:** ```$data = array('language_id' => ...);```
* **output:** `none`

Example
**admin/controller/d_seo_module/d_seo_module_myfeature.php**
```php
public function language_add($data) {
	...
}
```

####2. admin/model/localisation/language/deleteLanguage/after
#####language_delete()
_Called when a language is deleted. Similar to `language_add($data)`._

* **method:** ```public function language_delete($data)```
* **parameters:** ```$data = array('language_id' => ...);```
* **output:** `none`

---

###catalog
####1. admin/view/catalog/category_form/after
#####category_form_tab_general()
_Modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab._

* **method:** `public function category_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####category_form_tab_general_language()
_You can add html to the language tabs._

* **method:** `public function category_form_tab_general_language()`
* **parameters:** `none`
* **output:** `$html_tab_general_language = array()`

#####category_form_tab_data()
* **method:** `public function category_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####category_form_tab_seo()
_This is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function category_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####category_form_style()
_This is a style input. You can use this for adding CSS to the form. Yet we recomend using he default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function category_form_style()`
* **parameters:** `none`
* **output:** `html`

#####category_form_script()
_Add js scripts to the form._

* **method:** `public function category_form_script()`
* **parameters:** `none`
* **output:** `html`


####2. admin/model/catalog/category/addCategory/after
#####category_form_add()
_After a new category has been added, you can preform your own actions using an array $data._

* **method:** ```public function category_form_add($data)```
* **parameters:** ```$data = array('category_id' => ..., ...);```
* **output:** `none`

Example:
**admin/controller/d_seo_module/d_seo_module_myfeature.php**
```php
public function category_form_add($data) {
	...
}
```

####3. admin/model/catalog/category/editCategory/after
#####category_form_edit()
_After a new category has been edited, you can preform your own actions using an array $data._

* **method:** ```public function category_form_edit($data)```
* **parameters:** ```$data = array('category_id' => ..., ...);```
* **output:** `none`

####4. admin/view/catalog/product_form/after
#####product_form_tab_general()
_Modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab._

* **method:** `public function product_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####product_form_tab_general_language()
_You can add html to the language tabs._

* **method:** `public function product_form_tab_general_language()`
* **parameters:** `none`
* **output:** `$html_tab_general_language = array()`

#####product_form_tab_data()
* **method:** `public function product_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####product_form_tab_links()
* **method:** `public function product_form_tab_links()`
* **parameters:** `none`
* **output:** `html`

#####product_form_tab_seo()
_This is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function product_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####product_form_style()
_This is a style input. You can use this for adding CSS to the form. We recommended using the default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function product_form_style()`
* **parameters:** `none`
* **output:** `html`

#####product_form_script()
_Add js scripts to the form._

* **method:** `public function product_form_script()`
* **parameters:** `none`
* **output:** `html`

####5. admin/model/catalog/product/addProduct/after
#####product_form_add()
_After a new product has been added, you can preform your own actions using an array $data._

* **method:** `public function product_form_add($data)`
* **parameters:** `$data = array('product_id' => ..., ...)`
* **output:** `none`


####6. model/catalog/product/editProduct/after
#####product_form_edit()
_After a product has been edited, you can preform your own actions using an array $data._

* **method:** `public function product_form_edit($data)`
* **parameters:** `$data = array('product_id' => ..., ...)`
* **output:** `none`

####7. admin/view/catalog/manufacturer_form/after
#####manufacturer_form_tab_general()
_Modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab._

* **method:** `public function manufacturer_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_tab_general_language()
_You can add html to the language tabs._

* **method:** `public function manufacturer_form_tab_general_language()`
* **parameters:** `none`
* **output:** `$html_tab_general_language = array()`

#####manufacturer_form_tab_data()
* **method:** `public function manufacturer_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_tab_seo()
_This is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function manufacturer_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_style()
_This is a style input. You can use this for adding CSS to the form. We recommended using the default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function manufacturer_form_style()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_script()
_Add js scripts to the form._

* **method:** `public function manufacturer_form_script()`
* **parameters:** `none`
* **output:** `html`

####8. admin/model/catalog/manufacturer/addManufacturer/after
#####manufacturer_form_add()
_After a new product has been added, you can preform your own actions using an array $data._

* **method:** `public function manufacturer_form_add($data)`
* **parameters:** `$data = array('manufacturer_id' => ..., ...)`
* **output:** `none`

####9. admin/model/catalog/manufacturer/editManufacturer/after
#####manufacturer_form_edit()
_After a new product has been added, you can preform your own actions using an array $data._

* **method:** `public function manufacturer_form_edit($data)`
* **parameters:** `$data = array('manufacturer_id' => ..., ...)`
* **output:** `none`

####10. admin/view/catalog/information_form/after
#####information_form_tab_general()
_Modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab._

* **method:** `public function information_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####information_form_tab_general_language()
_You can add html to a language tabs._

* **method:** `public function information_form_tab_general_language()`
* **parameters:** `none`
* **output:** `$html_tab_general_language = array()`

#####information_form_tab_data()
* **method:** `public function information_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####information_form_tab_seo()
_This is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function information_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####information_form_style()
_This is a style input. You can use this for adding CSS to the form. We recommended using the default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function information_form_style()`
* **parameters:** `none`
* **output:** `html`

#####information_form_script()
_Add js scripts to the form._

* **method:** `public function information_form_script()`
* **parameters:** `none`
* **output:** `html`

####11. admin/model/catalog/information/addInformation/after
#####information_add_after()
_After a product has been edited, you can preform your own actions using an array $data._

* **method:** `public function information_add_after($data)`
* **parameters:** `$data = array('information_id' => ..., ...)`
* **output:** `none`

####12. admin/model/catalog/information/editInformation/after
#####information_edit_after()
_After a product has been edited, you can preform your own actions using an array $data._

* **method:** `public function information_edit_after($data)`
* **parameters:** `$data = array('information_id' => ..., ...)`
* **output:** `none`

---

##Catalog list of events and their methods
> ####How to use it?
> For the frontend you have two basic events:
> - `data` (before event - here you modify the data array)
> - `html` (after event - here you modify the HTML).

1. `catalog/view/common/home/before` is called before the `home.tpl` is rendered to the screen.
2. To subsribe you will need to add the method `public function home_data($data)` to your controller file `catalog/controller/d_seo_module/d_seo_module_myfeature.php` with a parameter `$data`
3.  You will modify `$data` accordingly and `return $data;`

###catalog common
####1. catalog/view/common/header/before
#####header_data()
_Modify the data that will be rendered to the `header.tpl`._

* **method:** `public function header_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

Example
**catalog/controller/d_seo_module/d_seo_module_myfeature.php**
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function header_data($data) {
	//load models and language files
	$this->load->language($this->route);
	
	$this->load->model($this->route);
	
	//get language data
	$data['myfeature'] = $this->language->get('myfeature');
	
	return $data;
}
```

####2. catalog/view/*/template/common/header/after
#####header_html()
_Modify the HTML of the `header.tpl` before bowser renders it._

* **method:** `public function header_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

Example
**catalog/controller/d_seo_module/d_seo_module_myfeature.php**
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function header_html($html) {
	//load models and language files
	$this->load->language($this->route);
	
	$this->load->model($this->route);
	
	//get language data
	$myfeature = $this->language->get('myfeature');
	
	$html_dom = new d_simple_html_dom();
	$html_dom->load((string)$html, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
		
	foreach ($html_dom->find('#myfeature') as $element) {
		$element->innertext = $myfeature;
	}
				
	return (string)$html_dom;
}
```

####3. catalog/view/common/footer/before
#####footer_data()
_Modify the data that will be rendered to the `footer.tpl`._

* **method:** `public function footer_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####4. catalog/view/*/template/common/footer/after
#####footer_html()
_Modify the HTML of the `footer.tpl` before bowser renders it._

* **method:** `public function footer_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####5. catalog/view/common/home/before
#####home_data()
_Modify the data that will be rendered to the `home.tpl`._

* **method:** `public function home_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####6. catalog/view/*/template/common/home/after
#####home_html()
_Modify the HTML of the `home.tpl` before bowser renders it._

* **method:** `public function home_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####7. catalog/controller/common/language/language
#####seo_url_language()
_When switching the language you can preform your own actions._

* **method:** `public function seo_url_language()`
* **parameters:** `none`
* **output:** `none`

Example
**admin/controller/d_seo_module/d_seo_module_myfeature.php**
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'd_seo_module/d_seo_module_myfeature';

public function seo_url_language() {
	$this->load->model($this->route);
		
	if (isset($this->request->post['redirect'])) {
		$this->request->post['redirect'] = $this->{'model_d_seo_module_' . $this->codename}->getURLForLanguage($this->request->post['redirect'], $this->session->data['language']);
	}
}
```

---

###startup
####1. catalog/controller/startup/seo_url/index
#####seo_url()
_Here you can get route of your page by seo keyword or preform your own actions until the route has not yet been determined._

* **method:** `public function seo_url()`
* **parameters:** `none`
* **output:** `none`

#####seo_url_check()
_Here you can preform your own actions after route of the page has been already determined._

* **method:** `public function seo_url_check()`
* **parameters:** `none`
* **output:** `html`

####2. catalog/controller/startup/seo_url/rewrite
#####seo_url_rewrite()
_Modify the link that will be returned function url->link._

* **method:** `public function seo_url_rewrite($link)`
* **parameters:** `(string) $link`
* **output:** `(string) $link`

---

###product
####1. catalog/view/product/category/before
#####category_data()
_Modify the data that will be rendered to the `category.tpl`._

* **method:** `public function category_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####2. catalog/view/*/template/product/category/after
#####category_html()
_Modify the HTML of the `category.tpl` before bowser renders it._

* **method:** `public function category_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####3. catalog/view/product/product/before
#####product_data()
_Modify the data that will be rendered to the `product.tpl`._

* **method:** `public function product_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####4. catalog/view/*/template/product/product/after
#####product_html()
_Modify the HTML of the `product.tpl` before bowser renders it._

* **method:** `public function product_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####5. catalog/view/product/manufacturer_list/before
#####manufacturer_list_data()
_Modify the data that will be rendered to the `manufacturer_list.tpl`._

* **method:** `public function manufacturer_list_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####6. catalog/view/*/template/product/manufacturer_list/after
#####manufacturer_list_html()
_Modify the HTML of the `manufacturer_list.tpl` before bowser renders it._

* **method:** `public function manufacturer_list_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####7. catalog/view/product/manufacturer_info/before
#####manufacturer_info_data()
_Modify the data that will be rendered to the `manufacturer_info.tpl`._

* **method:** `public function manufacturer_info_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####8. catalog/view/*/template/product/manufacturer_info/after
#####manufacturer_info_html()
_Modify the HTML of the `manufacturer_info.tpl` before bowser renders it._

* **method:** `public function manufacturer_info_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####9. catalog/view/product/search/before
#####search_data()
_Modify the data that will be rendered to the `search.tpl`._

* **method:** `public function search_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####10. catalog/view/*/template/product/search/after
#####search_html()
_Modify the HTML of the `search.tpl` before bowser renders it._

* **method:** `public function search_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

####11. catalog/view/product/special/before
#####special_data()
_Modify the data that will be rendered to the `special.tpl`._

* **method:** `public function special_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####12. catalog/view/*/template/product/special/after
#####special_html()
_Modify the HTML of the `special.tpl` before bowser renders it._

* **method:** `public function special_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

---

###information
####1. catalog/view/information/information/before
#####information_data()
_Modify the data that will be rendered to the `information.tpl`._

* **method:** `public function information_data($data)`
* **parameters:** `$data = array(...)`
* **output:** `$data = array(...)`

####2. catalog/view/*/template/information/information/after
#####information_html()
_Modify the HTML of the `information.tpl` before bowser renders it._

* **method:** `public function information_html($html)`
* **parameters:** `(string) $html`
* **output:** `(string) $html`

---
