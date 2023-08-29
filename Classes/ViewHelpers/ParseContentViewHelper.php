<?php

namespace Archriss\ArcGlossary\ViewHelpers;

use Archriss\ArcGlossary\Utility\GlossaryUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Replace the old parser content
 * Cache handling
 *
 * @example:
 * <arcGlossary:parseContent
 * abbrTag="<abbr class=\"s-abbreviation__abbr\" title=\"%s\">%s</abbr>"
 * typolinkConfiguration="{parameter: settings.pid.glossary, 'section.': {field: 'uid', wrap: 'c'}, ATagParams: 'class=\"s-abbreviation\"'}"
 * >
 * {bodytext}
 * </arcGlossary:parseContent>
 */
class ParseContentViewHelper extends AbstractViewHelper
{

    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('abbrTag', 'string', 'Tag used for replacement.', false, '<abbr title="%s">%s</abbr>');
        $this->registerArgument('typolinkConfiguration', 'array', 'Typolink configuration to wrap arround abbrTag', false, []);
        $this->registerArgument('wordsPid', 'integer', 'Glossary words definition pid.');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $glossaryUtility = GeneralUtility::makeInstance(GlossaryUtility::class);
        $expressions = $glossaryUtility->getExpressions($arguments['wordsPid']);
        $content = $renderChildrenClosure();
        if (count($expressions) && $content !== '') {
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            foreach ($expressions as $row) {
                $word = $row['term'];
                if (trim($word)) {
                    $regex = '/(?![^<]*">)(\s|\'|’|&#039;|>|\t|^\")(' . $glossaryUtility->getFixedWord($word) . ')([[:punct:]]|[[:space:]])/s';
                    if (preg_match($regex, $content)) {
                        $cObj->data = $row;
                        $accessibilityTag = sprintf($arguments['abbrTag'], $row['title'], '${2}');
                        $overlink = '$1' . $cObj->typolink($accessibilityTag, ($arguments['typolinkConfiguration'] ?? [])) . '$3';
                        $content = preg_replace($regex, $overlink, $content);
                    }
                }
            }
        }
        return $content;
    }
}
