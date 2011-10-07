<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Andreas Lappe <nd@off-pist.de>
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
		if (count($this->config) > 0) {
			$this->configstring = $this->handleConfiguration($this->config);
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
		return str_replace('</body>', $this->configstring . '</body>', $content);
	}

	/**
	 * Get configuration and build the config string to be inserted
	 *
	 * @param array $configuration
	 * @return string
	 */
	public function handleConfiguration(array $configuration) {
		$script = '';

		$functionOverrides = $this->handleFunctionOverrides($configuration);
		$basicConfiguration = $this->handleBasicConfiguration($configuration);

		$script .= $this->wrapJavaScriptInTags($functionOverrides);
		$script .= $this->wrapJavaScriptInTags('Hyphenator.config(' . $basicConfiguration . '); Hyphenator.run()') . chr(10);

		return $script;
	}

	/**
	 * Create an array of normal values (strings/booleans/numbers) and return it
	 * as a json string…
	 *
	 * @param array $configuration
	 * @return string
	 */
	public function handleBasicConfiguration(array $configuration) {
		$functionPrefix = 'txHyphenator.';
		$configArray = array();

		if (isset($this->config['classname'])) {
			$configArray[] = '"classname":"' . $this->config['classname'] . '"';
		}

		if (isset($this->config['donthyphenateclassname'])) {
			$configArray[] = '"donthyphenateclassname":"' . $this->config['donthyphenateclassname'] . '"';
		}

		if (isset($this->config['minwordlength'])) {
			$configArray[] = '"minwordlength":"' . (int) $this->config['minwordlength'];
		}

		if (isset($this->config['displaytogglebox'])) {
			$configArray[] = '"displaytogglebox":' . ($this->config['displaytogglebox']) ? 'true' : 'false';
		}

		if (isset($this->config['remoteloading'])) {
			$configArray[] = '"remoteloading":' . ($this->config['remoteloading']) ? 'true' : 'false';
		}

		if (isset($this->config['enablecache'])) {
			$configArray[] = '"enablecache":' . ($this->config['enablecache']) ? 'true' : 'false';
		}

		if (isset($this->config['intermediatestate'])) {
			$configArray[] = '"intermediatestate":"' . $this->config['intermediatestate'] . '"';
		}

		if (isset($this->config['safecopy'])) {
			$configArray[] = '"safecopy":"' . ($this->config['safecopy']) ? 'true' : 'false';
		}

		if (isset($this->config['doframes'])) {
			$configArray[] = '"doframes":"' . ($this->config['doframes']) ? 'true' : 'false';
		}

		if (isset($this->config['storagetype'])) {
			$configArray[] = '"storagetype":"' . $this->config['storagetype'] . '"';
		}

		// Functions:
		if (isset($this->config['selectorfunction'])) {
			$configArray[] = '"selectorfunction":' . $functionPrefix . 'selectorfunction';
		}

		if (isset($this->config['onerrorhandler'])) {
			$configArray[] = '"onerrorhandler":' . $functionPrefix . 'onerrorhandler';
		}

		if (isset($this->config['togglebox'])) {
			$configArray[] = '"togglebox":' . $functionPrefix . 'togglebox';
		}

		if (isset($this->config['onhyphenationdonecallback'])) {
			$configArray[] = '"onhyphenationdonecallback":' . $functionPrefix . 'onhyphenationdonecallback';
		}

		return '{' . implode(',', $configArray) . '}';
	}

	/**
	 * Handle function overrides…
	 *
	 * @param array $configuration
	 * @return string
	 */
	public function handleFunctionOverrides(array $configuration) {
		$functions = array();
		$script = '';
		if (isset($configuration['selectorfunction'])) {
			$functions[] = '"selectorfunction":' . $configuration['selectorfunction'];
		}

		if (isset($configuration['onerrorhandler'])) {
			$functions[] = '"onerrorhandler":' . $configuration['onerrorhandler'];
		}

		if (isset($configuration['togglebox'])) {
			$functions[] = '"togglebox":' . $configuration['togglebox'];
		}

		if (isset($configuration['onhyphenationdonecallback'])) {
			$functions[] = '"onhyphenationdonecallback":' . $configuration['onhyphenationdonecallback'];
		}

		$script .= 'var txHyphenator = { ' . chr(10) . implode(','.chr(10), $functions) . chr(10) . '};';

		if ($configuration['compressInlineJavaScript']) {
			$script = JSMin::minify($script);
		}

		return $script;
	}

	/**
	 * Wrap the string in JavaScript-tags
	 *
	 * @param string $javaScriptCode
	 * @return string
	 */
	public function wrapJavaScriptInTags($javaScriptCode) {
		return '<script type="text/javascript">' . $javaScriptCode . '</script>';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hyphenator/Classes/Hyphenator.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hyphenator/Classes/Hyphenator.php']);
}
?>