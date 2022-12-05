$(document).ready(function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.btn-midtrans').click(function(e){
        e.preventDefault();
        console.log('test');
        alert('test');
    });

    $('.btn-razorpay').click(function(e) {
        e.preventDefault();

        var data = {
            'fname':$('#fname').val(),
            'lname':$('#lname').val(),
            'phone':$('#phone').val(),
            'address':$('#address').val(),
            'city':$('#city').val(),
            'state':$('#state').val(),
            'country':$('#country').val(),
            'code':$('#code').val(),
        }

        $.ajax({
            method: "POST",
            url: "/razorpay",
            data:data,
            success: function(response){
                var options = {
                    "key": "rzp_test_ER2OgXFCZ1GsaS", // Enter the Key ID generated from the Dashboard
                    "amount": response.total_price, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                    "currency": "INR",
                    "name": response.fname+' '+response.lname,
                    "description": "Thank you for choosing us",
                    "image": "https://example.com/your_logo",
                    // "order_id": "order_9A33XWu170gUtm", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                    "handler": function (responsea){
                        // alert(responsea.razorpay_payment_id);
                        $.ajax({
                            method: "POST",
                            url: "/order",
                            data: {
                                'fname':response.fname,
                                'lname':response.lname,
                                'email':response.email,
                                'phone':response.phone,
                                'address':response.address,
                                'city':response.city,
                                'state':response.state,
                                'country':response.country,
                                'code':response.code,
                                'payment_mode':"Paid by Razorpay",
                                'payment_id':responsea.razorpay_payment_id,
                            },
                            success: function(responseb){
                                Swal.fire({
                                    title : responseb.status,
                                    icon : "success",
                                    toast: true,
                                    position: 'top-right',
                                    timer: 3000,
                                    showConfirmButton: false,
                                });
                                window.location.href = "/my-orders";
                            }
                        })
                    },
                    "prefill": {
                        "name": response.fname+' '+response.lname,
                        "email": response.email,
                        "contact": response.phone
                    },
                    "notes": {
                        "address": "Razorpay Corporate Office"
                    },
                    "theme": {
                        "color": "#3399cc"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            }
        })
    })
});