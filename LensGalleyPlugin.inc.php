<?php

/**
 * @file plugins/viewableFiles/lensGalley/LensGalleyPlugin.inc.php
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class LensGalleyPlugin
 * @ingroup plugins_viewableFiles_lensGalley
 *
 * @brief Class for LensGalley plugin
 */

import('classes.plugins.ViewableFilePlugin');

class LensGalleyPlugin extends ViewableFilePlugin {
	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.viewableFiles.lensGalley.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.viewableFiles.lensGalley.description');
	}

	/**
	 * @see ViewableFilePlugin::displayArticleGalley
	 */
	function displayArticleGalley($templateMgr, $request, $params) {
		$journal = $request->getJournal();
		if (!$journal) return '';

		$fileId = (isset($params['fileId']) && is_numeric($params['fileId'])) ? (int) $params['fileId'] : null;
		if (!$fileId) {
			// unfortunate, but occasionally browsers upload PDF files as application/octet-stream.
			// Even setting the file type in the display template will not cause a correct render in this case.
			// So, update the file type if this is the case.
			$galley = $templateMgr->get_template_vars('galley'); // set in ArticleHandler
			$file = $galley->getFirstGalleyFile('pdf');
			if (!preg_match('/\.pdf$/', $file->getFileType())) {
				$file->setFileType('application/pdf');
				$submissionFileDao = DAORegistry::getDAO('SubmissionFileDAO');
				$submissionFileDao->updateObject($file);
			}
		}
		$templateMgr->assign('pluginJSPath', $this->getJSPath($request));

		return parent::displayArticleGalley($templateMgr, $request, $params);
	}

	/**
	 * returns the base path for JS included in this plugin.
	 * @param $request PKPRequest
	 * @return string
	 */
	function getJSPath($request) {
		return $request->getBaseUrl() . '/' . $this->getPluginPath() . '/js';
	}
}

?>
