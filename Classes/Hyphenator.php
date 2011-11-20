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

		$basicConfiguration = $this->handleBasicConfiguration($configuration);

		$script .= $this->wrapJavaScriptInTags($basicConfiguration . chr(10) . 'Hyphenator.config(txHyphenator); Hyphenator.run()');

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
		$script = '';
		$configArray = array();

		if (isset($configuration['classname'])) {
			$configArray[] = '"classname":"' . $configuration['classname'] . '"';
		}

		if (isset($configuration['donthyphenateclassname'])) {
			$configArray[] = '"donthyphenateclassname":"' . $configuration['donthyphenateclassname'] . '"';
		}

		if (isset($configuration['minwordlength'])) {
			$configArray[] = '"minwordlength":"' . (int) $configuration['minwordlength'];
		}

		if (isset($configuration['displaytogglebox'])) {
			$configArray[] = '"displaytogglebox":' . ($configuration['displaytogglebox']) ? 'true' : 'false';
		}

		if (isset($configuration['remoteloading'])) {
			$configArray[] = '"remoteloading":' . ($configuration['remoteloading']) ? 'true' : 'false';
		}

		if (isset($configuration['enablecache'])) {
			$configArray[] = '"enablecache":' . ($configuration['enablecache']) ? 'true' : 'false';
		}

		if (isset($configuration['intermediatestate'])) {
			$configArray[] = '"intermediatestate":"' . $configuration['intermediatestate'] . '"';
		}

		if (isset($configuration['safecopy'])) {
			$configArray[] = '"safecopy":"' . ($configuration['safecopy']) ? 'true' : 'false';
		}

		if (isset($configuration['doframes'])) {
			$configArray[] = '"doframes":"' . ($configuration['doframes']) ? 'true' : 'false';
		}

		if (isset($configuration['storagetype'])) {
			$configArray[] = '"storagetype":"' . $configuration['storagetype'] . '"';
		}

		// Functions:
		if (isset($configuration['selectorfunction'])) {
			$configArray[] = '"selectorfunction":' . $configuration['selectorfunction'];
		}

		if (isset($configuration['onerrorhandler'])) {
			$configArray[] = '"onerrorhandler":' . $configuration['onerrorhandler'];
		}

		if (isset($configuration['togglebox'])) {
			$configArray[] = '"togglebox":' . $configuration['togglebox'];
		}

		if (isset($configuration['onhyphenationdonecallback'])) {
			$configArray[] = '"onhyphenationdonecallback":' . $configuration['onhyphenationdonecallback'];
		}

		$script .= 'var txHyphenator = { ' . chr(10) . implode(','.chr(10), $configArray) . chr(10) . '};';

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
		return '<script type="text/javascript">/* <![CDATA[ */ ' . $javaScriptCode . ' /* ]]> */</script>';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hyphenator/Classes/Hyphenator.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/hyphenator/Classes/Hyphenator.php']);
}
?>