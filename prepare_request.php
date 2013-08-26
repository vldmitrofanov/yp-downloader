<?php
//prepare_request.php
//require_once ('./main.php');
if (!defined('INCLUDING')){
    exit();
}
?>

<script src="./js/prepare_request.js" type="text/javascript"></script>

<div class="prepare_request">
    <h3>Start here:</h3>
<form id="frm1" method=post action="getYP_US_leads.php">
<!--<form id="frm1" method=post action="post_form.php"> -->
    
 <div class="styled-select">
  <select id="state" name="state" disabled="disabled">
            <option>Select State</option>
        </select>
 </div>
 
 <div class="styled-select">
  <select id="city" name="city" disabled="disabled">
            <option>Select City</option>
        </select>
 </div>
 
  <div class="styled-select">
  <select id="industry" name="industry" disabled="disabled">
            <option>Select Industry</option>
        </select>
 </div>
  
<div id="selectBox"></div>
<div id="selectBoxInfo"></div>
<div id="buttonPR1">
    <a href="#" class="more" onclick=formSubmit('frm1')>Submit</a>
<!--    <input type=button onclick=formSubmit('frm1') value='Submit form'>-->
</div>
</form>

<div id="links">If there no desired city, or state,<br /> then
<a href="#" id="AddCityA">Add City</a> to a state you want to download,
<br /> and this state will appear in this drop-down</div>
<form id="addCityForm">
<div id="AddCity">
    <div class="styled-select">
    <select id="stateAdd" name="stateAdd">
   </select></div>
<div class="styled-select">
<input type="text" id="add_city" name="add_city" disabled="disabled"></input>
</div>
    <div class="buttonPR2">
    <a href="#" class="more" id="addCityFormSubmit">Add City</a>
<!--<input type="submit" class="more" value='Add City'>-->
    </div>
</div>
</form>

<div id="AddCityInfo"></div>

<div id="link_ind"><a href="#" id="AddIndA">Add a New Industry</a></div>
<form id="addIndForm">
<div id="AddInd">
    <div class="styled-select">
<input type="text" id="add_ind" name="add_ind"></input>
    </div>
    <div class="buttonPR2">
<!--<input type="submit" value='Add Industry'>-->
<a href="#" class="more" id="addIndFormSubmit">Add Industry</a>
    </div>
    <div id="AddIndInfo"></div>
</div>
</form>

</div>

