<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\IdentityProvider\Srn;

/**
 * Class Converter
 * Converts Srn string to object and vice versa
 */
class Converter
{
    /**
     * Convert string to srn object
     * @param string $srnString
     *
     * @return Srn
     */
    public static function fromString($srnString)
    {
        if (strlen($srnString) > SrnRules::MAX_LENGTH) {
            throw new \InvalidArgumentException('SRN is too long');
        }

        $components = explode(SrnRules::SEPARATOR, $srnString);
        if (count($components) < SrnRules::MIN_COMPONENTS) {
            throw new \InvalidArgumentException('Invalid number of components in SRN');
        }

        if ($components[0] != SrnRules::SCHEME) {
            throw new \InvalidArgumentException(
                sprintf('Invalid scheme, must start with "%s"', SrnRules::SCHEME)
            );
        }

        if (empty($components[1])) {
            throw new \InvalidArgumentException('Partition cannot be empty');
        }

        if (empty($components[2])) {
            throw new \InvalidArgumentException('Service cannot be empty');
        }

        foreach ($components as $component) {
            if (!preg_match(SrnRules::ALLOWED_CHARS, $component)) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid component characters, only allow "%s"', SrnRules::ALLOWED_CHARS)
                );
            }
        }

        if (!preg_match(SrnRules::TENANT_REGEX, $components[4])) {
            throw new \InvalidArgumentException('Invalid tenant id');
        }
        $components[4] = str_pad($components[4], SrnRules::TENANT_LENGTH, '0', STR_PAD_LEFT);

        $srn = new Srn();
        return $srn->setPartition($components[1])
            ->setService($components[2])
            ->setRegion($components[3])
            ->setTenantId($components[4])
            ->setResource(array_slice($components, 5));
    }

    /**
     * Convert Srn object to Srn string.
     *
     * @param Srn $srn
     * @return string
     */
    public static function toString(Srn $srn)
    {
        if (empty($srn->getPartition())) {
            throw new \InvalidArgumentException('Partition is invalid');
        }

        if (empty($srn->getService())) {
            throw new \InvalidArgumentException('Service is invalid');
        }

        if (empty($srn->getResource())) {
            throw new \InvalidArgumentException('Resource type is invalid');
        }

        return sprintf(
            '%s:%s:%s:%s:%s:%s',
            SrnRules::SCHEME,
            $srn->getPartition(),
            $srn->getService(),
            $srn->getRegion(),
            $srn->getTenantId(),
            implode(SrnRules::SEPARATOR, $srn->getResource())
        );
    }
}
