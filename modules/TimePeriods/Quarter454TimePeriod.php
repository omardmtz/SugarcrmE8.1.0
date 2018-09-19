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
 * Implements the fiscal quarter representation of a time period where the monthly
 * leaves are split by the longest month occurring in the middle month
 * @api
 */
class Quarter454TimePeriod extends TimePeriod implements TimePeriodInterface {
    /**
     * constructor override
     *
     * @param null $start_date date string to set the start date of the quarter time period
     */
    public function __construct($start_date = null) {
        parent::__construct();
        //set defaults
        $this->type = 'Quarter454';
        $this->is_fiscal = true;
        $this->date_modifier = '13 week';
    }

    /**
     * build leaves for the timeperiod by creating the specified types of timeperiods
     *
     * @return mixed
     */
    public function buildLeaves($shownBackwardDifference, $shownForwardDifference)
    {
        if($this->hasLeaves()) {
            throw new Exception("This TimePeriod already has leaves");
        }

        $this->load_relationship('related_timeperiods');

        //1st month leaf
        $leafPeriod = BeanFactory::newBean('MonthTimePeriods');
        $leafPeriod->is_fiscal = true;
        $leafPeriod->setStartDate($this->start_date, 4);
        $leafPeriod->save();
        $this->related_timeperiods->add($leafPeriod->id);

        //create second month leaf it gets the extra week
        $leafPeriod = $leafPeriod->createNextTimePeriod(5);
        $this->related_timeperiods->add($leafPeriod->id);

        //create third month leaf
        $leafPeriod = $leafPeriod->createNextTimePeriod(4);
        $this->related_timeperiods->add($leafPeriod->id);

    }
}