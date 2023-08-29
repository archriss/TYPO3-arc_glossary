<?php

namespace Archriss\ArcGlossary\ViewHelpers;

use Archriss\ArcGlossary\Utility\GlossaryUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GetWordsViewHelper extends AbstractViewHelper
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
        $this->registerArgument('as', 'string', 'Template variable name to assign.');
        $this->registerArgument('wordsPid', 'integer', 'Glossary words definition pid.');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): mixed
    {
        $glossaryUtility = GeneralUtility::makeInstance(GlossaryUtility::class);
        $expressions = $glossaryUtility->getExpressions($arguments['wordsPid']);
        if (!is_null($arguments['as'])) {
            $renderingContext->getVariableProvider()->add(
                $arguments['as'],
                $expressions
            );
            return null;
        } else {
            return $expressions;
        }
    }
}
