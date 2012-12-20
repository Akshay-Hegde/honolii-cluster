<?php
//messages
$lang['site:not_super_admin']		=	'You are not a Super Admin.';
$lang['site:really_delete']			=	'Are you sure? If you click this button "%s" will be deleted in its entirety. This cannot be undone.';
$lang['site:exists']				=	'The Site Domain must be unique. A site is already registered with the domain "%s"';
$lang['site:site_deleted']			=	'The site has been deleted successfully.';
$lang['site:site_delete_error']		=	'The site\'s files could not be deleted. You will have to remove these files manually %s';
$lang['site:folder_exists']			=	'There is a folder that must be removed before you can create a site by the same name:<br />%s';
$lang['site:create_success']		=	'The site has been created successfully.';
$lang['site:edit_success']			=	'Your changes to "%s" have been made successfully.';
$lang['site:delete_error']			=	'The site records could not be removed from the database';
$lang['site:delete_manually']		=	'The following folders must be deleted manually: <br />%s';
$lang['site:db_error']				=	'The site changes could not be saved to the database';
$lang['site:rename_notice']			=	'The following folders must be renamed manually: <br />%s';
$lang['site:rename_manually']		=	'Rename %s to %s';
$lang['site:create_manually']		=	'The following folders must be created manually: <br />%s';
$lang['site:admin_create_success']	=	'%s is now a Super Admin';
$lang['site:user_exists']			=	'Sorry, the email "%s" is already registered';
$lang['site:disable_self']			=	'You cannot disable yourself';
$lang['site:settings_success']		=	'The settings were updated successfully';

//addon management messages
$lang['site:addon_exists']			=	'"%s" already exists. You must remove it before re-uploading';
$lang['site:addon_duplicate']		=	'"%s" exists in both the site specific folder and the shared folder or you have a duplicate details class. One must be removed';
$lang['site:uninstall_success']		=	'"%s" has been uninstalled successfully';
$lang['site:uninstall_error']		=	'"%s" could not be uninstalled';
$lang['site:addon_not_specified']	=	'You must specify an addon to delete';
$lang['site:delete_success']		=	'"%s" has been deleted successfully';
$lang['site:manually_remove']		=	'The Add-on could not be completely removed. You must manually remove "%s"';
$lang['site:delete_addon_error']	=	'"%s" could not be deleted';
$lang['site:upload_success']		=	'The %s was uploaded successfully';
$lang['site:install_success']		=	'"%s" was installed successfully';
$lang['site:install_error']			=	'"%s" could not be installed';
$lang['site:enable_success']		=	'"%s" was enabled successfully';
$lang['site:enable_error']			=	'"%s" could not be enabled';
$lang['site:disable_success']		=	'"%s" was disabled successfully';
$lang['site:disable_error']			=	'"%s" could not be disabled';
$lang['site:upgrade_success']		=	'"%s" was upgraded successfully';
$lang['site:upgrade_error']			=	'"%s" could not be upgraded';

//addons confirm messages
$lang['site:confirm_install']		=	'If tables exist from a previous installation they may be dropped. Are you sure you want to proceed with the installation?';
$lang['site:confirm_uninstall']		=	'All database information will be lost! Are you sure you want to uninstall?';
$lang['site:confirm_upgrade']		=	'An attempt will be made to upgrade this addon. Do you have a backup in case it fails?';
$lang['site:confirm_delete']		=	'All database information and addon files will be lost!! Are you sure you want to proceed?';
$lang['site:confirm_shared_delete']	=	'If you delete this addon it will affect ALL sites!! Are you sure you want to proceed?';

//page titles
$lang['site:create_site']			=	'Create A New Site';
$lang['site:edit_site']				=	'Editing "%s"';
$lang['site:sites']					=	'Multi-Site Manager';
$lang['site:user_manager']			=	'Super Admin Manager';
$lang['site:create_admin']			=	'Create Another Super Admin';
$lang['site:edit_admin']			=	'Editing "%s"';
$lang['site:module_list']			=	'Modules for %s';
$lang['site:shared_module_list']	=	'Shared Modules';
$lang['site:widget_list']			=	'Widgets for %s';
$lang['site:shared_widget_list']	=	'Shared Widgets';
$lang['site:theme_list']			=	'Themes for %s';
$lang['site:shared_theme_list']		=	'Shared Themes';
$lang['site:shared_plugin_list']	=	'Shared Plugins';
$lang['site:modules']				=	'Modules';
$lang['site:widgets']				=	'Widgets';
$lang['site:themes']				=	'Themes';
$lang['site:plugins']				=	'Plugins';

//page descriptions
$lang['site:edit_site_desc']		=	'Edit and delete sites with a few clicks of your mouse';
$lang['site:create_site_desc']		=	'Create a completely new site right from this interface';
$lang['site:create_admin_desc']		=	'Manage additional Super-Admins who can create and delete sites';
$lang['site:super_admin_list']		=	'A list of all current Super Admins';
$lang['site:settings_desc']			=	'Manage settings for the Multi-Site Manager interface';
$lang['site:manage_addons_desc']	=	'Manage site specific add-ons as well as shared add-ons';


//labels
$lang['site:remove_admin']			=	'Delete Super Admin';
$lang['site:site']					=	'Site';
$lang['site:existing_sites']		=	'Existing Sites';
$lang['site:site_details']			=	'Site Details';
$lang['site:descriptive_name']		=	'Descriptive Name';
$lang['site:domain']				=	'Domain';
$lang['site:ref']					=	'Reference';
$lang['site:created_on']			=	'Created On';
$lang['site:manage']				=	'Manage';
$lang['site:super_admins']			=	'Super Admins';
$lang['site:add_super_admin']		=	'Add Super Admin';
$lang['site:first_admin']			=	'First User (Admin)';
$lang['site:username']				=	'Username';
$lang['site:email']					=	'Email';
$lang['site:active']				=	'Active';
$lang['site:status']				=	'Status';
$lang['site:last_login']			=	'Last Login';
$lang['site:last_admin_login']		=	'Last Admin Login';
$lang['site:stats']					=	'Stats';
$lang['site:addons']				=	'Add-ons';
$lang['site:resource']				=	'Resource';
$lang['site:usage']					=	'Usage';
$lang['site:tables']				=	'Database tables:';
$lang['site:users']					=	'Registered users:';
$lang['site:schema_version']		=	'Database schema version:';
$lang['site:settings']				=	'Settings';
$lang['site:date_format']			=	'Date Format';
$lang['site:lang_direction']		=	'Language Direction';
$lang['site:status_message']		=	'Status Message';
$lang['site:shared_title']			=	'Upload a shared %s';
$lang['site:site_upload_title']		=	'Upload a %s for this site only';
$lang['site:upload_desc']			=	'Select a zip file to upload';
$lang['site:addons_upload']			=	'Upload Add-ons';
$lang['site:allowed']				=	'Allowed';
$lang['site:disabled']				=	'Disabled';