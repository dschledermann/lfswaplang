<?php

class Tx_Lfswaplang_Swapper {
	protected $pid = null;
	protected $lang = null;
	protected $lang_uid = null;
	protected $really = false;

	public function setSwapLang($lang) {
		$this->lang = $lang;
	}

	public function setPid($pid) {
		$this->pid = $pid;
	}

	public function setReally($really) {
		if (!	$this->really = ($really == "really")) {
			echo "Append 'really' at the end of the command to execute the changes for real.\n";
			echo "==========================================================================\n";
		}
	}

	public function run() {
		global $TYPO3_DB;

		// Find language in sys_language
		$rs = $TYPO3_DB->exec_SELECTquery('uid', 'sys_language', "title = '$this->lang'");
		list($lang_uid) = $TYPO3_DB->sql_fetch_row($rs);

		if (!$lang_uid) {
			throw new Exception("Fatal error: Language $this->lang could not be found");
		}
		$this->lang_uid = $lang_uid;

		// Locate the page in question
		$rs = $TYPO3_DB->exec_SELECTquery('uid, deleted', 'pages', "uid = $this->pid");
		list($uid, $deleted) = $TYPO3_DB->sql_fetch_row($rs);
		
		if (!$uid) {
			throw new Exception("Fatal error: Page $this->pid not found");
		}

		if ($deleted) {
			throw new Exception("Fatal error: Page $this->pid is a deleted page");
		}

		// Preflight check done
		$this->doSwapLang();
	}

	private function doSwapLang() {
		global $TYPO3_DB;

		// Find entire sub page tree for the page in question
		$pids = $this->findSubPages($this->pid);

		// Match all these pages
		$rs_pages = $TYPO3_DB->exec_SELECTquery("p.uid, p.title AS ptitle, plo.title AS plotitle",
																						"pages AS p, pages_language_overlay AS plo",
																						"p.uid = plo.pid " . 
																						" AND p.uid IN (" . implode(",", $pids) . ")" .
																						" AND plo.sys_language_uid = " . $this->lang_uid . " ");

		echo "Swapping these pages:\n";
		while($page_row = $TYPO3_DB->sql_fetch_assoc($rs_pages)) {
			echo $page_row['uid'] . ": '" . $page_row['ptitle'] . "' <--> '" . $page_row['plotitle'] . "'";

			if ($this->really) {
				// Swapping page title
				$TYPO3_DB->exec_UPDATEquery('pages',
																		'uid = ' . $page_row['uid'],
																		array('title' => $page_row['plotitle']));

				$TYPO3_DB->exec_UPDATEquery('pages_language_overlay',
																		'pid = ' . $page_row['uid'] . " AND sys_language_uid = " . $this->lang_uid,
																		array('title' => $page_row['ptitle']));

				echo " .. done\n";
			}
			else {
				echo " .. skip\n";
			}

			// Retrieve content elements on this page
			$rs_content = $TYPO3_DB->exec_SELECTquery("orglang.uid AS orguid, swaplang.uid AS swapuid, orglang.header AS orgheader, swaplang.header AS swapheader",
																								"tt_content AS orglang, tt_content AS swaplang",
																								"orglang.pid = " . $page_row['uid'] . " " .
																								"AND orglang.uid = swaplang.l18n_parent " . 
																								"AND orglang.deleted = 0 " . 
																								"AND swaplang.sys_language_uid = " . $this->lang_uid . " " .
																								"AND orglang.sys_language_uid = 0");

			while ($content_row = $TYPO3_DB->sql_fetch_assoc($rs_content)) {
				echo "  " . $content_row['orguid'] . ":'" . $content_row['orgheader'] . "' <--> " . $content_row['swapuid'] . ":'" . $content_row['swapheader'] . "'";

				if ($this->really) {
					// Swapping content text fields
					$TYPO3_DB->sql_query("UPDATE tt_content AS orglang " . 
															 " INNER JOIN tt_content AS swaplang ON orglang.uid = swaplang.l18n_parent " .
															 " SET orglang.header = swaplang.header, " .
															 "     orglang.subheader = swaplang.subheader, " .
															 "     orglang.bodytext = swaplang.bodytext, " .
															 "     swaplang.header = orglang.header, " .
															 "     swaplang.subheader = orglang.subheader, " .
															 "     swaplang.bodytext = orglang.bodytext " .
															 " WHERE orglang.sys_language_uid = 0 " .
															 " AND   swaplang.sys_language_uid = " . $this->lang_uid . " " .
															 " AND   orglang.uid = " . $content_row['orguid']);

					echo " .. done\n";
				}
				else {
					echo " .. skip\n";
				}
			}
			echo "\n";
		}
	}

	private function findSubPages($pid) {
		global $TYPO3_DB;

		$rs = $TYPO3_DB->exec_SELECTquery('uid', 'pages', "pid = $pid AND deleted = 0");
		$res = array($pid);

		while (list($uid) = $TYPO3_DB->sql_fetch_row($rs)) {
			$res = array_merge($res, $this->findSubPages($uid));
		}

		sort($res);

		return $res;
	}
}

