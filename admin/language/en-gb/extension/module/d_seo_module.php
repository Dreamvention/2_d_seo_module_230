<?php
// Heading
$_['heading_title']       				= '<span style="color:#449DD0; font-weight:bold">SEO Module</span><span style="font-size:0.9em; color:#999"> by <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=Dreamvention" style="font-size:1em; color:#999" target="_blank">Dreamvention</a></span>'; 
$_['heading_title_main']  				= 'SEO Module';

// Text
$_['text_edit']            				= 'Edit SEO Module settings';
$_['text_install']              		= 'Install Module';
$_['text_modules']         				= 'Modules';
$_['text_settings']       				= 'Settings';
$_['text_instructions']   				= 'Instructions';
$_['text_basic_settings'] 				= 'Basic Settings';
$_['text_htaccess'] 					= 'htaccess';
$_['text_robots'] 						= 'robots';
$_['text_success']        				= 'Success: You have modified module SEO Module!';
$_['text_success_install']        		= 'Success: You have installed module SEO Module!';
$_['text_success_uninstall']			= 'Success: You have uninstalled module SEO Module!';
$_['text_module']              			= 'Module';
$_['text_all_stores']     				= 'All Stores';
$_['text_all_languages']  				= 'All Languages';
$_['text_yes'] 							= 'Yes';
$_['text_no'] 							= 'No';
$_['text_enabled']          			= 'Enabled';
$_['text_disabled']          			= 'Disabled';
$_['text_uninstall_confirm']          	= 'After uninstalling of SEO Module will delete all additional fields in the product, category, manufacturer and information that have been added after installation.\nYou sure you want to uninstall SEO Module? ';
$_['text_general']						= 'General';
$_['text_data']							= 'Data';
$_['text_seo']            				= 'SEO';
$_['text_instructions_full'] 			= '
<div class="row">
	<div class="col-sm-2">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#vtab_instruction_install"  data-toggle="tab">Installation and Updating</a></li>
			<li><a href="#vtab_instruction_setting" data-toggle="tab">Settings</a></li>
		</ul>
	</div>
	<div class="col-sm-10">
		<div class="tab-content">
			<div id="vtab_instruction_install" class="tab-pane active">
				<div class="tab-body">
					<h3>Installation</h3>
					<ol>
						<li>Unzip distribution file.</li>
						<li>Upload everything from the folder <code>UPLOAD</code> into the root folder of you shop.</li>
						<li>Goto admin of your shop and navigate to extensions -> modules -> SEO Module.</li>
						<li>Click install button.</li>
					</ol>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>Our installation process requires you to have access to the internet because we will install all the required dependencies before we install the module.</p>
					</div>
					<div class="bs-callout bs-callout-warning">
						<h4>Warning!</h4>
						<p>If you get an error on this step, be sure to make you <code>DOWNLOAD</code> folder (usually in system folder of you shop) writable.</p>
					</div>
					<h3>Updating</h3>
					<ol>
						<li>Unzip distribution file.</li>
						<li>Upload everything from the folder <code>UPLOAD</code> into the root folder of you shop.</li>
						<li>Click overwrite for all files.</li>
					</ol>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>Although we follow strict standards that do not allow feature updates to cause a full reinstall of the module, still it may happen that major releases require you to uninstall/install the module again before new feature take place.</p>
					</div>
					<div class="bs-callout bs-callout-warning">
						<h4>Warning!</h4>
						<p>If you have made custom corrections to the code, your code will be rewritten and lost once you update the module.</p>
					</div>
				</div>
			</div>
			<div id="vtab_instruction_setting" class="tab-pane">
				<div class="tab-body">
					<h3>Basic Settings</h3>
					<p>Here you can:</p>
					<ol>
						<li>Enable/Disable SEO Module on the pages of your shop by click Status.</li>
						<li>Uninstall SEO Module.</li>
					</ol>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>Uninstall SEO Module is possible only after uninstalling all other SEO modules.</p>
					</div>
					<div class="bs-callout bs-callout-warning">
						<h4>Warning!</h4>
						<p>After uninstalling of SEO Module will delete all additional fields in the product, category, manufacturer and information that have been added after installation.</p>
					</div>
					<h3>htaccess</h3>
					<p>Here you can edit, enable, disable file .htaccess.</p>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>.htaccess - the configuration file of Apache web server that allows you to manage the work through a variety of options the web server and website settings (directives) without changing the basic configuration of web server files.</p>
					</div>
					<div class="bs-callout bs-callout-warning">
						<h4>Warning!</h4>
						<p>Please be careful when editing the .htaccess file! The errors in this file can completely destroy your shop.</p>
					</div>
					<h3>robots</h3>
					<p>Here you can edit, enable, disable file robots.txt.</p>
					<div class="bs-callout bs-callout-info">
						<h4>Note!</h4>
						<p>robots.txt - the text file located in the root directory of the site, which recorded special instructions for search engine robots. The file contains directives, describing access to sections of the site and indicates which pages of data and should not be indexed.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';
$_['text_not_found'] = '
<div class="jumbotron">
<h1>Please install Shopunity</h1>
<p>Before you can use this module you will need to install Shopunity. Simply download the archive for your version of opencart and install it view Extension Installer or unzip the archive and upload all the files into your root folder from the UPLOAD folder.</p>
<p><a class="btn btn-primary btn-lg" href="https://shopunity.net/download" target="_blank">Download</a></p>
</div>';	

// Entry
$_['entry_status']        				= 'Status';
$_['entry_uninstall']					= 'Uninstall Module';
$_['entry_text']						= 'Text';

// Button		
$_['button_save'] 						= 'Save';
$_['button_save_and_stay'] 				= 'Save and Stay';
$_['button_cancel'] 					= 'Cancel';
$_['button_install'] 					= 'Install';
$_['button_uninstall'] 					= 'Uninstall';

// Error
$_['error_warning']          			= 'Warning: Please check the form carefully for errors!';
$_['error_permission']    				= 'Warning: You do not have permission to modify module SEO Module!';
$_['error_dependencies']    			= 'Warning: You can not uninstall this module until you uninstall dependencies modules!';

?>