var fp_site_url = window.location.href;
var fp_menusLoaded = new Array();
var fp_restListComplete = new Array();
var fp = {
    ui: {
        loadUI: function(options) {
            var settings = {
                ui: 'default'
            };
            if (options) {
                $.extend(settings, options);
            }

            $(document).on('click', '#register', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                fp.ui.buildDialog({
                    appendTo: '.mainCont',
                    classes: 'register',
                    height: 769,
                    width: 553
                });
                $('.register').find('.dialog').load(site_url + '/register/reg_form_ui', function() {
                    //If this is the checkout screen, change the message at the top of the form.
                    if (settings.ui == "checkout") {
                        $('.dialog p').first().html("Please verify your information and consider registering with us.");
                        $('#guest_form .static_submit').prop({
                            "value": "Verify",
                            "id": "verify"
                        });
                        $('#guest_form .static_reset').remove();

                        //IF any of the form fields are changed.
                        $('#guest_form input').change(function(e) {
                            $('#guest_form .static_submit').prop({
                                "value": "Update"
                            });
                        });

                        //If verify is clicked without any changes.
                        $('#guest_form #verify').bind('click', function(e) {
                            e.stopImmediatePropagation();
                            e.preventDefault();

                            if ($(e.target).val() == "Update") {
                                var options = {
                                    url: site_url + "customers/updateCustomer",
                                    data: $('#guest_form').serialize(),
                                    req_type: "guest",
                                    type: "post"
                                }

                                if (document.getElementById('guest_form').checkValidity()) {
                                    fp.ui.ajaxRequest(options);
                                } else {
                                    //Flash required fields.
                                    $('input[required]').fadeIn(300).fadeOut(300).fadeIn(300);
                                }
                            } else
                                if (document.getElementById('guest_form').checkValidity()) {
                                    alert('testing');
                                } else {
                                    //Flash required fields.
                                    $('input[required]').fadeIn(300).fadeOut(300).fadeIn(300);
                                }
                        });
                    }
                });
            });
            switch (settings.ui) {
                case 'checkout':
                    {
                        $.each($('tr[data-paired]'), function(i, e){
                            var foodid = $(e).data('paired');
                            var elem = $(e).clone(true);
                            var elemTxt = elem.find('td').eq(1).text();
                            elemTxt = "<span style='color:grey; text-indent:15px;'>-"+elemTxt+"</span>";
                            elem.find('td').eq(1).html(elemTxt);
                            $(e).remove();
                            $('tr[data-foodid='+foodid+']').after(elem);
                        });
                        $('#edit').bind('click', function() {
                            fp.ui.buildDialog({
                                appendTo: '.mainCont',
                                classes: 'edit',
                                height: 769,
                                width: 553
                            });
                            $('.edit').find('.dialog').load(site_url + '/checkout/change_form', function() {
                                //If this is the checkout screen, change the message at the top of the form.
                                $('#guest_form .static_submit').prop({
                                    "value": "Verify",
                                    "id": "verify"
                                });
                                $('#guest_form .static_reset').remove();

                                //IF any of the form fields are changed.
                                $('#guest_form input').change(function(e) {
                                    $('#guest_form .static_submit').prop({
                                        "value": "Update"
                                    });
                                });

                                //If verify is clicked without any changes.
                                $('#guest_form #verify').bind('click', function(e) {
                                    e.stopImmediatePropagation();
                                    e.preventDefault();

                                    if ($(e.target).val() == "Update") {
                                        var options = {
                                            url: site_url + "customers/updateCustomer",
                                            data: $('#guest_form').serialize(),
                                            req_type: "guest",
                                            type: "post"
                                        }

                                        if (document.getElementById('guest_form').checkValidity()) {
                                            fp.ui.ajaxRequest(options);
                                        } else {
                                            //Flash required fields.
                                            $('input[required]').fadeIn(300).fadeOut(300).fadeIn(300);
                                        }
                                    } else
                                        alert('testing');
                                });
                            });
                        });
                        //First we must clear the initial values in the address field
                        $('.address').html("");
                        var addrNum = (sessionStorage.getItem('altAddr_num') !== "undefined") ? sessionStorage.getItem('altAddr_num') : "";
                        var addrStreet = (sessionStorage.getItem('altAddr_street') !== "undefined") ? sessionStorage.getItem('altAddr_street') : "";
                        var addrCity = (sessionStorage.getItem('altAddr_city') !== "undefined") ? sessionStorage.getItem('altAddr_city') : "";
                        var addrState = (sessionStorage.getItem('altAddr_state') !== "undefined") ? sessionStorage.getItem('altAddr_state') : "";
                        var addrZip = (sessionStorage.getItem('altAddr_zip') !== "undefined") ? sessionStorage.getItem('altAddr_zip') : "";

                        $('.address').append($('<span>', {
                            class: "chckOut_addrLn1",
                            style: "display:block;",
                            text: addrNum + " " + addrStreet
                        }));
                        $('.address').append($('<span>', {
                            class: "chkOut_addrLn2",
                            style: "display:block;",
                            text: addrCity + " " + addrState + ", " + addrZip
                        }));

                        var requiredFields = ["div.name", 'div.address', "div.phone"];

                        $.each(requiredFields, function(i, e) {
                            if ($.trim($(e).text()) == "") {
                                //Activate the register UI for customer information or registration.
                                $('a#register').trigger('click');
                            }
                        });
                        $('.orders_table').on('keypress', '.qty', function(e){
                            //Lets make sure the value stays where it's supposed to be.
                            var origVal = $(e.target).data('origqty');
                            $(e.target).val(origVal);
                            return false;
                        }); 
                        $('.orders_table .qty').on("change", function(e){
                            //TODO: Same functionality as the original shopping cart, but the variables
                            //are obtained differently. Refactor at a later time into a function perhaps.
                            var $qtyInput = $(e.target);
                            var qtyVal = $qtyInput.val();

                            //Build the item string
                            var itemString = $.trim($qtyInput.parent('td').siblings('td').eq(0).text());
                            itemString += " - $"+$.trim($qtyInput.parent('td').siblings('td').eq(1).text());
                            var foodName = itemString.substring(0, itemString.indexOf(" - "));
                            var rowid = $qtyInput.parents('tr').first().prev('input').val();
                            var rest_id = $qtyInput.parents('tr').first().data('restid');

                            if (qtyVal == 0) {
                                if (confirm("This will remove " + itemString + " from your shopping cart. Are you sure?")) {
                                    $.ajax({
                                        url: site_url + "main/updateCart",
                                        data: {
                                            rowid: rowid,
                                            qty: qtyVal,
                                            operation: "minus",
                                        },
                                        req_type: "cart_update",
                                        type: "post",
                                        complete: function(){
                                           window.location.href = window.location.href;
                                        }
                                    })
                                } else {
                                    $(e.target).val(qtyVal + 1);
                                }
                            } else if(qtyVal < $(e.target).data('origqty')){
                                $.ajax({
                                    url: site_url + "main/updateCart",
                                    data: {
                                        rowid: rowid,
                                        qty: qtyVal,
                                        operation: "minus",
                                    },
                                    req_type: "cart_update",
                                    type: "post",
                                    complete: function(){
                                        window.location.href = window.location.href;
                                    }
                                });
                            }else
                            {
                                var inc = 0;
                                var count = qtyVal - $qtyInput.data('origqty');
                                var foodid = $qtyInput.parents('tr').first().data('foodid');
                                var topic = $.trim(itemString.substring((itemString.search(/[-]/)+1), itemString.lastIndexOf('-')));
                                var promoFoods = new Array();

                                var price = $qtyInput.parent('td').siblings('.price').text();
                                $itemrow = $qtyInput.parents('tr').first();

                                var promos = new Array();
                                promos = ($itemrow.attr('data-promos') !== undefined) ? $itemrow.attr('data-promos').split(",") : "";

                                if($.inArray("NONE", promos) == -1)
                                {
                                    $.ajax({
                                        url: site_url + "main/processPromos",
                                        data: {
                                            'promos': promos,
                                            'rest_id': rest_id,
                                            'food_id': foodid,
                                            'food_spec': topic,
                                        },
                                        type: "post",
                                        complete: function(data) 
                                        {
                                            var promoInfo = $.parseJSON(data.responseText);
                                            //First let's build the dialog if there are promos attached.
                                            //if(promoInfo.foods[1] !== undefined && promoInfo.foods[1].length > 0)
                                            //{
                                                fp.ui.buildDialog(
                                                {
                                                    "classes" : "food_selection",
                                                    "appendTo": "document",
                                                    "title" : "Select a side",
                                                    "width" : "300px",
                                                    "height": "400px",
                                                    "resize": "auto",
                                                    "allowStacking": true
                                                });
                                                $('.food_selection').css({"z-index": 20001, "background-color": "white"}).siblings('.ui-widget-overlay').css({"z-index": 20000});
                                                $('.food_selection').find('.ui-dialog-content').css({height: "400px"});

                                                //The unordered list
                                                var $ul = $('<ul>', {class: "promos"});
                                                //Get the dialog content.
                                                var $dialog = $('.food_selection').find('.ui-dialog-content');

                                                //Gather the data to be displayed.
                                                
                                                $.each(promoInfo.foods, function(i, foodList)
                                                {
                                                    $.each(foodList, function(j, food){
                                                        $.each(food.topics, function(k, topic){
                                                            var $foodName = $('<span>', {text: food.food_name+" "});
                                                            var $foodTopic = $('<span>', {text: topic, "data-topic": topic});
                                                            var $li = $('<li>', {class: "promo", "data-food": food.food_id});
                                                            $li.append($foodName).append($foodTopic);
                                                            $ul.append($li);
                                                        });
                                                    });
                                                });
                                                $ul.prepend($('<li>', {class: "promo", text: "NONE"}));
                                                //Build the dialog content.
                                                $dialog.append($ul);
                                                $(document).on('click', '.ui-dialog-titlebar-close', function(e)
                                                {
                                                    $qtyInput.val(qtyVal - 1);
                                                    clearInterval(interval);
                                                });
                                                //This to handle bubbling.
                                                $(document).on('click', '.food_selection .promos span', function(elem)
                                                {
                                                    $(elem.target).parents('.promo').first().trigger('click');
                                                });
                                                $(document).on('click', '.food_selection li.promo', function(li)
                                                {
                                                    var promoFoodId = $(li.target).data('food');
                                                    var promoFoodSpec = $(li.target).find('span').eq(1).data('topic');
                                                    var promoFoodName = $.trim($(li.target).find('span').eq(0).text());
                                                    promoFoods[promoFoods.length] = new foodItem(promoFoodId, parseFloat("0.00"), promoFoodName, {"food_spec": promoFoodSpec, "rest_id": rest_id, "paired": foodid, "randomVal": Math.random()});
                                                    $('.food_selection .dialog').dialog("close");
                                                });
                                            //}
                                            
                                            var interval = setInterval(function()
                                            {
                                                //If the box is still checked...
                                                if(($('.food_selection .dialog').dialog('isOpen') !== true)){
                                                    clearInterval(interval);
                                                    $('.order-tab h3').after($("<img>", {
                                                        src: 'assets/images/ajax-loader.gif'
                                                    }));

                                                    $.ajax({
                                                        url: site_url + "main/updateCart",
                                                        data: {
                                                            rowid: rowid,
                                                            qty: qtyVal,
                                                        },
                                                        type: "post",
                                                        complete: function(){
                                                            var options = {
                                                                url: site_url + 'main/addCartItems',
                                                                req_type: "add_cart",
                                                                type: "post",
                                                                data: JSON.stringify(promoFoods),
                                                                refresh: true
                                                            };
                                                            fp.ui.ajaxRequest(options);
                                                        }
                                                    })
                                                }
                                            }, 100);
                                        }
                                    });
                                }else
                                {
                                    var options = {
                                        url: site_url + "main/updateCart",
                                        data: {
                                            rowid: rowid,
                                            qty: qtyVal,
                                        },
                                        req_type: "add_cart",
                                    };
                                    fp.ui.ajaxRequest(options);
                                }
                            }
                        });
                        //This will also fall through to the default case.
                    }
                case 'default':
                    {
                        $("#extruderRight").buildMbExtruder({
                            positionFixed:true,
                            width:350,
                            sensibility:800,
                            position:"right", // left, right, bottom
                            extruderOpacity:1,
                            flapDim:100,
                            textOrientation:"bt", // or "tb" (top-bottom or bottom-top)
                            onExtOpen:function(){},
                            onExtContentLoad:function(){
                                $('.extruder-content').css({'max-height': (window.innerHeight-5), 'overflow-y': 'auto !imnportant'});   
                            },
                            onExtClose:function(){},
                            hidePanelsOnClose:true,
                            autoCloseTime:0, // 0=never
                            slideTimer:100
                          });

                        updateItemCount();

                        var options = {
                            url: site_url + "main/displayCartItems",
                            type: "post",
                            req_type: 'displayCart',
                        }
                        fp.ui.ajaxRequest(options);

                        if ($('.ext_wrapper .order-tab .item').length == 0) {
                            $('.ext_wrapper .order-tab .cart_contents form .totals').before($('<span>', {
                                class: 'item',
                                text: "Your cart is currently empty..."
                            }));
                        }
                        //When we load the homepage check for default address and run the geocoding based on that.
                        $('#default_addr').val(sessionStorage['full_addr'] !== undefined ? sessionStorage['full_addr'] : $('#default_addr').val());
                        if ($('#default_addr').val() != "") {
                            setSessionValues(false, $('#default_addr').val());
                        }
                        $('.ext_wrapper').on('keypress', '.qty', function(e){
                            //Lets make sure the value stays where it's supposed to be.
                            var origVal = $(e.target).data('origqty');
                            $(e.target).val(origVal);
                            return false;
                        });
                        //Apply UI events to shopping cart
                        //If the quantiy box is moved to 0
                        $('.ext_wrapper').on('change', '.qty', function(e) {
                            $('#extruderRight').closeMbExtruder()
                            var qtyInput = e.target;
                            var qtyVal = $(e.target).val();
                            var itemString = $(e.target).parent('div').siblings('.cart_itm_name').text();
                            var foodName = itemString.substring(0, itemString.indexOf(" - "));
                            var rowid = $(e.target).parents('div.item').first().data('rowid');
                            var rest_id = $(e.target).parents('div.item_cont').first().data('restid');

                            if (qtyVal == 0) {
                                if (confirm("This will remove " + itemString + " from your shopping cart. Are you sure?")) {
                                    $.ajax({
                                        url: site_url + "main/updateCart",
                                        data: {
                                            rowid: rowid,
                                            qty: qtyVal,
                                            operation: "minus",
                                        },
                                        req_type: "cart_update",
                                        type: "post",
                                        complete: function(){
                                           window.location.href = window.location.href;
                                        }
                                    })
                                } else {
                                    $(e.target).val(qtyVal + 1);
                                }
                            } else if(qtyVal < $(e.target).data('origqty')){
                                $.ajax({
                                    url: site_url + "main/updateCart",
                                    data: {
                                        rowid: rowid,
                                        qty: qtyVal,
                                        operation: "minus",
                                    },
                                    req_type: "cart_update",
                                    type: "post",
                                    complete: function(){
                                        window.location.href = window.location.href;
                                    },
                                    error: function(){
                                        console.log("Error");
                                    }
                                });
                            }else
                            {   var inc = 0;
                                var count = qtyVal - $(e.target).data('origqty');
                                //Collect the parent div's food id
                                var foodid = $(e.target).parents('.item').data('foodid');
                                var topic = $.trim(itemString.substring((itemString.search(/[-]/)+1), itemString.lastIndexOf('-')));
                                var promoFoods = new Array();
                                //Have to re-write this portion, because I couldn't get the checkbox click function to work properly.
                                var price = $('.food[data-id='+foodid+']').siblings('.prices').find('span:contains('+topic+')').eq(1).siblings('label').text().replace(new RegExp("[$]"), "");
                                var $chkbx = $('.food[data-id='+foodid+']').siblings('.prices').find('span:contains('+topic+')').eq(1).siblings('input[type=checkbox]');
                                //We must check to see if there are any promos attached to the selected item.
                                var promos = new Array();
                                promos = $chkbx.attr('data-promo').split(",");
                                if($.inArray("NONE", promos) == -1)
                                {
                                    $.ajax({
                                        url: site_url + "main/processPromos",
                                        data: {
                                            'promos': promos,
                                            'rest_id': rest_id,
                                            'food_id': foodid,
                                            'food_spec': topic,
                                        },
                                        type: "post",
                                        complete: function(data) 
                                        {
                                            var promoInfo = $.parseJSON(data.responseText);
                                            //First let's build the dialog if there are promos attached.
                                            //if(promoInfo.foods[1] !== undefined && promoInfo.foods[1].length > 0)
                                            //{
                                                fp.ui.buildDialog(
                                                {
                                                    "classes" : "food_selection",
                                                    "appendTo": "document",
                                                    "title" : "Select a side",
                                                    "width" : "300px",
                                                    "height": "400px",
                                                    "resize": "auto",
                                                    "allowStacking": true
                                                });
                                                $('.food_selection').css({"z-index": 20001, "background-color": "white"}).siblings('.ui-widget-overlay').css({"z-index": 20000});
                                                $('.food_selection').find('.ui-dialog-content').css({height: "400px"});

                                                //The unordered list
                                                var $ul = $('<ul>', {class: "promos"});
                                                //Get the dialog content.
                                                var $dialog = $('.food_selection').find('.ui-dialog-content');

                                                //Gather the data to be displayed.
                                                
                                                $.each(promoInfo.foods, function(i, foodList)
                                                {
                                                    $.each(foodList, function(j, food){
                                                        $.each(food.topics, function(k, topic){
                                                            var $foodName = $('<span>', {text: food.food_name+" "});
                                                            var $foodTopic = $('<span>', {text: topic, "data-topic": topic});
                                                            var $li = $('<li>', {class: "promo", "data-food": food.food_id});
                                                            $li.append($foodName).append($foodTopic);
                                                            $ul.append($li);
                                                        });
                                                    });
                                                });
                                                $ul.prepend($('<li>', {class: "promo", text: "NONE"}));
                                                //Build the dialog content.
                                                $dialog.append($ul);
                                                $(document).on('click', '.ui-dialog-titlebar-close', function(e)
                                                {
                                                    $(qtyInput).val(qtyVal - 1);
                                                    clearInterval(interval);
                                                });
                                                //This to handle bubbling.
                                                $(document).on('click', '.food_selection .promos span', function(elem)
                                                {
                                                    $(elem.target).parents('.promo').first().trigger('click');
                                                });
                                                $(document).on('click', '.food_selection li.promo', function(li)
                                                {
                                                    var promoFoodId = $(li.target).data('food');
                                                    var promoFoodSpec = $(li.target).find('span').eq(1).data('topic');
                                                    var promoFoodName = $.trim($(li.target).find('span').eq(0).text());
                                                    promoFoods[promoFoods.length] = new foodItem(promoFoodId, parseFloat("0.00"), promoFoodName, {"food_spec": promoFoodSpec, "rest_id": rest_id, "paired": foodid, "randomVal": Math.random()});
                                                    $('.food_selection .dialog').dialog("close");
                                                });
                                            //}
                                            
                                            var interval = setInterval(function()
                                            {
                                                //If the box is still checked...
                                                if(($('.food_selection .dialog').dialog('isOpen') !== true)){
                                                    clearInterval(interval);
                                                    $('.order-tab h3').after($("<img>", {
                                                        src: 'assets/images/ajax-loader.gif'
                                                    }));

                                                    $.ajax({
                                                        url: site_url + "main/updateCart",
                                                        data: {
                                                            rowid: rowid,
                                                            qty: qtyVal,
                                                        },
                                                        type: "post",
                                                        complete: function(){
                                                            var options = {
                                                                url: window.location.href + 'main/addCartItems',
                                                                req_type: "add_cart",
                                                                type: "post",
                                                                data: JSON.stringify(promoFoods)
                                                            };
                                                            fp.ui.ajaxRequest(options);
                                                        }
                                                    })
                                                }
                                            }, 100);
                                        }
                                    });
                                }else
                                {
                                    var options = {
                                        url: site_url + "main/updateCart",
                                        data: {
                                            rowid: rowid,
                                            qty: qtyVal
                                        },
                                        req_type: "add_cart"
                                    };
                                    fp.ui.ajaxRequest(options);
                                }
                            }
                        });

                        //TODO: Add remove all items button

                        //If the remove button is clicked
                        $('.ext_wrapper').on('click', '.remove', function(e) {
                            var qty = $(e.target).parents('.row').find('.qty');
                            qty.val(0).trigger('change');
                        });
                        //Hover handler for cart items
                        $('.ext_wrapper').on('mouseenter', '.item', function(e) {
                            var id = $(e.target).closest('.item_cont').data('restid');
                            $('.well[data-id=' + id + ']').css('border', '1px solid red');
                        });
                        $('.ext_wrapper').on('mouseleave', '.item', function(e) {
                            var id = $(e.target).closest('.item_cont').data('restid');
                            $('.well[data-id=' + id + ']').css('border', 'none');
                        });

                        var dialogOptions = {
                            classes: 'welcome_dialog',
                            closeImg: '',
                            appendTo: 'body',
                            title: 'Your Profile',
                            modal: true,
                            allowStacking: false,
                            resizable: false,
                            draggable: true,
                            height: 300,
                            width: '15em'
                        };
                        if(sessionStorage.getItem('isCustomer') === null)
                        {
                            fp.ui.buildDialog(dialogOptions);
                        }

                        $('.welcome_dialog').find('.ui-dialog-titlebar').remove();
                        $('.welcome_dialog .ui-dialog-content').load("assets/views/home/welcome_dialog.php");
                        $('.welcome_dialog .ui-dialog-content').addClass('col-lg-12');
                        $('.welcome_dialog').on('click', '#customers_link', function(e){
                            $('.welcome_dialog .dialog').dialog("close");
                            sessionStorage.setItem('isCustomer', true);
                            return false;
                        }); 
                        //Handler for menu items
                        $('.restaurants').on('click', '.prices input[type=checkbox]', function(evt) {
                            var rest_id = $(evt.target).parents('.well').first().data('id');
                            var food_spec = $(evt.target).siblings('span').text();
                            var food_name = $(evt.target).parents('.prices').siblings('.food').find('span').first().data('food').toString().replace(new RegExp("_", "g"), " ");
                            var food_id = $(evt.target).parents('.prices').siblings('.food').data('id');
                            var price = $(evt.target).siblings('label').text().replace(new RegExp("[$]"), "");
                            var promoFoods = new Array();
                            if ($(evt.target).is(':checked')) {
                                //We must check to see if there are any promos attached to the selected item.
                                var promos = new Array();
                                promos = $(evt.target).attr('data-promo').split(",");
                                console.log(promos);
                                if($.inArray("NONE", promos) == -1)
                                {
                                    $.ajax({
                                        url: site_url + "main/processPromos",
                                        data: {
                                            'promos': promos,
                                            'rest_id': rest_id,
                                            'food_id': food_id,
                                            'food_spec': food_spec,
                                        },
                                        type: "post",
                                        complete: function(data) {
                                            try{
                                                var promoInfo = $.parseJSON(data.responseText);
                                            }catch(exception){
                                                console.log("Error"+data.responseText);
                                            }
                                            
                                            //First let's build the dialog if there are promos attached.
                                            //if(promoInfo.foods[1] !== undefined && promoInfo.foods[1].length > 0)
                                            //{
                                                fp.ui.buildDialog({
                                                    "classes" : "food_selection",
                                                    "appendTo": "document",
                                                    "title" : "Select a side",
                                                    "width" : "300px",
                                                    "height": "400px",
                                                    "resize": "auto"
                                                });
                                                $('.food_selection').css({"z-index": 20001, "background-color": "white"}).siblings('.ui-widget-overlay').css({"z-index": 20000});
                                                $('.food_selection').find('.ui-dialog-content').css({height: "400px"});

                                                //The unordered list
                                                var $ul = $('<ul>', {class: "promos"});
                                                //Get the dialog content.
                                                var $dialog = $('.food_selection').find('.ui-dialog-content');

                                                //Gather the data to be displayed.
                                                $.each(promoInfo.foods, function(i, foodList){
                                                    $.each(foodList, function(j, food){
                                                        $.each(food.topics, function(k, topic){
                                                            var $foodName = $('<span>', {text: food.food_name+" "});
                                                            var $foodTopic = $('<span>', {text: topic, "data-topic": topic});
                                                            var $li = $('<li>', {class: "promo", "data-food": food.food_id});
                                                            $li.append($foodName).append($foodTopic);
                                                            $ul.append($li);
                                                        });
                                                    });
                                                });
                                                $ul.prepend($('<li>', {class: "promo", text: "NONE"}));
                                                //Build the dialog content.
                                                $dialog.append($ul);

                                                $(document).on('click', '.ui-dialog-titlebar-close', function(e){
                                                    $(evt.target).prop('checked', false);
                                                    clearInterval(interval);
                                                });
                                                //This to handle bubbling.
                                                $(document).on('click', '.food_selection .promos span', function(elem){
                                                    $(elem.target).parents('.promo').first().trigger('click');
                                                });
                                                $(document).on('click', '.food_selection li.promo', function(li){
                                                    var promoFoodId = $(li.target).data('food');
                                                    var promoFoodSpec = $(li.target).find('span').eq(1).data('topic');
                                                    var promoFoodName = $.trim($(li.target).find('span').eq(0).text());
                                                    promoFoods[promoFoods.length] = new foodItem(promoFoodId, parseFloat("0.00"), promoFoodName, {"food_spec": promoFoodSpec, "rest_id": rest_id, "paired": food_id});
                                                    $('.food_selection .dialog').dialog("close");
                                                });
                                            //}
                                            var interval = setInterval(function(){
                                                //If the box is still checked...
                                                if(($('.food_selection .dialog').dialog('isOpen') !== true) && ($(evt.target).is(':checked'))){
                                                    clearInterval(interval);
                                                    var foodItem1 = new foodItem(food_id, parseFloat(price), food_name, {"food_spec": food_spec, "rest_id": rest_id, "promos": promos});
                                                    promoFoods[promoFoods.length] = foodItem1;
                                                    var options = {
                                                        url: window.location.href + 'main/addCartItems',
                                                        req_type: "add_cart",
                                                        type: "post",
                                                        data: JSON.stringify(promoFoods)
                                                    };
                                                    $('.order-tab h3').after($("<img>", {
                                                        src: 'assets/images/ajax-loader.gif'
                                                    }));
                                                    fp.ui.ajaxRequest(options);
                                                }
                                            }, 100);
                                        }
                                    });
                                }else
                                {
                                    var foodItem1 = new foodItem(food_id, parseFloat(price), food_name, {"food_spec": food_spec, "rest_id": rest_id});
                                    promoFoods[promoFoods.length] = foodItem1;
                                    var options = {
                                        url: window.location.href + 'main/addCartItems',
                                        req_type: "add_cart",
                                        type: "post",
                                        data: JSON.stringify(promoFoods)
                                    };
                                    $('.order-tab h3').after($("<img>", {
                                        src: 'assets/images/ajax-loader.gif',
                                        class: 'ajax-loader'
                                    }));
                                    fp.ui.ajaxRequest(options);
                                }
                            } else {
                                var needle = food_name.ucFirst() + " - " + food_spec + " - $" + parseFloat(price).toFixed(2);
                                console.log(needle);
                                $('.cart_itm_name').each(function(index, e) {
                                    if ($(e).text() == needle) {
                                        $(e).siblings('.remove').trigger('click');
                                    }
                                });
                            }

                        });

                        $('.search_form').submit(function(e) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            var inAddress = $('#addr_search').val();
                            var confirm = (e.confirm !== undefined) ? e.confirm : true;

                            if (confirm) {
                                $.get(site_url + "main/numCartItems", function(data) {
                                    var numItems = data;

                                    if (numItems > 0) {
                                        if (window.confirm("Please note: By changing locations some items in your cart may become undeliverable at checkout, or an additional service fee may be applied for multiple locations and extra mileage. Do you still wish to continue?")) {
                                            setSessionValues(true, inAddress);
                                        } else {
                                            $('#addr_search').val(sessionStorage["full_addr"]);
                                        }
                                    } else {
                                        setSessionValues(false, inAddress);
                                    }
                                })
                            } else {
                                setSessionValues(false, inAddress);
                            }
                        });

                        //If you click on a restaurant item's "more" text
                        $(".rest_disp").on('click', '.menu_control em:contains("More...")', function(e) {
                            $(e.target).parents('.well').last().find('.menu').show();
                            $(e.target).text("Less...");

                            var rest_name = $(e.target).parents('.well').last().find('.rest_name').text();
                            var rest_id = $(e.target).parents('.well').last().data('id');
                            var currElement = $(e.target).parent().siblings('.menu').first();

                            var options = {
                                url: window.location.href + "restaurants/getMenuItems",
                                data: {
                                    'rest_name': rest_name,
                                    'rest_id': rest_id,
                                },
                                req_type: "restaurant-menu",
                                type: "post",
                                element: currElement,
                                rest_id: rest_id,
                                food: e.food !== undefined ? e.food.replace(new RegExp(" ", "g"), "_").toLowerCase() : "",
                                foodAlt: e.foodAlt
                            }

                            if ($.inArray(rest_id, fp_menusLoaded) == -1) {
                                fp.ui.ajaxRequest(options);
                            }
                        });

                        //Click handlers for menu links
                        $('.restaurants').on('click', '.links li', function(e) {
                            var id = $(e.target).data('link');
                            $(e.target).parents('.links').first().siblings('.food_displ').find('.content').hide();
                            $(e.target).parents('.links').first().siblings('.food_displ').find(id).show();

                            var height = $(e.target).parents('.links').first().siblings('.food_displ').height()
                                //Adjust the height of the links nav to match the height of the menu to avoid awkward wrapping issue.
                            $(e.target).parents('.links').first().css('min-height', height + 'px');
                        });
                        $(".rest_disp").on('click', '.menu_control em:contains("Less...")', function(e) {
                            $(e.target).parents('.well').last().find('.menu').hide();
                            //Clear all elemeents within the menu.
                            $(e.target).text("More...");
                        });

                        break;
                    }
                case 'employees':
                    {
                        var requestOptions = {
                            url: site_url + 'employees/getAllCarriers',
                            req_type: "carriers_display",
                            type: 'post'
                        }
                        fp.ui.ajaxRequest(requestOptions);

                        $('.employees').load(site_url + "employees/getAllEmployees");
                        break;
                    }
                case 'reg_form':
                    {
                        //If the values of input fields are blank make sure the value attribute is not present else the "required" attribute does not work.
                        if ($('input.input').each(function(i, e) {
                            if ($(e).attr('value') == "") {
                                $(e).removeAttr('value');
                            }
                        }));
                        var addrNum = (sessionStorage.getItem('altAddr_num') !== "undefined") ? sessionStorage.getItem('altAddr_num') : "";
                        var addrStreet = (sessionStorage.getItem('altAddr_street') !== "undefined") ? sessionStorage.getItem('altAddr_street') : "";
                        var addrCity = (sessionStorage.getItem('altAddr_city') !== "undefined") ? sessionStorage.getItem('altAddr_city') : "";
                        var addrState = (sessionStorage.getItem('altAddr_state') !== "undefined") ? sessionStorage.getItem('altAddr_state') : "";
                        var addrZip = (sessionStorage.getItem('altAddr_zip') !== "undefined") ? sessionStorage.getItem('altAddr_zip') : "";
                        var phone = $('#guest_form input.phone').first().data('phone');

                        $('#guest_form input.addr1').val(addrNum + " " + addrStreet);
                        $('#guest_form input.city').val(addrCity);
                        $('#guest_form input.state').val(addrState);
                        $('#guest_form input.zip').val(addrZip);
                        if (phone !== "") {
                            $('#guest_form input.phone').eq(0).val(phone.match(/\(\d{3}\)/).toString().replace(new RegExp("[\(\)]", "g"), ""));
                            $('#guest_form input.phone').eq(1).val(phone.match(/-\d{3}-/).toString().replace(new RegExp("-", "g"), ""));
                            $('#guest_form input.phone').eq(2).val(phone.match(/\d{4}$/).toString());
                        }
                        $('#register_form').bind({
                            submit: function(e) {
                                //global
                                stop = false;
                                $('#guest_form').find('input').each(function() {
                                    if ($(this).attr('required')) {
                                        if ($(this).val() == '') {
                                            $(this).siblings('.err').html('*');
                                            stop = true;
                                        }
                                    }
                                });
                                if (stop === true) {
                                    return false;
                                }
                                var custInfo = $('#guest_form').serialize();
                                var options = {
                                    url: site_url + 'register/user_submit/',
                                    req_type: 'register',
                                    data: custInfo + '&' + $('#register_form').serialize(),
                                    type: 'post'
                                };
                                e.preventDefault();
                                fp.ui.ajaxRequest(options);
                            }
                        });

                        $('.register').on('blur', '.username', function() {
                            var username = $(this).val();
                            if (username == '') {
                                $('.username').siblings('.err').html('*');
                            } else {
                                $('.username').siblings('.err').html('');
                                var options = {
                                    url: site_url + 'register/get_user/' + username,
                                    req_type: 'username'
                                };
                                fp.ui.ajaxRequest(options);
                            }
                        });

                        $('#guest_form').bind({
                            submit: function(e) {
                                stop = false;
                                $('#guest_form').find('input').each(function() {
                                    if ($(this).attr('required')) {
                                        if ($(this).val() == '') {
                                            $(this).siblings('.err').html('*');
                                            stop = true;
                                        }
                                    }
                                });
                                if (stop === true) {
                                    return false;
                                }

                                var options = {
                                    url: site_url + 'customers/add_cust/',
                                    req_type: 'guest',
                                    data: $('#guest_form').serialize(),
                                    type: 'post'
                                };
                                e.preventDefault();
                                fp.ui.ajaxRequest(options);
                            }
                        })
                        break;
                    }
                case 'accounts':
                    {

                        break;
                    }
                case 'restaurants':
                    {
                        //Add event handlers for checkboxes under add_rules
                        $('.restaurants').on('click', '.rules_form .topic', function(e) {
                            var $this = $(e.target);
                            if ($this.is(':checked')) {
                                //Allow modification of the price element.
                                $this.siblings('input.price').removeAttr('readonly');
                                $this.siblings('input.discount').removeAttr('readonly');
                                //Set the hidden field to the proper foodid.
                                var food_id = $this.parents('ul').first().attr('data-foodid');
                                $this.parents('ul').first().find('input[type=hidden]').first().val(food_id);
                            } else {
                                $this.siblings('input.price').attr('readonly', true);
                                $this.siblings('input.discount').attr('readonly', true);
                            }
                        });

                        $('.restaurants').on('submit', '.rules_form', function(e) {

                            $('li > input[type=checkbox]').not(':checked').parent('li').remove();
                            var $formData = $(e.target).serialize();
                            e.preventDefault();
                            $.ajax({
                                url: site_url + "restaurants/addPromoRules",
                                data: $formData,
                                type: "post",
                                complete: function(data) {
                                    window.location.href = window.location.href;
                                }
                            })
                        });

                        //Add event handlers for category and menu item buttons
                        $('.restaurants').on('click', 'ul', function(e) {
                            $(e.target).find('.add_nav').toggle();
                            $(e.target).find('form').toggle();
                        });
                        $('.restaurants').on('click', 'li', function(e) {
                            $(e.target).next('.add_nav').toggle();
                            $(e.target).siblings('form').remove();
                        });
                        $('.restaurants').on('submit', '#add_cat_form', function(e) {
                            e.preventDefault();
                            //Collect the restaurant Id.
                            var rest_id = $(e.target).parents('.well').first().data('id');

                            formData = $(e.target).serialize();

                            formData += "&rest_id=" + encodeURIComponent(rest_id);

                            var options = {
                                url: site_url + "restaurants/addFoodCategory",
                                req_type: "add_food_cat",
                                data: formData,
                                type: 'post'
                            }

                            fp.ui.ajaxRequest(options);
                        });
                        $('.restaurants').on('click', '.add_cat', function(e) {
                            $(e.target).parents('add_nav').siblings('form#add_food_form').remove();
                            $this = e;
                            $.ajaxSetup({
                                cache: false
                            });
                            $.get(site_url + './assets/views/restaurants/add_category_form.html', function(html) {
                                var html = $.parseHTML(html);
                                if ($(e.target).parents('add_nav').siblings('#add_cat_form').length == 0) {
                                    $($this.target).parents('.well').eq(1).append(html);
                                }

                                $('.restaurants').on('click', 'ul', function(e) {
                                    $(e.target).find('form').remove();
                                });
                                $('.restaurants').on('click', 'li > button', function(e) {
                                    $(e.target).parents('.well').eq(1).find('form').remove();
                                });
                            });
                        })

                        $('.restaurants').on('click', '.add_item', function(e) {
                            $(e.target).parents('add_nav').siblings('form.rules_form').remove();
                            $(e.target).parents('add_nav').siblings('form#add_food_form').remove();
                            var parent = $(e.target).parents('.well').eq(1);
                            var rest_id = parent.data('id');
                            $this = e;
                            $.ajaxSetup({
                                cache: false
                            });
                            $.get(site_url + './assets/views/restaurants/add_food_form.php', function(html) {
                                var html = $.parseHTML(html);
                                if ($(e.target).parents('add_nav').siblings('#add_cat_form').length == 0) {
                                    $($this.target).parents('.well').eq(1).append(html);
                                }

                                $('.restaurants').on('click', 'ul', function(e) {
                                    $(e.target).find('form').remove();
                                });
                                $('.restaurants').on('click', 'li > button', function(e) {
                                    $(e.target).parents('.well').eq(1).find('form').remove();
                                });
                            }).done(function() {
                                var options = {
                                    url: site_url + "restaurants/getFoodCategories/" + rest_id,
                                    req_type: "food_select",
                                    type: "post",
                                    element: $this.target,
                                    rest_id: rest_id,
                                    parentElem: parent
                                }

                                fp.ui.ajaxRequest(options);
                            });

                            //If the plus button is clicked...
                            $('.restaurants').on('click', 'span.add', function(e) {
                                e.stopImmediatePropagation();
                                var theClone = $(e.target).parents('.cloneable').first().clone(true);

                                if (theClone.find('span.min').length == 0) {
                                    theClone.find('span.add').after($('<span>', {
                                        class: "min glyphicon glyphicon-minus"
                                    }));
                                }


                                theClone.find('input[type=text]').val('');
                                var theForm = $(e.target).parents('#add_food_form');

                                theForm.find('input.keywords').siblings('label').before(theClone);

                                //Collect the number of promo divs
                                var numOfPricePoints = $('div.promos').length;

                                for (var i = 0; i < numOfPricePoints; i++) {
                                    $.each($('div.promos').eq(i).find('input[type=checkbox]'), function(index, e) {
                                        $(e).attr('name', "price[promos][" + i + "][]");
                                    });
                                }
                            });

                            //If the minus button is clicked...
                            $('.restaurants').on('click', 'span.min', function(e) {
                                $(e.target).parents('.cloneable').first().remove();
                            });
                        });

                        $('.restaurants').on('click', '.edit_item', function(e) {
                            $(e.target).parents('add_nav').siblings('form.rules_form').remove();
                            $(e.target).parents('add_nav').siblings('form#add_food_form').remove();
                            var rest_id = $(e.target).parents('.well').eq(1).data('id');
                            var rest_name = $(e.target).parents('.well').eq(1).find('span.rest_name').text();
                            $this = e;
                            $.ajaxSetup({
                                cache: false
                            });

                            $.ajax({
                                url: site_url + 'restaurants/getItemTable',
                                data: {
                                    rest_name: rest_name,
                                    rest_id: rest_id,
                                },
                                type: "post",
                                complete: function(data) {
                                    var html = $.parseHTML(data.responseText);

                                    $($this.target).parents('.well').first().after(html);
                                }
                            });
                        });

                        $('.restaurants').on('click', '.add_rule', function(e) {
                            $(e.target).parents('add_nav').siblings('form#add_food_form').remove();
                            var rest_id = $(e.target).parents('.well').eq(1).data('id');
                            var rest_name = $(e.target).parents('.well').eq(1).find('span.rest_name').text();
                            $this = e;
                            $.ajaxSetup({
                                cache: false
                            });

                            $.ajax({
                                url: site_url + 'restaurants/loadComboPage',
                                data: {
                                    rest_name: rest_name,
                                    rest_id: rest_id,
                                },
                                type: "post",
                                complete: function(data) {
                                    var html = $.parseHTML(data.responseText);
                                    $($this.target).parents('.well').first().after(html);
                                    //Add the restaurant id to the form for processing
                                    $('input.rest_id').val(rest_id);
                                }
                            });
                        });

                        //add edit field here

                        $('.restaurants').on('submit', '#add_food_form', function(e) {
                            e.preventDefault();
                            $this = $(e.target);
                            $this.find('input[type=checkbox]').not(':checked').each(function(i, e) {
                                $(e).val(0).prop('checked', true);
                            });
                            var formData = $(e.target).serialize();
                            formData += "&fdCat=" + $(e.target).find('.cat_select').find('option:selected').data('catid');
                            var options = {
                                url: site_url + "restaurants/addFoodItem",
                                data: formData,
                                type: "post",
                                complete: function(data) {
                                    if ($.trim(data.responseText) == "Success") {
                                        if (confirm(data.responseText)) {
                                            window.location.href = window.location.href;
                                        }
                                    }
                                }
                            }

                            fp.ui.ajaxRequest(options);
                        });

                        //Set the values of the option elements to match the cat_ids
                        $('input[name="cat_id"]').each(function(i) {
                            var $inputElem = $(this);
                            $('select.rest_cat option').eq(i).val($inputElem.val());
                        });

                        $.ajax({
                            url: site_url + "restaurants/getAllRestaurants",
                            success: function(data) {
                                buildRestaurantList(data);
                            }
                        })
                        break;
                    }
            }
            //Should be used to load UI functions that will be used on every page.
        } /*End loadUI*/ ,
        buildDialog: function(options) {
            var settings = {
                classes: '',
                closeImg: '',
                appendTo: 'body',
                title: 'Your Profile',
                width: '400px',
                height: '400px',
                modal: true,
                allowStacking: false,
                resizable: false,
                draggable: true
            };
            if (options) {
                $.extend(settings, options);
            }
            var $container = $(settings.appendTo);
            var $dialogDiv = $('<div>', {
                class: 'dialog'
            });

            //Append the content to the dialog
            $dialogDiv.html(settings.content);
            //Append the dialog to it's container
            $container.append($dialogDiv);

            //TODO: Make sure the modal overlay is not only visible, but does what it's supposed to do.
            $dialogDiv.dialog({
                open: function() {
                    if ($('.ui-dialog').length > 1) {
                        if (settings.allowStacking === false) {
                            $('.dialog').eq(0).dialog('close');
                        }
                    }
                },
                close: function() {
                    $(this).dialog('destroy').remove();
                },
                modal: settings.modal,
                width: settings.width,
                height: settings.height,
                resizable: settings.resizable,
                //position: 'center',
                title: settings.title,
                draggable: settings.draggable
            }).open;

            //Add some special dialog features to match folder image.
            $('.ui-dialog-titlebar-close').text("X");
            $('.ui-dialog').addClass(settings.classes);
            //$('.register .ui-dialog-titlebar').remove();
            $('.register .ui-dialog-content').css({
                'margin-top': '20px',
                'height': '662px'
            });
            //$('.edit .ui-dialog-titlebar').remove();
             $('.edit .ui-dialog-content').css({
                'margin-top': '20px',
                'height': '662px'
            });
        }, //End buildDialog
        ajaxRequest: function(options) {
            $.ajaxSetup({
                cache: false,
            });
            var settings = {
                url: '',
                req_type: '',
                type: 'post',
                complete: function(data) 
                {
                    switch (settings.req_type) {
                        case 'username':
                            {
                                fp.ui.usernameAvail(data.responseText);
                                break;
                            }
                        case 'register':
                            {
                                fp.ui.registered(data.responseText);
                                break;
                            }
                        case 'restaurants_display':
                            {
                                $('.restaurants').html("");
                                //TODO: Create the restaurants view, then clone it for every restaurant object.
                                var restaurants = $.parseJSON($.trim(data.responseText));
                                $.get('assets/views/restaurants/restaurant-list.php', function(html) {

                                    var $rest_view = $.parseHTML(html);
                                    var defAddr = $('#default_addr').val();
                                    $.each(restaurants, function(k, v) {

                                        //TODO: Eventually make an ajax call to a controller to store the data for learning purposes.
                                        var options = {
                                                'fromAddr': defAddr.replace(new RegExp(" ", "g"), "+"),
                                                'toAddr': v.address.replace('<br>', "").replace(new RegExp(" ", "g"), "+"),
                                                'callbackAction': '.distance'
                                            }
                                            //if (this.city == $('#default_addr').data('city') && this.zip == $('#default_addr').data('zip')) {
                                        $('.restaurants').append($($rest_view).clone());
                                        $('.well').last().find('.rest_name').text(v.name);
                                        $('.well').last().find('.list-item').append(v.address);
                                        $('.well').last().attr({
                                            'data-id': v.rest_id
                                        });
                                        getDistance(options, $('.well').last().find('.distance'));
                                        $('.logo').last().append('<img width="100" src="' + v.logo_loc + '" alt="' + v.name + '_logo">');
                                        // }
                                    })
                                }).done(function() {
                                    if ($('.well').length === 0) {
                                        $('.restaurants').html("<p>Sorry, we have not yet reached your area; we are however hoping to expand quickly, so keep checking back!</p>");
                                    }

                                    //Go through each of the shopping cart items
                                    //Access the restaurant's menu, and select already selected items.
                                    $('.order-tab').find('.item_cont').each(function(index, e) {
                                        var restid = $(e).data('restid');
                                        var rest_name = $('.well[data-id=' + restid + ']').find('span.rest_name').text();
                                        $(e).find('.item').each(function(index, c) {
                                            var itemString = $(c).find('.cart_itm_name').text();
                                            var food = $.trim(itemString.substring(0, itemString.indexOf(" - "))).toLowerCase().replace(new RegExp("[.]", "g"), " ").replace(new RegExp(" ", "g"), "_").replace(new RegExp("__", "g"), "_");
                                            var foodAlt = $.trim(itemString.substring(itemString.indexOf(" - ") + 3, itemString.lastIndexOf(" - ")));

                                            //This will assure the menu is loaded for each cart item's restaurant
                                            if ($('.well[data-id=' + restid + ']').find('.menu_control em:contains("More...")').length == 1) {
                                                //We're passing all the data along to the trigger so that the checkboxes can be clicked once the menu loads.
                                                $('.well[data-id=' + restid + ']').find('.menu_control em:contains("More...")').trigger({
                                                    type: "click",
                                                    restid: restid,
                                                    food: food !== undefined ? food : "",
                                                    foodAlt: foodAlt
                                                });
                                            } else {
                                                var timer = setInterval(function() {
                                                    if ($.inArray(restid, fp_restListComplete) !== -1) {
                                                        $('.well[data-id=' + restid + ']').find('div.food span[data-food=' + food + ']').parent('.food').siblings('.prices').find('span:contains(' + foodAlt + ')').siblings('input[type=checkbox]').attr('checked', 'checked');
                                                        clearInterval(timer);
                                                    }
                                                }, 500)
                                            }
                                        });
                                    });
                                });
                                break;
                            }
                        case 'carriers_display':
                            {
                                var carriers = $.parseJSON(data.responseText);

                                $.each(carriers, function(k, v) {
                                    $('.carriers').append($('<option>', {
                                        'value': v.domain,
                                        'text': v.name.ucFirst()
                                    }));
                                });
                                break;
                            }
                        case 'add_food_cat':
                            {
                                if ($.trim(data.responseText) == "Success") {
                                    $('#add_cat_form').find('.messages').css('color', 'green').text(data.responseText);
                                }
                                break;
                            }
                        case 'food_select':
                            {
                                var cats = $.parseJSON(data.responseText);
                                $.each(cats, function(k, v) {
                                    $(settings.element).parents('.well').next('#add_food_form').find('.cat_select').append($('<option>', {
                                        text: v.name,
                                        value: v.name,
                                        "data-catId": v.fdCat_id
                                    }));
                                });

                                $.ajax({
                                    url: site_url + "restaurants/getPromos",
                                    data: {
                                        "rest_id": settings.rest_id,
                                    },
                                    type: "post",
                                    complete: function(data) {
                                        var promos = $.parseJSON(data.responseText);
                                        $.each(promos, function(index, elem) {
                                            $input_cont = $('<div>', {
                                                class: 'input-control'
                                            });
                                            $checkbox = $('<input>', {
                                                type: "checkbox",
                                                name: "price[promos][]",
                                                value: elem.promo_id
                                            }),
                                            $label = $('<label>', {
                                                text: elem.promo_name
                                            });
                                            $input_cont.append($checkbox).append($label);
                                            settings.parentElem.find('div.promos').append($input_cont);
                                        });

                                        var checkboxes = settings.parentElem.find('div.promos').find('input[type=checkbox]');
                                        if (checkboxes.length > 0) {
                                            settings.parentElem.find('div.promos').find('input[type="hidden"]').remove();
                                            //Collect the number of promo divs
                                            var numOfPricePoints = $('div.promos').length;

                                            for (var i = 0; i < numOfPricePoints; i++) {
                                                $.each($('div.promos').eq(i).find('input[type=checkbox]'), function(index, e) {
                                                    $(e).attr('name', "price[promos][" + i + "][]");
                                                });
                                            }
                                        }
                                    }
                                });

                                break;
                            }
                        case 'restaurant-menu':
                            {
                                fp_restListComplete.push(settings.rest_id);
                                //Keep track of the menus that have already been loaded.
                                fp_menusLoaded.push(settings.rest_id);
                                var items = $.parseJSON(data.responseText);
                                var $menu_view;

                                $.get('assets/views/restaurants/food_item_display.php', function(html) {
                                    $menu_view = $.parseHTML(html);
                                    for (var i = 0; i < items.length; i++) {
                                        var sideName = items[i].name;
                                        $menu_wrapper = $($menu_view).clone(true);
                                        if (settings.element.find('.links ul').find('li:contains(' + sideName + ')').length == 0) {
                                            settings.element.find('.links ul').append($('<li>', {
                                                text: items[i].name,
                                                "data-link": "." + items[i].name.toLowerCase().replace(new RegExp(" ", "g"), "_")
                                            }));
                                        }
                                        var $theContent = $menu_wrapper.eq(0);
                                        $theContent.addClass(items[i].name.toLowerCase().replace(new RegExp(" ", "g"), "_"));
                                        $theContent.find('.food').append($('<span>', {
                                            "data-food": items[i].food_name.toLowerCase().replace(new RegExp("[ .]", "g"), "_").replace(new RegExp("__", "g"), "_"),
                                            text: items[i].food_name
                                        }));
                                        $theContent.find('.food').attr('data-id', items[i].food_id);

                                        if (items[i].description !== null) {
                                            $theContent.find('.food').append("<br><span class='description'>" + items[i].description);
                                        }

                                        for (var j = 0; j < items[i].price.topic.length; j++) {
                                            var colDiv = Math.ceil(12 / items[i].price.topic.length);
                                            var priceTxt = $.parseHTML("<span>" + items[i].price.topic[j] + "</span><br><label>" + items[i].price.price[j] + "</label>&nbsp;<input type='checkbox' data-promo='" + items[i].price.promos[j] + "'/>");
                                            $theContent.find('.prices').append($('<span>', {
                                                html: priceTxt,
                                                class: "col-lg-" + colDiv
                                            }));
                                        }
                                        settings.element.find('.food_displ').append($menu_wrapper);
                                    }
                                }).done(function() {
                                    //Then we'll select the appropriate checkboxes based on what's in the cart.
                                    $('.well[data-id=' + settings.rest_id + ']').find('div.food span[data-food=' + settings.food + ']').parent('.food').siblings('.prices').find('span:contains(' + settings.foodAlt + ')').siblings('input[type=checkbox]').attr('checked', 'checked');
                                });
                                break;
                            }
                        case 'add_cart':
                            {
                                var options = {
                                    url: site_url + "main/displayCartItems",
                                    type: "post",
                                    req_type: 'displayCart',
                                    refresh: settings.refresh,
                                }
                                fp.ui.ajaxRequest(options);
                                break;
                            }
                        case 'checkmenuBoxes':
                            {
                                //Note: This will be performed after the first box has been checked on the menu.
                                //  This will simply prevent the menu from being loaded twice.

                            }
                        case 'displayCart':
                            {
                                try{
                                    var cartItems = $.parseJSON(data.responseText);
                                } catch (exception) {
                                    $('.totals').hide();
                                    if ($('.order-tab .item').length == 0) {
                                        $('.order-tab .cart_contents form .totals').before($('<span>', {
                                            class: 'item',
                                            text: data.responseText
                                        }));
                                        $('.order-tab img').first().remove();
                                    }
                                    break;
                                }
                                $.ajaxSetup({
                                    cache: false
                                });
                                $.get('assets/views/restaurants/cart_display.php?test="i"', function(html) {
                                    var parsedHtml = $.parseHTML(html);
                                    var pairedItems = new Array();
                                    $('.order-tab .cart_contents form .item').siblings('hr').remove();
                                    $('.order-tab .cart_contents form .item').remove();
                                    $.each(cartItems, function(k, v) {
                                        if(v.options.paired == undefined){
                                            var cart_view = $(parsedHtml).clone();
                                            if ($('.cart_contents form div[data-restid=' + v.options.rest_id + ']').length == 0) {
                                                $('.cart_contents form .totals').before($('<div>', {
                                                    class: "col-lg-12 col-xs-12 item_cont",
                                                    "data-restid": v.options.rest_id
                                                }));
                                            }
                                            $('.order-tab .cart_contents form div[data-restid=' + v.options.rest_id + ']').append(cart_view);
                                            $('.cart_contents form .item').last().attr({
                                                'data-rowId': v.rowid,
                                                'data-foodId': v.id
                                            });
                                            $('.cart_contents form').find('div[data-restid=' + v.options.rest_id + ']').find('.item').last().after($('<hr>'));
                                            $('.cart_contents form').find('div[data-restid=' + v.options.rest_id + ']').find('.item').find('.qty').last().val(v.qty).data("origqty", v.qty);
                                            $('.cart_contents form').find('div[data-restid=' + v.options.rest_id + ']').find('.item').find('.cart_itm_name').last().text((v.name).ucFirst() + " - " + v.options.food_spec + " - $" + parseFloat(v.price).toFixed(2));
                                        }//Instead do this
                                        else{
                                            pairedItems[pairedItems.length] = v;
                                        }
                                    });
                                    //Go through each of the items and apply the promos underneath.
                                    $.each(pairedItems, function(index, item){
                                        $parentItem = $('.cart_contents form .item[data-foodid='+item.options.paired+']');
                                        $parentItem.append($('<div>', {class: "col-lg-12 col-lg-offset-3 col-xs-11 col-xs-offset-1", style: "color:green;", text: "~"+item.name+" "+item.options.food_spec}));
                                    })
                                }).complete(function() {
                                    updateItemCount();
                                    $('.ajax-loader').remove();
                                    cartCategorize();
                                    $('.totals').show();

                                    //Lets fill the totals
                                    var options = {
                                        url: site_url + "main/getAllTotals",
                                        type: "post",
                                        data: {
                                            hi: "hi"
                                        },
                                        req_type: "displayTotals",
                                        refresh: settings.refresh
                                    }
                                    fp.ui.ajaxRequest(options);
                                });
                                $('.cart_contents').on('click', '.up_arrow', function(e){
                                    e.stopImmediatePropagation();
                                    var $qtyElem = $(e.target).siblings('.qty');
                                    var qtyVal = $qtyElem.val();
                                    $qtyElem.val(parseInt(qtyVal) + 1);
                                    $qtyElem.trigger('change');
                                });
                                 $('.cart_contents').on('click', '.down_arrow', function(e){
                                    e.stopImmediatePropagation();
                                    var $qtyElem = $(e.target).siblings('.qty');
                                    var qtyVal = $qtyElem.val();
                                    $qtyElem.val(parseInt(qtyVal) - 1);
                                    $qtyElem.trigger('change');
                                });
                                break;
                            }
                        case 'cart_update':
                            {
                                location.reload(true);
                                break;
                            }
                        case 'displayTotals':
                            {
                                var totals = $.parseJSON(data.responseText);
                                $('.subtotal').text(" $" + totals.subtotal.toFixed(2));
                                $('.rest_tax').text(" $" + totals.rest_tax.toFixed(2));
                                $('.labor').text(" $" + totals.fee.toFixed(2));
                                if (totals.mileage != 0) {
                                    $('.mileageHead').show();
                                    $('.mileage').show().text(" $" + totals.mileage.toFixed(2));
                                }
                                $('.total').html(" <strong>$" + totals.total.toFixed(2) + "</strong>");
                                if (settings.refresh) {
                                    window.location.href = window.location.href;
                                }
                                break;
                            }
                        case 'initial_login':
                            {
                                $('.dialog').dialog('close');
                                location.reload();
                            }
                        case 'guest':
                            {
                                var inAddress = $('#guest_form .addr1').val() + " " + $('#guest_form .city').val() + ", " + $('#guest_form .state').val();

                                setSessionValues(true, inAddress);
                                $('.dialog').dialog('close');
                            }
                    }
                },
                error: function(e) {
                    console.log('an error occurred' + e.responseText);
                }
            }
            $.extend(settings, options);
            $.ajax(settings);
        }, //End ajaxRequest
        /**
         * Displays username availability.
         * @param {type} data
         */
        usernameAvail: function(data) {
            $('#register_form .msg').html(data);
            if ($.trim(data) === "This username is available") {
                $('#register_form .msg').css('color', 'green');
            } else {
                $('#register_form .msg').css('color', 'red');
            }
        },
        registered: function(data) {
            $('#register_form .msg').html(data);
            var options = {
                url: site_url + "logins/login",
                type: "post",
                data: $('#register_form').serialize(),
                req_type: 'initial_login'
            };
            fp.ui.ajaxRequest(options);
        }
    } //End ui
}; //End fp

//TODO: break this up into a partial view with pagination.
function buildRestaurantList(data, showCity) {
    $.each(data, function(k, v) {
        var listObj = $('<ul>', {
            class: "well",
            style: "cursor: pointer;",
            "data-id": v.rest_id
        });
        var itemObj = $('<li>', {
            style: "list-style-type:none"
        });
        var add_nav = $('<ul>', {
            class: "well add_nav",
            style: "display: none;"
        });
        var btn_container = $('<li>', {
            style: "list-style-type: none; display: inline-block; margin-right: 10px;"
        });
        var add_cat = $('<button>', {
            text: "Add Category",
            class: "btn btn-primary add_cat"
        });
        var add_item = $('<button>', {
            text: "Add Menu Item",
            class: "btn btn-primary add_item"
        });
        var edit_item = $('<button>', {
            text: "Edit Menu Items",
            class: "btn btn-primary edit_item"
        });
        var add_rule = $('<button>', {
            text: "Add Menu Rules",
            class: "btn btn-primary add_rule"
        });
        var btn_container2 = btn_container.clone(false);
        var btn_container3 = btn_container.clone(false);
        var btn_container4 = btn_container.clone(false);
        listObj.html($('<span>', {
            text: v.name,
            class: "rest_name"
        }));
        itemObj.html(v.address);
        listObj.append(itemObj);
        btn_container.append(add_cat);
        btn_container2.append(add_item);
        btn_container3.append(edit_item);
        btn_container4.append(add_rule);
        add_nav.append(btn_container).append(btn_container2).append(btn_container3).append(btn_container4);
        listObj.append(add_nav);
        $('.restaurants').append(listObj);
    });
}

String.prototype.ucFirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function updateItemCount(){
    //Let's make sure the extruder tab is loaded
    $.get(site_url + "main/getNumItems", function(data){
        if($('#extruderRight .flap').find('span.cart_num').length == 0){
            $('#extruderRight').find('.flap').append($('<span>', {class: "cart_num", text: data}));
        }else
        {
            $('span.cart_num').html(data);
        }
    });
}

function cartCategorize() {
    //Let's clear all the h4 elements so that they aren't repeated.
    $('.cart_contents form .item_cont').prev('h4').remove();
    $('.cart_contents form .item_cont').each(function(index, e) {
        var rest_id = $(e).data('restid');
        var rest_name = null;

        $.ajax({
            url: "restaurants/restaurant/" + rest_id,
            success: function(data) {
                var data = $.parseJSON(data);
                var rest_name = data[0].name;

                $('.cart_contents form').find('div[data-restid=' + rest_id + ']').before($('<h4>', {
                    text: rest_name
                }));
            }
        });
    });

}

function setSessionValues(refresh, inAddress) {
    //Analyze the address entered.
    codeAddress(inAddress, function() {
        var addrNum = $('#default_addr').data('num');
        var addrStreet = $('#default_addr').data('street');
        var addrCity = $('#default_addr').data('city');
        var addrState = $('#default_addr').data('state');
        var addrZip = $('#default_addr').data('zip');
        var fullAddr = addrNum + " " + addrStreet + ", " + addrCity + " " + addrState;

        //Use this address instead of the customer's default one.
        sessionStorage.setItem("altAddr_num", addrNum);
        sessionStorage.setItem("altAddr_street", addrStreet);
        sessionStorage.setItem("altAddr_city", addrCity);
        sessionStorage.setItem("altAddr_state", addrState);
        sessionStorage.setItem("altAddr_zip", addrZip);
        sessionStorage.setItem("full_addr", fullAddr);

        var updateTimer = setInterval(function() {
            if (sessionStorage["full_addr"] == fullAddr) {
                //Check the shopping cart
                var options = {
                    url: site_url + "main/displayCartItems",
                    type: "post",
                    req_type: 'displayCart',
                    refresh: refresh,
                }
                fp.ui.ajaxRequest(options);

                clearInterval(updateTimer);
                $.ajax({
                    url: site_url + 'main/updateDeliveryAddress',
                    type: 'post',
                    data: {
                        address: sessionStorage.getItem("full_addr"),
                    },
                    complete: function() {
                        var rest_dispOptions = {
                            url: site_url + '/restaurants/getAllRestaurants',
                            req_type: "restaurants_display",
                            type: 'post',
                        }
                        fp.ui.ajaxRequest(rest_dispOptions);
                    }
                });
            }
        }, 100);

    });
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}