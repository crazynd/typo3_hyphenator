<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009     Andreas Lappe <nd@off-pist.de>
 *
 *  All rights reserved
 *
 *  This script is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; version 2 of the License.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Hyphenator
 *
 * @author Andreas Lappe <nd@off-pist.de>
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class tx_hyphenator {

	/**
	 * @var array
	 */
	protected $config = Array();

	/**
	 * @var string
	 */
	private $configstring = '';


	/**
	 * main processing method
	 *
	 * @param array $params
	 * @param $reference
	 */
	public function contentPostProc_all(&$params, &$reference){

		// Set config global
		$this->config = &$params['pObj']->config['config']['tx_hyphenator.'];

		// Build config string from it
		if(count($this->config) > 0) {
			$this->configstring = $this->handleConfig();
		}

		// process the page with these options
		$params['pObj']->content = $this->process($params['pObj']->content);
	}

	/**
	 * Add the JS block to the page
	 *
	 * @param string $content
	 * @return string
	 */
	public function process($content){
		// Add the tracking code to the end of <head> element
		$content = str_replace('</body>', $this->generateScriptCode( $pageName ).'</body>', $content);
		return $content;
	}

	/**
	 * Generate JS block
	 * 
	 * @param void
	 * @return string
	 */
	public function generateScriptCode() {
		return '<script type="text/javascript">'.$this->configstring.' Hyphenator.run();</script>'.chr(10);
	}

	/**
	 * Get configuration and build the config string to be inserted
	 *
	 * @param void
	 * @return string
	 */
	public function handleConfig() {
		$configArray;

		if(isset($this->config['classname'])) {
			$configArray[] = 'classname: \''.$this->config['classname'].'\'';
		}

		if(isset($this->config['donthyphenateclassname'])) {
			$configArray[] = 'donthyphenateclassname: \''.$this->config['donthyphenateclassname'].'\'';
		}

		if(isset($this->config['displaytogglebox'])) {
			$configArray[] = ($this->config['displaytogglebox']) ? 'displaytogglebox: true' : 'displaytogglebox: false';
		}

		if(isset($this->config['minwordlength'])) {
			$configArray[] = 'minwordlength: '.$this->config['minwordlength'];
		}

		if(isset($this->config['remoteloading'])) {
			$configArray[] = ($this->config['remoteloading']) ? 'remoteloading: true' : 'remoteloading: false';
		}

		if(isset($this->config['enablecache'])) {
			$configArray[] = ($this->config['enablecache']) ? 'enablecache: true' : 'enablecache: false';
		}

		if(isset($this->config['intermediatestate'])) {
			$configArray[] = 'intermediatestate: \''.$this->config['intermediatestate'].'\'';
		}

		if(isset($this->config['onerrorhandler'])) {
			$configArray[] = 'onerrorhandler: '.$this->config['onerrorhandler'];
		}

		// done
		return 'Hyphenator.config({'.implode(', ', $configArray).'});';
	}

}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/hyphenator/class.tx_hyphenator.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/hyphenator/class.tx_hyphenator.php"]);
}

?>
