<?php

if (TYPO3_MODE === 'BE') {
    /**
     * Registers a Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Linkfactory.' . $_EXTKEY,
        'web',   // Make module a submodule of 'web'
        'swap',    // Submodule key
        '',                                             // Position
        array(
            'Swap' => 'swap, execute',
        ),
        array(
            'access' => 'user,group',
            'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_langswap.xlf',
        )
    );

}

