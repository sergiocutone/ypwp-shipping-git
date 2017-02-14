(function(YPWPCode) {

    YPWPCode(window.jQuery, window, document);

}(function($, window, document) {

    $(function() {


        function checkAjax() {
            // Yes the locations are loaded: #locations-results > .location-item exists
            if ($(".summary > h1").length) {
                clearInterval(checkAjaxTimer);
                document.body.innerHTML = document.body.innerHTML.replace("(604) 582-0808", "(604) 200-5915");
                document.body.innerHTML = document.body.innerHTML.replace("(514) 748-1396", "(514) 587-9704");
                document.body.innerHTML = document.body.innerHTML.replace("(604) 466-3053", "(604) 256-3231");
                document.body.innerHTML = document.body.innerHTML.replace("(403) 410-9155", "(403) 879-1908");
                document.body.innerHTML = document.body.innerHTML.replace("(604) 535-2273", "(604) 635-1673");
                document.body.innerHTML = document.body.innerHTML.replace("(604) 738-8380", "(604) 901-3339");
                document.body.innerHTML = document.body.innerHTML.replace("(250) 383-9099", "(250) 984-7045");
                console.log("yes");
            } else if ($(".mylocations").length) {
                clearInterval(checkAjaxTimer);
            }
        }

        URL = window.location.href;

        // Check to see if we're on the proxy site retirementconcepts.calls.net/locations
        if (URL.match("/test.dev/product/bulk/")) {
            // Because the locations are displayed via Ajax, let's make sure they're loaded before performing the replace
            checkAjaxTimer = setInterval(checkAjax, 10);
            console.log(window.location.href); // debug
        }

        // - - - - - Return dimensions in array - - - - - //
        function getDimension(d) {
            dimension = { l: parseFloat($("input[name=yp_length]").val()), w: parseFloat($("input[name=yp_width]").val()) };
            if (d) {
                dimension.d = parseFloat($("select[name=yp_depth]").val());
            }
            return dimension;
        }

        // - - - - - Return square footage - - - - - //
        function sf(l, w) {
            return parseInt(l * w);
        }

        // - - - - - Return bulk - - - - - //
        function bulk() {
            return
        }

        // - - - - - Toggle add to cart button - - - - - //          
        function addToCartButton(x) {
            button = x ? $(".single-product .single_add_to_cart_button").show() : $(".single-product .single_add_to_cart_button").hide();
        }

        // - - - - - Toggle instructions - - - - - //
        function showInstructions(x) {
            instructions = x ? $("#yp_instruct").show() : $("#yp_instruct").hide();
        }

        // - - - - - Does product have variants - - - - - //
        function isVariant() {
            return x = $(".variations select").length ? true : false;
        }

        // - - - - - Get price for variant product - - - - - //
        function getVariablePrice() {
            return parseFloat($(".woocommerce-variation-price .price .woocommerce-Price-amount").text()).toFixed(2);
        }

        // - - - - - Is variant price showing? - - - - - //
        function checkVariant() {
            if ($(".single_variation").css("display") === "none") {
                $(".yp_custompricing").hide();
                console.log("hide!");
            }
        }

        function setDollar(x) {
            return "$" + (x).toFixed(2);
        }


        // ((Depth / 12) * (Length * Width) / 27)
        function chk_BulkDimension() {
            dimensions = getDimension(true);

            // Check to see if product is variable
            if ($(".woocommerce-variation-price").length) {
                itemprice = parseFloat($(".woocommerce-variation-price .price .woocommerce-Price-amount").text());
            } else {
                itemprice = $("meta[itemprop=price]").attr("content");
            }



            // Check if Length, Width & Depth fields are filled out
            if (dimensions.l && dimensions.w && dimensions.d) { // Yes

                addToCartButton(true); // Show Add To Cart button
                showInstructions(false); // Hide instructions

                cubicYards = Math.ceil((dimensions.d / 12) * (dimensions.l * dimensions.w) / 27);

                // Set cubic yards in field
                $("input[name=yp_cubicyards]").val(cubicYards);

                // Get sub total
                subTotal = parseFloat(cubicYards * itemprice);

                // Set sub total in element
                $("#subtotal").html(setDollar(subTotal));

                // - - - - - Set shipping price - - - - - //
                //
                shippingPrice = 150; // Base shipping price for 1 Cubic Yard
                if (cubicYards > 1) {
                    shippingPrice = 125; // Base shipping price for 2+ Cubic Yards
                }

                // Set shipping price in element
                $("#shippingprice").html(setDollar(shippingPrice));

                // Set shipping price in field
                $("input[name=yp_shippingprice]").val(shippingPrice);

                finalPrice = parseInt(subTotal + shippingPrice);
                if (!isVariant()) {
                    $("div[itemprop=offers] > .price > .amount").html(setDollar(finalPrice));
                }

                // Set total price in element
                $("#totalprice").html(setDollar(finalPrice));

                // Set New Price Override
                $("input[name=yp_price]").val(finalPrice.toFixed(2));

            } else {

                addToCartButton(false); // Hide Add to Cart button
                showInstructions(true); // Show instructions

            }
        }

        // Pallet Order
        function chk_BagDimension() {
            dimensions = getDimension(false);

            // Check to see if product is variable
            if ($(".woocommerce-variation-price").length) {
                itemprice = parseFloat($(".woocommerce-variation-price .price .woocommerce-Price-amount").text());
            } else {
                itemprice = $("meta[itemprop=price]").attr("content");
            }

            // - - - - - Check if Length and Width fields are filled out - - - - - //
            if (dimensions.l && dimensions.w) { // Yes

                addToCartButton(true); // Show Add To Cart button
                showInstructions(false); // Hide instructions

                // Get square footage L x W
                squareFootage = sf(dimensions.l, dimensions.w);

                // Get subtotal (square footage * price)
                subTotal = parseFloat(squareFootage * itemprice);

                // Get number of pallets (700 sf = 1 pallet)
                palletAmount = Math.ceil(squareFootage / 700);

                // Set number of pallets in field
                $("input[name=yp_numbags]").val(palletAmount);

                // Set square footage in field
                $("input[name=yp_sf]").val(squareFootage);

                // Set sub total in element
                $("#subtotal").html(setDollar(squareFootage * itemprice));

                // - - - - - Set shipping price - - - - - //
                //
                shippingPrice = 150; // Base shipping price for 1 - 2 pallets
                if (palletAmount > 2) {
                    shippingPrice = 75; // Special shipping price for over 2 pallets
                }

                // Set shipping price in element
                $("#shippingprice").html(setDollar(shippingPrice));
                //
                // - - - - - end Set shipping price - - - - - //

                finalPrice = parseInt(subTotal + shippingPrice);
                if (!isVariant()) {
                    $("div[itemprop=offers] > .price > .amount").html(setDollar(finalPrice));
                }

                $("#totalprice").html(setDollar(finalPrice));

                // Set New Price Override
                $("input[name=yp_price]").val(finalPrice.toFixed(2));

                // - - - - - If Length and Width get nulled - - - - - //
                //
            } else {

                addToCartButton(false); // Hide Add to Cart button
                showInstructions(true); // Show instructions

            }
        }

        // - - - - - Reset fields if user changes a variation - - - - - //
        //          
        if (isVariant()) { // If variations exist
            $(".single_variation").css("opacity", 0);

            // - - - - - When variant price is set - - - - - //
            $(".single_variation_wrap").on("show_variation", function(event, variation) {
                $(".woocommerce-variation-price .price .woocommerce-Price-amount .woocommerce-Price-currencySymbol").remove();
                itemprice = parseFloat($(".woocommerce-variation-price .price .woocommerce-Price-amount").text());

                console.log("done" + " : " + itemprice);

                if (itemprice && $(".woocommerce-variation").css("display") == "block") {

                    $(".yp_custompricing").show();
                    $("#yp_prodprice").html(getVariablePrice());
                    console.log("aaaa");

                    if ($("#calcbulk").length) {
                        chk_BulkDimension();
                    } else {
                        chk_BagDimension();
                    }

                } else {
                    $(".yp_custompricing").hide();
                }

            });
            // - - - - - end When variant price is set - - - - - // 

        } else {
            $(".yp_custompricing").show();
            $("#yp_prodprice").html($("meta[itemprop=price]").attr("content"));
        }


        // - - - - - Set datepicker for shipping date - - - - - //

        $('.datepicker').datepicker({ minDate: 0 });

        // - - - - - end - - - - - //
        //
        // - - - - - Check if custom calculation on single product page exists - - - - - //

        if ($("#calcbulk").length) {
            $(".ypdimension").on("keyup", chk_BulkDimension);
            $("select[name=yp_depth]").on("change", chk_BulkDimension);
            $(".single_add_to_cart_button").hide();
        } else if ($("#calcbags").length) {
            $(".ypdimension").on("keyup", chk_BagDimension);
        }

        // - - - - - end - - - - - //
        //  
        // - - - - - Allow only numbers in fields - - - - - //

        $(".numonly").keydown(function(e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        // - - - - - end - - - - - //

    });
}));
