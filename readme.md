#Seo Module
The fist professional SEO extension for opencart

##Installation & Update
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

#API
You can extend the SEO Module functionality by using the built-in API. The SEO module will look inside the ```admin/controller/extension/module/``` for ```d_seo_module_*.php``` and if found, will call specially named methods. The result will be used to modify the output using Opencart Event Methods.

####For the API to work you will need
1. name your extension controller beginning with ```d_seo_module_```
2. Add method, that corresponds to the event your want to subscribe to.

Here is an example of add a new item to the SEO module Menu:

```
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

##Admin list of events and their methods
> ####How to use it?
> This is how you should understand the following events:
> 1. ` admin/view/common/column_left/before` is called before the `column_left.tpl` is rendered to the screen.
> 2. To subsribe your will add the method `public function menu($menu_data)` to your controller file `admin/controller/extension/module/d_seo_module_myfeature.php` with a parameter `$menu_data`
> 3.  You will populate `$menu_data` with your menu item `array('name' => ..., 'href' => ..., 'children' => ...)` and `return $menu_data;`

###common
####1. view/common/column_left/before
_add a item in admin to seo menu. You will recieve the menu array, only to add your own menu item and return the menu array_

* **method:** `public function menu($menu_data)`
* **parameters:** `$menu_data[] = array( 'name' => ..., 'href' => ..., 'children' => ...);`
* **return:** `$menu_data = array()`

Exemple
```
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

###setting
####1. view/setting/setting/after & view/setting/store_form/after
_modify the output of store setting form and new store create form. You simply return an HTML of the input or anything else that you want to place into the form and tab_

* **method:** `public function setting_tab_general()`
* **parameters:** `none`
* **output:** `html`

Exemple
```
code
```

_You can add html to a language tab, by using the `$language_id`_
* **method:** `public function setting_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

Exemple
```
code
```


* **method:** `public function setting_tab_store()`
* **parameters:** `none`
* **output:** `html`

* **method:** `public function setting_tab_local()`
* **parameters:** `none`
* **output:** `html`

* **method:** `public function setting_tab_option()`
* **parameters:** `none`
* **output:** `html`


_this is a custom seo tab. It will be visible if your module adds html to it._
* **method:** `public function setting_tab_seo()`
* **parameters:** `none`
* **output:** `html`


_This is a style input. You can use this for adding CSS to the form. Yet we recomend using he default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_
* **method:** `public function setting_style()`
* **parameters:** `none`
* **output:** `html`

_Add js scripts to the form_
* **method:** `public function setting_script()`
* **parameters:** `none`
* **output:** `html`

###localisation
####1. model/localisation/language/addLanguage/after
_after a new language has been added, you can preform your own actions like add a new column to a table_

* **method:** ```public function language_add($data)```
* **parameters:** ```$data = array( 'language_id' => ...);```

Exemple
**admin/controller/extension/module/d_seo_module_myfeature.php**
```
public function language_add($data) {
	$this->load->model($this->route);
	$this->{'model_extension_module_' . $this->codename}->addLanguage($data);
}
```
**admin/model/extension/module/d_seo_module_myfeature.php**
```
public function addLanguage($data) {
	$this->db->query("ALTER TABLE " . DB_PREFIX . "url_redirect ADD (url_to_" . (int)$data['language_id'] . " VARCHAR(512) NOT NULL)");

	$this->db->query("UPDATE " . DB_PREFIX . "url_redirect SET url_to_" . (int)$data['language_id'] . " = url_to_" . (int)$this->config->get('config_language_id'));
	}
```
####2. model/localisation/language/deleteLanguage/after
_called when a language is deleted. Similar to `language_add($data)`_
* **method:** ```public function language_delete($data)```
* **parameters:** ```$data = array( 'language_id' => ...);```

###catalog
####1. view/catalog/category_form/after
_modify the HTML output of category form. You simply return an HTML of the input or anything else that you want to place into the form based on the tab_

* **method:** `public function category_form_tab_general()`
* **parameters:** `none`
* **output:** `html`

Exemple
```
code
```

_You can add html to a language tab, by using the `$language_id`_
* **method:** `public function category_form_tab_general_language($language_id)`
* **parameters:** `$language_id`
* **output:** `html`

Exemple
```
code
```


* **method:** `public function category_form_tab_data()`
* **parameters:** `none`
* **output:** `html`

_this is a custom seo tab. It will be visible if your module adds html to it._
* **method:** `public function category_form_tab_seo()`
* **parameters:** `none`
* **output:** `html`


_This is a style input. You can use this for adding CSS to the form. Yet we recomend using he default `$this->document->addStyle($href, $rel = 'stylesheet', $media = 'screen')`;_
* **method:** `public function category_form_style()`
* **parameters:** `none`
* **output:** `html`

_Add js scripts to the form_
* **method:** `public function category_form_script()`
* **parameters:** `none`
* **output:** `html`


####2. model/catalog/category/addCategory/after
_after a new category has been added, you can preform your own actions like update cache_

* **method:** ```public function category_form_add($data)```
* **parameters:** ```$data = array( 'category_id' => ...);```

Example:
```
code
```
####3. model/catalog/category/editCategory/after
_after a new category has been edited, you can preform your own actions like update cache_
* **method:** ```public function category_form_edit($data)```
* **parameters:** ```$data = array( 'category_id' => ...);```

Example:
```
code
```

####4. view/catalog/product_form/after
####5. model/catalog/product/addProduct/after
####6. model/catalog/product/editProduct/after
####7. view/catalog/manufacturer_form/after
####8. model/catalog/manufacturer/addManufacturer/after
####9. model/catalog/manufacturer/editManufacturer/after
####10. view/catalog/information_form/after
####11. model/catalog/information/addInformation/after
####12. model/catalog/information/editInformation/after

##Catalog list of events and their methods
###common
1. view/common/home/before
2. view/*/template/common/home/after

###product
1. view/product/category/before
2. view/*/template/product/category/after
3. view/product/product/before
4. view/*/template/product/product/after
5. view/product/manufacturer_info/before
6. view/*/template/product/manufacturer_info/after

###information
1. view/information/information/before
2. view/*/template/information/information/after
