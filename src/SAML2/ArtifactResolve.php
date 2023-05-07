<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2;

use DOMElement;
use SimpleSAML\SAML2\Utils\XPath;

use function is_null;

/**
 * The Artifact is part of the SAML 2.0 IdP code, and it builds an artifact object.
 * I am using strings, because I find them easier to work with.
 * I want to use this, to be consistent with the other saml2_requests
 *
 * @author Danny Bollaert, UGent AS. <danny.bollaert@ugent.be>
 * @package SimpleSAMLphp
 */
class ArtifactResolve extends Request
{
    private string $artifact;


    /**
     * Constructor for SAML 2 ArtifactResolve.
     *
     * @param \DOMElement|null $xml The input assertion.
     */
    public function __construct(DOMElement $xml = null)
    {
        parent::__construct('ArtifactResolve', $xml);

        if (!is_null($xml)) {
            $xpCache = XPath::getXPath($xml);
            $results = XPath::xpQuery($xml, './saml_protocol:Artifact', $xpCache);
            $this->artifact = $results[0]->textContent;
        }
    }

    /**
     * Retrieve the Artifact in this response.
     *
     * @return string artifact.
     */
    public function getArtifact(): string
    {
        return $this->artifact;
    }


    /**
     * Set the artifact that should be included in this response.
     *
     * @param string $artifact
     * @return void
     */
    public function setArtifact(string $artifact): void
    {
        $this->artifact = $artifact;
    }


    /**
     * Convert the response message to an XML element.
     *
     * @return \DOMElement This response.
     */
    public function toUnsignedXML(): DOMElement
    {
        $root = parent::toUnsignedXML();
        $artifactelement = $this->document->createElementNS(Constants::NS_SAMLP, 'Artifact', $this->artifact);
        $root->appendChild($artifactelement);

        return $root;
    }
}
