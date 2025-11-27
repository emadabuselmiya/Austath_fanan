$(function() {
    'use strict';

    var create_payment = $('#create_payment');

    if (status === 'true') {

        new QRCode(document.getElementById("qr-image"), {
            text: pay_address,
            width: 200,
            height: 200,
            colorDark: "#0a1128",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    create_payment.on('click', function(e) {
        var formPayment = new FormData($("#form_payment")[0]);

        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: "/usdt/payment/create",
            dataType: 'json',
            data: formPayment,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                toastr.success('تم بنجاح😍😍');

                // payment_id = data.payment_id;
                status = data.status;

                new QRCode(document.getElementById("qr-image"), {
                    text: data.pay_address,
                    width: 200,
                    height: 200,
                    colorDark: "#0a1128",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                document.getElementById('pay_address').value = data.pay_address;
                document.getElementById('price_amount_label').innerHTML = "usd " + data.price_amount + " / " + data.pay_amount + " " + data.pay_currency;
                $("#error").hide();
                document.getElementById("create-payment").style.display = "none";
                document.getElementById("status-payment").style.display = "block";
            },
            error: function(data) {

                toastr.error('يرجى التأكد من إدخال بشكل صحيح');
                var string = "";
                $.each(data.responseJSON.errors, function(key, value) {
                    string = "يرجى التأكد من إدخال بشكل صحيح"
                    string = string + value[0] + "<br><br>";
                    $("#error").html(string);
                });
                $("#error").show();

            }
        });
    });

    setInterval(function() {

        if (status === 'true') {
            $.ajax({
                type: "get",
                url: "/usdt/payment/status/" + payment_id,
                dataType: "",
                success: function(data) {
                    document.getElementById('payment_status_label').innerHTML = data.payment_status;
                    console.log(data.payment_status);

                    const status_payment = ["finished", "partially_paid"];

                    if (status_payment.includes(data.payment_status)) {
                        document.location = "/usdt";
                        status = false;
                    }
                }
            });
        }
    }, 30000);

    $(document)
        .on("click", ".resend-code", function(e) {
            $.ajax({
                type: "get",
                url: $resend,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {

                    toastr.success(data.message);

                    $("#success").html(data.message);

                    $("#success").show();
                    // payment_id = data.message;


                },
                error: function(data) {
                    $("#success").hide();

                    toastr.error('يرجى التأكد من إدخال بشكل صحيح');

                }
            });

            document.getElementById("resend-code").disabled = true;
        })
});
