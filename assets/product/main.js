var iglink = null;
var paise = null;
var service = null;
var quantity = null;

var is_rz_order = null;
var is_orderid = null;

// create rzpay order id
async function create_order(amount) {
    if(is_rz_order != null && is_orderid != null){
        data = {
            "rzorder": is_rz_order,
            "orderid": is_orderid
        }
        return data;
    }
    else{
        var rzcreateorderurl = "https://grambaba.com/order/create-order.php";
        var response = await fetch(rzcreateorderurl, {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'payAmount='+amount
        });
        var data = await response.json();
        return data;
    }
}




function gettotal(price) {
    var converstionfee = (price * 2) / 100;
    document.getElementById("conv").innerHTML = converstionfee + " INR";
    var totalPrice = price + converstionfee;
    document.getElementById("price").innerHTML = price + " INR";
    document.getElementById("pricedata").value = totalPrice;
    document.getElementById("total").innerHTML = totalPrice + " INR";
    paise = (totalPrice.toFixed(2) * 100).toFixed(0); //paise
}




document.querySelector('form#order').addEventListener('submit', function(e) {
    // alert("clicked!");
    e.preventDefault();
    service = $("input[name='service']").val();
    iglink = $("input[name='instagramlink']").val();
    quantity = $("input[name='quantity']:checked").val();
    if(iglink == null){
        alert("please enter your instagram link");
        return false;
    }
    else if(quantity == null) {
        alert("please select quantity");
        return false;
    }
    else if(paise == null){
        return false;
    }
    // alert(`${quantity}+${service}+${paise}`);
    document.body.classList.add("modal-active");
    document.getElementById("payLoader").style.display = "grid";
    document.querySelector("#payLoader span").style.display = "block";
    document.getElementById("quantitydata").value = quantity;
    document.getElementById("iglinkdata").value = iglink;
    
    
    var neworderData = create_order(paise);
    neworderData.then(data =>
        initRzPayVar(data)
    ).catch(err => {
        console.warn(err);
    });

    });







function initRzPayVar(data) {
    is_orderid = data.orderid;
    is_rz_order = data.rzorder;
    document.getElementById("nonce").value = data.nonce;
    
    options = {
        "key": "rzp_test_z34MFsISPWM3ID",
        "amount": paise,
        "currency": "INR",
        "name": "GRAMBABA",
        "description": "Test Transaction",
        "image": "https://example.com/your_logo",
        "order_id": data.rzorder,
        "callback_url": "#",
        "prefill": {
            "name": "test user",
            "contact": 9999999999,
            "email": "test@test.com"
        },
        "theme": {
            "color": "#3399cc"
        },
        "handler": function (response) {
            document.getElementById("signature").value = response.razorpay_signature;
            document.getElementById("paymentid").value = response.razorpay_payment_id;
            document.getElementById("orderiddata").value = data.orderid;
            document.querySelector('form#order').reset();
            $("input[name='quantity']").closest("div.form-group").removeAttr("style");
            is_rz_order = null;
            is_orderid = null;
            document.getElementById("orderData").submit();

        }
    }
    options.modal = {
        ondismiss: function() {
            document.body.classList.remove("modal-active");
            document.getElementById("payLoader").style.display = "none";
            document.querySelector("#payLoader span").style.display = "none";
        },
        // should close the checkout form. (default: true)
        escape: true,
        // space outside checkout form should close the form. (default: false)
        backdropclose: false
    };
    var rzp1 = new Razorpay(options);
    rzp1.open();

    rzp1.on('payment.failed', function (response) {
        document.body.classList.remove("modal-active");
        document.getElementById("payLoader").style.display = "none";
        document.querySelector("#payLoader span").style.display = "none";
        alert(response.error.code);
        alert(response.error.description);
        alert(response.error.source);
        // alert(response.error.step);
        alert(response.error.reason);
        // alert(response.error.metadata.order_id);
        // alert(response.error.metadata.payment_id);
    });

}

