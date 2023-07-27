<?php

namespace Archriss\ArcGlossary\Middleware;

use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Glossary processing:
 * all found words in content wich correspond with the glossary entries
 * will be enriched with special accessibility markup and link
 */
class Parser implements MiddlewareInterface
{

    protected ?TypoScriptFrontendController $controller = null;

    protected array $conf = [];
    protected string $abbrTag = '<abbr><span class="tooltip" title="%s">%s</span></abbr>';

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->isParsingEnabled($request)) {
            $body = $response->getBody();
            $body->rewind();
            $response = $response->withBody(
                $this->getNewBody(
                    $this->parseContent(
                        $body->getContents()
                    )
                )
            );
        }
        return $response;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return bool
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    protected function isParsingEnabled(ServerRequestInterface $request): bool
    {
        if ((bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('arc_glossary', 'enable/parsing')) {
            $this->controller = $request->getAttribute('frontend.controller');
            $this->conf = $this->controller->tmpl->setup['plugin.']['tx_arcglossary.'] ?? [];
            $overrideAbbr = (string)GeneralUtility::makeInstance(ExtensionConfiguration::class)
                ->get('arc_glossary', 'format/abbrTag');
            if ($overrideAbbr !== '') {
                $this->abbrTag = $overrideAbbr;
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $content
     * @return Stream
     */
    protected function getNewBody(string $content): Stream
    {
        $body = new Stream('php://temp', 'rw');
        $body->write($content);
        return $body;
    }

    /**
     * @param string $content
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function parseContent(string $content): string
    {
        // Performing replacements only if page should be parsed ----------------------------------
        if (empty($this->controller->page['tx_arcglossary_donotparse']) || $_GET['parse']) {
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $expressions = $this->getParsercontentExpressions();

            // Extracting page parts where replacements can be performed ------------------------------
            $partsPattern = '/(<!--ARCGLOSSARY_begin-->)(.*?)(<!--ARCGLOSSARY_end-->)/si';
            preg_match_all($partsPattern, $content, $tempParts);
            $originalParts = $transformedParts = $tempParts[0];
            $originalPartsCount = count($originalParts);

            // Parsing each part, searching for parsercontent words to link --------------------------------
            for ($i = 0; $i < $originalPartsCount; $i++) {
                foreach ($expressions as $row) {
                    $word = $row['term'];
                    if (trim($word)) {
                        $regex = '/(?![^<]*">)(\s|\'|’|&#039;|>|\t|^\")('.$this->getFixedWord($word).')([[:punct:]]|[[:space:]])/s';
                        if (preg_match($regex, $transformedParts[$i])) {
                            $cObj->data = $row;
                            $accessibilityTag = sprintf($this->abbrTag,  $row['title'], '${2}');
                            $overlink = '$1' . $cObj->typolink($accessibilityTag, ($this->conf['parser.']['replacementTypolink.'] ?? [])) . '$3';
                            $transformedParts[$i] = preg_replace($regex, $overlink, $transformedParts[$i]);
                        }
                    }
                }
            }
            $content = str_replace($originalParts, $transformedParts, $content);
        }

        return $content;
    }

    protected function getFixedWord(string $word): string
    {
        $myWord = str_replace('.', '\.', trim($word));
        $myWord = str_replace(' ', '[^a-z0-9]+', $myWord);
        return $myWord;
    }

    /**
     * Retrieve and returns parsercontent expressions array
     *
     * @return array of expressions to search
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getParsercontentExpressions(): array
    {
        if ($this->conf['pidInList'] ?? false) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_arcglossary_glossary_entries');
            return $queryBuilder
                ->select('uid', 'term', 'alias', 'type', 'language', 'title')
                ->addSelectLiteral('LENGTH(term) as lgth')
                ->from('tx_arcglossary_glossary_entries')
                ->where(
                    $queryBuilder->expr()->in(
                        'pid',
                        $queryBuilder->createNamedParameter($this->conf['pidInList'])
                    )
                )
                ->orderBy('lgth', 'DESC')
                ->execute()
                ->fetchAllAssociative();
        } else {
            return [];
        }
    }
}
