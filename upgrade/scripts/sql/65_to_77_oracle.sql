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

ALTER TABLE config add platform varchar2(32) NULL;
ALTER TABLE meetings ADD (duration_minutes_chr varchar2(4));
UPDATE meetings SET duration_minutes_chr = to_char(duration_minutes);
UPDATE meetings SET duration_minutes = NULL;
ALTER TABLE meetings MODIFY duration_minutes varchar2(4);
UPDATE meetings SET duration_minutes = duration_minutes_chr;
ALTER TABLE meetings DROP COLUMN duration_minutes_chr;

ALTER TABLE currencies RENAME COLUMN conversion_rate TO conversion_rate_old;
ALTER TABLE currencies ADD conversion_rate NUMBER(20,2);
UPDATE currencies SET conversion_rate = conversion_rate_old;
ALTER TABLE currencies DROP COLUMN conversion_rate_old;

ALTER TABLE forecasts RENAME COLUMN best_case TO best_case_old;
ALTER TABLE forecasts ADD best_case NUMBER(26,6);
UPDATE forecasts SET best_case = best_case_old;
ALTER TABLE forecasts DROP COLUMN best_case_old;

ALTER TABLE forecasts RENAME COLUMN likely_case TO likely_case_old;
ALTER TABLE forecasts ADD likely_case NUMBER(26,6);
UPDATE forecasts SET likely_case = likely_case_old;
ALTER TABLE forecasts DROP COLUMN likely_case_old;

ALTER TABLE forecasts RENAME COLUMN worst_case TO worst_case_old;
ALTER TABLE forecasts ADD worst_case NUMBER(26,6);
UPDATE forecasts SET worst_case = worst_case_old;
ALTER TABLE forecasts DROP COLUMN worst_case_old;

ALTER TABLE opportunities RENAME COLUMN amount TO amount_old;
ALTER TABLE opportunities ADD amount NUMBER(26,6);
UPDATE opportunities SET amount = amount_old;
ALTER TABLE opportunities DROP COLUMN amount_old;

ALTER TABLE opportunities RENAME COLUMN amount_usdollar TO amount_usdollar_old;
ALTER TABLE opportunities ADD amount_usdollar NUMBER(26,6);
UPDATE opportunities SET amount_usdollar = amount_usdollar_old;
ALTER TABLE opportunities DROP COLUMN amount_usdollar_old;

ALTER TABLE products RENAME COLUMN quantity TO quantity_old;
ALTER TABLE products ADD quantity NUMBER(20,2);
UPDATE products SET quantity = quantity_old;
ALTER TABLE products DROP COLUMN quantity_old;

ALTER TABLE quotas RENAME COLUMN amount TO amount_old;
ALTER TABLE quotas ADD amount NUMBER(26,6);
UPDATE quotas SET amount = amount_old;
ALTER TABLE quotas DROP COLUMN amount_old;

ALTER TABLE quotas RENAME COLUMN amount_base_currency TO amount_base_currency_old;
ALTER TABLE quotas ADD amount_base_currency NUMBER(26,6);
UPDATE quotas SET amount_base_currency = amount_base_currency_old;
ALTER TABLE quotas DROP COLUMN amount_base_currency_old;
