<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');
if (TYPO3_MODE=='FE') require_once(t3lib_extMgm::extPath('hyphenator').'Classes/Hyphenator.php');
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'tx_hyphenator->contentPostProc_all'; 
?>