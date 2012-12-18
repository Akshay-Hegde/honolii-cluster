<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Finnish translation.
 *
 * @author Mikael Kundert
 */

#section settings
$lang['settings_site_name']						= 'Sivuston nimi';
$lang['settings_site_name_desc']				= 'Sivuston nimi, jota näytetään ympäri sivustoa.';

$lang['settings_site_slogan']					= 'Sivuston iskulause';
$lang['settings_site_slogan_desc']				= 'Sivuston iskulause, jota käytetään ympäri sivustoa.';

$lang['settings_site_lang']						= 'Sivuston kieli';
$lang['settings_site_lang_desc']				= 'Sivuston natiivinen kieli, jota käytetään sähköposiviestien mallipohjissa, sisäisissä ilmoituksissa, vastaanotettujen vieraiden yhteydenottoja ja muut ominaisuudet, jotka eivät ole riippuvaisia kävijän kielivalinnasta.';

$lang['settings_contact_email']					= 'Yhteydenotto sähköpostiosoite';
$lang['settings_contact_email_desc']			= 'Kaikki yhteydenotot lähetetään tähän osoitteeseen.';

$lang['settings_server_email']					= 'Palvelimen sähköpostiosoite';
$lang['settings_server_email_desc']				= 'Sivuston lähettämät sähköpostit lähetetään tästä osoitteesta.';

$lang['settings_meta_topic']					= 'Meta aihe';
$lang['settings_meta_topic_desc']				= 'Muutama sana, joka kertoo yrityksestäsi/sivustostasi.';

$lang['settings_currency']						= 'Valuutta';
$lang['settings_currency_desc']					= 'Valuutta symboli, jota käytetään tuotteiden hinnoissa, palveluissa jne.';

$lang['settings_dashboard_rss']					= 'Dashboard RSS syöte';
$lang['settings_dashboard_rss_desc']			= 'Linkki RSS syötteeseen, joka näytetään dashboardissa.';

$lang['settings_dashboard_rss_count']			= 'Dashboard RSS syötteitä';
$lang['settings_dashboard_rss_count_desc']		= 'Kuinka monta syötettä haluat listaa dashboardissa?';

$lang['settings_date_format']					= 'Päiväyksen muoto';
$lang['settings_date_format_desc']				= 'Miten päiväyksen muoto tulisi olla sivustolla ja hallintapaneelissa? ' .
													'Käyttämällä PHP:n <a href="http://php.net/manual/en/function.date.php" target="_black">date muotoa</a> - TAI - ' .
													'Käyttämällä PHP:n <a href="http://php.net/manual/en/function.strftime.php" target="_black">strftime muotoa</a>.';

$lang['settings_frontend_enabled']				= 'Sivuston status';
$lang['settings_frontend_enabled_desc']			= 'Käytä tätä asetusta kun et halua näyttää sivuja käyttäjille. Käytetään yleensä sivuston ylläpidon yhteydessä.';

$lang['settings_mail_protocol']					= 'Sähköpostin protokolla';
$lang['settings_mail_protocol_desc']			= 'Valitse sähköpostin protokolla.';

$lang['settings_mail_sendmail_path']			= 'Sendmail polku';
$lang['settings_mail_sendmail_path_desc']		= 'Polku sendmailin binääiin.';

$lang['settings_mail_smtp_host']				= 'SMTP isäntä';
$lang['settings_mail_smtp_host_desc']			= 'Kirjoita isäntäpalvelimen osoite SMTP:lle.';

$lang['settings_mail_smtp_pass']				= 'SMTP salasana';
$lang['settings_mail_smtp_pass_desc']			= 'SMTP salasana.';

$lang['settings_mail_smtp_port'] 				= 'SMTP portti';
$lang['settings_mail_smtp_port_desc'] 			= 'SMTP palvelimen portin numero.';

$lang['settings_mail_smtp_user'] 				= 'SMTP käyttäjätunnus';
$lang['settings_mail_smtp_user_desc'] 			= 'SMTP käyttäjätunnus.';

$lang['settings_unavailable_message']			= 'Virheviesti';
$lang['settings_unavailable_message_desc']		= 'Kun palvelimella on teknisiä ongelmia tai jostain tuntemattomasta syystä ei pysty lähettämään, niin tämä viesti näytetään käyttäjälle.';

$lang['settings_default_theme']					= 'Oletus teema';
$lang['settings_default_theme_desc']			= 'Valitse teema, jotka käyttäjän näkevät oletuksena.';

$lang['settings_activation_email']				= 'Sähköpostin aktivoiminen';
$lang['settings_activation_email_desc']			= 'Lähettää rekisteröityneille käyttäjille aktivointi sähköpostiosoitteen. Poista tämä asetus käytöstä, jos haluat järjestelmänvalvojien hyväksyvän uusia käyttäjiä';

$lang['settings_records_per_page']				= 'Riviä per sivu';
$lang['settings_records_per_page_desc']			= 'Monta riviä haluat näytettävän yhdellä sivulla hallintapaneelissa?';

$lang['settings_rss_feed_items']				= 'Syötteen lukumäärä';
$lang['settings_rss_feed_items_desc']			= 'Monta artikkelia haluat näyttää RSS/uutis syötteissä?';

$lang['settings_require_lastname']				= 'Vaadi sukunimet?';
$lang['settings_require_lastname_desc']			= 'Tietyissä tilanteissa sukunimi ei ole pakollinen. Haluatko vaatia käyttäjiltä sukunimeä?';

$lang['settings_enable_profiles']				= 'Profiili ominaisuus';
$lang['settings_enable_profiles_desc']			= 'Anna käyttäjille mahdollisuus muokata profiilia.';

$lang['settings_ga_email']						= 'Google Analytic sähköpostiosoite';
$lang['settings_ga_email_desc']					= 'Sähköpostiosoitteesi, jota käytät Google Analyticsissä. Tämä vaaditaan, jos haluat nähdä statistiikat dashboardissa.';

$lang['settings_ga_password']					= 'Google Analytic salasana';
$lang['settings_ga_password_desc']				= 'Google Analytics salasana. Tätä vaaditaan myös jos haluat nähdä statistiikat dashboardissa.';

$lang['settings_ga_profile']					= 'Google Analytic profiili';
$lang['settings_ga_profile_desc']				= 'Google Analyticsin profiilin ID.';

$lang['settings_ga_tracking']					= 'Google Tracking koodi';
$lang['settings_ga_tracking_desc']				= 'Syötä Google Analyticsin seuranta koodi, joka on muotoa: UA-19483569-6';

$lang['settings_twitter_username']				= 'Käyttäjätunnus';
$lang['settings_twitter_username_desc']			= 'Twitterin käyttäjätunnus.';

$lang['settings_twitter_feed_count']			= 'Syöte lukumäärä';
$lang['settings_twitter_feed_count_desc']		= 'Monta Twitterin syötteitä haluat näyttää lohkossa?';

$lang['settings_twitter_cache']					= 'Välimuistin aika';
$lang['settings_twitter_cache_desc']			= 'Monen minuutin välimuistitusta haluat käyttää Twitterin viesteihin?';

$lang['settings_akismet_api_key']				= 'Akismet API avain';
$lang['settings_akismet_api_key_desc']			= 'Akismet on roskapostin suodattaja WordPressin tekjöiltä. Se suojaa roskapostilta ilman, että käyttäjät joutuvat syöttämään kuvatunnisteen kirjaimia.';

$lang['settings_comment_order']					= 'Kommenttien järjestys';
$lang['settings_comment_order_desc']			= 'Määritä kommenttien järjestys.';

$lang['settings_enable_comments'] 				= 'Aktivoi Kommentit';
$lang['settings_enable_comments_desc']			= 'Salli kävijöiden jättää kommentteja?';
	
$lang['settings_moderate_comments']				= 'Moderoi kommentteja';
$lang['settings_moderate_comments_desc']		= 'Valitse tämä, jos haluat takistaa kommentit ennen julkaisua.';

$lang['settings_comment_markdown']				= 'Salli Markdown';
$lang['settings_comment_markdown_desc']			= 'Haluatko sallia käyttien jättää Markdown-muotoiltuja kommentteja?';

$lang['settings_version']						= 'Versio';
$lang['settings_version_desc']					= '';

$lang['settings_site_public_lang']				= 'Julkiset kielet';
$lang['settings_site_public_lang_desc']			= 'Mitkä kielet ovat tuettuna ja tarjotaa kävijöille?';

$lang['settings_admin_force_https']				= 'Pakota HTTPS salaus päälle?';
$lang['settings_admin_force_https_desc']		= 'Salli vain HTTPS protokollan käyttö ylläpidon puolella?';

$lang['settings_files_cache']					= 'Tiedostojen välimuisti';
$lang['settings_files_cache_desc']				= 'Kun kuva jaetaan site.com/files hakemistosta, kuinka pitkä välimuistin expiroitumis aika asetetaan?';

$lang['settings_auto_username']					= 'Automaattinen käyttäjätunnus';
$lang['settings_auto_username_desc']			= 'Luo käyttäjätunnus automaattiseti, niin että käyttäjät voivat jättää sen kentän täyttämättä.';

$lang['settings_registered_email']				= 'Ilmoitus rekisteröitymisestä';
$lang['settings_registered_email_desc']			= 'Lähetä sähköposti-ilmoitus kun joku rekisteröityy sivustolle.';

$lang['settings_ckeditor_config']               = 'CKEditor Asetukset';
$lang['settings_ckeditor_config_desc']          = 'Löydät listan CKEDitorin kentistä <a target="_blank" href="http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html">CKEditorin ohjeista.</a>';

$lang['settings_enable_registration']           = 'Käyttäjien rekisteröityminen';
$lang['settings_enable_registration_desc']      = 'Salli käyttäjien rekisteröityä sivustolle.';

$lang['settings_profile_visibility']            = 'Profile Visibility'; #translate
$lang['settings_profile_visibility_desc']       = 'Specify who can view user profiles on the public site'; #translate

$lang['settings_cdn_domain']                    = 'CDN Domain'; #translate
$lang['settings_cdn_domain_desc']               = 'CDN domains allow you to offload static content to various edge servers, like Amazon CloudFront or MaxCDN.'; #translate

#section titles
$lang['settings_section_general']				= 'Yleistä';
$lang['settings_section_integration']			= 'Integrointi';
$lang['settings_section_comments']				= 'Kommentit';
$lang['settings_section_users']					= 'Käyttäjät';
$lang['settings_section_statistics']			= 'Statistiikat';
$lang['settings_section_twitter']				= 'Twitter';
$lang['settings_section_files']					= 'Tiedostot';

#checkbox and radio options
$lang['settings_form_option_Open']				= 'Avaa';
$lang['settings_form_option_Closed']			= 'Sulje';
$lang['settings_form_option_Enabled']			= 'Päällä';
$lang['settings_form_option_Disabled']			= 'Pois päältä';
$lang['settings_form_option_Required']			= 'Pakollinen';
$lang['settings_form_option_Optional']			= 'Vaihtoehtoinen';
$lang['settings_form_option_Oldest First']		= 'Vanhin ensin';
$lang['settings_form_option_Newest First']		= 'Uusin ensin';
$lang['settings_form_option_Text Only']			= 'Vain Teksti';
$lang['settings_form_option_Allow Markdown']	= 'Salli Markdown';
$lang['settings_form_option_Yes']				= 'Kyllä';
$lang['settings_form_option_No']				= 'Ei';
$lang['settings_form_option_profile_public']	= 'Visible to everybody'; #translate
$lang['settings_form_option_profile_owner']		= 'Only visible to the profile owner'; #translate
$lang['settings_form_option_profile_hidden']	= 'Never visible'; #translate
$lang['settings_form_option_profile_member']	= 'Visible to any logged in user'; #translate
$lang['settings_form_option_activate_by_email']        	= 'Activate by email'; #translate
$lang['settings_form_option_activate_by_admin']        	= 'Activate by admin'; #translate
$lang['settings_form_option_no_activation']         	= 'No activation'; #translate

// titles
$lang['settings_edit_title']					= 'Muokkaa asetuksia';

// messages
$lang['settings_no_settings']					= 'Asetuksia ei ole määritetty.';
$lang['settings_save_success']					= 'Asetukset tallennettiin!';

/* End of file settings_lang.php */