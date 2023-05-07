<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2\XML\ds;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\SAML2\Constants as C;
use SimpleSAML\SAML2\XML\ds\X509Certificate;
use SimpleSAML\XML\Chunk;

/**
 * Class representing a ds:X509Data element.
 *
 * @package SimpleSAMLphp
 */
class X509Data
{
    /**
     * The various X509 data elements.
     *
     * Array with various elements describing this certificate.
     * Unknown elements will be represented by \SimpleSAML\XML\Chunk.
     *
     * @var (\SimpleSAML\XML\Chunk|\SimpleSAML\SAML2\XML\ds\X509Certificate)[]
     */
    private array $data = [];


    /**
     * Initialize a X509Data.
     *
     * @param \DOMElement|null $xml The XML element we should load.
     */
    public function __construct(DOMElement $xml = null)
    {
        if ($xml === null) {
            return;
        }

        for ($n = $xml->firstChild; $n !== null; $n = $n->nextSibling) {
            if (!($n instanceof DOMElement)) {
                continue;
            }

            if ($n->namespaceURI !== C::NS_XDSIG) {
                $this->addData(new Chunk($n));
                continue;
            }
            switch ($n->localName) {
                case 'X509Certificate':
                    $this->addData(new X509Certificate($n));
                    break;
                default:
                    $this->addData(new Chunk($n));
                    break;
            }
        }
    }


    /**
     * Collect the value of the data-property
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    /**
     * Set the value of the data-property
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


    /**
     * Add the value to the data-property
     *
     * @param \SimpleSAML\XML\Chunk|\SimpleSAML\SAML2\XML\ds\X509Certificate $data
     * @return void
     */
    public function addData($data): void
    {
        Assert::isInstanceOfAny($data, [Chunk::class, X509Certificate::class]);
        $this->data[] = $data;
    }


    /**
     * Convert this X509Data element to XML.
     *
     * @param \DOMElement $parent The element we should append this X509Data element to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent): DOMElement
    {
        $doc = $parent->ownerDocument;

        $e = $doc->createElementNS(C::NS_XDSIG, 'ds:X509Data');
        $parent->appendChild($e);

        /** @var \SimpleSAML\XML\Chunk|\SimpleSAML\SAML2\XML\ds\X509Certificate $n */
        foreach ($this->getData() as $n) {
            $n->toXML($e);
        }

        return $e;
    }
}
