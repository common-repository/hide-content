/*
Plugin Name: Hide Content
Plugin URI: http://school-wp.net/plaginy/plagin-wordpress-hide-content/
Description: Plugin hides the content from unauthorized users.
Version: 0.2
Author: School-wp.net
Author URI: http://school-wp.net
License: GPLv2 or later
*/

(function() {
	tinymce.create('tinymce.plugins.swp_add_hide_content_button', {
		init : function(ed, url) {
			ed.addButton('hide', { 
				title : 'Hide Content', 
				image : url+'/icon.png', 
				onclick : function() {
					ed.selection.setContent('[hide]' + ed.selection.getContent() + '[/hide]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : 'swp_add_hide_content_button',
				author : 'School-WP',
				authorurl : 'http://school-wp.net',
				infourl : 'http://school-wp.net',
				version : '0.1'
			};
		}
	});
	tinymce.PluginManager.add('swp_add_hide_content_button', tinymce.plugins.swp_add_hide_content_button);
})();