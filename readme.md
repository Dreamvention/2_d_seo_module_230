#Seo Module
The fist professional SEO extension for opencart

##Installation & Update
The easiest way is to use Shopunity.net extension to install the module.

###Shopunity (recomended)
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

##Admin list of events and their functions
###common
####1. view/common/column_left/before
_add a item in admin to seo menu_

* **method:** `public function menu($menu_data)`
* **received parameters:** `$menu_data[] = array( 'name' => ..., 'href' => ..., 'children' => ...);`


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
####1. view/setting/setting/after
_modify the output of store settings list_

* **method:** ```public function menu($menu_data)```
* **parameters:** ```$menu_data = array( 'name' => ..., 'href' => ..., 'children' => ...);```

Exemple
```
code
```

####2. view/setting/store_form/after
_modify the output of a store setting form_

* **method:** ```public function menu($menu_data)```
* **parameters:** ```$menu_data = array( 'name' => ..., 'href' => ..., 'children' => ...);```

Exemple
```
code
```

###localisation
####1. model/localisation/language/addLanguage/after
_after a new language has been added, you can preform your own actions like add a new column to a table_

* **method:** ```public function language_add($data)```
* **received parameters:** ```$data = array( 'language_id' => ...);```

Exemple
```
/* admin/controller/extension/module/d_seo_module_myfeature.php  */
public function language_add($data) {
	$this->load->model($this->route);
	$this->{'model_extension_module_' . $this->codename}->addLanguage($data);
}
```

```
/* admin/model/extension/module/d_seo_module_myfeature.php */
public function addLanguage($data) {
	$this->db->query("ALTER TABLE " . DB_PREFIX . "url_redirect ADD (url_to_" . (int)$data['language_id'] . " VARCHAR(512) NOT NULL)");

	$this->db->query("UPDATE " . DB_PREFIX . "url_redirect SET url_to_" . (int)$data['language_id'] . " = url_to_" . (int)$this->config->get('config_language_id'));
	}
```
2. model/localisation/language/deleteLanguage/after
language_delete

###catalog
1. view/catalog/category_form/after
2. model/catalog/category/addCategory/after
3. model/catalog/category/editCategory/after
4. view/catalog/product_form/after
5. model/catalog/product/addProduct/after
6. model/catalog/product/editProduct/after
7. view/catalog/manufacturer_form/after
8. model/catalog/manufacturer/addManufacturer/after
9. model/catalog/manufacturer/editManufacturer/after
10. view/catalog/information_form/after
11. model/catalog/information/addInformation/after
12. model/catalog/information/editInformation/after

##Catalog list of events and their functions
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
