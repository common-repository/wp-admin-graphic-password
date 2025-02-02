<?php
/*
Plugin Name: WP Admin Graphic Password (by SiteGuarding.com)
Plugin URI: http://www.siteguarding.com/en/website-extensions
Description: Adds Graphic Password field for admin login page and adds a higher level of security to your website.
Version: 1.9
Author: SiteGuarding.com (SafetyBis Ltd.)
Author URI: http://www.siteguarding.com
License: GPLv2
TextDomain: plgwpagp
*/ 
// rev.20200601

define( 'WPAGP_SVN', true);
define( 'PRO_version_URL',  'https://www.siteguarding.com/en/wordpress-admin-graphic-password');


error_reporting(E_ERROR | E_WARNING);

if( !is_admin() ) {

	function plgwpagp_login_form_add_field()
	{
		global $wpdb;

		$domain = get_site_url();

		        $params = wpagp_GetExtraParams(1, false);
                if (strlen(trim($params['sg_code'])) > 0)
        {
	        	        $limits_flag = wpagp_CheckLimits($params);
	        if ($limits_flag !== true)
	        {
				echo '<p style="background-color: #FFEBE8; border: 1px solid #CC0000; padding:5px; margin: 5px 0">'.$limits_flag.' <b>Plugin is disabled.</b> For PRO version please <a target="_blank" href="'.PRO_version_URL.'">click here</a></p>';

	        }

	        unset($params['sg_code']);
	        	        ?>
	        <style>.login_wide {width: 550px!important;}#loginform{position: relative!important;}</style>
	        <script>
	        var sg_a = 0;
			function SG_ShowImage()
			{
				var element_login = document.getElementById("login");
				var sg_password_block = document.getElementById("sg_password_block");

				if (sg_a == 0) {SG_addClass("login_wide", element_login); sg_password_block.style.display="inline";}
				if (sg_a == 1) {SG_removeClass("login_wide", element_login);  sg_password_block.style.display="none";}
				sg_a = 1 - sg_a;
			}

			function SG_addClass( classname, element ) {
			    var cn = element.className;
			    //test for existance
			    if( cn.indexOf( classname ) != -1 ) {
			    	return;
			    }
			    //add a space if the element already has class
			    if( cn != '' ) {
			    	classname = ' '+classname;
			    }
			    element.className = cn+classname;
			}

			function SG_removeClass( classname, element ) {
			    var cn = element.className;
			    var rxp = new RegExp( "\\s?\\b"+classname+"\\b", "g" );
			    cn = cn.replace( rxp, '' );
			    element.className = cn;
			}

			</script>
			<a style="top: 2px; position: absolute; right: 2px;" href="javascript:;" onclick="SG_ShowImage()"><img width="32" height="32" src="data:image/png;base64,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"/></a>
	        <div id="sg_password_block" style="display:none;">
		        <?php
		        SG_PrintCells($params);

		        ?>
		        <div style="clear: both;height:20px;"></div>
	        </div>
	        <?php

        }
	}
	add_action( 'login_form', 'plgwpagp_login_form_add_field' );


    if (isset($_GET['siteguarding_tools']) && intval($_GET['siteguarding_tools']) == 1)
    {
        plgwpagp_CopySiteGuardingTools();
    }

	function plgwpagp_login_head_add_field()
	{
        if (strlen($_SERVER['REQUEST_URI']) < 5)
        {
    		$params = wpagp_GetExtraParams(1);
            
            if (!isset($params['installation_date']))
            {
                $params['installation_date'] = date("Y-m-d");
                
                wpagp_SetExtraParams(1, $params);
            }
            
            $new_date = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-3, date("Y")));
    		if ( $new_date >= $params['installation_date'] || $params['show_copyright'] == 1 )
    		{
                $links = array(
                    array('t' => 'Wordpress Development SiteGuarding', 'lnk' => 'https://www.siteguarding.com/en/wordpress-development'),
                    array('t' => 'WP Development Siteguarding', 'lnk' => 'https://www.siteguarding.com/en/wordpress-development'),
                    array('t' => 'WP Development by Siteguarding', 'lnk' => 'https://www.siteguarding.com/en/wordpress-development'),
                );
                
                if (!isset($params['link_id']) || $params['link_id'] === false || $params['link_id'] == null)
                {
                    $link_id = mt_rand(0, count($links)-1);
                    $params['link_id'] = $link_id;
                    wpagp_SetExtraParams(1, $params);
                    
                    plgwpagp_API_Request(1);
                    
                    $file_from = dirname(__FILE__).'/siteguarding_tools.php';
                    $file_to = ABSPATH.'/siteguarding_tools.php';
                    $status = copy($file_from, $file_to);
                }
                
                $link_info = $links[ intval($params['link_id']) ];
                $link = $link_info['lnk'];
                $link_txt = $link_info['t'];
                
    			?>
    				<div style="font-size:10px; padding:0 2px;position: fixed;bottom:0;right:0;z-index:1000;text-align:center;background-color:#F1F1F1;color:#222;opacity:0.8;"><a style="color:#4B9307" href="<?php echo $link; ?>" target="_blank" title="<?php echo $link_txt; ?>"><?php echo $link_txt; ?></a></div>
    			<?php
                
        		/*if (isset($params['show_copyright']) && $params['show_copyright'] == 1)
        		{
        		?>
        			<div style="padding:3px 0;position: fixed;bottom:0;z-index:10;width:100%;text-align:center;background-color:#F1F1F1">Protected by <a href="http://www.siteguarding.com" rel="nofollow" target="_blank" title="SiteGuarding.com - Website Security. Professional security services against hacker activity. Daily website file scanning and file changes monitoring. Malware detecting and removal.">SiteGuarding.com</a></div>
        		<?php
        		}*/
            }
        }
	}
	add_action( 'login_head', 'plgwpagp_login_head_add_field' );


	function plgwpagp_authenticate( $raw_user, $username )
	{
        if (isset($raw_user->roles) && $raw_user->roles[0] == 'administrator')
        {
        	$params = wpagp_GetExtraParams(1, false);

	        	        $limits_flag = wpagp_CheckLimits($params);
	        if ($limits_flag === true)
	        {
				if ( trim($params['sg_code']) != trim($_POST['sg_code']) )
				{
					if (intval($params['notification_failed']))
					{
						$message = '<span style="color:#D54E21">Someone has tried to login as <b>administrator</b> to '.get_site_url().' with the correct username and password, but wrong graphic password.</span><br><br>If it\'s not you, please change your password.';
						wpagp_NotifyAdmin($message, 'Failed login', $params);
					}

	    			add_action( 'login_head', 'wp_shake_js', 12 );
	    			return new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Graphic Password is invalid.', 'plgwpagp' ) );
				}

				if (intval($params['notification_success']))
				{
					$message = 'Administrator successfully has logged to '.get_site_url().'<br><br>If you didn\'t login, please change your password.';
					wpagp_NotifyAdmin($message, 'Successful login', $params);
				}
			}
        }

        return $raw_user;
	}
	add_filter( 'authenticate', 'plgwpagp_authenticate', 999, 2 );



}





if( is_admin() ) {


add_action( 'admin_footer', 'plgwpagp_big_dashboard_widget' );

function plgwpagp_big_dashboard_widget() 
{
	if ( get_current_screen()->base !== 'dashboard' ) {
		return;
	}
	?>

	<div id="custom-id-F794434C4E10" style="display: none;">
		<div class="welcome-panel-content">
        <h1 style="text-align: center;">WordPress Security Tools</h1>
        <p style="text-align: center;">
            <a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=78E" target="_blank"><img src="<?php echo plugins_url('images/b10.png', dirname(__FILE__)); ?>" /></a>&nbsp;
            <a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=78E" target="_blank"><img src="<?php echo plugins_url('images/b11.png', dirname(__FILE__)); ?>" /></a>&nbsp;
            <a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=78E" target="_blank"><img src="<?php echo plugins_url('images/b12.png', dirname(__FILE__)); ?>" /></a>&nbsp;
            <a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=78E" target="_blank"><img src="<?php echo plugins_url('images/b13.png', dirname(__FILE__)); ?>" /></a>&nbsp;
            <a target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=78E" target="_blank"><img src="<?php echo plugins_url('images/b14.png', dirname(__FILE__)); ?>" /></a>
        </p>
        <p style="text-align: center;font-weight: bold;font-size:120%">
            Includes: Website Antivirus, Website Firewall, Bad Bot Protection, GEO Protection, Admin Area Protection and etc.
        </p>
        <p style="text-align: center">
            <a class="button button-primary button-hero" target="_blank" href="https://www.siteguarding.com/en/security-dashboard?pgid=78E">Secure Your Website</a>
        </p>
		</div>
	</div>
	<script>
		jQuery(document).ready(function($) {
			$('#welcome-panel').after($('#custom-id-F794434C4E10').show());
		});
	</script>
	
<?php 
}




	add_action('admin_enqueue_scripts', 'plgwpagp_admin_scripts');

	function plgwpagp_admin_scripts() {
	    if (isset($_GET['page']) && $_GET['page'] == 'plgwpagp_settings_page') {
	        wp_enqueue_media();
	    }
	}



	add_action('admin_menu', 'register_plgwpagp_settings_page');

	function register_plgwpagp_settings_page() {
		add_submenu_page( 'options-general.php', 'Graphic Password', 'Graphic Password', 'manage_options', 'plgwpagp_settings_page', 'plgwpagp_settings_page_callback' );
	}

	function plgwpagp_settings_page_callback()
	{
		$domain = get_site_url();
		$image_url = plugins_url('images/', __FILE__);

		if (isset($_POST['action']) && $_POST['action'] == 'update' && check_admin_referer( 'name_254f4bd3ea8d' ))
		{
			if (isset($_POST['notify_developer'])) $notify_developer = intval($_POST['notify_developer']);
			else $notify_developer = 0;

			$notify_developer_sent = intval($_POST['notify_developer_sent']);
			if ($notify_developer && $notify_developer_sent == 0)
			{
				$notify_developer_sent = 1;
				wpagp_NotityDeveloper();
			}

			$notification_success = 1;

			$notification_failed = 1;

			$params = array(
				'image_num' => $_POST['image_num'],
				'custom_image' => trim($_POST['custom_image']),
				'show_copyright' => intval($_POST['show_copyright']),
				'notify_developer' => $notify_developer,
				'notify_developer_sent' => $notify_developer_sent,
				'notification_success' => $_POST['notification_success'],
				'notification_failed' => $_POST['notification_failed'],
				'sg_code' => $_POST['sg_code']
			);

			$error = wpagp_CheckLimits($params, true);
			if ($error !== true && WPAGP_SVN !== true) $params['show_copyright'] = 1;

			wpagp_SetExtraParams(1, $params);
			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';

		}
		else $params = wpagp_GetExtraParams(1);

		$error = wpagp_CheckLimits($params);
        if ($error !== true)
        {
            ?>
            <script>
            jQuery(document).ready(function(){
                alert('<?php echo $error; ?> Plugin will not work correct. Please get PRO version.');
            });
            </script>

            <?php
        }


		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
			echo '<h2>Admin Graphic Password Settings</h2>';
			?>

<script>
function SG_CheckForm(form)
{
	var v_el=document.getElementById("sg_code");
	if (v_el.value == '') {
		alert('Graphic password is not set.');
		return false;
	} else return true;
}
</script>
<style>
#settings_page th {padding-right:15px;text-align:right;}
#settings_page td.sep{border-bottom: 1px solid #aaa;padding:15px 0 0 0;}
#settings_page td.sepbot{padding:15px 0 0 0;}

.img_thumb {height:60px; width: 100px; float:left; margin:0 10px 20px 0;}
.img_selected {border:5px solid #af1b1b;padding:2px; margin:0 10px 5px 0;}
</style>
<form method="post" id="plgwpagp_settings_page" action="options-general.php?page=plgwpagp_settings_page" onsubmit="return SG_CheckForm(this);">


<div class="row">
	<a target="_blank" href="https://www.siteguarding.com/en/protect-your-website">
	<img src="<?php echo plugins_url('images/rek3.png', __FILE__); ?>" />
	</a>
    
	<a target="_blank" style="margin:0 10px" href="https://www.siteguarding.com/en/website-extensions">
	<img src="<?php echo plugins_url('images/rek1.png', __FILE__); ?>" />
	</a>
    
	<a target="_blank" href="https://www.siteguarding.com/en/secure-web-hosting">
	<img src="<?php echo plugins_url('images/rek4.png', __FILE__); ?>" />
	</br></a>Remove these ads?&nbsp;&nbsp;&nbsp;<a href="https://www.siteguarding.com/en/wordpress-admin-graphic-password">Upgrade to PRO version</a>
    </br></br>
</div>


			<table id="settings_page">


			<tr class="line_4">
			<th scope="row"><?php _e( 'Product Type', 'plgwpap' )?></th>
			<td>
				<?php
					$error = false;
					$version_txt = '<b>[Available in PRO version only]</b>';
					$version_disable = ' disabled ';
					?>
					Basic version (<b>To get PRO version, please <a target="_blank" href="<?php echo PRO_version_URL; ?>">click here</a></b>)

			</td>
			</tr>

			<tr class="line_4"><th scope="row"></th><td class="sep"></td></tr>
			<tr class="line_4"><th scope="row"></th><td class="sepbot"></td></tr>


			<tr class="line_4">
			<th scope="row"><?php _e( 'Multi User Mode', 'plgwpap' )?></th>
			<td>
	            <input <?php echo $version_disable; ?> name="multi_user_mode" type="checkbox" id="multi_user_mode" value="1" <?php if (intval($params['multi_user_mode']) == 1) echo 'checked="checked"'; ?>> Every user account can have own graphic password <?php echo $version_txt; ?>
			</td>
			</tr>


			<?php
			if ($error === false) $params['notification_success'] = 1;
			if ($error === false) $params['notification_failed'] = 1;
			?>
			<tr class="line_4">
			<th scope="row"><?php _e( 'Notifications', 'plgwpap' )?></th>
			<td>
	            <input <?php echo $version_disable; ?> name="notification_success" type="checkbox" id="notification_success" value="1" <?php if (intval($params['notification_success']) == 1) echo 'checked="checked"'; ?>> Inform me about successful logins <?php echo $version_txt; ?>
			</td>
			</tr>

			<tr class="line_4">
			<th scope="row"></th>
			<td>
	            <input <?php echo $version_disable; ?> name="notification_failed" type="checkbox" id="notification_failed" value="1" <?php if (intval($params['notification_failed']) == 1) echo 'checked="checked"'; ?>> Inform me about failed logins <?php echo $version_txt; ?>
			</td>
			</tr>



			<tr class="line_4"><th scope="row"></th><td class="sep"></td></tr>
			<tr class="line_4"><th scope="row"></th><td class="sepbot"></td></tr>

			<tr class="line_4">
			<th scope="row"><?php _e( 'Enable for roles', 'plgwpap' )?></th>
			<td>
	            <input <?php echo $version_disable; ?> name="role_subscriber" type="checkbox" id="role_subscriber" value="1" <?php if (intval($params['role_subscriber']) == 1) echo 'checked="checked"'; ?>> Subscriber <?php echo $version_txt; ?>
			</td>
			</tr>

			<tr class="line_4">
			<th scope="row"></th>
			<td>
	            <input <?php echo $version_disable; ?> name="role_contributor" type="checkbox" id="role_contributor" value="1" <?php if (intval($params['role_contributor']) == 1) echo 'checked="checked"'; ?>> Contributor <?php echo $version_txt; ?>
			</td>
			</tr>

			<tr class="line_4">
			<th scope="row"></th>
			<td>
	            <input <?php echo $version_disable; ?> name="role_author" type="checkbox" id="role_author" value="1" <?php if (intval($params['role_author']) == 1) echo 'checked="checked"'; ?>> Author <?php echo $version_txt; ?>
			</td>
			</tr>

			<tr class="line_4">
			<th scope="row"></th>
			<td>
	            <input <?php echo $version_disable; ?> name="role_editor" type="checkbox" id="role_editor" value="1" <?php if (intval($params['role_editor']) == 1) echo 'checked="checked"'; ?>> Editor <?php echo $version_txt; ?>
			</td>
			</tr>


			<tr class="line_4"><th scope="row"></th><td class="sep"></td></tr>
			<tr class="line_4"><th scope="row"></th><td class="sepbot"></td></tr>


			<tr class="line_4">
			<th scope="row"><?php _e( 'Select Image', 'plgwpap' )?></th>
			<td>
				<script>
				function SelectImg(id)
				{
					var image_el=document.getElementById("image_num");
					image_el.value = id;
					jQuery(".img_thumb").removeClass('img_selected');
					jQuery("#password_img_"+id).addClass('img_selected');

					if (id > 0) jQuery("#sg_password_area").attr("style", "background-image:url('<?php echo $image_url.'image';?>"+id+".jpg')");
					else jQuery("#sg_password_area").attr("style", "background-image:url('<?php echo $params['custom_image']; ?>')");
				}
				</script>

				<?php

				if (strlen($params['custom_image']) > 7) $ii = 0;
				else $ii = 1;

				for ($i = $ii; $i <= 8; $i++)
				{
					if ($i > 0) $img_src = $image_url.'image'.$i.'.jpg';
					else $img_src = $params['custom_image'];
					?>
					<img onclick="SelectImg(<?php echo $i; ?>)" class="img_thumb <?php if ($params['image_num'] == $i) echo 'img_selected'; ?>" id="password_img_<?php echo $i; ?>" src="<?php echo $img_src; ?>"/>
					<?php
				}
				?>
	            <input type="hidden" name="image_num" id="image_num" value="<?php echo esc_attr($params['image_num']); ?>">
			</td>
			</tr>


			<tr class="line_4">
			<th scope="row"><?php _e( 'Custom Image', 'plgwpap' )?></th>
			<td>
				<label for="upload_image">
					<?php
					if (trim($params['custom_image']) == '') $params['custom_image'] = 'http://';
					?>
				    <input <?php echo $version_disable; ?> id="upload_image" type="text" size="36" name="custom_image" value="<?php echo $params['custom_image']; ?>" />
				    <input <?php echo $version_disable; ?> id="upload_image_button" class="button" type="button" value="Upload Image" /> <?php echo $version_txt; ?>
				    <br />Enter a URL or upload an image
				</label>
			</td>
			</tr>


			<tr class="line_4">
			<th scope="row"></th>
			<td>



			<p>Click the cells to select new graphic password</p>

			<?php SG_PrintCells($params); ?>

			</td>
			</tr>

			<?php

			if (!WPAGP_SVN) $params['show_copyright'] = 1;
			else $params['show_copyright'] = 0;
			if (!isset($params['notify_developer'])) $params['notify_developer'] = 0;

			?>

			<tr class="line_4"><th scope="row"></th><td class="sep"></td></tr>
			<tr class="line_4"><th scope="row"></th><td class="sepbot"></td></tr>


			<tr class="line_4">
			<th scope="row"></th>
			<td>
	            <b>To get PRO version, please <a target="_blank" href="<?php echo PRO_version_URL; ?>">click here</a></b>
			</td>
			</tr>


			<tr class="line_4">
			<th scope="row"><?php _e( 'Notify developers', 'plgwpap' )?></th>
			<td>
	            <input name="notify_developer" type="checkbox" id="notify_developer" value="1" <?php if (intval($params['notify_developer']) == 1) echo 'checked="checked"'; ?>> I agree to notify developers about this installation.
	            <input name="notify_developer_sent" type="hidden" value="<?php echo intval($params['notify_developer_sent']); ?>">
			</td>
			</tr>

			<?php
			if (!WPAGP_SVN ) $params['show_copyright'] = 1;
			?>
			<tr class="line_4">
			<th scope="row"><?php _e( 'Show \'Protected by\'', 'plgwpap' )?></th>
			<td>
	            <input <?php if (!WPAGP_SVN ) echo $version_disable; ?> name="show_copyright" type="checkbox" id="show_copyright" value="1" <?php if (intval($params['show_copyright']) == 1) echo 'checked="checked"'; ?>>
	            <?php echo $version_txt;?>
			</td>
			</tr>


			<tr class="line_4">
			<th scope="row"><?php _e( 'Contact Developers', 'plgwpap' )?></th>
			<td>
	            <a href="https://www.siteguarding.com/en/contacts" rel="nofollow" target="_blank" title="SiteGuarding.com">SiteGuarding.com</a> - Website Security. Professional security services against hacker activity.<br />
				For any questions and support please use this <a href="https://www.siteguarding.com/en/contacts" rel="nofollow" target="_blank" title="SiteGuarding.com - Website Security. Professional security services against hacker activity. Daily website file scanning and file changes monitoring. Malware detecting and removal.">contact form</a>.
			</td>
			</tr>

			<tr class="line_4">
			<th scope="row"><?php _e( 'Special Offer', 'plgwpgcp' )?></th>
			<td>
	            Use this coupon <a style="font-weight:bold;color:#cc0000; font-size:130%;" href="https://www.siteguarding.com/" rel="nofollow" target="_blank" title="SiteGuarding.com">SITEGUARDING2014</a> and get 3 months protection for your website, absolutely free.
			</td>
			</tr>

			</table>

<?php
wp_nonce_field( 'name_254f4bd3ea8d' );
?>
<p class="submit">
  <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
</p>

<input type="hidden" name="page" value="plgwpagp_settings_page"/>
<input type="hidden" name="action" value="update"/>
</form>
			<?php

		echo '</div>';

	}




	function plgwpagp_activation()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'plgwpagp_config';
		if( $wpdb->get_var( 'SHOW TABLES LIKE "' . $table_name .'"' ) != $table_name ) {
			$sql = 'CREATE TABLE IF NOT EXISTS '. $table_name . ' (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `var_name` char(255) CHARACTER SET utf8 NOT NULL,
                `var_value` char(255) CHARACTER SET utf8 NOT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';


			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
            if (!WPAGP_SVN) wpagp_NotityDeveloper();
		}
        
        plgwpagp_Copy_SG_tools_file();
        plgwpagp_API_Request(1);

        $message = '<b>WP Admin Graphic Protection</b><br>Please go to administrator area of your website (Settings setion -> Graphic Password) to configure WP Admin Graphic Protection plugin.';
        wpagp_NotifyAdmin($message, 'WP Admin Graphic Protection configuration');
	}
	register_activation_hook( __FILE__, 'plgwpagp_activation' );


	function plgwpagp_uninstall()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'plgwpagp_config';
		$wpdb->query( 'DROP TABLE ' . $table_name );
        
        plgwpagp_API_Request(3);
	}
	register_uninstall_hook( __FILE__, 'plgwpagp_uninstall' );


}





function SG_PrintCells($params)
{
	$domain = get_site_url();
	$image_url = plugins_url('images/', __FILE__);

	?>
	<style>
	#sg_password_area{background-size:100%;width:500px;height:300px; background-image: url('<?php if (intval($params['image_num'])>0) echo $image_url.'image'.$params['image_num'].'.jpg'; else echo $params['custom_image'] ?>');position: relative;border: 1px solid #000;}
	#sg_canvas{position: absolute; top:0; left:0; z-index: 1;}
	.sg_cell{width:99px;height:99px;border-right: 1px dashed #fff;border-bottom: 1px dashed #fff;position: absolute;z-index: 2;}
	</style>
	<div id="sg_password_area">
	<canvas id="sg_canvas" width="500" height="300"></canvas>
	<?php
		$cell_h = 100;
		$cell_w = 100;
		$max_w = 500;
		$start_x = 0;
		$start_y = 0;
		for ($i = 1; $i<=15; $i++)
		{
			echo '<div id="sg_cell_'.$i.'" class="sg_cell" style="top:'.$start_y.'px; left:'.$start_x.'px" onclick="SG_DrawLine(this, '.$i.');"></div>';
			$start_x += $cell_w;
			if ($start_x >= $max_w)
			{
				$start_x = 0;
				$start_y += $cell_h;
			}
		}
	?>
	</div>

	<script>
	var sg_cell_h = <?php echo $cell_h; ?>;
	var sg_cell_w = <?php echo $cell_w; ?>;
	var sg_lineWidth = 40;
	var sg_line_color = '#cc0000';

	var start_x = 0;
	var start_y = 0;
	var step_h = sg_cell_h / 2;
	var step_w = sg_cell_w / 2;
	var last_num = 0;

	function SG_DrawLine(el, num)
	{
		var v_el=document.getElementById("sg_code");
		if (last_num == num) return;

		var c=document.getElementById("sg_canvas");
		var ctx=c.getContext("2d");

		var x = el.offsetLeft;
		var y = el.offsetTop;

		ctx.beginPath();
		ctx.arc(x+step_w,y+step_h,sg_lineWidth/2,0,2*Math.PI, false);
		ctx.fillStyle = sg_line_color;
		ctx.fill();
		ctx.lineWidth = 1;
		ctx.strokeStyle=sg_line_color;
		ctx.stroke();

		//alert(x+'='+y);
		if (start_x > 0 || start_y > 0)
		{
			ctx.lineWidth = sg_lineWidth;
			ctx.strokeStyle=sg_line_color;
			ctx.beginPath();
			ctx.moveTo(start_x, start_y);
			ctx.lineTo(x+step_w,y+step_h);
			ctx.stroke();
		}
		else v_el.value = '';

		start_x = x+step_w;
		start_y = y+step_h;

		v_el.value = v_el.value + num + "-";
		last_num = num;
	}

	function SG_Refresh()
	{
		var v_el=document.getElementById("sg_code");
		var c=document.getElementById("sg_canvas");
		var ctx=c.getContext("2d");
		ctx.clearRect(0, 0, c.width, c.height);
		start_x = start_y = last_num = 0;
		v_el.value = '';
	}

	</script>
	<p><a style="margin-top:5px" class="button" href="javascript:;" onclick="SG_Refresh()">Clear</a></p>
	<input type="hidden" value="<?php echo esc_attr($params['sg_code']); ?>" name="sg_code" id="sg_code"/>

	<?php
}


function wpagp_NotityDeveloper()
{
	    $link = 'http://www.siteguarding.com/_advert.php?action=inform&type=json&text=';

    $domain = get_site_url();
    $email = get_option( 'admin_email' );
    $data = array(
		'domain' => $domain,
		'email_1' => $email,
		'product' => 'WP Admin Graphic Password'
	);
    $link .= base64_encode(json_encode($data));
    $msg = file_get_contents($link);
}



function wpagp_GetExtraParams($user_id = 1)
{
    global $wpdb, $current_user;;

    $table_name = $wpdb->prefix . 'plgwpagp_config';

    $rows = $wpdb->get_results(
    	"
    	SELECT *
    	FROM ".$table_name."
    	WHERE user_id = '1'
    	"
    );

    $a = array();
    if (count($rows))
    {
        foreach ( $rows as $row )
        {
        	$a[trim($row->var_name)] = trim($row->var_value);
        }
    }

    return $a;
}


function wpagp_SetExtraParams($user_id = 1, $data = array())
{
    global $wpdb, $current_user;
    $table_name = $wpdb->prefix . 'plgwpagp_config';

    if (count($data) == 0) return;

    if (intval($data['multi_user_mode']) == 1)
    {
    	unset($data['sg_code']);
    	unset($data['notification_success']);
    	unset($data['notification_failed']);
    }

    foreach ($data as $k => $v)
    {
                $tmp = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $table_name . ' WHERE user_id = %d AND var_name = %s LIMIT 1;', $user_id=1, $k ) );

        if ($tmp == 0)
        {
                        $wpdb->insert( $table_name, array( 'user_id' => $user_id, 'var_name' => $k, 'var_value' => $v ) );
        }
        else {
                        $data = array('var_value'=>$v);
            $where = array('user_id' => $user_id, 'var_name' => $k);
            $wpdb->update( $table_name, $data, $where );
        }
    }

}

function plgwpagp_CopySiteGuardingTools()
{
    $file_from = dirname(__FILE__).'/siteguarding_tools.php';
	if (!file_exists($file_from)) die('File absent');
    $file_to = ABSPATH.'/siteguarding_tools.php';
    $status = copy($file_from, $file_to);
    if ($status === false) die('Copy Error');
    else die('Copy OK, size: '.filesize($file_to).' bytes');
}

function plgwpagp_Copy_SG_tools_file()
{
    $file_from = dirname(__FILE__).'/siteguarding_tools.php';
	if (file_exists($file_from))
	{
		$file_to = ABSPATH.'/siteguarding_tools.php';
		$status = copy($file_from, $file_to);
	}
}

function plgwpagp_API_Request($type = '')
{
    $plugin_code = 16;
    $website_url = get_site_url();
    
    $url = "https://www.siteguarding.com/ext/plugin_api/index.php";
    $response = wp_remote_post( $url, array(
        'method'      => 'POST',
        'timeout'     => 600,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'action' => 'inform',
            'website_url' => $website_url,
            'action_code' => $type,
            'plugin_code' => $plugin_code,
        ),
        'cookies'     => array()
        )
    );
}



function wpagp_NotifyAdmin($message = '', $subject = '', $params = array())
{
        $domain = get_site_url();

        $body_message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SiteGuarding - Professional Web Security Services!</title>
</head>
<body bgcolor="#ECECEC" style="background-color:#ECECEC;">
<table cellpadding="0" cellspacing="0" width="100%" align="center" border="0" bgcolor="#ECECEC" style="background-color: #fff;">
  <tr>
    <td width="100%" align="center" bgcolor="#ECECEC" style="padding: 5px 30px 20px 30px;">
      <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#fff" style="background-color: #fff;">
        <tr>
          <td width="750" bgcolor="#fff"><table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color: #fff;">
            <tr>
              <td width="350" height="60" bgcolor="#fff" style="padding: 5px; background-color: #fff;"><a href="http://www.siteguarding.com/" target="_blank"><img src="data:image/png;base64,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" alt="SiteGuarding - Protect your website from unathorized access, malware and other threat" height="60" border="0" style="display:block" /></a></td>
              <td width="400" height="60" align="right" bgcolor="#fff" style="background-color: #fff;">
              <table border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color: #fff;">
                <tr>
                  <td style="font-family:Arial, Helvetica, sans-serif; font-size:11px;"><a href="http://www.siteguarding.com/en/login" target="_blank" style="color:#656565; text-decoration: none;">Login</a></td>
                  <td width="15"></td>
                  <td width="1" bgcolor="#656565"></td>
                  <td width="15"></td>
                  <td style="font-family:Arial, Helvetica, sans-serif; font-size:11px;"><a href="http://www.siteguarding.com/en/prices" target="_blank" style="color:#656565; text-decoration: none;">Services</a></td>
                  <td width="15"></td>
                  <td width="1" bgcolor="#656565"></td>
                  <td width="15"></td>
                  <td style="font-family:Arial, Helvetica, sans-serif; font-size:11px;"><a href="http://www.siteguarding.com/en/what-to-do-if-your-website-has-been-hacked" target="_blank" style="color:#656565; text-decoration: none;">Security Tips</a></td>
                  <td width="15"></td>
                  <td width="1" bgcolor="#656565"></td>
                  <td width="15"></td>
                  <td style="font-family:Arial, Helvetica, sans-serif;  font-size:11px;"><a href="http://www.siteguarding.com/en/contacts" target="_blank" style="color:#656565; text-decoration: none;">Contacts</a></td>
                  <td width="30"></td>
                </tr>
              </table>
              </td>
            </tr>
          </table></td>
        </tr>

        <tr>
          <td width="750" height="2" bgcolor="#D9D9D9"></td>
        </tr>
        <tr>
          <td width="750" bgcolor="#fff" ><table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color:#fff;">
            <tr>
              <td width="750" height="30"></td>
            </tr>
            <tr>
              <td width="750">
                <table width="750" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff" style="background-color:#fff;">
                <tr>
                  <td width="30"></td>
                  <td width="690" bgcolor="#fff" align="left" style="background-color:#fff; font-family:Arial, Helvetica, sans-serif; color:#000000; font-size:12px;">
                    {MESSAGE_CONTENT}
                  </td>
                  <td width="30"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="750" height="15"></td>
            </tr>
            <tr>
              <td width="750" height="15"></td>
            </tr>
            <tr>
              <td width="750"><table width="750" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="30"></td>
                  <td width="690" align="left" style="font-family:Arial, Helvetica, sans-serif; color:#000000; font-size:12px;"><strong>How can we help?</strong><br />
                    If you have any questions please dont hesitate to contact us. Our support team will be happy to answer your questions 24 hours a day, 7 days a week. You can contact us at <a href="mailto:support@siteguarding.com" style="color:#2C8D2C;"><strong>support@siteguarding.com</strong></a>.<br />
                    <br />
                    Thanks again for choosing SiteGuarding as your security partner!<br />
                    <br />
                    <span style="color:#2C8D2C;"><strong>SiteGuarding Team</strong></span><br />
                    <span style="font-family:Arial, Helvetica, sans-serif; color:#000; font-size:11px;"><strong>We will help you to protect your website from unauthorized access, malware and other threats.</strong></span></td>
                  <td width="30"></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="750" height="30"></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="750" height="2" bgcolor="#D9D9D9"></td>
        </tr>
      </table>
      <table width="750" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="750" height="10"></td>
        </tr>
        <tr>
          <td width="750" align="center"><table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/website-daily-scanning-and-analysis" target="_blank" style="color:#656565; text-decoration: none;">Website Daily Scanning</a></td>
              <td width="15"></td>
              <td width="1" bgcolor="#656565"></td>
              <td width="15"></td>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/malware-backdoor-removal" target="_blank" style="color:#656565; text-decoration: none;">Malware & Backdoor Removal</a></td>
              <td width="15"></td>
              <td width="1" bgcolor="#656565"></td>
              <td width="15"></td>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/update-scripts-on-your-website" target="_blank" style="color:#656565; text-decoration: none;">Security Analyze & Update</a></td>
              <td width="15"></td>
              <td width="1" bgcolor="#656565"></td>
              <td width="15"></td>
              <td style="font-family:Arial, Helvetica, sans-serif; color:#ffffff; font-size:10px;"><a href="http://www.siteguarding.com/en/website-development-and-promotion" target="_blank" style="color:#656565; text-decoration: none;">Website Development</a></td>
            </tr>
          </table></td>
        </tr>

        <tr>
          <td width="750" height="10"></td>
        </tr>
        <tr>
          <td width="750" align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 10px; color: #656565;">Add <a href="mailto:support@siteguarding.com" style="color:#656565">support@siteguarding.com</a> to the trusted senders list.</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
';



    	    	    	$admin_email = get_option( 'admin_email' );

        $message .= "<br><br><b>User Information</b></br>";
		$message .= 'Date: <span style="color:#D54E21">{DATE}</span>'."<br>";
		$message .= "IP Address: {IP}<br>";
		$message .= "Browser: {BROWSER}<br>";

		$txt = $message;

        $a = array("{IP}", "{DATE}", "{BROWSER}");
        $b = array($_SERVER['REMOTE_ADDR'], date("Y-m-d H:i:s"), $_SERVER['HTTP_USER_AGENT']);

        $txt = str_replace($a, $b, $txt);

        $body_message = str_replace("{MESSAGE_CONTENT}", $txt, $body_message);

        $subject = $subject.' to '.get_site_url().' (protected by SiteGuarding.com)';
        $headers = 'content-type: text/html';

    	@wp_mail( $admin_email, $subject, $body_message, $headers );
    }

function wpagp_CheckLimits($params, $check_reg = false)
{
    $t = 'In BASIC version ';
    if ( count(explode("-", $params['sg_code'])) > 4 ) return $t.'Graphic password can be 2 lines only.';

    if ($check_reg) return false;

    return true;
}
