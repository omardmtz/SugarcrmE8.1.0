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
/**
 * Change locked fields to be a relate collection
 */
class SugarUpgradeLockedFieldsToRelateCollection extends UpgradeScript
{
    public $order = 9011;
    public $type = self::UPGRADE_DB;

    /**
     * Process Definition bean
     * @var pmse_BpmProcessDefinition
     */
    public $pd;

    /**
     * Flow Bean
     * @var pmse_BpmFlow
     */
    public $flow;

    /**
     * The DB fields we need to work on. These are aliases to actual fields.
     * @var array
     */
    protected $dbFields = [
        'mod' => 'bean_module',
        'id' => 'bean_id',
        'pd' => 'pd_id',
    ];

    public function run()
    {
        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            // Get the assembled SugarQuery object
            $q = $this->getAssembledQuery();

            // Execute the query to get our rows now
            $rows = $q->execute('array', true);

            // Loop through and add the relationship
            foreach ($rows as $row) {
                // Load the target record
                $recordBean = BeanFactory::getBean(
                    $row[$this->dbFields['mod']],
                    $row[$this->dbFields['id']]
                );

                // Load the correct process definition
                $this->pd->retrieve($row[$this->dbFields['pd']]);

                // Now try to load the relationship
                if ($recordBean->load_relationship('locked_fields_link')) {
                    // And handle errors as needed if adding doesn't work
                    if (!$recordBean->locked_fields_link->add($this->pd)) {
                        // Get our log message
                        $msg = $this->getLogMessage($recordBean);

                        // And log it
                        $this->log($msg);
                    }
                }
            }
        }
    }

    /**
     * Gets a SugarQuery object for use in getting our records that are needed
     *
     * @return SugarQuery
     */
    public function getAssembledQuery()
    {
        // We need this as the primary bean for the query
        $this->setPD();

        // We need this to join on for the record check
        $this->setFlow();

        //Grab all records that have locked fields
        $q = new \SugarQuery();

        // Set the FROM part first so it sets the bean to get data from
        $q->from($this->pd, array('alias' => 'pd'));

        // Set the JOIN second so it sets the join alias and join table
        $q->joinTable($this->flow->getTableName(), array('alias' => 'flow'))
            ->on()
            ->equalsField('flow.pro_id', 'pd.id');

        // Set the WHERE now so that everything can be assembled properly
        $q->where()
            ->notIn('flow.cas_flow_status', array('CLOSED', 'TERMINATED'))
            ->isNotEmpty('pd.pro_locked_variables');

        // Lastly set the SELECT fields now that everything is set up
        $q->select(
            array(
                array('pd.id', $this->dbFields['pd']),
                array('flow.cas_sugar_object_id', $this->dbFields['id']),
                array('flow.cas_sugar_module', $this->dbFields['mod']),
            )
        );

        return $q;
    }

    /**
     * Sets the PD object on this object
     *
     * @param string $beanName The name of the bean to load
     */
    public function setPD($beanName = 'pmse_BpmProcessDefinition')
    {
        $this->pd = \BeanFactory::newBean($beanName);
    }

    /**
     * Sets the flow object on this object
     *
     * @param string $beanName The name of the bean to load
     */
    public function setFlow($beanName = 'pmse_BpmFlow')
    {
        $this->flow = \BeanFactory::newBean($beanName);
    }

    /**
     * Gets the message to log in case of failure
     * @param SugarBean $bean
     * @return string
     */
    public function getLogMessage(SugarBean $bean)
    {
        return sprintf(
            'Failed to create relationship for record: %s pd: %s',
            $bean->id,
            $this->pd->id
        );
    }
}
