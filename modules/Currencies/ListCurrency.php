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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

 class ListCurrency{
	var $focus = null;
	var $list = null;
	var $javascript = '<script>';
    var $recordSaved = false;
     /**
      * Look up the currencies in the system and set the list
      *
      * @param bool $activeOnly     only return active currencies to the list
      */
     public function lookupCurrencies($activeOnly = false)
     {
         $this->focus = BeanFactory::newBean('Currencies');
         $db = DBManagerFactory::getInstance();
         $where = '';
         if ($activeOnly === true) {
             $where = $this->focus->table_name . '.status = ' . $db->quoted('Active');
         }
         $this->list = $this->focus->get_full_list('name', $where);
         $this->focus->retrieve('-99');
         if (is_array($this->list)) {
             $this->list = array_merge(Array($this->focus), $this->list);
         } else {
             $this->list = Array($this->focus);
         }

     }

     /**
      * handle creating or updating a currency record
      *
      */
     function handleAdd()
     {
        global $current_user;

        if($current_user->is_admin)
        {
            if(isset($_POST['edit']) && $_POST['edit'] == 'true' && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['conversion_rate']) && !empty($_POST['conversion_rate']) && isset($_POST['symbol']) && !empty($_POST['symbol']))
            {

                $currency = BeanFactory::newBean('Currencies');
                $isUpdate = false;
                if(isset($_POST['record']) && !empty($_POST['record'])){
                   $isUpdate = true;
                   $currency->retrieve($_POST['record']);
                }
                $currency->name = $_POST['name'];
                $currency->status = $_POST['status'];
                $currency->symbol = $_POST['symbol'];
                $currency->iso4217 = $_POST['iso4217'];
                $previousConversionRate = $currency->conversion_rate;
                $currency->conversion_rate = (string) unformat_number($_POST['conversion_rate']);
                $currency->save();
                $this->focus = $currency;
                // Used to tell calling code that a change was made
                $this->recordSaved = true;

                //Check if the conversion rates changed and, if so, update the rates with a scheduler job
                if($isUpdate && $previousConversionRate != $currency->conversion_rate)
                {
                    global $timedate;
                    // use bean factory here
                    $job = BeanFactory::newBean('SchedulersJobs');
                    $job->name = "SugarJobUpdateCurrencyRates: " . $timedate->getNow()->asDb();
                    $job->target = "class::SugarJobUpdateCurrencyRates";
                    $job->data = $currency->id;
                    $job->retry_count = 0;
                    $job->assigned_user_id = $current_user->id;
                    $jobQueue = new SugarJobQueue();
                    $jobQueue->submitJob($job);
                }
            }
        }
		
	}
		
	function handleUpdate() {
		global $current_user;
			if($current_user->is_admin) {
				if(isset($_POST['id']) && !empty($_POST['id'])&&isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['rate']) && !empty($_POST['rate']) && isset($_POST['symbol']) && !empty($_POST['symbol'])){
			$ids = $_POST['id'];
			$names= $_POST['name'];
			$symbols= $_POST['symbol'];
			$rates  = $_POST['rate'];
			$isos  = $_POST['iso'];
			$size = sizeof($ids);
			if($size != sizeof($names)|| $size != sizeof($isos) || $size != sizeof($symbols) || $size != sizeof($rates)){
				return;	
			}
			
				$temp = BeanFactory::newBean('Currencies');
			for($i = 0; $i < $size; $i++){
				$temp->id = $ids[$i];
				$temp->name = $names[$i];
				$temp->symbol = $symbols[$i];
				$temp->iso4217 = $isos[$i];
				$temp->conversion_rate = $rates[$i];
				$temp->save();
			}
	}}
	}

	function getJavascript(){
		// wp: DO NOT add formatting and unformatting numbers in here, add them prior to calling these to avoid double calling
		// of unformat number
		return $this->javascript . <<<EOQ
					function get_rate(id){
						return ConversionRates[id];
					}
					function ConvertToDollar(amount, rate){
						return amount / rate;
					}
					function ConvertFromDollar(amount, rate){
						return amount * rate;
					}
					function ConvertRate(id,fields){
							for(var i = 0; i < fields.length; i++){
								fields[i].value = toDecimal(ConvertFromDollar(toDecimal(ConvertToDollar(toDecimal(fields[i].value), lastRate)), ConversionRates[id]));
							}
							lastRate = ConversionRates[id];
						}
					function ConvertRateSingle(id,field){
						var temp = field.innerHTML.substring(1, field.innerHTML.length);
						unformattedNumber = unformatNumber(temp, num_grp_sep, dec_sep);

						field.innerHTML = CurrencySymbols[id] + formatNumber(toDecimal(ConvertFromDollar(ConvertToDollar(unformattedNumber, lastRate), ConversionRates[id])), num_grp_sep, dec_sep, 2, 2);
						lastRate = ConversionRates[id];
					}
					function CurrencyConvertAll(form){
                        try {
                        var id = form.currency_id.options[form.currency_id.selectedIndex].value;
						var fields = new Array();

						for(i in currencyFields){
							var field = currencyFields[i];
							if(typeof(form[field]) != 'undefined' && form[field].value.length > 0){
								form[field].value = unformatNumber(form[field].value, num_grp_sep, dec_sep);
								fields.push(form[field]);
							}

						}

							ConvertRate(id, fields);
						for(i in fields){
							fields[i].value = formatNumber(fields[i].value, num_grp_sep, dec_sep);

						}

						} catch (err) {
                            // Do nothing, if we can't find the currency_id field we will just not attempt to convert currencies
                            // This typically only happens in lead conversion and quick creates, where the currency_id field may be named somethnig else or hidden deep inside a sub-form.
                        }

					}
				</script>
EOQ;
	}


	function getSelectOptions($id = '', $base_rate = null){
		global $current_user;
		$this->javascript .="var ConversionRates = new Array(); \n";
		$this->javascript .="var CurrencySymbols = new Array(); \n";
		$options = '';
		$this->lookupCurrencies();
		$setLastRate = false;
		if(isset($this->list ) && !empty($this->list )){
		foreach ($this->list as $data){
			if($data->status == 'Active'){
				$rate = $data->conversion_rate;
				if($id == $data->id){
					$options .= '<option value="'. $data->id . '" selected>';
					$setLastRate = true;

					// if a rate was passed in, this means the record has a locked rate and shouldn't be changed,
					/// when the currency is the same.
					if (!is_null($base_rate)) {
						$rate = $base_rate;
					}

					$this->javascript .= 'var lastRate = "' . $rate . '";';
				} else {
					$options .= '<option value="'. $data->id . '">'	;
				}
				$options .= $data->name . ' : ' . $data->symbol;
			$this->javascript .=" ConversionRates['".$data->id."'] = '". $rate ."';\n";
			$this->javascript .=" CurrencySymbols['".$data->id."'] = '".$data->symbol."';\n";
		}}
		if(!$setLastRate){
			$this->javascript .= 'var lastRate = "1";';
		}

	}
	return $options;
	}
	function getTable(){
		$this->lookupCurrencies();
		$usdollar = translate('LBL_US_DOLLAR');
		$currency = translate('LBL_CURRENCY');
		$currency_sym = $sugar_config['default_currency_symbol'];
		$conv_rate = translate('LBL_CONVERSION_RATE');
		$add = translate('LBL_ADD');
		$delete = translate('LBL_DELETE');
		$update = translate('LBL_UPDATE');
        $module = InputValidation::getService()->getValidInputRequest('module', 'Assert\Mvc\ModuleName', '');
        $action = InputValidation::getService()->getValidInputRequest('action');

		$form = $html = "<br><table cellpadding='0' cellspacing='0' border='0'  class='tabForm'><tr><td><tableborder='0' cellspacing='0' cellpadding='0'>";
		$form .= <<<EOQ
					<form name='DeleteCurrency' action='index.php' method='post'>
                        <input type='hidden' name='action' value='{$action}'>
		                <input type='hidden' name='module' value='{$module}'>
		                <input type='hidden' name='deleteCur' value=''>
                    </form>

					<tr><td><B>$currency</B></td><td><B>ISO 4217</B>&nbsp;</td><td><B>$currency_sym</B></td><td colspan='2'><B>$conv_rate</B></td></tr>
					<tr><td>$usdollar</td><td>USD</td><td>$</td><td colspan='2'>1</td></tr>
					<form name="UpdateCurrency" action="index.php" method="post">
                        <input type='hidden' name='action' value='{$action}'>
					    <input type='hidden' name='module' value='{$module}'>
EOQ;
		if(isset($this->list ) && !empty($this->list )){
		foreach ($this->list as $data){

			$form .= '<tr><td>'.$data->iso4217. '<input type="hidden" name="iso[]" value="'.$data->iso4217.'"></td><td><input type="hidden" name="id[]" value="'.$data->id.'">'.$data->name. '<input type="hidden" name="name[]" value="'.$data->name.'"></td><td>'.$data->symbol. '<input type="hidden" name="symbol[]" value="'.$data->symbol.'"></td><td>'.$data->conversion_rate.'&nbsp;</td><td><input type="text" name="rate[]" value="'.$data->conversion_rate.'"><td>&nbsp;<input type="button" name="delete" class="button" value="'.$delete.'" onclick="document.forms[\'DeleteCurrency\'].deleteCur.value=\''.$data->id.'\';document.forms[\'DeleteCurrency\'].submit();"> </td></tr>';
		}
		}
		$form .= <<<EOQ
					<tr><td></td><td></td><td></td><td></td><td></td><td>&nbsp;<input type='submit' name='Update' value='$update' class='button'></TD></form> </td></tr>
					<tr><td colspan='3'><br></td></tr>
					<form name="AddCurrency" action="index.php" method="post">
					<input type='hidden' name='action' value='{$action}'>
					<input type='hidden' name='module' value='{$module}'>
					<tr><td><input type = 'text' name='addname' value=''>&nbsp;</td><td><input type = 'text' name='addiso' size='3' maxlength='3' value=''>&nbsp;</td><td><input type = 'text' name='addsymbol' value=''></td><td colspan='2'>&nbsp;<input type ='text' name='addrate'></td><td>&nbsp;<input type='submit' name='Add' value='$add' class='button'></td></tr>
					</form></table></td></tr></table>
EOQ;
	return $form;

	}

	function setCurrencyFields($fields){
		$json = getJSONobj();
		$this->javascript .= 'var currencyFields = ' . $json->encode($fields) . ";\n";
	}


}

