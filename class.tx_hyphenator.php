<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009     Andreas Lappe <nd@off-pist.de>,
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
 */


class tx_hyphenator {

	/**
	 * main processing method
	 */
	function contentPostProc_all(&$params, &$reference){
			
		// process the page with these options
		$params['pObj']->content = $this->process($params['pObj']->content);

	}

	/**
	 */
	function process($content){
		
		// Add the tracking code to the end of <head> element
		$content = str_replace('</body>', $this->script_code( $pageName ).'</body>', $content);
				
		return $content;
	}

	/**
	 * generates the google tracking code (js script at the end of the body tag).
	 * 
	 * @return	string	js tracking code
	 */
	function script_code() {
		return '<script type="text/javascript">Hyphenator.config({ minwordlength : 4 }); Hyphenator.run();</script>'.chr(10);
	}
	
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/hyphenator/class.tx_hyphenator.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/hyphenator/class.tx_hyphenator.php"]);
}

?>
