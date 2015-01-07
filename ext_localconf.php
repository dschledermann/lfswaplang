<?php

/**
 * Registering class to convert language
 */
$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys']['swaplang'] = array(
    'EXT:'.$_EXTKEY.'/cli/swap_lang.php','_CLI_lfswaplang',
    'extension' => $_EXTKEY
);

