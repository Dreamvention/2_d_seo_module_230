Seo Module
==========
The fist professional SEO extension for opencart 2
Opencart version supported 2.3.x (other versions are in developement. Comming soon...)

##### Table of content
1. [Installation & Update](#installation-and-update)
2. [API](#api)
	1. [Admin events](#admin-list-of-events-and-their-methods)
		* [common](#common)
		* [setting](#setting)
		* [localisation](#localisation)
		* [catalog](#catalog)
	2. [Catalog events](#catalog-list-of-events-and-their-methods)
		* [common](#catalog-common)
		* [product](#product)


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
You can extend the SEO Module functionality by using the built-in API. The SEO module will look inside the ```admin/controller/extension/module/``` for ```d_seo_module_*.php``` and if found, will call specially named methods. The result will be used to modify the output using Opencart Event Methods.

####For the API to work you will need
1. name your extension controller beginning with ```d_seo_module_```
2. Add method, that corresponds to the event you want to subscribe to.

Here is an example of adding a new item to the SEO module Menu in admin panel:

```php
private $route = 'extension/module/d_seo_module_myfeature';
public function menu($menu_data) {
	$this->load->language($this->route);
	if ($this->user->hasPermission('access', $this->route)) {
		$menu_data[] = array(
			'name'	   => $this->language->get('heading_title_main'),
			'href'     => $this->url->link($this->route, 'token=' . $this->session->data['token'], true),
			'children' => array()		
		);
	}

	return $menu_data;
}
```
---

##Admin list of events and their methods
> ####How to use it?
> This is how you should understand the following events:

>` admin/view/common/column_left/before` is called before the `column_left.tpl` is rendered to the screen.

>To subsribe you will need to add the method `public function menu($menu_data)` to your controller file `admin/controller/extension/module/d_seo_module_myfeature.php` with a parameter `$menu_data`

>You will populate `$menu_data` with your menu item `array('name' => ..., 'href' => ..., 'children' => ...)` and `return $menu_data;`


###common
####1. view/common/column_left/before
#####menu()
_add an item in admin to seo menu. You will recieve the menu array, only to add your own menu item and return the menu array_

* **method:** `public function menu($menu_data)`
* **parameters:** `$menu_data[] = array( 'name' => ..., 'href' => ..., 'children' => ...);`
* **return:** `$menu_data = array()`

Exemple
```php
public function menu($menu_data) {
	$this->load->language('extension/module/d_seo_module_myfeature');

	if ($this->user->hasPermission('access', 'extension/module/d_seo_module_myfeature')) {
		$menu_data[] = array(
			'name'	   => $this->language->get('heading_title_main'),
			'href'     => $this->url->link('extension/module/d_seo_module_myfeature', 'token=' . $this->session->data['token'], true),
			'children' => array()		
		);
	}

	return $menu_data;
}
```
---
###setting
####1. view/setting/setting/after & view/setting/store_form/after
#####setting_tab_general()
_modify the output of store setting form and new store create form. You simply return an HTML of the input or anything else that you want to place into the form and tab_

* **method:** `public function setting_tab_general()`
* **parameters:** `none`
* **output:** `html`

Exemple
**admin/controller/extension/module/d_seo_module_myfeature.php**
```php
public function setting_tab_general() {
	//load models and language files
	$this->load->language('extension/module/d_seo_module_myfeature');
	$this->load->model('extension/module/d_seo_module_myfeature');

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

	//render the $data with the setting_tab_general_language.tpl. the HTML will be returned and added to the final HTML inside the Store Setting General tab.						
	return $this->load->view('extension/module/d_seo_module_myfeature/setting_tab_general.tpl', $data);
}
```

#####setting_tab_general_language()
_You can add html to a language tab, by using the `$language_id`_

* **method:** `public function setting_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

Exemple
**admin/controller/extension/module/d_seo_module_myfeature.php**
```php
public function setting_tab_general_language($language_id) {
	//load models and language files
	$this->load->language('extension/module/d_seo_module_myfeature');
	$this->load->model('extension/module/d_seo_module_myfeature');

	//get required parameters
	$data['language_id'] = $language_id;
	$data['languages'] = $this->model_extension_module_d_seo_module_myfeature->getLanguages();

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
		$data['config_myfeature'][$language_id]['myfeature_title'] = $this->config->get('config_myfeature_title');
	}

	//render the $data with the setting_tab_general_language.tpl. the HTML will be returned and added to the final HTML inside the Store Setting General tab.						
	return $this->load->view('extension/module/d_seo_module_myfeature/setting_tab_general_language.tpl', $data);
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
_this is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function setting_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####setting_style()
_This is a style input. You can use this for adding CSS to the form. Yet we recommend using he default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function setting_style()`
* **parameters:** `none`
* **output:** `html`

#####setting_script()
_Add js scripts to the form_
* **method:** `public function setting_script()`
* **parameters:** `none`
* **output:** `html`

###localisation
####1. model/localisation/language/addLanguage/after
#####language_add()
_after a new language has been added, you can preform your own actions like add a new column to a table_

* **method:** ```public function language_add($data)```
* **parameters:** ```$data = array( 'language_id' => ...);```
* **output:** `none`

Exemple
**admin/controller/extension/module/d_seo_module_myfeature.php**
```php
public function language_add($data) {
	$this->load->model('extension/module/d_seo_module_myfeature');
	$this->model_extension_module_d_seo_module_myfeature->addLanguage($data);
}
```
**admin/model/extension/module/d_seo_module_myfeature.php**
```php
public function addLanguage($data) {
	$this->db->query("ALTER TABLE " . DB_PREFIX . "url_redirect ADD (url_to_" . (int)$data['language_id'] . " VARCHAR(512) NOT NULL)");

	$this->db->query("UPDATE " . DB_PREFIX . "url_redirect SET url_to_" . (int)$data['language_id'] . " = url_to_" . (int)$this->config->get('config_language_id'));
}
```
####2. model/localisation/language/deleteLanguage/after
#####language_delete()
_called when a language is deleted. Similar to `language_add($data)`_

* **method:** ```public function language_delete($data)```
* **parameters:** ```$data = array( 'language_id' => ...);```
* **output:** `none`

---
###catalog
####1. view/catalog/category_form/after
#####category_form_tab_general()
_modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab_

* **method:** `public function category_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####category_form_tab_general_language()
_You can add html to a language tab, by using the `$language_id`_

* **method:** `public function category_form_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

#####category_form_tab_data()
* **method:** `public function category_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####category_form_tab_seo()
_this is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function category_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####category_form_style()
_This is a style input. You can use this for adding CSS to the form. Yet we recomend using he default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function category_form_style()`
* **parameters:** `none`
* **output:** `html`

#####category_form_script()
_Add js scripts to the form_

* **method:** `public function category_form_script()`
* **parameters:** `none`
* **output:** `html`


####2. model/catalog/category/addCategory/after
#####category_form_add()
_after a new category has been added, you can preform your own actions like update cache_

* **method:** ```public function category_form_add($data)```
* **parameters:** ```$data = array( 'category_id' => ...);```
* **output:** `none`

Example:
**admin/controller/extension/module/d_seo_module_myfeature.php**
```php
public function category_form_add($data) {
	$this->load->model('extension/module/d_seo_module_myfeature');

	//custom model method for updating category cache (it is up to you to implement it.
	$this->model_extension_module_d_seo_module_myfeature->updateCategoryCache($data);
}

```

####3. model/catalog/category/editCategory/after
#####category_form_edit()
_after a new category has been edited, you can preform your own actions like update cache_

* **method:** ```public function category_form_edit($data)```
* **parameters:** ```$data = array( 'category_id' => ...);```
* **output:** `none`

####4. view/catalog/product_form/after
#####product_form_tab_general()
_modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab_

* **method:** `public function product_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####product_form_tab_general_language()
_You can add html to a language tab, by using the `$language_id`_

* **method:** `public function product_form_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

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
_Add js scripts to the form_

* **method:** `public function product_form_script()`
* **parameters:** `none`
* **output:** `html`

####5. model/catalog/product/addProduct/after
#####product_form_add()
_after a new product has been added, you can preform your own actions like generate empty seo url_

* **method:** `public function product_form_add($data)`
* **parameters:** `$data = array( 'product_id' => ... )`
* **output:** `none`


####6. model/catalog/product/editProduct/after
#####product_form_edit()
_after a product has been edited, you can preform your own actions like update product url cache_

* **method:** `public function product_form_edit($data)`
* **parameters:** `$data = array( 'product_id' => ... )`
* **output:** `none`

####7. view/catalog/manufacturer_form/after
#####manufacturer_form_tab_general()
_modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab_

* **method:** `public function manufacturer_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_tab_general_language()
_You can add html to a language tab, by using the `$language_id`_

* **method:** `public function manufacturer_form_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

#####manufacturer_form_tab_data()
* **method:** `public function manufacturer_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_tab_seo()
_this is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function manufacturer_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_style()
_This is a style input. You can use this for adding CSS to the form. We recommended using the default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function manufacturer_form_style()`
* **parameters:** `none`
* **output:** `html`

#####manufacturer_form_script()
_Add js scripts to the form_
* **method:** `public function manufacturer_form_script()`
* **parameters:** `none`
* **output:** `html`

####8. model/catalog/manufacturer/addManufacturer/after
#####manufacturer_form_add()
_after a new product has been added, you can preform your own actions like add manufacturer description_

* **method:** `public function manufacturer_form_add($data)`
* **parameters:** `$data = array('manufacturer_id' => ... )`
* **output:** `none`

Example:
**controller/extension/module/d_seo_module_myfeature.php**
```php
public function manufacturer_form_add($data) {
	$this->load->model('extension/module/d_seo_module_myfeature');
	$this->model_extension_module_d_seo_module_myfeature->addManufacturerDescription($data);
}
```
**model/extension/module/d_seo_module_myfeature.php**
```php
public function addManufacturerDescription($data) {
	foreach ($data['manufacturer_description'] as $language_id => $manufacturer_description) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$data['manufacturer_id'] . "', language_id = '" . (int)$language_id . "'");
	}
}
```
####9. model/catalog/manufacturer/editManufacturer/after
#####manufacturer_form_edit()
_after a new product has been added, you can preform your own actions like generate seo url_

* **method:** `public function manufacturer_form_edit($data)`
* **parameters:** `$data = array('manufacturer_id' => ... )`
* **output:** `none`

####10. view/catalog/information_form/after
#####information_form_tab_general()
_modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab_

* **method:** `public function information_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

#####information_form_tab_general_language()
_You can add html to a language tab, by using the `$language_id`_

* **method:** `public function information_form_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

#####information_form_tab_data()
* **method:** `public function information_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

#####information_form_tab_seo()
_this is a custom seo tab. It will be visible if your module adds html to it._

* **method:** `public function information_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`

#####information_form_style()
_This is a style input. You can use this for adding CSS to the form. We recommended using the default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_

* **method:** `public function information_form_style()`
* **parameters:** `none`
* **output:** `html`

#####information_form_script()
_Add js scripts to the form_

* **method:** `public function information_form_script()`
* **parameters:** `none`
* **output:** `html`

####11. model/catalog/information/addInformation/after
#####information_add_after()
_after a product has been edited, you can preform your own actions like update product url cache_

* **method:** `public function information_add_after($data)`
* **parameters:** `$data = array( 'information_id' => ... )`
* **output:** `none`

####12. model/catalog/information/editInformation/after
#####information_edit_after()
_after a product has been edited, you can preform your own actions like update product url cache_

* **method:** `public function information_edit_after($data)`
* **parameters:** `$data = array( 'information_id' => ... )`
* **output:** `none`

---

##Catalog list of events and their methods
> ####How to use it?
> For the frontend you have two basic events:
> -`data` (before event - here you modify the data array)
> - `html` (after event - here you modify the HTML).

1. `view/common/home/before` is called before the `home.tpl` is rendered to the screen.
2. To subsribe you will need to add the method `public function home_data($data)` to your controller file `catalog/controller/extension/module/d_seo_module_myfeature.php` with a parameter `$data`
3.  You will modify `$data` accordingly and `return $data;`

###catalog common
####1. view/common/home/before
#####home_data()
_modify the data that will be rendered to the `home.tpl`_

* **method:** `public function home_data($data)`
* **parameters:** `$data = array( ... )`
* **output:** `$data = array( ... )`

Example:
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'extension/module/d_seo_module_myfeature';
private $config_file = 'd_seo_module_myfeature';

public function home_data($data) {
	$this->load->model($this->route);
	$this->load->model('setting/setting');

	$store_id = (int)$this->config->get('config_store_id');
	$language_id = (int)$this->config->get('config_language_id');

	// Get settings of d_seo_module_myfeature
	$this->config->load($this->config_file);
	$config_setting = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();

	$setting = $this->model_setting_setting->getSetting($this->codename, $store_id);


	$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
	$setting = isset($setting[$this->codename . '_setting']) ? $setting[$this->codename . '_setting'] : array();

	if (!empty($setting)) {
		$config_setting = array_replace_recursive($config_setting, $setting);
	}

	$setting = $config_setting;

	//check for d_seo_module_myfeature_status
	if ($status) {
		if (isset($this->request->post['config_description'])) {
			$description = $this->request->post['config_description'];
			$meta_title = $description[$language_id]['meta_title'];
			$meta_description= $description[$language_id]['meta_description'];
			$meta_keyword = $description[$language_id]['meta_keyword'];
		} elseif ($this->config->get('config_description')) {
			$description = $this->config->get('config_description');
			$meta_title = $description[$language_id]['meta_title'];
			$meta_description = $description[$language_id]['meta_description'];
			$meta_keyword = $description[$language_id]['meta_keyword'];
		} else {
			$meta_title = $this->config->get('config_meta_title');
			$meta_description = $this->config->get('config_meta_description');
			$meta_keyword = $this->config->get('config_meta_keyword');
		}

		$this->document->setTitle($meta_title);
		$this->document->setDescription($meta_description);
		$this->document->setKeywords($meta_keyword);

		$data['header'] = $this->load->controller('common/header');
	}

	return $data;
}
```

####2. view/*/template/common/home/after
#####home_html()
_modify the HTML of the `home.tpl` before bowser renders it_

* **method:** `public function home_html($output)`
* **parameters:** `(string) $output`
* **output:** `(string) $output`

Example:
```php
private $codename = 'd_seo_module_myfeature';
private $route = 'extension/module/d_seo_module_myfeature';
private $config_file = 'd_seo_module_myfeature';

public function home_html($html) {
		$this->load->model($this->route);
		$this->load->model('setting/setting');

		$store_id = (int)$this->config->get('config_store_id');
		$language_id = (int)$this->config->get('config_language_id');

		// Setting
		$this->config->load($this->config_file);
		$config_setting = ($this->config->get($this->codename . '_setting')) ? $this->config->get($this->codename . '_setting') : array();

		$setting = $this->model_setting_setting->getSetting($this->codename, $store_id);
		$status = isset($setting[$this->codename . '_status']) ? $setting[$this->codename . '_status'] : false;
		$setting = isset($setting[$this->codename . '_setting']) ? $setting[$this->codename . '_setting'] : array();

		if (!empty($setting)) {
			$config_setting = array_replace_recursive($config_setting, $setting);
		}

		$setting = $config_setting;

		if ($status) {
			if (isset($this->request->post['config_description'])) {
				$description = $this->request->post['config_description'];
				$meta_robots = $description[$language_id]['meta_robots'];
			} elseif ($this->config->get('config_description')) {
				$description = $this->config->get('config_description');
				$meta_robots = $description[$language_id]['meta_robots'];
			} else {
				$meta_robots = 'index,follow';
			}

			$html_dom = new d_simple_html_dom();
			$html_dom->load($html, $lowercase=true, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT);

			$html_dom->find('head', 0)->innertext .= '<meta name="robots" content="' . $meta_robots . '" />';

			return $html_dom;
		}

		return $html;
	}
```

---
###product
####1. view/product/category/before
#####category_data()
_modify the data that will be rendered to the `category.tpl`_

* **method:** `public function category_data($data)`
* **parameters:** `$data = array( ... )`
* **output:** `$data = array( ... )`

####2. view/*/template/product/category/after
#####category_html()
_modify the HTML of the `category.tpl` before bowser renders it_

* **method:** `public function category_html($output)`
* **parameters:** `(string) $output`
* **output:** `(string) $output`

####3. view/product/product/before
#####product_data()
_modify the data that will be rendered to the `product.tpl`_

* **method:** `public function product_data($data)`
* **parameters:** `$data = array( ... )`
* **output:** `$data = array( ... )`

####4. view/*/template/product/product/after
#####product_html()
_modify the HTML of the `product.tpl` before bowser renders it_

* **method:** `public function product_html($output)`
* **parameters:** `(string) $output`
* **output:** `(string) $output`

####5. view/product/manufacturer_info/before
#####manufacturer_info_data()
_modify the data that will be rendered to the `manufacturer_info.tpl`_

* **method:** `public function manufacturer_info_data($data)`
* **parameters:** `$data = array( ... )`
* **output:** `$data = array( ... )`

####6. view/*/template/product/manufacturer_info/after
#####manufacturer_info_html()
_modify the HTML of the `manufacturer_info.tpl` before bowser renders it_

* **method:** `public function manufacturer_info_html($output)`
* **parameters:** `(string) $output`
* **output:** `(string) $output`

---
###information
####1. view/information/information/before
#####information_data()
_modify the data that will be rendered to the `information.tpl`_

* **method:** `public function information_data($data)`
* **parameters:** `$data = array( ... )`
* **output:** `$data = array( ... )`

####2. view/*/template/information/information/after
#####information_html()
_modify the HTML of the `information.tpl` before bowser renders it_

* **method:** `public function information_html($output)`
* **parameters:** `(string) $output`
* **output:** `(string) $output`

---
