<?php
function cabinet_html_head($title,$keywords,$description,$additional_styles){
        echo"
        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
        <html xmlns=\"http://www.w3.org/1999/xhtml\">
        <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <title>\"".$title."\"</title>
        <meta name=\"keywords\" content=\"".$keywords."\" />
        <meta name=\"description\" content=\"".$description."\" />
        <link href=\"templates/templatemo/css/templatemo_style.css\" rel=\"stylesheet\" type=\"text/css\" />
        <link href=\"css/yp_style.css\" rel=\"stylesheet\" type=\"text/css\" />
         ".$additional_styles."
        <script type=\"text/javascript\" src=\"templates/templatemo/js/swfobject.js\"></script>
        <script type=\"text/javascript\">
                var flashvars = {};
                flashvars.xml_file = \"photo_list.xml\";
                var params = {};
                params.wmode = \"transparent\";
                var attributes = {};
                attributes.id = \"slider\";
                swfobject.embedSWF(\"flash_slider.swf\", \"flash_grid_slider\", \"480\", \"360\", \"9.0.0\", false, flashvars, params, attributes);
        </script>

        <script language=\"javascript\" type=\"text/javascript\">
        function clearText(field)
        {
            if (field.defaultValue == field.value) field.value = '';
            else if (field.value == '') field.value = field.defaultValue;
        }
        function goBack()
        {
            window.history.back()
        }
        </script>

        <link rel=\"stylesheet\" type=\"text/css\" href=\"templates/templatemo/css/ddsmoothmenu.css\" />
        
        <!--<link rel=\"stylesheet\" type=\"text/css\" href=\"css/jquery.autocomplete.css\" />-->
        
        <script type=\"text/javascript\" src=\"js/jquery_v1.7.2.js\"></script>
        <script type=\"text/javascript\" src=\"js/jquery-ui-1.8.9.custom.min.js\"></script> 
        
        <!--<script type=\"text/javascript\" src=\"templates/templatemo/js/jquery.min.js\"></script>-->
        
        <script type=\"text/javascript\ src=\"js/jquery.autocomplete.1.2.2.js\"></script>
        <script type=\"text/javascript\" src=\"templates/templatemo/js/ddsmoothmenu.js\">        
        /***********************************************
        * Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
        * This notice MUST stay intact for legal use
        * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
        ***********************************************/
        </script>
        <script type=\"text/javascript\">
           $(document).ready(function(){
                $(\"a[href='#']\").click(function(e) {
                        e.preventDefault();
                        });
                 });
        </script>

        <script type=\"text/javascript\">

        ddsmoothmenu.init({
                mainmenuid: \"templatemo_menu\", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to \"h\" or \"v\"
                classname: 'ddsmoothmenu', //class added to menu's outer DIV
                //customtheme: [\"#1c5a80\", \"#18374a\"],
                contentsource: \"markup\" //\"markup\" or [\"container_id\", \"path_to_menu_file\"]
        })

        </script>

        </head>     
        ";
}

function cabinet_header($top_banner){
    
    
    ?>

<body id="sub_page">
<div id="templatemo_wrapper">
	<div id="templatemo_top">
    	<div id="templatemo_login">
<?php 
        if ( !empty($_SESSION['user'])){
            echo "<font style=\"color: #fff;\">Welcome&nbsp;&nbsp;&nbsp;<a href=\"user.php\"><strong style=\"color: #fff;\">".$_SESSION['user']."</a>!&nbsp;&nbsp;&nbsp;&nbsp;</strong></font><a href=\"logout.php\">Logout</a>";
        }
        else{
?>
            <form action="login.php" method="post">
              <input type="text" value="username" name="login" size="10" id="username" title="username" onfocus="clearText(this)" onblur="clearText(this)" class="txt_field" />
              <input type="password" value="password" name="passwd" size="10" id="password" title="password" onfocus="clearText(this)" onblur="clearText(this)" class="txt_field" />
              <input type="submit" name="Submit" value="" alt="Submit" id="searchbutton" title="Submit" class="sub_btn"  />
            </form>
<?php
       }
?>
		</div>
    </div> <!-- end of top -->
    
  <div id="templatemo_header">
    	<div id="site_title"><h1><a href="<?php echo _TOP_LOGO_HTML_;?>">Free CSS Templates</a></h1></div>
        <div id="templatemo_menu" class="ddsmoothmenu">
            <ul>
              	<li><a href="index.html">Home</a></li>
          		<li><a href="dashboard.php" class="selected">Dashboard</a>
                    <ul>
                        <li><a href="dashboard.php?action=usaBuz">USA Business</a></li>
                        <li><a href="submenupage.html">Sub menu 2</a></li>
                        <li><a href="submenupage.html">Sub menu 3</a></li>
                  	</ul>
              	</li>
          		<li><a href="<?php echo _TOOLS_HTML_;?>"><?php echo _TOOLS_;?></a>
                    <ul>
                        <li><a href="<?php echo _TOOLS_SUBMEN1_HTML_;?>"><?php echo _TOOLS_SUBMEN1_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN2_HTML_;?>"><?php echo _TOOLS_SUBMEN2_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN3_HTML_;?>"><?php echo _TOOLS_SUBMEN3_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN4_HTML_;?>"><?php echo _TOOLS_SUBMEN4_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN5_HTML_;?>"><?php echo _TOOLS_SUBMEN5_;?></a></li>
                  	</ul>
              	</li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="contact.html">Contact</a></li>
            </ul>
            <br style="clear: left" />
        </div> <!-- end of templatemo_menu -->
    </div> <!-- end of header -->

<?php 
if (!empty($top_banner)){
        switch ($top_banner){
           case "show_YP_predict_search":
               show_YP_predict_search();
           default:
               $top_banner = "";
        }
    }
    else {
            $top_banner ="";
        } 
?>
    
    <div id="templatemo_main">
<?php
}

function cabinet_header_wide(){
    ?>

<body id="sub_page">
<div id="templatemo_wrapper_wide">
	<div id="templatemo_top">
    	<div id="templatemo_login">
<?php 
        if ( !empty($_SESSION['user'])){
            echo "<font style=\"color: #fff;\">Welcome&nbsp;&nbsp;&nbsp;<a href=\"user.php\"><strong style=\"color: #fff;\">".$_SESSION['user']."</a>!&nbsp;&nbsp;&nbsp;&nbsp;</strong></font><a href=\"logout.php\">Logout</a>";
        }
        else{
?>
            <form action="login.php" method="post">
              <input type="text" value="username" name="login" size="10" id="username" title="username" onfocus="clearText(this)" onblur="clearText(this)" class="txt_field" />
              <input type="password" value="password" name="passwd" size="10" id="password" title="password" onfocus="clearText(this)" onblur="clearText(this)" class="txt_field" />
              <input type="submit" name="Submit" value="" alt="Submit" id="searchbutton" title="Submit" class="sub_btn"  />
            </form>
<?php
       }
?>
		</div>
    </div> <!-- end of top -->
    
  <div id="templatemo_header_wide">
    	<div id="site_title"><h1><a href="http://www.templatemo.com">Free CSS Templates</a></h1></div>
        <div id="templatemo_menu" class="ddsmoothmenu">
            <ul>
              	<li><a href="index.html">Home</a></li>
          		<li><a href="dashboard.php" class="selected">Dashboard</a>
                    <ul>
                        <li><a href="dashboard.php?action=usaBuz">USA Business</a></li>
                        <li><a href="submenupage.html">Sub menu 2</a></li>
                        <li><a href="submenupage.html">Sub menu 3</a></li>
                  	</ul>
              	</li>
          		<li><a href="<?php echo _TOOLS_HTML_;?>"><?php echo _TOOLS_;?></a>
                    <ul>
                        <li><a href="<?php echo _TOOLS_SUBMEN1_HTML_;?>"><?php echo _TOOLS_SUBMEN1_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN2_HTML_;?>"><?php echo _TOOLS_SUBMEN2_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN3_HTML_;?>"><?php echo _TOOLS_SUBMEN3_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN4_HTML_;?>"><?php echo _TOOLS_SUBMEN4_;?></a></li>
                        <li><a href="<?php echo _TOOLS_SUBMEN5_HTML_;?>"><?php echo _TOOLS_SUBMEN5_;?></a></li>
                  	</ul>
              	</li>
				<li><a href="blog.html">Blog</a></li>
				<li><a href="contact.html">Contact</a></li>
            </ul>
            <br style="clear: left" />
        </div> <!-- end of templatemo_menu -->
    </div> <!-- end of header -->


    
    <div id="templatemo_main_wide">
        <?php 
}
function show_YP_predict_search(){
?>
        <style>

        </style>
<script type="text/javascript">
//$(document).ready(function(){
//    $('#search-terms').autocomplete({
//     serviceUrl: 'internal/IndustryPredict.php', // Страница для обработки запросов автозаполнения
//     minChars: 1, // Минимальная длина запроса для срабатывания автозаполнения
//     delimiter: /(,|;)\s*/, // Разделитель для нескольких запросов, символ или регулярное выражение
//     maxHeight: 400, // Максимальная высота списка подсказок, в пикселях
//     width: 300, // Ширина списка
//     zIndex: 9999, // z-index списка
//     deferRequestBy: 1, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
//     params: { country: 'Yes'}, // Дополнительные параметры
//     onSelect: function(data, value){ }, // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
//     lookup: ['January', 'February', 'March'] // Список вариантов для локального автозаполнения
// });
//});
   $(function() {           
            $("#search-location").autocomplete({
	    source: function(request,response) {
	      $.ajax({
		type:"GET",
	        url: "internal/AddressPredict.php",
                dataType: "json",
                deferRequestBy: 80,
	        data: {
	         // featureClass: "P",
	        //  style: "full",
	        //  maxRows: 12,
	          qgss: request.term
	        },
		//data:{query:q},
	        success: function(data) {
	          response($.map(data, function(item) {
	            return {
	              label: item.matchstring,
	              value: item.matchstring 
	            }
	          }));

	        }
	      });
	    },
	    //minLength: 3,
            minChars: 1,
	    select: function(event,ui) {
	      $("<p/>").text(ui.item ? ui.item.value : "Ничего не выбрано!").prependTo("#log");
	      $("#log").attr("scrollTop", 0);
	    }
	  });
	  $("#search-terms").autocomplete({
	    source: function(request,response) {
	      $.ajax({
		type:"GET",
	        url: "internal/IndustryPredict.php",
                dataType: "json",
                deferRequestBy: 80,
	        data: {
	         // featureClass: "P",
	        //  style: "full",
	        //  maxRows: 12,
	          q: request.term
	        },
		//data:{query:q},
	        success: function(data) {
//                    var optionsObj=eval( "("+data+")" );
//                    response($.each(optionsObj, function(i, item) {
//                        return {label: item.suggestion,value: item.suggestion}}));
	          response($.map(data, function(item) {
	            return {
	              label: item.suggestion,
	              value: item.suggestion 
	            }
	          }));

	        }
	      });
	    },
	    //minLength: 3,
            minChars: 1,
	    select: function(event,ui) {
	      $("<p/>").text(ui.item ? ui.item.value : "Ничего не выбрано!").prependTo("#log");
	      $("#log").attr("scrollTop", 0);
	    }
	  });
	});
	
//$(function() {
//	  $("#search-terms").autocomplete({
//	    source: function(request,response) {
//	      $.ajax({
//	        url: "http://ws.geonames.org/searchJSON",
//	        dataType: "jsonp",
//	        data: {
//	          featureClass: "P",
//	          style: "full",
//	          maxRows: 12,
//	          name_startsWith: request.term
//	        },
//	        success: function(data) {
//	          response($.map(data.geonames, function(item) {
//	            return {
//	              label: item.name + ", " + item.countryName,
//	              value: item.name + " (" + item.countryName + ")" + " [" + item.lat + ", " + item.lng + "]"
//	            }
//	          }));
//	        }
//	      });
//	    },
//	    minLength: 3,
//	    select: function(event,ui) {
//	      $("<p/>").text(ui.item ? ui.item.value : "Ничего не выбрано!").prependTo("#log");
//	      $("#log").attr("scrollTop", 0);
//	    }
//	  });
//	});

</script>
<div class="home_main">
    <div id="global-header">
        <div class="header-panel" style="background-color: #D9D8D0; background-image: url('images/default_1.jpg');">
            <div class="panel">
                <div class="header-branding"><a href="http://www.yellowpages.com/" class="no-tracks yp-logo">YP.com</a></div>
                    <div class="header-search">
                        <form action="quick_load.php" class="no-tracks track-form search-form" id="standard-search-form" method="post" >
                        <input id="tracks" name="tracks" type="hidden" value="true" />
                        <span class="label">
                        <label for="search-terms">
                        What are you looking for?
                        </label>
                        <input data-search_term="" id="search-terms" maxlength="100" name="industry" tabindex="1" type="text" value=""  />
                        </span>
                        <span class="near">near</span>
                        <span class="label">
                        <label for="search-location">
                        Where?
                        </label>
                        <input data-geo_term="CA" id="search-location" name="address" tabindex="2" type="text" value="" />
                        </span>
                        <input class="findbutton" id="search-submit" tabindex="3" type="submit" value="Find" />
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php 
        }
        ?>