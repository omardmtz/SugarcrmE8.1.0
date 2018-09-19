!function ($) {
    	$(function() {
    		$( ".row-fluid" ).sortable({handle : '.drag', connectWith: '.row-fluid'});
    		$( ".row-fluid" ).disableSelection();
    	});
}(window.jQuery)