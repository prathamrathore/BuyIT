</div>
        <footer class="text-center" id="footer">&copy; Copyright 2018-2022 Aizen Clothes</footer>

   

        <script>
        jQuery(window).scroll(function()
            {
                var vscroll = jQuery(this).scrollTop();
                jQuery('#text').css({
                    "transform" : "translate(0px,"+vscroll/2+"px)"
                });
                var vscroll = jQuery(this).scrollTop();
                jQuery('#image').css({
                    "transform" : "translate(0px,"+vscroll/9+"px)"
                });
            }
        );


        function detailsmodal(id){
           var data = {"id" : id};
           jQuery.ajax({
               url :  '/ecom/includes/detailsmodal.php',
               method : "post",
               data : data,
               success : function(data){
                   jQuery('body').prepend(data);
                   jQuery('#details-modal').modal('toggle');
               },
               error : function(){
                   alert("Something went wrong!");
               }

           });
        }
        function add_to_cart(){
          jQuery('#modal_errors').html("");
          var size = jQuery('#size').val();
          var quantity = jQuery('#quantity').val();
          var available = jQuery('#available').val();
         var data =  jQuery('#add_product_form').serialize();
         var error = '';
         if(size == '' || quantity == '' || quantity == 0){
            error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';
             jQuery('#modal_errors').html(error);
        
             return;
         }else if(quantity > available){
             error += '<p class="text-danger text-center">There are only '+available+' available.</p>';
             jQuery('#modal_errors').html(error);
             return;
         }
         else{
             jQuery.ajax({
                 url : '/ecom/admin/parser/add_cart.php',
                 method: "post",
                 data : data,
                 success : function(){
                    location.reload();
                 },
                 error : function(){
                     alert("something went wrong.");
                 }

             });

         }
      
         }
        
        </script>
    </body>
</html>