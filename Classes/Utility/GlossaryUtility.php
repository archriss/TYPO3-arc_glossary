<?php

namespace Archriss\ArcGlossary\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class GlossaryUtility implements SingletonInterface
{

    protected array $expressions = [];
    protected bool $parsingEnabled = false;
    protected array $pidLimitedExpressions = [];

    public function __construct(TypoScriptFrontendController $typoScriptFrontendController)
    {
        // Search for forced parse through get parameter or page parsing enabled 
        $getParse = GeneralUtility::_GET('parse');
        if (!is_null($getParse) && (bool)$getParse) {
            $this->parsingEnabled = true;
        } else {
            $this->parsingEnabled = (!($typoScriptFrontendController->page['tx_arcglossary_donotparse'] ?? false));
        }
    }

    public function isParsingEnabled(): bool
    {
        return $this->parsingEnabled;
    }

    public function initializeObject()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_arcglossary_glossary_entries');
        $this->expressions = $queryBuilder
            ->select('uid', 'pid', 'term', 'title', 'description')
            ->addSelectLiteral('LENGTH(term) as lgth')
            ->from('tx_arcglossary_glossary_entries')
            ->orderBy('lgth', 'DESC')
            ->execute()
            ->fetchAllAssociative();
    }

    public function getExpressions(int $pid = null): array
    {
        if (!is_null($pid)) {
            if (!array_key_exists($pid, $this->pidLimitedExpressions)) {
                $this->pidLimitedExpressions[$pid] = [];
                foreach ($this->expressions as $expression) {
                    if ($expression['pid'] === $pid) {
                        $this->pidLimitedExpressions[$pid][] = $expression;
                    }
                }
            }
            return $this->pidLimitedExpressions[$pid];
        }
        return $this->expressions;
    }

    public function getFixedWord(string $word): string
    {
        $myWord = str_replace('.', '\.', trim($word));
        $myWord = str_replace(' ', '[^a-z0-9]+', $myWord);
        return $myWord;
    }
}