<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2\XML\md;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\SAML2\Constants as C;
use SimpleSAML\SAML2\Utils\XPath;
use SimpleSAML\XML\Exception\MissingElementException;
use SimpleSAML\XML\Utils as XMLUtils;

/**
 * Class representing SAML 2 metadata AuthnAuthorityDescriptor.
 *
 * @package SimpleSAMLphp
 */
class AuthnAuthorityDescriptor extends RoleDescriptor
{
    /**
     * List of AuthnQueryService endpoints.
     *
     * Array with EndpointType objects.
     *
     * @var \SimpleSAML\SAML2\XML\md\EndpointType[]
     */
    private array $AuthnQueryService = [];

    /**
     * List of AssertionIDRequestService endpoints.
     *
     * Array with EndpointType objects.
     *
     * @var \SimpleSAML\SAML2\XML\md\EndpointType[]
     */
    private array $AssertionIDRequestService = [];

    /**
     * List of supported NameID formats.
     *
     * Array of strings.
     *
     * @var string[]
     */
    private array $NameIDFormat = [];


    /**
     * Initialize an IDPSSODescriptor.
     *
     * @param \DOMElement|null $xml The XML element we should load.
     * @throws \Exception
     */
    public function __construct(DOMElement $xml = null)
    {
        parent::__construct('md:AuthnAuthorityDescriptor', $xml);

        if ($xml === null) {
            return;
        }

        $xpCache = XPath::getXPath($xml);

        /** @var \DOMElement $ep */
        foreach (XPath::xpQuery($xml, './saml_metadata:AuthnQueryService', $xpCache) as $ep) {
            $this->addAuthnQueryService(new EndpointType($ep));
        }
        if ($this->getAuthnQueryService() === []) {
            throw new MissingElementException('Must have at least one AuthnQueryService in AuthnAuthorityDescriptor.');
        }

        /** @var \DOMElement $ep */
        foreach (XPath::xpQuery($xml, './saml_metadata:AssertionIDRequestService', $xpCache) as $ep) {
            $this->addAssertionIDRequestService(new EndpointType($ep));
        }

        $this->setNameIDFormat(XMLUtils::extractStrings($xml, C::NS_MD, 'NameIDFormat'));
    }


    /**
     * Collect the value of the AuthnQueryService-property
     *
     * @return \SimpleSAML\SAML2\XML\md\EndpointType[]
     */
    public function getAuthnQueryService(): array
    {
        return $this->AuthnQueryService;
    }


    /**
     * Set the value of the AuthnQueryService-property
     *
     * @param \SimpleSAML\SAML2\XML\md\EndpointType[] $authnQueryService
     * @return void
     */
    public function setAuthnQueryService(array $authnQueryService): void
    {
        $this->AuthnQueryService = $authnQueryService;
    }


    /**
     * Add the value to the AuthnQueryService-property
     *
     * @param \SimpleSAML\SAML2\XML\md\EndpointType $authnQueryService
     * @return void
     */
    public function addAuthnQueryService(EndpointType $authnQueryService): void
    {
        $this->AuthnQueryService[] = $authnQueryService;
    }


    /**
     * Collect the value of the AssertionIDRequestService-property
     *
     * @return \SimpleSAML\SAML2\XML\md\EndpointType[]
     */
    public function getAssertionIDRequestService(): array
    {
        return $this->AssertionIDRequestService;
    }


    /**
     * Set the value of the AssertionIDRequestService-property
     *
     * @param \SimpleSAML\SAML2\XML\md\EndpointType[] $assertionIDRequestService
     * @return void
     */
    public function setAssertionIDRequestService(array $assertionIDRequestService): void
    {
        $this->AssertionIDRequestService = $assertionIDRequestService;
    }


    /**
     * Add the value to the AssertionIDRequestService-property
     *
     * @param \SimpleSAML\SAML2\XML\md\EndpointType $assertionIDRequestService
     * @return void
     */
    public function addAssertionIDRequestService(EndpointType $assertionIDRequestService): void
    {
        $this->AssertionIDRequestService[] = $assertionIDRequestService;
    }


    /**
     * Collect the value of the NameIDFormat-property
     *
     * @return string[]
     */
    public function getNameIDFormat(): array
    {
        return $this->NameIDFormat;
    }


    /**
     * Set the value of the NameIDFormat-property
     *
     * @param string[] $nameIDFormat
     * @return void
     */
    public function setNameIDFormat(array $nameIDFormat): void
    {
        $this->NameIDFormat = $nameIDFormat;
    }


    /**
     * Add this IDPSSODescriptor to an EntityDescriptor.
     *
     * @param \DOMElement $parent The EntityDescriptor we should append this AuthnAuthorityDescriptor to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent): DOMElement
    {
        Assert::notEmpty($this->AuthnQueryService);

        $e = parent::toXML($parent);

        foreach ($this->AuthnQueryService as $ep) {
            $ep->toXML($e, 'md:AuthnQueryService');
        }

        foreach ($this->AssertionIDRequestService as $ep) {
            $ep->toXML($e, 'md:AssertionIDRequestService');
        }

        XMLUtils::addStrings($e, C::NS_MD, 'md:NameIDFormat', false, $this->NameIDFormat);

        return $e;
    }
}
