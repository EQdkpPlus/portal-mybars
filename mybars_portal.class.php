<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2008
 * Date:		$Date: 2012-05-01 13:28:27 +0200 (Di, 01. Mai 2012) $
 * -----------------------------------------------------------------------
 * @author		$Author: hoofy_leon $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 11769 $
 * 
 * $Id: mycontent_portal.class.php 11769 2012-05-01 11:28:27Z hoofy_leon $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

class mybars_portal extends portal_generic {
	public static function __shortcuts() {
		$shortcuts = array('core', 'config', 'html', 'user');
		return array_merge(parent::$shortcuts, $shortcuts);
	}

	protected $path		= 'mybars';
	protected $data		= array(
		'name'			=> 'Custom Bars Module',
		'version'		=> '1.0',
		'author'		=> 'Hoofy',
		'contact'		=> EQDKP_PROJECT_URL,
		'description'	=> 'Output a custom content',
	);
	protected $positions = array('middle', 'left1', 'left2', 'right', 'bottom');
	protected $install	= array(
		'autoenable'		=> '0',
		'defaultposition'	=> 'left',
		'defaultnumber'		=> '7',
	);
	
	protected $multiple = true;
	
	public function get_settings() {
		$settings = array(
			'pk_mybars_headtext'	=> array(
				'name'		=> 'pk_mybars_headtext',
				'language'	=> 'pk_mybars_headtext',
				'property'	=> 'text',
				'size'		=> 30,
			),
			'pk_mybars_bars'	=> array(
				'name'		=> 'pk_mybars_bars',
				'language'	=> 'pk_mybars_bars',
				'property'	=> 'spinner',
				'min'		=> 1,
				'size'		=> 6,
				'change'	=> 'load_settings();',
			),
		);
		$bar_settings = array(
			'pk_bars_title'	=> array(
				'name'		=> 'pk_mybars_title',
				'language'	=> $this->user->lang('pk_mybars_title'),
				'property'	=> 'text',
				'no_lang'	=> true,
				'size'		=> 30,
			),
			'pk_bars_current'	=> array(
				'name'		=> 'pk_mybars_current',
				'language'	=> $this->user->lang('pk_mybars_current'),
				'property'	=> 'spinner',
				'no_lang'	=> true,
				'size'		=> 6,
			),
			'pk_bars_max'	=> array(
				'name'		=> 'pk_mybars_max',
				'language'	=> $this->user->lang('pk_mybars_max'),
				'property'	=> 'spinner',
				'no_lang'	=> true,
				'size'		=> 6,
			),
			'pk_bars_tooltip'	=> array(
				'name'		=> 'pk_mybars_tooltip',
				'language'	=> $this->user->lang('pk_mybars_tooltip'),
				'property'	=> 'textarea',
				'cols'		=> '40',
				'rows'		=> '8',
				'no_lang'	=> true,
				'codeinput' => true
			),	
		);
		$maxbars = ($this->config('pk_mybars_bars')) ? $this->config('pk_mybars_bars') : 1;
		for($i=1;$i<=$maxbars;$i++) {
			foreach($bar_settings as $key => $data) {
				$settings[$key.$i] = $data;
				$settings[$key.$i]['name'] .= $i;
				$settings[$key.$i]['language'] = sprintf($settings[$key.$i]['language'], $i);
			}
		}
		return $settings;
	}

	public function output() {
		if($this->config('pk_mybars_headtext')){
			$this->header = sanitize($this->config('pk_mybars_headtext'));
		}
		$maxbars = ($this->config('pk_mybars_bars')) ? $this->config('pk_mybars_bars') : 1;
		if($maxbars > 1) {
			$out = '';
			for($i=1;$i<=$maxbars;$i++) {
				$out .= $this->bar_out($i);
			}
			return $out;
		}
		return $this->bar_out();
	}

	public function bar_out($num=1) {
		$value = (int) $this->config('pk_mybars_current'.$num);
		$max = (int) $this->config('pk_mybars_max'.$num);
		$text = (string) $this->config('pk_mybars_title'.$num);
		$tooltip = $this->config('pk_mybars_tooltip'.$num);
		if(empty($tooltip)) return $this->html->bar($value, $max, '100%', $text);
		$positions = array(
			'left1' => array('my' => 'left center', 'at' => 'right center'),
			'left2' => array('my' => 'left center', 'at' => 'right center'),
			'middle' => array(),
			'right' => array('my' => 'right center', 'at' => 'left center'),
			'bottom' => array('my' => 'bottom center', 'at' => 'top center'),
		);
		return $this->html->ToolTip($tooltip, $this->html->bar($value, $max, '100%', $text), '', $positions[$this->position]);
	}
}
if(version_compare(PHP_VERSION, '5.3.0', '<')) registry::add_const('short_mybars_portal', mybars_portal::__shortcuts());
?>