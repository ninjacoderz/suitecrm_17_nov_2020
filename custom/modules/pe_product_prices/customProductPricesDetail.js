$(document).ready(function() {
    'use strict';
    //Clone button Duplicate
    $('#tab-actions').parent().append($('#tab-actions li:nth-child(2)').clone()); 
    //show/hide field website
    showFieldWebsite();
});


///////***** FUNCTION DECLARE *****//////////////////////

function showFieldWebsite() {
    let price_src = $(document).find('#pricing_source').val();
    if (price_src == 'website') {
      $(document).find('#website').closest('.detail-view-row-item').show();
    } else {
      $(document).find('#website').closest('.detail-view-row-item').hide();
    }
  }
  