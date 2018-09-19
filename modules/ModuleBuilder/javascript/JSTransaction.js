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

// $Id: JSTransaction.js 26474 2007-09-07 16:28:22Z tyoung $
   
function JSTransaction(){
    this.JSTransactions = new Array();
    this.JSTransactionIndex = 0;
    this.JSTransactionCanRedo = false;
    this.JSTransactionTypes = new Array(); 
    

}

    JSTransaction.prototype.record = function(transaction, data){
        this.JSTransactions[this.JSTransactionIndex] = {'transaction':transaction , 'data':data};
        this.JSTransactionIndex++;
        this.JSTransactionCanRedo = false
    }
    JSTransaction.prototype.register = function(transaction, undo, redo){
        this.JSTransactionTypes[transaction] = {'undo': undo, 'redo':redo};
    }
    JSTransaction.prototype.undo = function(){
        if(this.JSTransactionIndex > 0){
            if(this.JSTransactionIndex > this.JSTransactions.length ){
                this.JSTransactionIndex  = this.JSTransactions.length;
            }
            var transaction = this.JSTransactions[this.JSTransactionIndex - 1];
            var undoFunction = this.JSTransactionTypes[transaction['transaction']]['undo'];
            undoFunction(transaction['data']);
            this.JSTransactionIndex--;
            this.JSTransactionCanRedo = true;
        }
    }
    JSTransaction.prototype.redo = function(){
        if(this.JSTransactionCanRedo && this.JSTransactions.length < 0)this.JSTransactionIndex = 0;
        if(this.JSTransactionCanRedo && this.JSTransactionIndex <= this.JSTransactions.length ){
            this.JSTransactionIndex++;
            var transaction = this.JSTransactions[this.JSTransactionIndex - 1];
            var redoFunction = this.JSTransactionTypes[transaction['transaction']]['redo'];
            redoFunction(transaction['data']);
        }
    }



