<section class="title">
	<h4><?php echo lang('newsletters.statistics'); ?></h4>
</section>

<section class="item">
	<?php if (!empty($statistics)): ?>
	
		<ul class="statistics">
			<h4><?php echo lang('newsletters.open_statistics'); ?></h4>
			
			<li class="<?php echo alternator('', 'even'); ?>">
					<h6><?php echo lang('newsletters.unique_opens'). ' :<span class="data">' . $statistics->unique_opens; ?></span></h6>
			</li>
			
			<li class="<?php echo alternator('', 'even'); ?>">
					<h6><?php echo lang('newsletters.total_opens'). ' :<span class="data">' . $statistics->total_opens; ?></span></h6>
			</li>
		</ul>
	<br />
	<br />
		
	<h4><?php echo lang('newsletters.click_report'); ?></h4>
		<table border="0" class="table-list">    
		  <thead>
				<tr>
					<th>URL</th>
					<th>Unique Clicks</th>
					<th>Total Clicks</th>
				</tr>
		  </thead>
			<tbody>
				<?php foreach ($statistics as $url => $stats): ?>
				<?php if($url == 'unique_opens' OR $url == 'total_opens') continue; ?>
				<tr>
					<td><?php echo $url; ?></td>
					<td><?php echo $stats->unique_visitors; ?></td>
					<td><?php echo $stats->total_visitors; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="blank-slate">
			<img src="<?php echo BASE_URL.'addons/modules/newsletters/img/news.png' ?>" />
		
			<h2><?php echo lang('newsletters.no_newsletters_error');?></h2>
		</div>
	<?php endif;?>
</section>