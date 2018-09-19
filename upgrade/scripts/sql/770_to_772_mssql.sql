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

UPDATE calls SET parent_id = NULL from kbcontents s join calls m on s.id = m.parent_id AND s.deleted = 1 AND m.parent_type = 'KBContents';
UPDATE tasks SET parent_id = NULL from kbcontents s join tasks m on s.id = m.parent_id AND s.deleted = 1 AND m.parent_type = 'KBContents';
UPDATE meetings SET parent_id = NULL from kbcontents s join meetings m on s.id = m.parent_id AND s.deleted = 1 AND m.parent_type = 'KBContents';
