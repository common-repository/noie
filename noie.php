<?php
/*
Plugin Name: NoIE
Plugin URI: http://www.hnkweb.com/code/noie
Description: Displays a security alert when visiting the site with an Internet Explorer browser.
Version: 0.3
Author: Hanok
Author URI: http://www.hnkweb.com
*/

add_action('wp_head', 'wp_head_noie');
add_action('wp_footer', 'wp_foot_noie');
add_action('admin_menu', 'wp_admin_menu_noie');

function wp_head_noie() {
  if (UsaIE()) {
    echo '
    <!-- Added by NoIE -->
    <link rel="stylesheet" type="text/css" media="screen" href="'.URLplug().'/noie.css" />
    <script type="text/javascript"><!--
      var browserType;
      if (document.layers) {browserType = "nn4";}
      if (document.all) {browserType = "ie";}
      if (window.navigator.userAgent.toLowerCase().match("gecko")) {browserType= "gecko";}
      function hide_noie() {
        if (browserType == "gecko" ) document.poppedLayer = eval(\'document.getElementById("infobar")\');
        else if (browserType == "ie") document.poppedLayer = eval(\'document.getElementById("infobar")\');
        else document.poppedLayer =  eval(\'document.layers["infobar"]\');
        document.poppedLayer.style.visibility = "hidden";
      }
    --></script>
    <!-- End NoIE -->';
  }	
}

function wp_foot_noie() {
  if (UsaIE()) {
    $o = wp_get_options_noie();
    echo '
    <!-- Added by NoIE -->
    <div id="infobar"><a id="txt" href="'.URLplug().'/'.$o['html'].'">'.$o['msg'].'</a><a id="hidenoie" href="javascript:;" onclick="hide_noie()">X</a></div>		
    <!-- End NoIE -->';	
  }
}

function wp_admin_menu_noie(){
  add_options_page('NoIE, options page', 'NoIE', 9, basename(__FILE__), 'wp_options_page_noie');
}

function wp_get_options_noie(){
  $defaults = array();
  $defaults['msg'] = 'Est&aacute; ejecutando una versi&oacute;n de Internet Explorer para acceder a internet. Es posible que su equipo est&eacute; en riesgo.';
  $defaults['html'] = 'aviso.html';

  $options = get_option('wp_settings_noie');
  if (!is_array($options)){
    $options = $defaults;
    update_option('wp_settings_noie', $options);
  }

  return $options;
}

function wp_options_page_noie(){
  if ($_POST['noie']){
    update_option('wp_settings_noie', $_POST['noie']);
    $message = '<div class="updated"><p><strong>Options saved.</strong></p></div>';
  }

  $o = wp_get_options_noie();
?>
		<div class="wrap">
			<h2>NoIE Options</h2>
			<?php echo $message; ?>
			<form name="form1" method="post" action="options-general.php?page=noie.php">
			<fieldset class="options">
			<legend>General</legend>
				<table width="100%" cellspacing="2" cellpadding="5" class="editform">
				<tr valign="top">
          <th>Default Message</th>
          <td>
            <input type="text" value="<?php echo $o['msg']; ?>" name="noie[msg]" size="75" maxlength="1000" />
          </td>
        </tr>
        <tr valign="top">
          <th>HTML File <small>[should be placed in plugin folder]</small></th>
          <td>
            <input type="text" value="<?php echo $o['html']; ?>" name="noie[html]" size="75" maxlength="1000" />
          </td>
        </tr>					
				</table>
			</fieldset>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save') ?> &raquo;" /></p>
			</form>
	  </div>
<?php	
}

function UsaIE() {
 $agente = $_SERVER['HTTP_USER_AGENT'];
 $navegador = ' MSIE ';
 $comprobacion = strpos($agente, $navegador); 
 return ($comprobacion > 0) ? true : false;
}

function URLplug() {
  return trailingslashit(get_settings('siteurl')) . 'wp-content/plugins/noie';
}

?>