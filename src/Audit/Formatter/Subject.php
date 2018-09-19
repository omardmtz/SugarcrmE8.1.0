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
namespace Sugarcrm\Sugarcrm\Audit\Formatter;

use Sugarcrm\Sugarcrm\Audit\Formatter;
use Sugarcrm\Sugarcrm\Security\Subject\Formatter as SubjectFormatter;

class Subject implements Formatter
{
    private $formatter;

    public function __construct(SubjectFormatter $formatter = null)
    {
        $this->formatter = $formatter;
    }

    public function formatRows(array &$rows)
    {
        $subjects = array();
        // gather all subjects
        foreach ($rows as $k => $v) {
            if (!empty($v['source']['subject'])) {
                $subjects[$k] = $v['source']['subject'];
            }
        }

        $formattedSubjects = $this->formatter->formatBatch($subjects);

        // merge formatted subjects into rows
        foreach ($formattedSubjects as $k => $v) {
            $rows[$k]['source']['subject'] = $v;
        }

        return $rows;
    }
}
