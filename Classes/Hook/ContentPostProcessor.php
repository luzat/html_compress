<?php
namespace Markussom\HtmlCompress\Hook;

/*
 * This file is part of the Markussom/ project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use WyriHaximus\HtmlCompress\Factory;

/**
 * Class ContentPostProcessor
 */
class ContentPostProcessor
{
    /**
     * @param $funcRef
     * @param TypoScriptFrontendController $tsFrontendController
     */
    public function render($funcRef, TypoScriptFrontendController $tsFrontendController)
    {
        if ($this->isDefaultTypeNum()) {
            if ($this->isCompressBodyActive()) {
                $parser = Factory::construct();

                $pattern = '~<body.*?>(.*?)<\/body>~is';
                preg_match($pattern, $tsFrontendController->content, $body);
                $tsFrontendController->content = preg_replace(
                    '/<body .*<\/body>/is',
                    $parser->compress($body[0]),
                    $tsFrontendController->content
                );
            }
        }
    }

    /**
     * @return bool
     */
    protected function isDefaultTypeNum()
    {
        return GeneralUtility::_GET('type') === null;
    }

    /**
     * @return bool
     */
    protected function isCompressBodyActive()
    {
        return !empty($GLOBALS['TSFE']->config['config']['compressBody']);
    }
}
