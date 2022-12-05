$('.owl-carousel').owlCarousel({
    margin:10,
    dots:false,
    responsiveClass:true,
    responsive:{
        0:{
            items:2
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
})
var owl = $('.owl-carousel');
owl.owlCarousel();
// Go to the next item
$('.customNextBtn').click(function() {
    owl.trigger('next.owl.carousel');
})
// Go to the previous item
$('.customPrevBtn').click(function() {
    // With optional speed parameter
    // Parameters has to be in square bracket '[]'
    owl.trigger('prev.owl.carousel', [300]);
})
$(document).ready(function (){
    cartCount();
    wishlistCount();
    
    function cartCount()
    {
        $.ajax({
            method: "GET",
            url: "/cart-count",
            success: function(response){
                $('.cart-count').html('');
                $('.cart-count').html(response.count);
            }
        })
    }    

    function wishlistCount()
    {
        $.ajax({
            method: "GET",
            url: "/wishlist-count",
            success: function(response){
                $('.wishlist-count').html('');
                $('.wishlist-count').html(response.count);
            }
        })
    }    
    // add to cart
    $('.add-to-cart').click(function (e){
        e.preventDefault();

        var product_id = $(this).closest('.product-data').find('.product-id').val();
        var product_qty = $(this).closest('.product-data').find('.qty-input').val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            method: "POST",
            url: "/add-to-cart",
            data: {
                'product_id': product_id,
                'product_qty': product_qty,
            },
            success: function (response){
                if(response.status){
                    Swal.fire({
                        title : response.status,
                        icon : "success",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    cartCount();
                }else if(response.error){
                    Swal.fire({
                        title : response.error,
                        icon : "error",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }else{
                    Swal.fire({
                        title : response.warning,
                        icon : "warning",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            },
            error: function (response){
                if(response.status){
                    Swal.fire({
                        title : "Login to continue",
                        icon : "error",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            }
        });
    });
    //add to wihlist
    $('.add-to-wishlist').click(function (e){
        e.preventDefault();

        var product_id = $(this).closest('.product-data').find('.product-id').val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        console.log(product_id);
        $.ajax({
            method: "POST",
            url: "/wishlist",
            data: {
                'product_id': product_id,
            },
            success: function (response){
                if(response.status){
                    Swal.fire({
                        title : response.status,
                        icon : "success",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    wishlistCount();
                }else if(response.error){
                    Swal.fire({
                        title : response.error,
                        icon : "error",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }else{
                    Swal.fire({
                        title : response.warning,
                        icon : "warning",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            },
            error: function (response){
                if(response.status){
                    Swal.fire({
                        title : "Login to continue",
                        icon : "error",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            }
        });
    });

    $('.increment-btn').click(function (e){
        e.preventDefault();

        var inc_value = $(this).closest('.product-data').find('.qty-input').val();
        var value = parseInt(inc_value, 10);
        value = isNaN(value) ? 0 : value;
        if(value < 10){
            value++;
            $(this).closest('.product-data').find('.qty-input').val(value);
        }
    });
    $('.decrement-btn').click(function (e){
        e.preventDefault();

        var dec_value = $(this).closest('.product-data').find('.qty-input').val();
        var value = parseInt(dec_value, 10);
        value = isNaN(value) ? 0 : value;
        if(value > 1){
            value--;
            $(this).closest('.product-data').find('.qty-input').val(value);
        }
    });

    $('.changeQuantity').click(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var product_id = $(this).closest('.product-data').find('.product-id').val();
        var product_qty = $(this).closest('.product-data').find('.qty-input').val();
        data = {
            'product_id' : product_id,
            'product_qty': product_qty
        }
        $.ajax({
            method: "POST",
            url: "update-cart",
            data: data,
            success: function (response){
                window.location.reload();
            }
        });
    });

    $('.delete-item').click(function (e){
        e.preventDefault();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var product_id = $(this).closest('.product-data').find('.product-id').val();

        $.ajax({
            method: "POST",
            url: "delete-cart-item",
            data: {
                'product_id' : product_id,
            },
            success: function(response){
                if(response.status){
                    Swal.fire({
                        title : response.status,
                        icon : "success",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    window.location.reload();
                    cartCount();
                }
            },
            error: function (response){
                if(response.status){
                    Swal.fire({
                        title : "Login to continue",
                        icon : "error",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            }
        });
    });

    $('.delete-wishlist').click(function (e){
        e.preventDefault();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var product_id = $(this).closest('.product-data').find('.product-id').val();

        $.ajax({
            method: "DELETE",
            url: "/wishlist/"+product_id,
            data: {
                'product_id' : product_id,
            },
            success: function(response){
                if(response.status){
                    Swal.fire({
                        title : response.status,
                        icon : "success",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                    window.location.reload();
                    wishlistCount();
                }
            },
            error: function (response){
                if(response.status){
                    Swal.fire({
                        title : "Login to continue",
                        icon : "error",
                        toast: true,
                        position: 'top-right',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            }
        });
    });
});