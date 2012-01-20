<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Newsletters extends Module {

	public $version = '1.3.1';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Newsletters'
			),
			'description' => array(
				'en' => 'Send email newsletters to your subscribers.'
			),
			'skip_xss' => TRUE,
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'users',
		    'sections' => array(
			    'newsletters' => array(
				    'name' => 'newsletters.newsletters',
				    'uri' => 'admin/newsletters',
				    'shortcuts' => array(
						array(
					 	   'name' => 'newsletters.add_title',
						    'uri' => 'admin/newsletters/create',
							'class' => 'add'
						),
					),
				),
				'templates' => array(
				    'name' => 'newsletters.templates',
				    'uri' => 'admin/newsletters/templates',
			    ),
			    'subscribers' => array(
				    'name' => 'newsletters.subscribers',
				    'uri' => 'admin/newsletters/subscribers',
				    'shortcuts' => array(
						array(
					 	   'name' => 'newsletters.export_xml',
						    'uri' => 'admin/newsletters/subscribers/export/xml',
						),
						array(
					 	   'name' => 'newsletters.export_csv',
						    'uri' => 'admin/newsletters/subscribers/export/csv',
						),
						array(
					 	   'name' => 'newsletters.export_json',
						    'uri' => 'admin/newsletters/subscribers/export/json',
						),
					),
				),
		    ),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('newsletters');
		$this->dbforge->drop_table('newsletter_emails');
		$this->dbforge->drop_table('newsletter_opens');
		$this->dbforge->drop_table('newsletter_urls');
		$this->dbforge->drop_table('newsletter_clicks');
		$this->dbforge->drop_table('newsletter_templates');

		$newsletters = "
			CREATE TABLE ".$this->db->dbprefix('newsletters')." (
			  `id` int(6) unsigned NOT NULL auto_increment,
			  `title` varchar(100) collate utf8_unicode_ci NOT NULL default '',
			  `body` text collate utf8_unicode_ci NOT NULL,
			  `created_on` int(11) NOT NULL default 0,
			  `last_sent` int(5) NOT NULL default 0,
			  `sent_on` int(11) default NULL,
			  `send_cron` tinyint(1) NOT NULL default 0,
			  `read_receipts` tinyint(1) NOT NULL default 1,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$newsletter_emails = "
			CREATE TABLE ".$this->db->dbprefix('newsletter_emails')." (
			  `id` int(6) unsigned NOT NULL auto_increment,
			  `email` varchar(50) collate utf8_unicode_ci NOT NULL default '',
			  `hash` varchar(10) collate utf8_unicode_ci NOT NULL default '',
			  `active` tinyint(1) NOT NULL default 1,
			  `registered_on` int(11) default NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$newsletter_opens = "
			CREATE TABLE ".$this->db->dbprefix('newsletter_opens')." (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `newsletter_id` int(6) default NULL,
			  `ip` varchar(20) default NULL,
			  `time` int(11) default NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$newsletter_urls = "
			CREATE TABLE ".$this->db->dbprefix('newsletter_urls')." (
			  `id` int(6) unsigned NOT NULL auto_increment,
			  `target` varchar(200) default NULL,
			  `hash` varchar(5) default NULL,
			  `newsletter_id` int(6) default NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$newsletter_clicks = "
			CREATE TABLE ".$this->db->dbprefix('newsletter_clicks')." (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `url_id` varchar(6) default NULL,
			  `newsletter_id` int(6) default NULL,
			  `ip` varchar(20) default NULL,
			  `time` int(11) default NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$newsletter_templates = "
			CREATE TABLE ".$this->db->dbprefix('newsletter_templates')." (
			  `id` int(6) unsigned NOT NULL auto_increment,
			  `title` varchar(100) collate utf8_unicode_ci NOT NULL default '',
			  `body` text collate utf8_unicode_ci NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		";
		
		$template_1 = array(
							'title' => 'Default 1',
							'body'  => '
		<html>
			<head>
				<title></title>
			</head>
			<body bgcolor="#163755" leftmargin="0" marginheight="0" marginwidth="0" offset="0" topmargin="0">
				<style type="text/css">
		.headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
					 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
					 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
					 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
					 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
					 .defaultText { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
					 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
					 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
					 a { color:#FF6600; color:#FF6600; color:#FF6600; }		</style>
				<table bgcolor="#163755" cellpadding="10" cellspacing="0" class="backgroundTable" width="100%">
					<tbody>
						<tr>
							<td align="center" valign="top">
								<table cellpadding="0" cellspacing="0" width="550">
									<tbody>
										<tr>
											<td align="center" style="background-color: rgb(255, 204, 102); border-top: 0px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(255, 255, 255); text-align: center;">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 200%; font-family: verdana; text-decoration: none;">Email not displaying correctly? <a href="{{ url:site }}newsletters/archive/{{ newsletter:id }}" style="font-size: 10px; color: rgb(153, 102, 0); line-height: 200%; font-family: verdana; text-decoration: none;">View it in your browser.</a></span></td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 255); border-top: 0px solid rgb(51, 51, 51); border-bottom: 10px solid rgb(255, 255, 255);">
												<center>
													<a href="{{ url:site }}"><img align="center" alt="Your Company" border="0" id="editableImg1" src="img/logo_header.jpg" title="Your Company" /></a></center>
											</td>
										</tr>
									</tbody>
								</table>
								<table bgcolor="#ffffff" cellpadding="20" cellspacing="0" width="550">
									<tbody>
										<tr>
											<td bgcolor="#ffffff" style="font-size: 12px; color: rgb(0, 0, 0); line-height: 150%; font-family: trebuchet ms;" valign="top">
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet leo vel sem ultricies sagittis. Sed non risus in justo porta blandit quis in velit. Vestibulum fermentum dignissim diam eu porta. Vivamus dolor nulla, egestas in ultrices et, suscipit eu sapien. Integer consequat consequat odio at dictum. Donec nisi arcu, blandit vitae pharetra sed, condimentum pharetra libero. Donec blandit feugiat magna congue accumsan. Fusce adipiscing nunc sit amet elit fermentum faucibus. Ut tristique tristique lectus, pellentesque volutpat felis blandit non. In eleifend tellus vitae felis adipiscing interdum consectetur velit faucibus. Pellentesque interdum, magna porttitor luctus condimentum, purus diam sagittis odio, quis suscipit dolor nisi non nulla.</p>
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Duis venenatis dictum interdum. Duis sed mauris vitae nisi commodo dapibus. Vestibulum tempus lectus commodo ipsum condimentum malesuada. In eros mi, ullamcorper eget fringilla sed, elementum pharetra mi. Vivamus cursus imperdiet malesuada. Morbi ultricies vulputate mi a tempor. Proin at leo vitae dolor posuere convallis scelerisque ac nulla. Nam in sem urna, vitae bibendum libero. Sed tincidunt ornare nulla. Aenean elementum lobortis arcu sit amet dictum. Maecenas sodales arcu sit amet metus rhoncus sit amet volutpat est porttitor. Maecenas vitae consectetur massa. Nulla sed metus mauris. Morbi quam nisi, sodales vel consectetur eget, molestie in erat. In hac habitasse platea dictumst. Ut sit amet viverra massa. Mauris vel elit ac est tempus elementum.</p>
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Nulla rhoncus facilisis quam in varius. Cras viverra diam ut elit vulputate placerat. Nunc magna lectus, dapibus nec tincidunt vel, accumsan a ante. Donec posuere congue erat, non placerat lectus ullamcorper a. Curabitur hendrerit odio orci. Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum.</p>
											</td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 204); border-top: 10px solid rgb(255, 255, 255);" valign="top">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 100%; font-family: verdana;">Newsletter description...<br />
												<br />
												<a href="{{ url:site }}newsletters/unsubscribe/{{ recipient:hash }}">Unsubscribe</a> from this list with one click.<br />
												<br />
												Our mailing address is:<br />
												--your mailing address--<br />
												<br />
												Our telephone:<br />
												--phone number--<br />
												<br />
												Copyright &copy; {{ helper:date format="Y" }} {{ settings:site_name }} All rights reserved.</span></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</body>
		</html>
		');
		
		$template_2 = array(
							'title' => 'Default 2',
							'body'  => '
		<html>
			<head>
				<title></title>
			</head>
			<body bgcolor="#163755" leftmargin="0" marginheight="0" marginwidth="0" offset="0" topmargin="0">
				<style type="text/css">
		.headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
					 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
					 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
					 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
					 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
					 td { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
					 .sideColumn { background-color:#FFFFFF; border-right:1px dashed #CCCCCC; text-align:left; }
					 .sideColumnText { font-size:11px; font-weight:normal; color:#999999; font-family:arial; line-height:150%; }
					 .sideColumnTitle { font-size:15px; font-weight:bold; color:#333333; font-family:arial; line-height:150%; }
					 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
					 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
					 a { color:#FF6600; color:#FF6600; color:#FF6600; }		</style>
				<table bgcolor="#163755" cellpadding="10" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td align="center" valign="top">
								<table cellpadding="0" cellspacing="0" width="600">
									<tbody>
										<tr>
											<td align="center" style="background-color: rgb(255, 204, 102); border-top: 0px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(255, 255, 255); text-align: center;">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 200%; font-family: verdana; text-decoration: none;">Email not displaying correctly? <a href="{{ url:site }}newsletters/archive/{{ newsletter:id }}" style="font-size: 10px; color: rgb(153, 102, 0); line-height: 200%; font-family: verdana; text-decoration: none;">View it in your browser.</a></span></td>
										</tr>
										<tr>
											<td align="left" style="background-color: rgb(255, 255, 255); border-top: 0px solid rgb(51, 51, 51); border-bottom: 10px solid rgb(255, 255, 255);" valign="middle">
												<center>
													<a href="{{ url:site }}"><img align="center" alt="Your Company" border="0" id="editableImg1" src="uploads/files/logo.jpg" title="Your Company" /></a></center>
											</td>
										</tr>
									</tbody>
								</table>
								<table bgcolor="#ffffff" cellpadding="20" cellspacing="0" width="600">
									<tbody>
										<tr>
											<td style="background-color: rgb(255, 255, 255); border-right: 1px dashed rgb(204, 204, 204); text-align: left;" valign="top" width="200">
												<span style="font-size: 11px; font-weight: normal; color: rgb(153, 153, 153); font-family: arial; line-height: 150%;"><span style="font-size: 15px; font-weight: bold; color: rgb(51, 51, 51); font-family: arial; line-height: 150%;">Left Column:</span><br />
												Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum.<br />
												<br />
												<span style="font-size: 15px; font-weight: bold; color: rgb(51, 51, 51); font-family: arial; line-height: 150%;">More Left Column:</span><br />
												Vestibulum fermentum dignissim diam eu porta. Vivamus dolor nulla, egestas in ultrices et, suscipit eu sapien. Integer consequat consequat odio at dictum. Donec nisi arcu, blandit vitae pharetra sed, condimentum pharetra libero. </span></td>
											<td bgcolor="#ffffff" style="font-size: 12px; color: rgb(0, 0, 0); line-height: 150%; font-family: trebuchet ms;" valign="top" width="400">
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet leo vel sem ultricies sagittis. Sed non risus in justo porta blandit quis in velit. Vestibulum fermentum dignissim diam eu porta. Vivamus dolor nulla, egestas in ultrices et, suscipit eu sapien. Integer consequat consequat odio at dictum. Donec nisi arcu, blandit vitae pharetra sed, condimentum pharetra libero. Donec blandit feugiat magna congue accumsan. Fusce adipiscing nunc sit amet elit fermentum faucibus. Ut tristique tristique lectus, pellentesque volutpat felis blandit non. In eleifend tellus vitae felis adipiscing interdum consectetur velit faucibus. Pellentesque interdum, magna porttitor luctus condimentum, purus diam sagittis odio, quis suscipit dolor nisi non nulla.</p>
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Nulla rhoncus facilisis quam in varius. Cras viverra diam ut elit vulputate placerat. Nunc magna lectus, dapibus nec tincidunt vel, accumsan a ante. Donec posuere congue erat, non placerat lectus ullamcorper a. Curabitur hendrerit odio orci. Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum.</p>
											</td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 204); border-top: 10px solid rgb(255, 255, 255);" valign="top">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 100%; font-family: verdana;">Newsletter description...<br />
												<br />
												<a href="{{ url:site }}newsletters/unsubscribe/{{ recipient:hash }}">Unsubscribe</a> from this list with one click.<br />
												<br />
												Our mailing address is:<br />
												--your mailing address--<br />
												<br />
												Our telephone:<br />
												--phone number--<br />
												<br />
												Copyright &copy; {{ helper:date format="Y" }} {{ settings:site_name }} All rights reserved.</span></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</body>
		</html>
		');
		
		$template_3 = array(
							'title' => 'Default 3',
							'body'  => '
		<html>
			<head>
				<title></title>
			</head>
			<body bgcolor="#163755" leftmargin="0" marginheight="0" marginwidth="0" offset="0" topmargin="0">
				<style type="text/css">
		.headerTop { background-color:#FFCC66; border-top:0px solid #000000; border-bottom:1px solid #FFFFFF; text-align:center; }
					 .adminText { font-size:10px; color:#996600; line-height:200%; font-family:verdana; text-decoration:none; }
					 .headerBar { background-color:#FFFFFF; border-top:0px solid #333333; border-bottom:10px solid #FFFFFF; }
					 .title { font-size:20px; font-weight:bold; color:#CC6600; font-family:arial; line-height:110%; }
					 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
					 td { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
					 .sideColumn { background-color:#FFFFFF; border-left:1px dashed #CCCCCC; text-align:left; }
					 .sideColumnText { font-size:11px; font-weight:normal; color:#999999; font-family:arial; line-height:150%; }
					 .sideColumnTitle { font-size:15px; font-weight:bold; color:#333333; font-family:arial; line-height:150%; }
					 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
					 .footerText { font-size:10px; color:#996600; line-height:100%; font-family:verdana; }
					 a { color:#FF6600; color:#FF6600; color:#FF6600; }		</style>
				<table bgcolor="#163755" cellpadding="10" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td align="center" valign="top">
								<table cellpadding="0" cellspacing="0" width="600">
									<tbody>
										<tr>
											<td align="center" style="background-color: rgb(255, 204, 102); border-top: 0px solid rgb(0, 0, 0); border-bottom: 1px solid rgb(255, 255, 255); text-align: center;">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 200%; font-family: verdana; text-decoration: none;">Email not displaying correctly? <a href="{{ url:site }}newsletters/archive/{{ newsletter:id }}" style="font-size: 10px; color: rgb(153, 102, 0); line-height: 200%; font-family: verdana; text-decoration: none;">View it in your browser.</a></span></td>
										</tr>
										<tr>
											<td align="left" style="background-color: rgb(255, 255, 255); border-top: 0px solid rgb(51, 51, 51); border-bottom: 10px solid rgb(255, 255, 255);" valign="middle">
												<center>
													<a href="{{ url:site }}"><img align="center" alt="Your Company" border="0" id="editableImg1" src="uploads/files/logo.jpg" title="Your Company" /></a></center>
											</td>
										</tr>
									</tbody>
								</table>
								<table bgcolor="#ffffff" cellpadding="20" cellspacing="0" width="600">
									<tbody>
										<tr>
											<td bgcolor="#ffffff" style="font-size: 12px; color: rgb(0, 0, 0); line-height: 150%; font-family: trebuchet ms;" valign="top" width="400">
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum. Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum. Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum.</p>
												<p>
													<span style="font-size: 20px; font-weight: bold; color: rgb(204, 102, 0); font-family: arial; line-height: 110%;">Intro Text</span><br />
													<span style="font-size: 11px; font-weight: normal; color: rgb(102, 102, 102); font-style: italic; font-family: arial;">Author/sub text</span><br />
													Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet leo vel sem ultricies sagittis. Sed non risus in justo porta blandit quis in velit. Vestibulum fermentum dignissim diam eu porta. Vivamus dolor nulla, egestas in ultrices et, suscipit eu sapien. Integer consequat consequat odio at dictum. Donec nisi arcu, blandit vitae pharetra sed, condimentum pharetra libero. Donec blandit feugiat magna congue accumsan. Fusce adipiscing nunc sit amet elit fermentum faucibus. Ut tristique tristique lectus, pellentesque volutpat felis blandit non. In eleifend tellus vitae felis adipiscing interdum consectetur velit faucibus. Pellentesque interdum, magna porttitor luctus condimentum, purus diam sagittis odio, quis suscipit dolor nisi non nulla.</p>
											</td>
											<td style="background-color: rgb(255, 255, 255); border-left: 1px dashed rgb(204, 204, 204); text-align: left;" valign="top" width="200">
												<span style="font-size: 11px; font-weight: normal; color: rgb(153, 153, 153); font-family: arial; line-height: 150%;"><span style="font-size: 15px; font-weight: bold; color: rgb(51, 51, 51); font-family: arial; line-height: 150%;">Right Column:</span><br />
												Duis venenatis dictum interdum. Duis sed mauris vitae nisi commodo dapibus. Vestibulum tempus lectus commodo ipsum condimentum malesuada. In eros mi, ullamcorper eget fringilla sed, elementum pharetra mi.<br />
												<br />
												<span style="font-size: 15px; font-weight: bold; color: rgb(51, 51, 51); font-family: arial; line-height: 150%;">Right Column:</span><br />
												Integer sit amet leo vel sem ultricies sagittis. Sed non risus in justo porta blandit quis in velit. Vestibulum fermentum dignissim diam eu porta. Vivamus dolor nulla, egestas in ultrices et, suscipit eu sapien. Integer consequat consequat odio at dictum. Donec nisi arcu, blandit vitae pharetra sed, condimentum pharetra libero. </span></td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 204); border-top: 10px solid rgb(255, 255, 255);" valign="top">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 100%; font-family: verdana;">Newsletter description...<br />
												<br />
												<a href="{{ url:site }}newsletters/unsubscribe/{{ recipient:hash }}">Unsubscribe</a> from this list with one click.<br />
												<br />
												Our mailing address is:<br />
												--your mailing address--<br />
												<br />
												Our telephone:<br />
												--phone number--<br />
												<br />
												Copyright &copy; {{ helper:date format="Y" }} {{ settings:site_name }} All rights reserved.</span></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</body>
		</html>
		');
		
		$template_4 = array(
							'title' => 'Default 4',
							'body'  => '
		<html>
			<head>
				<title></title>
			</head>
			<body bgcolor="#163755" leftmargin="0" marginheight="0" marginwidth="0" offset="0" topmargin="0">
				<style type="text/css">
		.headerTop { background-color:#163755; border-top:0px solid #000000; border-bottom:0px solid #FFCC66; text-align:right; }
					 .adminText { font-size:10px; color:#FFFFCC; line-height:200%; font-family:verdana; text-decoration:none; }
					 .headerBar { background-color:#FFFFFF; border-top:0px solid #FFFFFF; border-bottom:0px solid #333333; }
					 .title { font-size:22px; font-weight:bold; color:#336600; font-family:arial; line-height:110%; }
					 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:arial; }
					 td { font-size:12px; color:#000000; line-height:150%; font-family:trebuchet ms; }
					 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
					 .footerText { font-size:10px; color:#333333; line-height:100%; font-family:verdana; }
					 a { color:#FF0000; color:#FF6600; color:#FF6600; }		</style>
				<table bgcolor="#163755" cellpadding="10" cellspacing="0" class="backgroundTable" width="100%">
					<tbody>
						<tr>
							<td align="center" valign="top">
								<table cellpadding="0" cellspacing="0" width="550">
									<tbody>
										<tr>
											<td align="center" style="background-color: rgb(22, 55, 85); border-top: 0px solid rgb(0, 0, 0); border-bottom: 0px solid rgb(255, 204, 102); text-align: right;">
												<span style="font-size: 10px; color: rgb(255, 255, 204); line-height: 200%; font-family: verdana; text-decoration: none;">Email not displaying correctly? <a href="{{ url:site }}newsletters/archive/{{ newsletter:id }}" style="font-size: 10px; color: rgb(255, 255, 204); line-height: 200%; font-family: verdana; text-decoration: none;">View it in your browser.</a></span></td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 255); border-top: 0px solid rgb(255, 255, 255); border-bottom: 0px solid rgb(51, 51, 51);">
												<center>
													<a href="{{ url:site }}"><img align="center" alt="Your Company" border="0" id="editableImg1" src="uploads/files/logo.jpg" title="Your Company" /></a></center>
											</td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 255); border-top: 0px solid rgb(255, 255, 255); border-bottom: 0px solid rgb(51, 51, 51);">
												<center>
													<a href="{{ url:site }}"><img alt="Lorem ipsum" border="0" height="300" src="uploads/files/header.jpg" width="550" /></a></center>
											</td>
										</tr>
									</tbody>
								</table>
								<table bgcolor="#ffffff" cellpadding="20" cellspacing="0" width="550">
									<tbody>
										<tr>
											<td bgcolor="#ffffff" style="font-size: 12px; color: rgb(0, 0, 0); line-height: 150%; font-family: trebuchet ms;" valign="top">
												<p>
													<span style="font-size: 22px; font-weight: bold; color: rgb(51, 102, 0); font-family: arial; line-height: 110%;">The Newsletter Body...</span><br />
													Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum. Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum. Vivamus adipiscing dui non neque feugiat pellentesque. Nam sodales eleifend venenatis. Fusce pharetra nulla quis purus rutrum et lacinia odio vestibulum.</p>
											</td>
										</tr>
										<tr>
											<td style="background-color: rgb(255, 255, 204); border-top: 10px solid rgb(255, 255, 255);" valign="top">
												<span style="font-size: 10px; color: rgb(153, 102, 0); line-height: 100%; font-family: verdana;">Newsletter description...<br />
												<br />
												<a href="{{ url:site }}newsletters/unsubscribe/{{ recipient:hash }}">Unsubscribe</a> from this list with one click.<br />
												<br />
												Our mailing address is:<br />
												--your mailing address--<br />
												<br />
												Our telephone:<br />
												--phone number--<br />
												<br />
												Copyright &copy; {{ helper:date format="Y" }} {{ settings:site_name }} All rights reserved.</span></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</body>
		</html>
		');
		
		if($this->db->query($newsletters) &&
		   $this->db->query($newsletter_emails) &&
		   $this->db->query($newsletter_opens) &&
		   $this->db->query($newsletter_urls) &&
		   $this->db->query($newsletter_clicks) &&
		   $this->db->query($newsletter_templates) &&
		   $this->db->insert('newsletter_templates', $template_1) &&
		   $this->db->insert('newsletter_templates', $template_2) &&
		   $this->db->insert('newsletter_templates', $template_3) &&
		   $this->db->insert('newsletter_templates', $template_4) )
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		return TRUE;
	}

	public function upgrade($old_version)
	{
		switch($old_version)
		{
			case '1.2.0':
				$newsletter_opt_in = array(
					'slug' => 'newsletter_opt_in',
					'title' => 'Require Opt In',
					'description' => 'Subscribers will receive an activation email with a link that they must click to complete the sign up. Edit the email format in Email Templates.',
					'`default`' => '0',
					'`value`' => '0',
					'type' => 'select',
					'`options`' => '0=Disabled|1=Enabled',
					'is_required' => 1,
					'is_gui' => 1,
					'module' => 'newsletters'
				);
				$this->db->insert('settings', $newsletter_opt_in);
				
				$this->dbforge->add_column('newsletter_emails',
					array('active' => array('type' => 'tinyint',
											'constraint' => 1,
											'NULL' => FALSE,
											'default' => 1
											)
						  )
					);
				
				$opt_in_template = "
					INSERT INTO " . $this->db->dbprefix('email_templates') . " (`slug`, `name`, `description`, `subject`, `body`, `lang`, `is_default`) VALUES ('newsletters_opt_in', 'Newsletters Opt In', 'Template for the email that\'s sent when a user subscribes.', '{{ settings:site_name }} :: Newsletter Activation',
					'<h3>You have recently subscribed to the newsletter at {{ settings:site_name }}</h3>
					<p><strong>To verify that you wish to have your email address added to our list you must click the activation link below.</strong><strong> </strong></p>
					<p><strong>If you did not sign up at our website please disregard this email. No further action is necessary.</strong></p>
					<p><span>Complete signup: <a href=\"{{ newsletter_activation }}\">{{ newsletter_activation }}</a></span></p>
					', 'en', '1');
				";
				$this->db->query($opt_in_template);
				
				// fix the formatting bug
				$this->db->where('slug', 'newsletters')->update('modules', array('skip_xss' => 1));
			
			break;
		
			case '1.2.1':
				$this->load->model('newsletters/templates_m');
				$templates = $this->templates_m->get_all();
				
				foreach ($templates AS &$template)
				{
					$find = array('{pyro:newsletter_activation}',
								  '{pyro:newsletter:id}',
								  '{pyro:settings:site_name}',
								  '{pyro:helper:date format="Y"}',
								  '{pyro:recipient:hash}',
								  '{pyro:url:site}',
								  );
					
					$replace = array('{{ newsletter_activation }}',
									 '{{ newsletter:id }}',
									 '{{ settings:site_name }}',
									 '{{ helper:date format="Y" }}',
									 '{{ recipient:hash }}',
									 '{{ url:site }}',
									 );
					
					$template->body = str_replace($find, $replace, $template->body);

					$this->templates_m->update($template->id, $template);
				}
				
			break;
		}
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "<style type=\"text/css\"> h6, h4 {margin-bottom: 0;}</style>
		<h4>Overview</h4>
		<p>The Newsletter module allows you to create email \"blasts\" to send to your email newsletter subscribers.</p>
		<h4>Subscribers</h4><hr>
		<p>To collect email addresses use the Newsletter Subscribe widget or create a Navigation link to http://example.com/newsletters/subscribe</p>
		<h4>Creating the Newsletter</h4><hr>
		<h6>Templates</h6>
		<p>Select a predefined template from the dropdown box. If you would like to edit a default template you may
		do so in the Template Manager. Note: the template cannot be changed when editing an existing newsletter. Choose
		the template carefully when creating a new newsletter.</p>
		<h6>Newsletter Subject</h6>
		<p>The Newsletter Subject will show in the recipient's email subject line and will have the site name + Newsletter appended to it.
		Example subject line: v2.0 will be released on 01/01/2015! | Acme Software Newsletter</p>
		<h6>Tracking Newsletter Opens</h6>
		<p>If this option is selected the newsletter module will add an invisible tracking image to the email. This is used
		in combination with Tracked URLs to generate statistics. This will not work if you are sending plain text emails.</p>
		<h6>Tracking URLs</h6>
		<p>If you want to place links in the email and track the recipient's clicks
		simply put the web address in the Target URL box and a unique link will be generated. Copy & Paste the generated
		link into the email body and it will be tracked. This works in html emails & plain text emails. You can test your
		links from the View page after the newsletter has been saved.</p>
		<h6>The Email Body</h6>
		<p>Creating a newsletter is very similar to creating page content. Use the WYSIWYG editor to generate the html
		that will be used in the email. You may insert images into the email like you would in page content. When
		the newsletter is sent the image links will be changed to absolute links so they can be read from any email
		client. The images are not actually sent in the email, the email just links to your server. Consequently you
		must not delete the images until you believe that all recipients have finished reading the email.</p>
		<h4>Sending the Newsletter</h4><hr>
		<h6>Send All</h6>
		<p>The way you send the newsletter depends on the Settings for this module. If the Email Limit is set to 0 the link will
		display as \"Send All\" and the Newsletter Module will send 50 emails at a time until all emails are successfully
		sent or the page is closed.</p>
		<h6>Send Batch</h6>
		<p>If you set the Email Limit to a number greater than 0 the link will display as \"Send Batch\"
		and only that number of emails will send. You will have to click the link again to send the next batch. This feature
		allows you to send newsletters (slowly) even if your host limits the number of emails you can send per day or per hour.</p>
		<h6>Send Cron</h6>
		<p>If you select Enable Cron in Settings the link will display as \"Send Cron\". When you click the link it will simply mark the newsletter
		as \"ready\" and it will be sent with the next cron job. The Email limit can be set or you may leave it as 0
		to send all emails at once. If have a cron job that runs at midnight and the limit is less than your number of subscribers it may take a couple nights
		to send all emails. To send newsletters with cron load http://yoursite.com/newsletters/cron/gy84kn</p>";
	}
}
/* End of file details.php */