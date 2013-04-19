<?php
/*
Theme Name: Smooth
Version: 1.0
*/

class Vimeography_Themes_Smooth extends Mustache
{
  public $version = '1.0';
  public $data;
  public $featured;
  public $gallery_id;
  public $gallery_width;

	public function __construct()
	{
		// Without the @, this generates warnings?
		// Notice: Undefined offset: 0 in /Users/davekiss/Sites/vimeography.com/wp-includes/plugin.php on line 762/780
		@add_action('wp_enqueue_scripts', $this->_load_scripts());
	}

	public function _load_scripts()
	{
		// First things first. jQuery.
		wp_enqueue_script('jquery');

		// Register our common scripts
		wp_register_script('froogaloop', 'http://a.vimeocdn.com/js/froogaloop2.min.js');
		wp_register_script('flexslider', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.flexslider.js', array('jquery'));
		wp_register_script('fitvids', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.fitvids.js', array('jquery'));
		wp_register_script('spin', VIMEOGRAPHY_ASSETS_URL.'js/plugins/spin.min.js', array('jquery'));
		wp_register_script('jquery-spin', VIMEOGRAPHY_ASSETS_URL.'js/plugins/jquery.spin.js', array('jquery', 'spin'));

		// Register our custom scripts
		wp_register_style('smooth', VIMEOGRAPHY_THEME_URL.'smooth/media/smooth.css');

		wp_enqueue_script('froogaloop');
		wp_enqueue_script('flexslider');
		wp_enqueue_script('fitvids');
		wp_enqueue_script('spin');
		wp_enqueue_script('jquery-spin');

		wp_enqueue_style('smooth');
	}

	public function dynamic_css()
	{
  	$custom_settings = get_transient('vimeography_theme_settings_'.$this->gallery_id);
  	if ($custom_settings === FALSE) return FALSE;

  	$settings['exists'] = TRUE;
  	$settings['settings'] = $custom_settings;

  	return $settings;
	}

  public function info()
  {
  	// optional helpers
  	require_once(VIMEOGRAPHY_PATH .'lib/helpers.php');
  	$helpers = new Vimeography_Helpers;

  	// add featured video to the beginning of the array
  	if (is_array($this->featured))
  		array_unshift($this->data, $this->featured[0]);

  	$items = array();

  	foreach($this->data as $item)
  	{
		if ($item->duration AND ! strpos($item->duration, ':'))
		{
			$item->duration = $helpers->seconds_to_minutes($item->duration);
		}
		$items[] = $item;
  	}

  	return $items;
  }

}