<?php

declare(strict_types=1);

namespace SimpleSAML\SAML2\XML\md;

use DOMElement;
use SimpleSAML\XML\Chunk;

/**
 * Class representing unknown RoleDescriptors.
 *
 * @package SimpleSAMLphp
 */
class UnknownRoleDescriptor extends RoleDescriptor
{
    /**
     * This RoleDescriptor as XML
     *
     * @var \SimpleSAML\XML\Chunk
     */
    private Chunk $xml;


    /**
     * Initialize an unknown RoleDescriptor.
     *
     * @param \DOMElement $xml The XML element we should load.
     */
    public function __construct(DOMElement $xml)
    {
        parent::__construct('md:RoleDescriptor', $xml);

        $this->xml = new Chunk($xml);
    }


    /**
     * Add this RoleDescriptor to an EntityDescriptor.
     *
     * @param \DOMElement $parent The EntityDescriptor we should append this RoleDescriptor to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent): DOMElement
    {
        return $this->xml->toXML($parent);
    }
}
