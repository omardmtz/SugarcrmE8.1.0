<?php declare(strict_types=1);
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

namespace Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Field;

use SugarBean;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\Field;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\FieldList;

/**
 * Represents an email field
 */
final class Email implements Field
{
    /**
     * @var string
     */
    private $id;

    /**
     * Constructor
     *
     * @param string $id The ID of the email to be erased
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'field_name' => 'email',
            'id' => $this->id,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function erase(SugarBean $bean) : void
    {
        $emailBean = \BeanFactory::getBean('EmailAddresses', $this->id);

        // handle all related beans contains this email
        $relatedBeans = $this->getAllRelatedBeans($emailBean);

        if (!empty($relatedBeans)) {
            foreach ($relatedBeans as $relBean) {
                if (!empty($relBean->emailAddress)) {
                    $relBean->emailAddress->removeAddressById($this->id);
                    $relBean->emailAddress->removeLegacyAddressForBean($relBean, $emailBean->email_address);
                    $relBean->emailAddress->save($relBean->id, $relBean->module_dir);
                    // this $bean will be saved late
                    if ($relBean->id != $bean->id) {
                        $relBean->save();
                    }
                }
            }
        }

        // erase this email
        $emailBean->erase(FieldList::fromArray(['email_address', 'email_address_caps']), false);
    }

    /**
     * to get all related beans to this email id
     * @param SugarBean $emailBean
     * @return array
     */
    protected function getAllRelatedBeans($emailBean)
    {
        return $emailBean->getRelatedBeansById($emailBean->id);
    }
}
