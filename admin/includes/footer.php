</div><br><br>
        <footer class="col-md-12 text-center" id="footer">&copy; Copyright 2018-2022 Aizen Clothes</footer>
        <script>
        function updatesize(){
            var SizeString = '';
            for(var i=1;i<=12;i++){
                if(jQuery('#size'+i).val() != ''){
                    SizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';

                }


            }
            jQuery('#size').val(SizeString);
            
        }


        function child_category(selected){
            if(typeof selected === 'undefined')
            {
                var selected = '';
            }
             var parentid = jQuery('#parent').val();
            jQuery.ajax({
                url : '/ecom/admin/parser/child_categories.php',
                type: 'POST',
                data: {parentid: parentid,selected:selected},
                success:function(data){
                    jQuery('#child').html(data);

                },
                error: function(){
                    alert('Something Went Wrong.')
                }

            });
       
        }
      

        
        
        jQuery('select[name="parent"]').change(function(){
            child_category();
        });
         }
         
              
      

        </script>

   


    </body>
</html>