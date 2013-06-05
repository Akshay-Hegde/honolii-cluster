<section class="title">
	<h4><?php echo lang('streams:new_entry'); ?></h4>
</section>

<section class="item">
<div class="no_data"><?php

	echo lang('streams:no_fields_msg_first');

	if (group_has_role('streams', 'admin_streams'))
	{
		echo ' '.lang('streams:no_field_assign_msg').' '.anchor('admin/streams/new_assignment/'.$this->uri->segment(5), lang('streams:add_some_fields')).'.';
	}

		 ?></div>
</section>