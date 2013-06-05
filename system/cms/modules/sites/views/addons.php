<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#modules"><span><?php echo lang('site:modules');?></span></a></li>
		<li><a href="#widgets"><span><?php echo lang('site:widgets');?></span></a></li>
		<li><a href="#themes"><span><?php echo lang('site:themes');?></span></a></li>
		<li><a href="#plugins"><span><?php echo lang('site:plugins');?></span></a></li>
	</ul>
	
<div class="tabs">

	<div id="modules">
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo sprintf(lang('site:module_list'), $site->name);?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/module/0/0', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th><?php echo lang('desc_label');?></th>
							<th><?php echo lang('version_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($modules AS $module): ?>
					<?php if ($module['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>">
								<?php if (is_array($module['info']['name']) and isset($module['info']['name'][CURRENT_LANGUAGE])): ?>
									<?php echo $module['info']['name'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($module['info']['name']) and isset($module['info']['name'][config_item('default_language')])): ?>
									<?php echo $module['info']['name'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $module['info']['name']; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (is_array($module['info']['description']) and isset($module['info']['description'][CURRENT_LANGUAGE])): ?>
									<?php echo $module['info']['description'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($module['info']['description']) and isset($module['info']['description'][config_item('default_language')])): ?>
									<?php echo $module['info']['description'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $module['info']['description']; ?>
								<?php endif; ?>
							</td>
							<td class="align-center"><?php echo $module['version']; ?></td>
							<td class="align-center btns">
								<?php if (isset($module['database']['installed']) and $module['database']['installed'] == '1'): ?>
									<?php if ($module['database']['enabled']): ?>
										<?php echo anchor('sites/addons/disable/' . $site->ref . '/module/' . $module['slug'], lang('global:disable'), array('class'=>'btn')); ?>
									<?php else: ?>
										<?php echo anchor('sites/addons/enable/' . $site->ref . '/module/' . $module['slug'], lang('global:enable'), array('class'=>'btn')); ?>
									<?php endif; ?>
									&nbsp;&nbsp;
									<?php if ($module['version'] == $module['database']['version']): ?>
										<?php echo anchor('sites/addons/uninstall/' . $site->ref . '/module/' . $module['slug'], lang('global:uninstall'), array('class'=>'confirm btn', 'title'=>lang('site:confirm_uninstall'))); ?>
									<?php else: ?>
										<?php echo anchor('sites/addons/upgrade/' . $site->ref . '/module/' . $module['slug'], lang('global:upgrade'), array('class'=>'confirm btn', 'title'=>lang('site:confirm_upgrade'))); ?>
									<?php endif; ?>
								<?php else: ?>
									<?php echo anchor('sites/addons/install/' . $site->ref . '/module/' . $module['slug'], lang('global:install'), array('class'=>'confirm btn', 'title'=>lang('site:confirm_install'))); ?>
								<?php endif; ?>
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/module/' . $module['slug'], lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
		
		
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo lang('site:shared_module_list');?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/module/0/1', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th><?php echo lang('desc_label');?></th>
							<th><?php echo lang('version_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($modules AS $module): ?>
					<?php if ( ! $module['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>">
								<?php if (is_array($module['info']['name']) and isset($module['info']['name'][CURRENT_LANGUAGE])): ?>
									<?php echo $module['info']['name'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($module['info']['name']) and isset($module['info']['name'][config_item('default_language')])): ?>
									<?php echo $module['info']['name'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $module['info']['name']; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (is_array($module['info']['description']) and isset($module['info']['description'][CURRENT_LANGUAGE])): ?>
									<?php echo $module['info']['description'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($module['info']['description']) and isset($module['info']['description'][config_item('default_language')])): ?>
									<?php echo $module['info']['description'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $module['info']['description']; ?>
								<?php endif; ?>
							</td>
							<td class="align-center"><?php echo $module['version']; ?></td>
							<td class="align-center btns">
								<?php if (isset($module['database']['installed']) and $module['database']['installed'] == '1'): ?>
									<?php if ($module['database']['enabled']): ?>
										<?php echo anchor('sites/addons/disable/' . $site->ref . '/module/' . $module['slug'], lang('global:disable'), array('class'=>'btn')); ?>
									<?php else: ?>
										<?php echo anchor('sites/addons/enable/' . $site->ref . '/module/' . $module['slug'], lang('global:enable'), array('class'=>'btn')); ?>
									<?php endif; ?>
									&nbsp;&nbsp;
									<?php if ($module['version'] == $module['database']['version']): ?>
										<?php echo anchor('sites/addons/uninstall/' . $site->ref . '/module/' . $module['slug'] . '/1', lang('global:uninstall'), array('class'=>'confirm btn', 'title'=>lang('site:confirm_uninstall'))); ?>
									<?php else: ?>
										<?php echo anchor('sites/addons/upgrade/' . $site->ref . '/module/' . $module['slug'] . '/1', lang('global:upgrade'), array('class'=>'confirm btn', 'title'=>lang('site:confirm_upgrade'))); ?>
									<?php endif; ?>
								<?php else: ?>
									<?php echo anchor('sites/addons/install/' . $site->ref . '/module/' . $module['slug'] . '/1', lang('global:install'), array('class'=>'confirm btn', 'title'=>lang('site:confirm_install'))); ?>
								<?php endif; ?>
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/module/' . $module['slug'] . '/1', lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_shared_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
	</div>
	
	
	<div id="widgets">
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo sprintf(lang('site:widget_list'), $site->name);?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/widget/0/0', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th><?php echo lang('desc_label');?></th>
							<th><?php echo lang('version_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($widgets AS $widget): ?>
					<?php if ($widget['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>">
								<?php if (is_array($widget['title']) and isset($widget['title'][CURRENT_LANGUAGE])): ?>
									<?php echo $widget['title'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($widget['title']) and isset($widget['title'][config_item('default_language')])): ?>
									<?php echo $widget['title'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $widget['title']; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (is_array($widget['description']) and isset($widget['description'][CURRENT_LANGUAGE])): ?>
									<?php echo $widget['description'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($widget['description']) and isset($widget['description'][config_item('default_language')])): ?>
									<?php echo $widget['description'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $widget['description']; ?>
								<?php endif; ?>
							</td>
							<td class="align-center"><?php echo $widget['version']; ?></td>
							<td class="align-center btns">
								<?php if (isset($widget['database']['enabled']) and $widget['database']['enabled'] == '1'): ?>
									<?php echo anchor('sites/addons/disable/' . $site->ref . '/widget/' . $widget['slug'], lang('global:disable'), array('class'=>'btn')); ?>
								<?php else: ?>
									<?php echo anchor('sites/addons/enable/' . $site->ref . '/widget/' . $widget['slug'], lang('global:enable'), array('class'=>'btn')); ?>
								<?php endif; ?>
								&nbsp;&nbsp;
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/widget/' . $widget['slug'], lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
		
		
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo sprintf(lang('site:shared_widget_list'), $site->name);?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/widget/0/1', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th><?php echo lang('desc_label');?></th>
							<th><?php echo lang('version_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($widgets AS $widget): ?>
					<?php if ( ! $widget['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>">
								<?php if (is_array($widget['title']) and isset($widget['title'][CURRENT_LANGUAGE])): ?>
									<?php echo $widget['title'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($widget['title']) and isset($widget['title'][config_item('default_language')])): ?>
									<?php echo $widget['title'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $widget['title']; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (is_array($widget['description']) and isset($widget['description'][CURRENT_LANGUAGE])): ?>
									<?php echo $widget['description'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($widget['description']) and isset($widget['description'][config_item('default_language')])): ?>
									<?php echo $widget['description'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $widget['description']; ?>
								<?php endif; ?>
							</td>
							<td class="align-center"><?php echo $widget['version']; ?></td>
							<td class="align-center btns">
								<?php if (isset($widget['database']['enabled']) and $widget['database']['enabled'] == '1'): ?>
									<?php echo anchor('sites/addons/disable/' . $site->ref . '/widget/' . $widget['slug'] . '/1', lang('global:disable'), array('class'=>'btn')); ?>
								<?php else: ?>
									<?php echo anchor('sites/addons/enable/' . $site->ref . '/widget/' . $widget['slug'] . '/1', lang('global:enable'), array('class'=>'btn')); ?>
								<?php endif; ?>
								&nbsp;&nbsp;
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/widget/' . $widget['slug'] . '/1', lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_shared_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
	</div>
	
	
	<div id="themes">
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo sprintf(lang('site:theme_list'), $site->name);?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/theme/0/0', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th><?php echo lang('desc_label');?></th>
							<th><?php echo lang('version_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($themes AS $theme): ?>
					<?php if ($theme['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>">
								<?php if (is_array($theme['name']) and isset($theme['name'][CURRENT_LANGUAGE])): ?>
									<?php echo $theme['name'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($theme['name']) and isset($theme['name'][config_item('default_language')])): ?>
									<?php echo $theme['name'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $theme['name']; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (is_array($theme['description']) and isset($theme['description'][CURRENT_LANGUAGE])): ?>
									<?php echo $theme['description'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($theme['description']) and isset($theme['description'][config_item('default_language')])): ?>
									<?php echo $theme['description'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $theme['description']; ?>
								<?php endif; ?>
							</td>
							<td class="align-center"><?php echo $theme['version']; ?></td>
							<td class="align-center btns">
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/theme/' . $theme['slug'], lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
		
		
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo sprintf(lang('site:shared_theme_list'), $site->name);?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/theme/0/1', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th><?php echo lang('desc_label');?></th>
							<th><?php echo lang('version_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($themes AS $theme): ?>
					<?php if ( ! $theme['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>">
								<?php if (is_array($theme['name']) and isset($theme['name'][CURRENT_LANGUAGE])): ?>
									<?php echo $theme['name'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($theme['name']) and isset($theme['name'][config_item('default_language')])): ?>
									<?php echo $theme['name'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $theme['name']; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (is_array($theme['description']) and isset($theme['description'][CURRENT_LANGUAGE])): ?>
									<?php echo $theme['description'][CURRENT_LANGUAGE]; ?>
								<?php elseif (is_array($theme['description']) and isset($theme['description'][config_item('default_language')])): ?>
									<?php echo $theme['description'][config_item('default_language')]; ?>
								<?php else: ?>
									<?php echo $theme['description']; ?>
								<?php endif; ?>
							</td>
							<td class="align-center"><?php echo $theme['version']; ?></td>
							<td class="align-center btns">
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/theme/' . $theme['slug'] . '/1', lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_shared_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
	</div>
	
	
	<div id="plugins">
		<section class="box float-none">
		
			<header>
				<h3 class="addons-header"><?php echo sprintf(lang('site:shared_plugin_list'), $site->name);?></h3>
				<div class="buttons align-right">
					<?php echo anchor('sites/addons/upload/'.$this->ref.'/plugin/0/1', lang('global:upload'), 'class="btn modal"'); ?>
				</div>
			</header>
		
				<table class="table-list">
					<thead>
						<tr>
							<th><?php echo lang('name_label');?></th>
							<th class="align-center"><?php echo lang('global:actions'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($plugins AS $plugin): ?>
					<?php if ( ! $plugin['shared']) continue; ?>
						<tr>
							<td class="<?php echo alternator('', 'even'); ?>"><?php echo $plugin['name']; ?></td>
							<td class="align-center btns">
								<?php echo anchor('sites/addons/delete/' . $site->ref . '/plugin/' . $plugin['slug'] . '/1', lang('global:delete'), array('class'=>'confirm btn red', 'title'=>lang('site:confirm_shared_delete'))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
		
		</section>
	</div>
	
</div>