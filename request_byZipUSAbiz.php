<?php
// request_byZipUSAbiz.php
//SELECT  primary_city, state,zip,estimated_population FROM `usa_zip_codes`
//where zip in ('10025') and `estimated_population`>'10000' group by primary_city
session_start();
require_once 'main.php';
$db = DB1::getInstance();
//$CityStateUSA = new CityStateUSA();
$IndustryUSA = new IndustryUSA();

if ( empty($_SESSION['level']) || $_SESSION['level'] < 4 ){
    header ('Location: login.php', true, 302);
    exit();
}
cabinet_html_head("YP GRABBER Dashboard","Yellow Pages","YP GRABBER Dashboard","");
echo "<script type=\"text/javascript\" src=\"js/jquery.form.js\"></script>";
cabinet_header("");
cabinet_content_top();

// if post data 'zip' & 'industry' were received
if (!empty($_POST['zip']) && !empty($_POST['industry'])){
    $_POST['zip'] = trim($_POST['zip']);
    if (preg_match("/^[0-9]{5}$/",$_POST['zip'])){
        $string = "'".$_POST['zip']."'";
        echo $string;
        exit();
    }
    else{
        if (preg_match("/[0-9]{5} [0-9]{5}/",$_POST['zip'])){
            $separator = " ";
        }
        elseif (preg_match("/[0-9]{5}\r[0-9]{5}/",$_POST['zip'])){
            $separator = "\r";
        }
        elseif (preg_match("/[0-9]{5}\n[0-9]{5}/",$_POST['zip'])){
            $separator = "\n";
        }
        elseif (preg_match("/[0-9]{5}\r\n[0-9]{5}/",$_POST['zip'])){
            $separator = "\r\n";
        }
        elseif (preg_match("/[0-9]{5},[0-9]{5}/",$_POST['zip'])){
            $separator = ",";
        }
        elseif (preg_match("/[0-9]{5};[0-9]{5}/",$_POST['zip'])){
            $separator = ";";
        }
        elseif (preg_match("/[0-9]{5}:[0-9]{5}/",$_POST['zip'])){
            $separator = ":";
        }
        if (empty($separator) ){
            exit("ERROR: wrong characters detected<br>");
        }
        $array = array_unique(explode($separator,$_POST['zip']));
        //print_r($array);
        $string = "";
        foreach ($array as $value){
            $string .="'".$value."',";
        }
        $ziplist = trim($string,",");
        $sql = "SELECT DISTINCT `state` from `usa_zip_codes` WHERE `zip` in ({$ziplist}) ORDER BY `state` ASC";
        $result = $db->query($sql);
        if (!$result){ printf("[%d] %s\n", $db->errno, $db->error); exit();}
        if ( $result->num_rows > 0 ) {
            while($row = $result->fetch_assoc()){
                 $StateArr[] = $row;
            }
            //print_r($StateArr);
            //print_r($_POST['industry']);
            $result->free();
            //$statecounter = 0;
            $serialize_industry = serialize($_POST['industry']);
            foreach ( $StateArr as $row ){
                $sql = "SELECT * from `usa_zip_codes` WHERE `zip` in ({$ziplist}) AND `state`='".$row['state']."' GROUP BY `usa_zip_codes`.`primary_city` ASC";
                $result = $db->query($sql);
                if (!$result)  { printf("[%d] %s\n", $db->errno, $db->error); exit();}
                //$ResultArr = array();                
//                    while($row = $result->fetch_assoc()){
//                        $ResultArr[] = $row;
//                    }  
?>
                    <script type="text/javascript">
                    $(document).ready(function(){
                        //when the form is submitted
                        $("#form_<?php echo $row['state'];?>").submit(function(e){
                          //prevent the form from actually submitting.
                          //e.preventDefault();                                                 
                          //alert('#form_<?php //echo $row['state'];?> has been submited');
                          //put your PHP URL in here..
                          var url = "internal/enqueueByZip.php";
                          //create empty object
                          var obj = {};
                          //grab the checkboxes and put in arr
                          var arr = $(this).serializeArray();                          
                          //var city = new Array();
                          //$("input:checked").each(function() {
                          //data['city<?php //echo $statecounter;?>[]'].push($(this).val());
                          //});
                          //iterate over the array and change it into an object
                          for (var i = 0; i < arr.length; ++i) obj[arr[i].name] = arr[i].value;
                          //post the data to the server
                          //$.post(url, obj, function(r){
                            //log the response when it is complete.
                          //  console.log(r);
                          $.ajax({
                                type: 'POST', // Works with 'GET', but failing with 'POST'
                                url: url,
                                data: arr,
                                //data: obj,
                                //data: { city:city },
                                //success: function(result){alert(result);}
                                success: function(html){$("#Info_<?php echo $row['state'];?>").html(html);
                                $('#DivForm_<?php echo $row['state'];?>').hide();}
                          });
                          return false;
                        });
                        $(function() {
                                $('.checkbox_<?php echo $row['state'];?>').click(function(){
                                $('#postme_<?php echo $row['state'];?>').prop('disabled',$('input.checkbox_<?php echo $row['state'];?>:checked').length == 0);
                                                 });
                                });
                      });
                      </script>
<?php
                    echo "<h3>".$row['state']."</h3>";
                    echo "<div id=\"Info_".$row['state']."\"></div>";
                    echo "<div id=\"DivForm_".$row['state']."\">";
                    echo "Select cities, you want add to query<br />";
                    echo "<form id=\"form_".$row['state']."\" method=\"post\" action=\"internal/enqueueByZip.php\">";                   
                    echo "<script language=\"JavaScript\">
                            function toggle_".$row['state']."(source) {
                            checkboxes_".$row['state']." = document.getElementsByName('city_".$row['state']."[]');
                            for(var i=0, n=checkboxes_".$row['state'].".length;i<n;i++) {
                            checkboxes_".$row['state']."[i].checked = source.checked;
                             }
                           }
                           </script> \n";
                    echo "<table class=\"citymultiselect\">\n";
                    echo "<tr><th colspan=\"2\">City</th><th>Zip</th><th>County</th><th>Popullation</th></tr>\n";
                    //$count = 0;
                    while($row2 = $result->fetch_assoc()) {
                        //$outputTr = ($count % 3) == 0;
                        //if($outputTr) echo '<tr>';
                        echo "<tr>\n";
                        echo "<td><input type=\"checkbox\" class=\"checkbox_".$row['state']."\" name=\"city_".$row['state']."[]\" value=\"".$row2['primary_city']."\"/> </td>
                            <td><b>".$row2['primary_city']." </b></td>";
                        echo"<td>".$row2['zip']."</td>";
                        echo"<td>".$row2['county']."</td>";
                        echo"<td>".$row2['estimated_population']."</td>";
                        echo "</tr>\n";
                        //if($outputTr) echo '</tr>';
                        //$count++;
                    }
                echo "<tr>";
                echo "<td><input type=\"checkbox\" class=\"checkbox_".$row['state']."\" onClick=\"toggle_".$row['state']."(this)\" /></td><td colspan=\"4\"> <i>Toggle All</i> </td></tr>\n";
                echo "</table>";
                echo "<input type=\"hidden\" name=\"state\" value=\"".$row['state']."\">\n";
                echo "<input type=\"hidden\" name=\"industry\" value=\"".base64_encode($serialize_industry)."\">";
                echo "<input type=\"submit\" id=\"postme_".$row['state']."\" value=\"Submit\" disabled=\"disabled\">\n";
                echo "</form><br>\n";
                echo "</div>";
                //$statecounter++;
                $result->free();
                //print_r($ResultArr);
            }
        }
        //cabinet_footer();
        exit();
        }
    }
    //else exit("zip and industry cant' be empty. Please go back and select something.")
?>
<div class="zipinsert">
    <form method="post">
        <p>Insert zip codes, separated by space or new line</p>
        <textarea name='zip'></textarea>
<?php
$indystryList = $IndustryUSA->getAllIndustries($db);

        echo "<script language=\"JavaScript\">
                            function toggle(source) {
                            checkboxes = document.getElementsByName('industry[]');
                            for(var i=0, n=checkboxes.length;i<n;i++) {
                            checkboxes[i].checked = source.checked;
                             }
                           }
                          </script> \n";
        echo "<p>Select industries</p>";
        echo "<table>";
        $count = 0;
        echo "<tr>";
        foreach ($indystryList as $industry){
                        if ($count == 2){echo "<tr>";}
                        echo "<td><input type=\"checkbox\" name=\"industry[]\" value=\"".$industry['ind_id']."\"/> ".$industry['Industry_title']."</td>";
                        $count++;
                        if ($count == 2){ echo "</tr>"; $count =0; }

                    }
        echo "</tr>";
        echo "<tr><td colspan=\"2\"><input type=\"checkbox\" onClick=\"toggle(this)\" /> <i>Toggle all</i></td></tr>"
?>
    </table>
    <input type="submit">
    </form>
</div>
<?php
//cabinet_footer();
$db->close();
?>