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
//Singleton
var FieldPanelItemFactory = (function () {
	var products = {
		"button": FieldPanelButton,
		"buttongroup": FieldPanelButtonGroup,
		"list": ListPanel,
		"form": FormPanel,
		"multiple": MultipleCollapsiblePanel,
		"item_container": ItemContainer
	};
	return {
		hasProduct: function (productName) {
			return !!products[productName];
		},
		canProduce: function (productClass) {
			var key;
			for (key in products) {
				if(products.hasOwnProperty(key)) {
					if(products[key] === productClass) {
						return true;
					}
				}
			}
			return false;
		},
		isProduct: function(productObject) {
			var key;
			for (key in products) {
				if(products.hasOwnProperty(key)) {
					if(productObject instanceof products[key]) {
						return true;	
					}
				}
			}
			return false;	
		},
		make: function(settings) {
			var productName = settings.type, Constructor;
			if(!this.hasProduct(productName)) {
				throw new Error("make(): The product \"" + productName + "\" can't be produced by this factory.");
			}
			Constructor = products[productName];
			return new Constructor(settings);
		}
	};
}());