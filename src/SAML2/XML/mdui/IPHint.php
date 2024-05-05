<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2\XML\mdui;

use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\StringElementTrait;

/**
 * Class implementing IPHint.
 *
 * @package simplesamlphp/saml2
 */
final class IPHint extends AbstractMduiElement
{
    use StringElementTrait;


    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->setContent($content);
    }


    /**
     * Validate the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @throws \Exception on failure
     * @return void
     */
    protected function validateContent(string $content): void
    {
        Assert::notEmpty($content, 'IPHint cannot be empty');
    }
}
