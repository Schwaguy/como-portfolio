// Checkbox Show/Hide Div	
jQuery(document).ready(function($){
	"use strict";
    //Register click events to all checkboxes inside question element
    $(document).on('click', '.social-toggle', function() {

        //Find the next answer element to the question and based on the checked status call either show or hide method
        var details = $(this).closest('.social-feed').children('.feed-info');

        if(this.checked){
            details.show(300);
        } else {
            details.hide(300);
        }
    });
});