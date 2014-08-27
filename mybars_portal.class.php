<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2008
 * Date:		$Date: 2013-01-27 12:42:30 +0100 (So, 27 Jan 2013) $
 * -----------------------------------------------------------------------
 * @author		$Author: hoofy_leon $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 12916 $
 * 
 * $Id: mybars_portal.class.php 12916 2013-01-27 11:42:30Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

class mybars_portal extends portal_generic {

	protected static $path		= 'mybars';
	protected static $data		= array(
		'name'			=> 'Custom Bars Module',
		'version'		=> '1.0.2',
		'author'		=> 'Hoofy',
		'icon'			=> 'fa-bar-chart-o',
		'contact'		=> EQDKP_PROJECT_URL,
		'description'	=> 'Output a custom content',
		'multiple'		=> true,
		'lang_prefix'	=> 'mybars_'
	);
	protected static $positions = array('middle', 'left1', 'left2', 'right', 'bottom');
	protected static $install	= array(
		'autoenable'		=> '0',
		'defaultposition'	=> 'left',
		'defaultnumber'		=> '7',
	);
	
	protected static $apiLevel = 20;
	
	public function get_settings($state) {
		$settings = array(
			'bars'	=> array(
				'type'		=> 'spinner',
				'min'		=> 1,
				'size'		=> 6,
				'class'		=> 'js_reload',
			),
		);
		$bar_settings = array(
			'title'	=> array(
				'type'		=> 'text',
				'size'		=> 30,
			),
			'current'	=> array(
				'type'		=> 'spinner',
				'size'		=> 6,
			),
			'max'	=> array(
				'type'		=> 'spinner',
				'size'		=> 6,
			),
			'tooltip'	=> array(
				'type'		=> 'textarea',
				'cols'		=> '40',
				'rows'		=> '8',
				'codeinput' => true
			),	
		);
		$maxbars = ($this->config('bars')) ? $this->config('bars') : 1;
		for($i=1;$i<=$maxbars;$i++) {
			foreach($bar_settings as $key => $data) {
				$settings[$key.$i] = $data;
				$settings[$key.$i]['dir_lang'] = sprintf($this->user->lang(static::$data['lang_prefix'].'f_'.$key), $i);
			}
		}
		return $settings;
	}

	public function output() {
		$maxbars = ($this->config('bars')) ? $this->config('bars') : 1;
		if($maxbars > 1) {
			$out = '';
			for($i=1;$i<=$maxbars;$i++) {
				$out .= $this->bar_out($i);
				//usleep(3);
			}
			return $out;
		}
		return $this->bar_out();
	}

	public function bar_out($num=1) {
		$value = (int) $this->config('current'.$num);
		$max = (int) $this->config('max'.$num);
		$text = (string) $this->config('title'.$num);
		$tooltip = $this->config('tooltip'.$num);
		if(empty($tooltip)) return $this->jquery->ProgressBar('mybar_'.unique_id(), 0, array(
			'total' 	=> $max,
			'completed' => $value,
			'text'		=> $text.' %progress%',
			'txtalign'	=> 'center',
		));
		$name = 'mybar_tt_'.unique_id();
		$positions = array(
			'left' => array('my' => 'left top', 'at' => 'right center', 'name' => $name),
			'middle' => array('name' => $name),
			'right' => array('my' => 'right center', 'at' => 'left center', 'name' => $name ),
			'bottom' => array('my' => 'bottom center', 'at' => 'top center', 'name' => $name ),
		);
		$arrPosition = (isset($positions[$this->position])) ? $positions[$this->position] : $positions['middle'];
		$tooltipopts	= array('label' => $this->jquery->ProgressBar('mybar_'.unique_id(), 0, array(
			'total' 	=> $max,
			'completed' => $value,
			'text'		=> $text.' %progress%',
			'txtalign'	=> 'center',
		)), 'content' => $tooltip);
		$tooltipopts	= array_merge($tooltipopts, $arrPosition);
		return new htooltip('mybars_tt'.$num, $tooltipopts);
	}
}
?>