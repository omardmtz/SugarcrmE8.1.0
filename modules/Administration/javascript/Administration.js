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
// defense
if(typeof(SUGAR) == 'undefined') {
	var SUGAR = {};
}

SUGAR.Administration = {
	/**
	 * calls modules/Administration/Async.php with JSON objects
	 */
	Async : {
	},

	/**
	 * Utility functions for RepairXSS screen
	 * @param HTMLSelectObject select dropdown
	 */
	RepairXSS : {
		toRepair : new Object, // assoc array of items to be cleaned
		currentRepairObject : "", // bean currently worked on
		currentRepairIds : new Array(), // array of ids for above bean
		repairedCount : 0,
		numberToFix: 25, // how many IDs to send at once from client

		/**
		 * Calculates how many rows to iterate through
		 */
		refreshEstimate : function(select) {
			this.toRepair = new Object();
			this.repairedCount = 0;

			var button = document.getElementById('repairXssButton');
			var selected = select.value;
			var totalDisplay = document.getElementById('repairXssDisplay');
			var counter = document.getElementById('repairXssCount');
			var repaired = document.getElementById('repairXssResults');
			var repairedCounter = document.getElementById('repairXssResultCount');

			if(selected != "0") {
				button.style.display = 'inline';
				repairedCounter.value = 0;
				AjaxObject.startRequest(callbackRepairXssRefreshEstimate, "&adminAction=refreshEstimate&bean=" + selected + '&csrf_token=' + SUGAR.csrf.form_token);
			} else {
				button.style.display = 'none';
				totalDisplay.style.display = 'none';
				repaired.style.display = 'none';
				counter.value = 0;
				repaired.value= 0;
			}
		},

		/**
		 * Takes selection and executes repair function
		 */
		executeRepair : function() {
			if(this.toRepair) {
				// if queue is empty load next
				if(this.currentRepairIds.length < 1) {
					if(!this.loadRepairQueue()) {
						alert(done);
						return; // we're done
					}
				}

				var beanIds = new Array();

				for(var i=0; i<this.numberToFix; i++) {
					if(this.currentRepairIds.length > 0) {
						beanIds.push(this.currentRepairIds.pop());
					}
				}

				var beanId = YAHOO.lang.JSON.stringify(beanIds);
				AjaxObject.startRequest(callbackRepairXssExecute, "&adminAction=repairXssExecute&bean=" + this.currentRepairObject + "&id=" + beanId + '&csrf_token=' + SUGAR.csrf.form_token);
			}
		},

		/**
		 * Loads the bean name and array of bean ids for repair
		 * @return bool False if load did not occur
		 */
		loadRepairQueue : function() {
			var loaded = false;

			this.currentRepairObject = '';
			this.currentRepairIds = new Array();

			for(var bean in this.toRepair) {
				if(this.toRepair[bean].length > 0) {
					this.currentRepairObject = bean;
					this.currentRepairIds = this.toRepair[bean];
					loaded = true;
				}
			}

			// 'unset' the IDs array so we don't iterate over it again
			this.toRepair[this.currentRepairObject] = new Array();

			return loaded;
		}
	}
}