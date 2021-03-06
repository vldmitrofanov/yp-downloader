$(document).ready(function(){
 loadState(0,"state");
 loadState("All","stateAdd");
 $('#AddCity').hide();
 $('#buttonPR1').hide();
 $('#AddCityA').click(function(){
         $('#AddCity').slideToggle("slow");
  });
 $('#AddInd').hide();
 $('#AddIndA').click(function(){
        $('#AddInd').slideToggle("slow");
  });
$('#addCityFormSubmit').click(function(){
                   $.ajax({  
                    type: "POST",  
                    url: "internal/AddCityUSA.php",  
                    data: "add_city="+$("#add_city").val()+"&stateAdd="+$("#stateAdd").val(),  
                    success: function(html){  
                        $("#AddCityInfo").html(html);
                        loadState(0,"state");
                        loadCity(0);
                        loadIndustry(0);
                        $("input[name='add_city']").val('');
                    }  
                });  
                return false;  
});
//  $('#addCityForm').submit(function(){  
//                $.ajax({  
//                    type: "POST",  
//                    url: "internal/AddCityUSA.php",  
//                    data: "add_city="+$("#add_city").val()+"&stateAdd="+$("#stateAdd").val(),  
//                    success: function(html){  
//                        $("#AddCityInfo").html(html);
//                        loadState(0,"state");
//                        loadCity(0);
//                        loadIndustry(0);
//                        $("input[name='add_city']").val('');
//                    }  
//                });  
//                return false;  
//            });
  $('#addIndFormSubmit').click(function(){  
                $.ajax({  
                    type: "POST",  
                    url: "post_form.php",  
                    data: "add_ind="+$("#add_ind").val(),  
                    success: function(html){  
                        $("#AddIndInfo").html(html);
                        loadState(0,"state");
                        loadCity(0);
                        loadIndustry(0);
                        //$("input[type='text']").val('');
                        $("input[name='add_ind']").val('');

                    }  
                });  
                return false;  
            });   
 }); 
function formSubmit(form)
{
document.getElementById(form).submit();
}
  function loadState(id,select)
        {
            var stateSelect = $('select[name='+select+']');
            stateSelect.attr('disabled', 'disabled'); //Disable state list
            $.ajax({
                url:"./internal/getStateCity_jq.php",cache:false,data:{idcategory:id},success:function(data){
                var optionsObj=eval( "("+data+")" );
                if (!$.isEmptyObject(optionsObj)){
                stateSelect.html(''); // Clean state list
                
                // filling state list
                stateSelect.append("<option value=''>Select State</option>");
                $.each(optionsObj, function(i, val) {
                stateSelect.append("<option value='"+ val.value +"'>"+ val.caption +"</option>");
                });
           
                stateSelect.removeAttr('disabled'); // Enable state list
                stateSelect.off('change');                
                stateSelect.change(function(){
                    $('#buttonPR1').hide();
                    $('#industry').attr('disabled','disabled');
                    $('#industry').css('border','1px solid #CCC');
                    stateSelect.find("option[value='']").remove();
                    if (id!="All"){
                        if ($(this).val() > 0){
                            $(this).nextAll().remove();
                            var state=$(this).val();
                            //alert(state);
                            loadCity($(this).val())
                        }else{
                            loadCity(0);
                            loadIndustry(0);
                        }
                    }else{
                        var addCityInput = $('input[name="add_city"]');
                        if($(this).val()!=''){
                            addCityInput.removeAttr('disabled');
                        }else{
                            addCityInput.attr('disabled', 'disabled');
                            $("input[name='add_city']").val('');
                        }
                    }
                   });
               }
            }  
            });
        }
   function loadCity(stateid)
        {
            $('#city').css('border','2px solid #EE178C');
            var citySelect = $('select[name="city"]');
            citySelect.attr('disabled', 'disabled'); //Disable city list
            if (stateid == "0"){
             $('#buttonPR1').hide();
             citySelect.html('');
            }else{
            $.ajax({url:"./internal/getStateCity_jq.php",cache:false,data:{idcategory:stateid},
             success:function(data2){
             var optionsObj=eval( "("+data2+")" );
               if (!$.isEmptyObject(optionsObj)){
               citySelect.html(''); // Clean city list
                
                // filling state list
                citySelect.append("<option value=''>Select City</option>");
                citySelect.append("<option value='all'>Select ALL</option>");
                $.each(optionsObj, function(i, val) {
                    citySelect.append("<option value='"+ val.value +"'>"+ val.caption +"</option>");
                });
               
                citySelect.removeAttr('disabled'); // Enable city list
                citySelect.off('change');                
                citySelect.change(function(){
                    $('#city').css('border','1px solid #CCC');
                    citySelect.find("option[value='']").remove();
                     if ($(this).val() == ''){
                         $('#buttonPR1').hide();
                         $('#city').css('backgroundColor','#EE178C');
                     }  
                     if ($(this).val() == 'all'){
                     window.location = 'multiselect_request.php?state='+ stateid;
                     }
                      $(this).nextAll().remove();
                      var city=$(this).val();
                      var region = city + "-" + stateid;
                      //alert(region);
                      //$('div#buttonPR1').slideDown("slow");
                      loadIndustry(region);
                    });                
               }else{
                    $("#selectBoxInfo").html("There no Cities, click Add City").    
                    fadeIn(1500,function(){
                    $(this).fadeOut(1500);
                    });
                }
            }  
            });
            }
        }
function loadIndustry(region)
        {
            $('#buttonPR1').hide();
            $('#industry').css('border','2px solid #EE178C');
            var industrySelect = $('select[name="industry"]');
            industrySelect.attr('disabled', 'disabled'); //Disable city list
            if (region == "0"){
             $('#buttonPR1').hide();
             industrySelect.html('');
            }else{
            $.ajax({url:"./internal/getIndustry_jq.php",cache:false,data:{region:region},success:function(data3){
               var optionsObj=eval( "("+data3+")" );
               if (!$.isEmptyObject(optionsObj)){
               industrySelect.html(''); // Clean city list
                
                // filling state list
                industrySelect.append("<option value=''>Select Industry</option>");
                $.each(optionsObj, function(i, val) {
                    industrySelect.append("<option value='"+ val.value +"'>"+ val.caption +"</option>");
                });
                industrySelect.removeAttr('disabled'); // Enable city list
                industrySelect.off('change');                
                industrySelect.change(function(){ 
                    $('#industry').css('border','1px solid #CCC');
                    industrySelect.find("option[value='']").remove();
                      $(this).nextAll().remove();
                      var industry=$(this).val();
                      //alert(industry);
                      $('#buttonPR1').slideDown("slow");
                    });                
               }else{
                    $("#selectBoxInfo").html("<font color='red'>There no any Industry left, click Add Industry</font>").    
                    fadeIn(1500,function(){
                    $(this).fadeOut(4500);
                    });
                }
            }  
            });
            }
        }

