<?php

namespace Linkfactory\Lfswaplang\Controller;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SwapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \TYPO3\CMS\Lang\Domain\Repository\LanguageRepository
	 * @inject
	 */
	protected $languageRepository;

	public function swapAction() {
		global $TYPO3_DB;

		$rs = $TYPO3_DB->sql_query("SELECT uid, title FROM sys_language WHERE hidden = 0");

		$languages = [];

		while (list($uid, $title) = $TYPO3_DB->sql_fetch_row($rs)) {
			$languages[$uid] = $title;
		}

		$this->view->assign('languages', $languages);
		return;
	}

	public function executeAction() {
		global $TYPO3_DB;
		$language_uid = intval($this->request->getArgument('language'));
		$pageId = intval(GeneralUtility::_GP('id'));

		$rs = $TYPO3_DB->sql_query("SELECT title FROM sys_language WHERE hidden = 0 AND uid = $language_uid");
		list($language) = $TYPO3_DB->sql_fetch_row($rs);

		$swapper = GeneralUtility::makeInstance('Linkfactory\\Lfswaplang\\Swapper');
		$swapper->setSwapLang($language);
		$swapper->setPid($pageId);
		$swapper->setReally(true);
		$swapper->run();

		$this->view->assign('debug', $swapper->debug);
		$this->view->assign('language', $language);

		return;
	}
}

