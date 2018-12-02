$(document).ready(function(){    
  $("#edit-search").blur(function(){
    if ($("#edit-search").val().length > 0 && $("#edit-search").val() > 0) {
      invoice_get_customer_info($("#edit-search").val());
    }
    else if ($("#edit-search").val().length < 1) {
      invoice_get_customer_info('set_empty');
    }
  });
});

function invoice_set_template(value) {
  $.get("http://"+Drupal.settings['invoice']['host']+Drupal.settings['basePath']+"invoice/set/template",
    { value: value
    },
    function(data) {    
      if (data['error'] != undefined && data['error'] != '') {        
        alert(data['error']);
      }
      else {
        $('#edit-vat').val(data['vat']);
      }
    },
    "json"
  );
}

function invoice_get_customer_info(value) {
  $.get("http://"+Drupal.settings['invoice']['host']+Drupal.settings['basePath']+"invoice/get/customer_info",
    { value: value
    },
    function(data) {    
      if (data['error'] != undefined && data['error'] != '') {        
        alert(data['error']);
      }
      else if (value == 'set_empty' || (data['set_empty'] != undefined && data['set_empty'] != '')) {
        $('#edit-company-name').val('');
        $('#edit-firstname').val('');
        $('#edit-lastname').val('');
        $('#edit-street').val('');
        $('#edit-building-number').val('');
        $('#edit-zipcode').val('');
        $('#edit-city').val('');
        $('#edit-country').val('');
        $('#edit-customer-description').val('');
        $('#edit-coc-number').val('');
        $('#edit-vat-number').val('');
      }
      else {
        $('#edit-search').val(data['search_customer']);
        $('#edit-company-name').val(data['company_name']);
        $('#edit-firstname').val(data['firstname']);
        $('#edit-lastname').val(data['lastname']);
        $('#edit-street').val(data['street']);
        $('#edit-building-number').val(data['building_number']);
        $('#edit-zipcode').val(data['zipcode']);
        $('#edit-city').val(data['city']);
        $('#edit-country').val(data['country']);
        $('#edit-coc-number').val(data['coc_number']);
        $('#edit-vat-number').val(data['vat_number']);
        if (window.mceToggle) { mceToggle('edit-customer-description', 'wysiwyg4customer-description'); }
        $('#edit-customer-description').val(data['description']);
        if (window.mceToggle) { mceToggle('edit-customer-description', 'wysiwyg4customer-description'); }
      }
    },
    "json"
  );
}

function invoice_save_item(value) {
  $.post("http://"+Drupal.settings['invoice']['host']+Drupal.settings['basePath']+"invoice/save/item",
    { iid: $("#edit-iid").val(),
      invoice_number: $("#edit-invoice-number").val(),
      description: $("#edit-description").val(),
      quantity: $("#edit-quantity").val(),
      price_without_vat: $("#edit-price-without-vat").val(),
      price_with_vat: $("#edit-price-with-vat").val(),
      vat: $("#edit-vat").val()
    },
    function(data) {    
      if (data['error'] != undefined && data['error'] != '') {        
        alert(data['error']);
      }
      else {
        if (data['remove_empty_row'] != undefined && data['remove_empty_row'] != '') {
          $('.invoice-items-empty').remove();
        }
                
        // if invoice item id is not empty we just changed an invoice item, so update the row
        if (data['iid'] != undefined && data['iid'] != '') {
          $('.invoice-items .item-'+data['iid']+' td:nth-child(1)').html(data['description']);
          $('.invoice-items .item-'+data['iid']+' td:nth-child(2)').html(data['vat']);
          $('.invoice-items .item-'+data['iid']+' td:nth-child(3)').html(data['quantity']);
          $('.invoice-items .item-'+data['iid']+' td:nth-child(4)').html(data['exunitcost']);
          $('.invoice-items .item-'+data['iid']+' td:nth-child(5)').html(data['incunitcost']);
          $('.invoice-items .item-'+data['iid']+' td:nth-child(6)').html(data['exsubtotal']);
          $('.invoice-items .item-'+data['iid']+' td:nth-child(7)').html(data['incsubtotal']);
        }
        else {
          $('.invoice-items table').append(data['content']);
        }

        // reset invoice item form
        $('#edit-iid').val('');
        $('#edit-description').val('');
        $('#edit-quantity').val('');
        $('#edit-price-without-vat').val('');
        $('#edit-price-with-vat').val('');
        $('#edit-vat').val(data['activevat']);
        $('#button-save-item').val(data['actionvalue']);
        
        // set new totals
        $('.invoice-items .extotal').html(data['extotal']);
        $('.invoice-items .inctotal').html(data['inctotal']);
      }
    },
    "json"
  );
}

function invoice_edit_item(value) {
  $.get("http://"+Drupal.settings['invoice']['host']+Drupal.settings['basePath']+"invoice/edit/item",
    { iid: value,
      invoice_number: $("#edit-invoice-number").val()
    },
    function(data) {    
      if (data['error'] != undefined && data['error'] != '') {        
        alert(data['error']);
      }
      else {
        $('#edit-iid').val(value);
        $('#edit-description').val(data['description']);
        $('#edit-vat').val(data['vat']);
        $('#edit-quantity').val(data['quantity']);
        $('#edit-price-without-vat').val(data['exunitcost']);
        $('#edit-price-with-vat').val(data['incunitcost']);
        $('#button-save-item').val(data['actionvalue']);
      }
    },
    "json"
  );
}

function invoice_delete_item(value) {
  $.get("http://"+Drupal.settings['invoice']['host']+Drupal.settings['basePath']+"invoice/delete/item",
    { iid: value,
      invoice_number: $("#edit-invoice-number").val()
    },
    function(data) {    
      if (data['error'] != undefined && data['error'] != '') {        
        alert(data['error']);
      }
      else {
        $('.invoice-items .item-'+value).remove();
        $('.invoice-items .extotal').html(data['extotal']);
        $('.invoice-items .inctotal').html(data['inctotal']);
      }
    },
    "json"
  );
}