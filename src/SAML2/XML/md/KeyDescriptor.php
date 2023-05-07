<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2\XML\md;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\SAML2\Constants as C;
use SimpleSAML\SAML2\Utils;
use SimpleSAML\SAML2\Utils\XPath;
use SimpleSAML\SAML2\XML\ds\KeyInfo;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Exception\MissingElementException;
use SimpleSAML\XML\Exception\TooManyElementsException;

use function count;

/**
 * Class representing a KeyDescriptor element.
 *
 * @package SimpleSAMLphp
 */
class KeyDescriptor
{
    /**
     * What this key can be used for.
     *
     * 'encryption', 'signing' or null.
     *
     * @var string|null
     */
    private ?string $use = null;

    /**
     * The KeyInfo for this key.
     *
     * @var \SimpleSAML\SAML2\XML\ds\KeyInfo|null
     */
    private ?KeyInfo $KeyInfo = null;

    /**
     * Supported EncryptionMethods.
     *
     * Array of \SimpleSAML\XML\Chunk objects.
     *
     * @var \SimpleSAML\XML\Chunk[]
     */
    private array $EncryptionMethod = [];


    /**
     * Initialize an KeyDescriptor.
     *
     * @param \DOMElement|null $xml The XML element we should load.
     * @throws \Exception
     */
    public function __construct(DOMElement $xml = null)
    {
        if ($xml === null) {
            return;
        }

        if ($xml->hasAttribute('use')) {
            $this->use = $xml->getAttribute('use');
        }

        $xpCache = XPath::getXPath($xml);

        $keyInfo = XPath::xpQuery($xml, './ds:KeyInfo', $xpCache);
        if (count($keyInfo) > 1) {
            throw new TooManyElementsException('More than one ds:KeyInfo in the KeyDescriptor.');
        } elseif (empty($keyInfo)) {
            throw new MissingElementException('No ds:KeyInfo in the KeyDescriptor.');
        }
        /** @var \DOMElement $keyInfo[0] */
        $this->KeyInfo = new KeyInfo($keyInfo[0]);

        /** @var \DOMElement $em */
        foreach (XPath::xpQuery($xml, './saml_metadata:EncryptionMethod', $xpCache) as $em) {
            $this->EncryptionMethod[] = new Chunk($em);
        }
    }


    /**
     * Collect the value of the use property.
     *
     * @return string|null
     */
    public function getUse(): ?string
    {
        return $this->use;
    }


    /**
     * Set the value of the use property.
     *
     * @param string|null $use
     * @return void
     */
    public function setUse(string $use = null): void
    {
        $this->use = $use;
    }


    /**
     * Collect the value of the KeyInfo property.
     *
     * @return \SimpleSAML\SAML2\XML\ds\KeyInfo|null
     */
    public function getKeyInfo(): ?KeyInfo
    {
        return $this->KeyInfo;
    }


    /**
     * Set the value of the KeyInfo property.
     *
     * @param \SimpleSAML\SAML2\XML\ds\KeyInfo $keyInfo
     * @return void
     */
    public function setKeyInfo(KeyInfo $keyInfo): void
    {
        $this->KeyInfo = $keyInfo;
    }


    /**
     * Collect the value of the EncryptionMethod property.
     *
     * @return \SimpleSAML\XML\Chunk[]
     */
    public function getEncryptionMethod(): array
    {
        return $this->EncryptionMethod;
    }


    /**
     * Set the value of the EncryptionMethod property.
     *
     * @param \SimpleSAML\XML\Chunk[] $encryptionMethod
     * @return void
     */
    public function setEncryptionMethod(array $encryptionMethod): void
    {
        $this->EncryptionMethod = $encryptionMethod;
    }


    /**
     * Add the value to the EncryptionMethod property.
     *
     * @param \SimpleSAML\XML\Chunk $encryptionMethod
     * @return void
     */
    public function addEncryptionMethod(Chunk $encryptionMethod): void
    {
        $this->EncryptionMethod[] = $encryptionMethod;
    }


    /**
     * Convert this KeyDescriptor to XML.
     *
     * @param \DOMElement $parent The element we should append this KeyDescriptor to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent): DOMElement
    {
        if ($this->KeyInfo === null) {
            throw new MissingElementException('Cannot convert KeyDescriptor to XML without KeyInfo set.');
        }

        $doc = $parent->ownerDocument;

        $e = $doc->createElementNS(C::NS_MD, 'md:KeyDescriptor');
        $parent->appendChild($e);

        if ($this->use !== null) {
            $e->setAttribute('use', $this->use);
        }

        $this->KeyInfo->toXML($e);

        foreach ($this->EncryptionMethod as $em) {
            $em->toXML($e);
        }

        return $e;
    }
}
