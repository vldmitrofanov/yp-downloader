<?php

function cabinet_content($cabinet_content){
    ?>
     <div id="templatemo_content">
         <?php echo $cabinet_content;?>
           </div> <!-- end of content -->
<?php
}
function cabinet_content_top(){
    echo "<div id=\"templatemo_content\">";
}
function cabinet_content_bottom(){
    echo " </div> <!-- end of content -->";
}
function cabinet_content_test(){
?>
 <div id="templatemo_content">
			<div class="col_fw">
                <h1>About Us</h1>
                <div class="image_frame_300 image_fl"><span></span><img src="images/templatemo_image_01.png" alt="Image 01" /></div>
                <p><em>Fusce vulputate ipsum eget nisl accumsan sit amet rhoncus nulla consectetur. Duis molestie quam sit amet nibh ullamcorper elementum. </em></p>
                <p>Donec porttitor egestas lacus. Nulla lobortis, mi eget fermentum eleifend, nisi risus congue lacus, a varius risus ipsum ac sem. <a href="#">Vivamus vel odio</a> mauris, a tempus mauris. In suscipit, lacus ut rhoncus aliquam, neque nibh convallis felis, et tincidunt elit arcu id metus. Nam tincidunt venenatis ipsum, in fermentum arcu accumsan at. Cras malesuada, odio sed dignissim vestibulum, massa urna porttitor dui, vel iaculis nulla nunc eget mi. Validate <a href="http://validator.w3.org/check?uri=referer" rel="nofollow"><strong>XHTML</strong></a> &amp; <a href="http://jigsaw.w3.org/css-validator/check/referer" rel="nofollow"><strong>CSS</strong></a>.</p>
                <a href="#" class="more">More</a>
            </div>
             <div class="col_fw_last">                 
				
                <div class="col_w300 float_l">
                	<h2>Our Services</h2>
                    <p><em>Pellentesque habitant morbi senectus et netus et malesuada fames ac turpis egestas. </em></p>
                    <ul class="templatemo_list">
                    	<li>Fusce nec felis id lacus</li>
                        <li>Morbi lacinia mauris</li>
                        <li>Suspendisse eu lorem</li>
                        <li>Ut mollis leo non tortor</li>
                    </ul>
                  <div class="cleaner h10"></div>
                    <a href="#" class="more">More</a>
                </div>
                
                <div class="col_w300 float_r">
                	<h2>Testimonial</h2>
                    <blockquote>
                    <p>Praesent tincidunt pharetra tellus, eget faucibus nulla dignissim varius. Nullam molestie mollis ullamcorper. Integer mauris tortor, viverra et vestibulum ac, aliquet vestibulum tortor. Sed rutrum porta elit. Aliquam erat volutpat.</p>
                    <cite><a href="#">Steve</a> - <span>Web Designer</span></cite>
                    </blockquote>
                    <div class="cleaner h30"></div>                    
					<a href="#" class="more">More</a>
                </div>
                
                <div class="cleaner"></div>
			</div>	
        </div> <!-- end of content -->
<?php
}
?>
