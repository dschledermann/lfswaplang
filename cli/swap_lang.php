<?php

echo "\n";
echo "==========================================================================\n";

$language_swap_banner = <<<EOF
IF8gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg
ICAgICAgICAgICAgICAgIAp8IHwgICAgX18gXyBfIF9fICAgX18gXyBfICAgXyAgX18gXyAgX18g
XyAgX19fICAgX19fX18gICAgICBfX19fIF8gXyBfXyAgCnwgfCAgIC8gX2AgfCAnXyBcIC8gX2Ag
fCB8IHwgfC8gX2AgfC8gX2AgfC8gXyBcIC8gX19cIFwgL1wgLyAvIF9gIHwgJ18gXCAKfCB8X198
IChffCB8IHwgfCB8IChffCB8IHxffCB8IChffCB8IChffCB8ICBfXy8gXF9fIFxcIFYgIFYgLyAo
X3wgfCB8XykgfAp8X19fX19cX18sX3xffCB8X3xcX18sIHxcX18sX3xcX18sX3xcX18sIHxcX19f
fCB8X19fLyBcXy9cXy8gXF9fLF98IC5fXy8gCiAgICAgICAgICAgICAgICAgIHxfX18vICAgICAg
ICAgICAgIHxfX18vICAgICAgICAgICAgICAgICAgICAgICAgIHxffCAgICAK
EOF;

echo base64_decode($language_swap_banner);
echo "==========================================================================\n";

if (($pid = $_SERVER['argv'][1]) && ($lang = $_SERVER['argv'][2])) {
	$langswap = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Linkfactory\\Lfswaplang\\Swapper');
	$langswap->setSwapLang($lang);
	$langswap->setPid($pid);
	$langswap->setReally($_SERVER['argv'][3]);
	$langswap->run();

	echo implode("\n", $langswap->debug);
}
else {
	throw new \Exception("Provide both pageid and language name.\n");
}
