<?php ob_start(); ?>
<?php
//require_once './Mobile_Detect.php';
//$detect = new Mobile_Detect;
//$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
//$scriptVersion = $detect->getScriptVersion();
//echo $deviceType;

//	header("p3p: CP=ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV CAO PSA OUR");
//	
//	
//	require_once("mobile_device_detect.php");
//	
//	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//
//        $path = parse_url($url, PHP_URL_PATH);
//	
//	$path = explode("/", $path);
//	
//	
//	$url = parse_url($url, PHP_URL_SCHEME) . "://" . parse_url($url, PHP_URL_HOST);
//	
//	if($path[1] != null)
//            mobile_device_detect(true, false, true,true, true, true, false, $url . "/m/". $path[1], "");
//	else
//            mobile_device_detect(true, false, true,true, true, true, false, $url . "/m/",  "");
?>
<?php
session_start();
require_once  __DIR__ . '/model/Global.php';
require_once __DIR__ . '/model/ConnectDB.php';
//require_once './libs/mobile_device_detect.php';

$current_page = "index.php";
$hide_section_header = "display:none;";
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>vakargo</title>
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<!--<script type="text/javascript" src="./js/detectmobilebrowser.js"></script>-->
	<?php require_once './includeHeader.php'; ?>
        
        <style type="text/css">
	    .memberlist
	    {
		margin-top: 10px;
		width: 48%;
	    }
	    .memberlist tr td
	    {
		text-align: center;
	    }

	    .participator{
		font-size: 12px;
		font-weight: normal;	
		padding: 2px 0 10px 0;
	    }
            
            .checkbox{
                background: url("images/unchecked.png") no-repeat scroll center center;
                height: 16px;
                padding: 3px;
                width: 16px;
		cursor: pointer;
                float: left;
                background-size: 18px;
                outline: none;
	    }
            
	    .checked 
            {
		background: url("images/checked.png") no-repeat scroll center center;
                height: 16px;
                padding: 3px;
                width: 16px;
		cursor: pointer;
                float: left;
                background-size: 18px;
                outline: none;
	    }
        </style>
        
        <script type="text/javascript">
//            navigator.geolocation.getCurrentPosition(GetLocation);
//            function GetLocation(location) 
//            {
//                console.log(location.coords.latitude);
//                console.log(location.coords.longitude);
//                console.log(location.coords.accuracy);
//                console.log("https://maps.google.com/maps?q=" + location.coords.latitude +  "," + location.coords.longitude + "+&hl=en");
//            }
        </script>
        
        <script type="text/javascript">
	    function getLoggedInMenu()
	    {
		$.ajax({
		    url: './generate_menu.php',
		    type: 'GET',
		    dataType: 'html',
		    data: {
		    },
		    beforeSend: (function() {

		    }),
		    success: (function(response) {
			$("#accountmenu").html(response);
			$("#accountmenu").show();
			$("#gotosignup").hide();
		    }),
		    error: (function(e)
		    {
		    })
		});
	    }

	    function clearHeader()
	    {
		$("#gotoaboutus").css("background", "");
		$("#gotocontactus").css("background", "");
	    }

	    function reAlignSection()
	    {
		var height = $("#signup").height();
		var margintop = (height - $(".ship_container").height()) / 3;
		$(".ship_container").css("margin-top", margintop);
		$(".aboutus_container").css("padding-top", "15px");
		$(".aboutus_container").css("margin-bottom", "10px");
		$(".contactus_container").css("margin-top", "100px");
	    }

	    function zoomSite()
	    {
		var screenWidth = $("body").width();
		var scale = screenWidth / 1350;
		$("body").css("zoom", scale);
	    }

	    $(document).ready(function()
	    {
		//zoomSite();

		$("#subshippingcontrol").hide();

		$("#click_tolistyourspace").click(function()
		{
		    window.location = "./listyourspace.php";
		});
		$("#click_tolistyourspace_header").click(function()
		{
		    window.location = "./listyourspace.php";
		});
		//Resize height of sections;
		var flag_disablescroll = false;
		
		
                  
		//alert(navigator.userAgent);
		var isMobile = isMobileDetected(); //khai bao trong file vakargo.js
		if(isMobile)
		//if($(window).width()<1000)
		{
			$("#all_section").width("1100px");
		}
		else
		{
			$("#all_section").width("100%");
		}
		
		if ($(window).height() <= 600)
		{
		    $("#signup").height(600 - $('header').height());
		    //Aboutus is auto height
		    $("#contactus").height(600 - $('header').height());
		   
		}
		else
		{
		    $("#signup").height($(window).height() - $('header').height() - $(window).height() * 0.05);
		    //$("#aboutus").height($(window).height() - $('header').height() - $(window).height() * 0.05);
		    $("#contactus").height($(window).height() - $('header').height() - $("#bottom_line_wrapping").height() * 5);
		    
		}

		reAlignSection();

		$("#order_content").css("min-height", $("#table_checkbox").height());

		$("#gotoaboutus").attr('unselectable', 'on').css('UserSelect', 'none').css('MozUserSelect', 'none');
		$("#gotocontactus").attr('unselectable', 'on').css('UserSelect', 'none').css('MozUserSelect', 'none');

		var top_singup = $('#signup').offset().top - $('header').height() - 200;
		var top_aboutsub = $('#aboutus').offset().top - $('header').height() - 200;
		var top_contactsub = $('#contactus').offset().top - $('header').height() - 200;
		$(window).scroll(function()
		{
		    if (window.pageYOffset >= $("#citytextbox").offset().top - $('header').height() + $("#citytextbox").height())
		    {
			$("#subshippingcontrol").fadeIn(200, null);
			$("#citytextbox").val($("#citybis").val());
			$("#ui-id-2").hide();
		    }
		    else
		    {
			$("#subshippingcontrol").fadeOut(200, null);
			$("#citybis").val($("#citytextbox").val());
			$("#ui-id-2").hide();
		    }

		    if (window.pageYOffset >= top_singup && window.pageYOffset <= top_aboutsub - 10)
		    {
			clearHeader();
		    }
		    else
		    if (window.pageYOffset >= top_aboutsub - 10 && window.pageYOffset <= top_contactsub - 10)
		    {
			if (flag_disablescroll === false)
			{
			    clearHeader();
			    $("#gotoaboutus").css("background", "url('./images/menu_indicator.png') repeat-x");
			}
		    }
		    else
		    if (window.pageYOffset >= top_contactsub - 10)
		    {
			if (flag_disablescroll === false)
			{
			    clearHeader();
			    $("#gotocontactus").css("background", "url('./images/menu_indicator.png') repeat-x");
			}
		    }
		});

		$("#citytextbox").click(function(e) {
		    var top_singup = $('#signup').offset().top - $('header').height();
		    $('html, body').animate({scrollTop: top_singup}, 'slow');
		    clearHeader();
		});

		$("#gotocontactus").click(function()
		{
		    var top_contact = $('#contactus').offset().top - $('header').height();
		    clearHeader();
		    flag_disablescroll = true;
		    $("#gotocontactus").css("background", "url('./images/menu_indicator.png') repeat-x");
		    $('html,body').animate({scrollTop: top_contact}, 'slow');
		    flag_disablescroll = false;
		});
		
		$("#gototraveltips").click(function(){
		    
		});

		$("#gotoaboutus").click(function()
		{
		    var top_about = $('#aboutus').offset().top - $('header').height();
		    clearHeader();
		    flag_disablescroll = true;
		    $("#gotoaboutus").css("background", "url('./images/menu_indicator.png') repeat-x");
		    $('html, body').animate({scrollTop: top_about}, 'slow');
		    flag_disablescroll = false;
		});

		$("#logo").click(function()
		{
		    var top_singup = $('#signup').offset().top - $('header').height();
		    $('html, body').animate({scrollTop: top_singup}, 'slow');
		    clearHeader();
		    flag_disablescroll = false;
		});

		$("#subscribebutton").keypress(function()
		{
		    $("#subscribeOK").hide();
		    $("#subscribeFAIL").show(100);
		});

		$("#subscribebutton").click(function()
		{
		    $("#subscribeOK").hide();
		    $("#subscribeFAIL").hide();

		    var email = $("#emailsubscribe").val();
		    if(!isValidEmailAddress(email))
		    {
				$("#subscribeFAIL").html("INVALID EMAIL ADDRESS! PLEASE TRY AGAIN!");
				$("#subscribeFAIL").show(100);
				$("#subscribeOK").hide();
					
				return;
		    }
		    if (email !== "" && email === $("#emailsubscribe_hidden").val())
		    {
				$("#subscribeFAIL").html("Oops! Looks like you've already subscribed to our list!");
				$("#subscribeFAIL").show(100);
				$("#subscribeOK").hide();
				return;
		    }
		    

		    $.ajax({
			url: './services/subscriber_register.php',
			type: 'GET',
			dataType: 'html',
			data: {
			    email: email
			},
			beforeSend: (function()
			{
			    $("#waitingsubscribe").css("display", "block");

			    $("#subscribebutton").attr("disabled", "disabled");
			    $("#subscribebutton").css("background", "#AAAAAA");
			}),
			success: (function(response)
			{
			    var as = JSON.parse(response)
			    if (as.result === true)
			    {
				$("#subscribeFAIL").html("Oops! Looks like you've already subscribed to our list")
				$("#subscribeFAIL").show(100);
				$("#subscribeOK").hide();
				
				$("#waitingsubscribe").css("display", "none");
				$("#emailsubscribe_hidden").val("");
				$("#emailsubscribe").val("");
				
				$("#subscribebutton").removeAttr("disabled")
				$("#subscribebutton").css("background", "#EA3556");
			    }
			    else
			    {
				$("#subscribeOK").show(100);
				$("#subscribeFAIL").hide();
				
				$("#emailsubscribe_hidden").val(email);
				$("#emailsubscribe").val("");
				$("#waitingsubscribe").css("display", "none");
				$("#subscribe_box").remove();
			    }
			    
			   
			}),
			error: (function(e)
			{
			    alert("Something is wrong, please try again!");
			    $("#waitingsubscribe").css("display", "none");

			    $("#subscribebutton").removeAttr("disabled")
			    $("#subscribebutton").css("background", "#EA3556");
			})
		    });


		});

		$("#resend_confirmationemail").click(function()
		{
		    $("#resend_confirmationemail").html("<img src='./images/ajax-loader.gif'/>");
		    $("#resend_confirmationemail").click(false);
		    $("#resend_confirmationemail").css("background", "#FFF");

		    $.ajax({
			url: './services/user_sendconfirmemail.php',
			type: 'GET',
			dataType: 'html',
			data: {
			    email: emailClient
			},
			beforeSend: (function() {

			}),
			success: (function(response) {
			    //alert(response)
			    $("#email_toconfirm").html(emailClient);
			    $("#transparentpage").css("display", "block");
			    $("#confirmedemailsentdialog").css("display", "block");

			    $("#transparentpage").click(function(e)
			    {
				$("#transparentpage").css("display", "none");
				$("#confirmedemailsentdialog").css("display", "none");
			    });

			    $("#resend_confirmationemail").hide();
			}),
			error: (function(e)
			{

			})
		    });
		});
	    });
        </script>

       
        <style type="text/css">
            #subscribeFAIL, #subscribeOK
            {
                display: none;
            }

        </style>
    </head>
    <body>
        <img id='transparentpage' src='images/bg_opacity.png' style='position: absolute; width: 100%;display: none'/>
        
	<?php require_once 'header.php'; ?>
        
         <script>
	    $(function() {
		$("#citytextbox").autocomplete({
		    source: function(request, response) {

			var kaeyword = ($.trim(request.term)).replace(/,.*/gim, "");
			//$("#dasdafasdf").val(kaeyword);

			$.ajax({
			    url: "./services/cities_getCities.php",
			    dataType: "json",
			    data: {
				limit: 20,
				keyword: kaeyword,
                                country: "<?php //echo $_COOKIE['country'];  ?>"
			    },
			    success: function(data) {
				
				response($.map(data.result, function(item) {
				    return {
					label: item.name + (item.state !== "" ? ", " + item.state : "") + ", " + item.countryname,
					id: item.geonameid
				    };
				}));
				//alert(kaeyword)
			    },
			    beforeSend: function()
			    {
				//alert(request.term.replace(",.*?",""))
			    },
			    statusCode: {
				404: function() {
				    alert("page not found");
				}
			    },
			    error: function(e)
			    {
				//alert(e.message);
			    }
			});
		    },
		    minLength: 1,
		    select: function(event, ui) {
			$("#citycode").val(ui.item.id);
			$("#city").val(ui.item.label);
		    },
		    open: function() {
			$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
			$("#ui-id-1").css("margin-left", "-10px");
			$("#ui-id-1").css("width", "410px");
		    },
		    close: function() {
			$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
		    }
		});
		$("#citybis").autocomplete({
		    source: function(request, response) {
			$.ajax({
			    url: "./services/cities_getCities.php",
			    dataType: "json",
			    data: {
				limit: 20,
				keyword: ($.trim(request.term)).replace(/,.*/gim, ""),
                                country: "<?php echo $_COOKIE['country'];  ?>"
			    },
			    success: function(data) {
				response($.map(data.result, function(item) {
				    return {
					label: item.name + (item.state !== "" ? ", " + item.state : "") + ", " + item.countryname,
					id: item.geonameid
				    };
				}));
			    },
			    statusCode: {
				404: function() {
				    alert("page not found");
				}
			    },
			    error: function(err) {
//				var err = eval("(" + xhr.responseText + ")");
				//alert(err.responseText);
			    }
			});
		    },
		    minLength: 1,
		    select: function(event, ui) {
			$("#citybis").val(ui.item.label);
		    },
		    open: function() {
			$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
			$("#ui-id-1").css("margin-left", "-6px");
			$("#ui-id-1").css("width", "300px");
		    },
		    close: function() {
			$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
		    }
		});
	    });
            
            
            
            var isFeedbackOpen = false;
            
            function ClickFeedback()
            {
                if(isFeedbackOpen === false)
                {
                    $("#all_section").animate({"margin-left": "190px"}, 300, function(){

                                        });
                    $("#btn_feedback").animate({"margin-left": "190px"}, 300, function(){
                                            $("#btn_feedback").attr("src", "images/feedback_open.png");
                                        });
                    
                    $("#all_section").width($("#all_section").width() + 90);
                    
                    $("#feedback_container").animate({"margin-left": "0px"}, 300, function(){
                                        });
                    
                    isFeedbackOpen = true;
                }
                else
                {
                    $("#all_section").animate({"margin-left": "0px"}, 300, function(){

                                        });
                    $("#btn_feedback").animate({"margin-left": "0px"}, 300, function(){
                                            $("#btn_feedback").attr("src", "images/feedback_close.png");
                                        });
                    $("#all_section").width($("#all_section").width() - 90);
                    
                    $("#feedback_container").animate({"margin-left": "-190px"}, 300, function(){})
                    isFeedbackOpen = false;
                }
            }
            
            $("#btn_submit_feedback").click(function(){
                
            })
        </script>
        <div id="all_section" >
        <section id="signup" class="ship_section">

            <div class="ship_container">
                <div class="ship_box">
                    <div class="order_title">NEED<br>SOMETHING<br>SHIPPED?</div>
		    <form id="form_search" action="searchresults.php" method="GET">
			<table id="table_checkbox" border="0">
			    <tr>
				<td class="redtext" rowspan="2" width="90px">YOU ARE<br>SENDING:</td>
				<td width="180px">                                    
                                    <input id="electtronic_devices" name="el_t" type='checkbox' class='checkbox' style="float: left; margin-top: -4px;" onclick="onClickCheckbox(this)"> 
                                    <label style="float: left;" for="electtronic_devices">Electronic Devices</label>
                                </td>
				<td width="180px">
                                    <input id="books" name="bo_t" type='checkbox' class='checkbox' style="float: left; margin-top: -4px;" onclick="onClickCheckbox(this)"> 
                                    <label style="float: left;" for="books"><?php
                                     $translate->__('Books');?></label>
                                </td>
			    </tr>
			    <tr>
				<td width="180px" >
                                    <input id="clothes" name="cl_t" type='checkbox' class='checkbox' style="float: left; margin-top: -4px;" onclick="onClickCheckbox(this)"> 
                                    <label style="float: left;" for="clothes">Clothes</label>
                                </td>
			    </tr>
			</table>

			<table id="table_search" class="table_search" border="0" width="100%">
			    <tr>
				<td>
				    <div style="text-align: right; border-style: solid; border-color: #9B9B9B; border-width: 1px; height: 40px; clear: both; background: #FFF">
					<style>
					    #btn_search, #btn_search:active, #btn_search:hover, #btn_search:focus{
						outline: none;
					    }
					</style>
					<script>
					    $(document).ready(function()
					    {
						$("#btn_search2").click(function()
						{
						    $("#form_search").submit();
						});
					    });
					</script>
					<img id="btn_search2" src="images/search_icon.png" style="width: 45px; float: right; outline: 0; cursor: pointer;">

					<input id="citytextbox" class="search_textbox" type="text" placeholder="Space seekers, type destination here" name="q" style="font-family: proxima_nova; font-size: 14px;"/>   
					<input id="citycode" name="citycode" type="hidden"/>
				    </div>

				</td>
			    </tr>
			</table>
		    </form>
                </div>

                <div class="extraspace_box">
                    <div class="order_title">HAVE<br>EXTRA<br>SPACE?</div>
                    <div id="order_content" class="order_content">If you have extra luggage space, list
                        it here! We can fill that hole and you can earn some extra spending
                        money on your next vacation!
                    </div>
                    
                    <input id="click_tolistyourspace" class="listspace" type="button" value="TRAVELERS, CLICK HERE TO LIST YOUR SPACE!">

                </div>
            </div>
        </section>

        <section id="aboutus" class="aboutus">
            <div class="aboutus_container">
                <div class="aboutusdescription" style="overflow:hidden">
                    <div class="videointro" style="margin-top: 20px;">
                        <iframe width="535" height="300" src="http://www.youtube.com/embed/zyP4oZOenaA?&wmode=opaque&controls=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <div class="description" style="font-size: 13px; margin-top: 12px;">
                        <div>
			<h3>OUR STORY</h3>
			<p>The vakargo team is composed of innovative entrepreneurs who travel in and 

			    outside of the United States.  When traveling overseas, we look forward to seeing 

			    and visiting our distant friends and family. Although the trips outside the U.S. may 

			    be few and far between, it is a rewarding experience to bring gifts to our loved ones.

			    As a favor, we will “share our luggage space” to carry gifts (kargo) for our friends or 

			    relatives back in the U.S. to bring to their friends or family overseas. After several 

			    trips around the world, we then realized that every day, someone is traveling 

			    somewhere either carrying kargo for someone or just traveling with spare luggage 

			    space. </p>

			<p style="padding-top: 10px;">“What if that spare luggage space could be ‘shared’ by the economy?” we asked 

			    ourselves.  This question spawned the conceptualization of an online social network 

			    that would be convenient, efficient, structured, and cost-effective in delivering kargo

			    across the globe.  This idea will change the way we deliver kargo by making it quick, 

			    lucrative, and reducing the use of natural resources.<p>
                        <h3>OUR TEAM</h3>
                        The vakargo team is comprised of many unique facets and skill sets with personnel 

			spanning across the United States and in Vietnam working together to make this 

			happen.  We are very motivated and passionate entrepreneurs who love using and 

			integrating technology to improve our daily lives. We are geeks at heart and some of 

			us look like geeks; however, we are different than typical geeks because… we LOVE 

			boba tea!
			</div>
			
			<div>
			    <div class="memberlist" style="width: 110%; height: auto; float: right; margin-right: -20px; ">
				<table width="100%">
				    <tr>
					<td style="width: 100px"><img src="images/christshilling.png" width="80px"></td>
					<td style="width: 100px"><img src="images/nhahoang.png" width="80px"></td>
					<td style="width: 100px"><img src="images/kellytran.png" width="80px"></td>
					<td style="width: 100px"><img src="images/lisaphan.png" width="80px"></td>
				    </tr>
				    <tr>
					<td class="participator">Chris Shilling</td>
					<td class="participator">Nha Hoang</td>
					<td class="participator">Kelly Tran</td>
					<td class="participator">Lisa Phan</td>
				    </tr>
				    <tr>
                                        <td style="padding-top: 10px"><img src="images/mimi.png" width="80px" style="border-radius: 50%;"></td>
					<td></td>
					<td></td>
					<td></td>
				    </tr>
				    <tr>
					<td class="participator">Mimi</td>
					<td class="participator"></td>
					<td class="participator"></td>
					<td class="participator"></td>
				    </tr>
				</table>

			    </div>
			</div>
			
                    </div>

                </div>

            </div>
       
        </section>

        <section class="line-wrapping" id="middle_line_wrapping"></section>

        <section id="contactus" class="contactus">
            <div class="contactus_container">
                <div class="contactus_box">
                    <div class="contact_header">CONTACT US!</div>

                    <div style="vertical-align: middle; margin-top: 15px; width: 350px; border: solid #9B9B9B 1px; height: auto; clear: both; overflow: hidden; background: #FFF">
                        <img src="images/email_white.png" style="float: left;border-style: solid; border-color: #9B9B9B; border-width:0px; border-right-width: 1px; ">
                        <a href="mailto:contact@vakargo.com" style="float: left; vertical-align: middle; margin:12px 0 0 15px; font-size: 14px; color: #EB3657; font-weight: bold;">contact@vakargo.com</a>
                    </div>

                    <div style="vertical-align: middle; margin-top: 15px; width: 350px; border: solid #9B9B9B 1px; height: auto; clear: both; overflow: hidden; background: #FFF">
                        <img src="images/twitter_white.png" style="float: left;border-style: solid; border-color: #9B9B9B; border-width:0px; border-right-width: 1px; ">
                        <a href="https://twitter.com/vakargo" style="float: left; vertical-align: middle; margin:12px 0 0 15px; font-size: 14px; color: #4BC4EE; font-weight: bold;">@vakargo</a>
                    </div>
                    <div style="vertical-align: middle; margin-top: 15px; width: 350px; border: solid #9B9B9B 1px; height: auto; clear: both; overflow: hidden; background: #FFF">
                        <img src="images/facebook_white.png" style="float: left; border-right-width: 1px; ">
                        <a href="https://www.facebook.com/vakargo" style="float: left; vertical-align: middle; margin:12px 0 0 15px; font-size: 14px; color: #294A8C; font-weight: bold;">/vakargo</a>
                    </div>
                </div>

                <div class="description">
                    <div class="contact_header">STAY UPDATED!</div>
                    <div style="margin-top: 10px;">vakargo is constantly changing and improving. Subscribe to our mailing list and stay updated on the lastest news about vakargo!</div>

                    <div id='subscribe_box' style="text-align: right; margin-top: 20px; border-style: solid; border-color: #9B9B9B; border-width: 1px; height: 40px; background: #FFF">
                        <input id="subscribebutton" type="button" value="SUBSCRIBE" style="background:#EA3656; width: 24%; float: right; margin: 0;">
                        <img id="waitingsubscribe" src="images/ajax-loader.gif" style=" float: right; margin: 4px 4px 0 0; display: none; width: 32px;"/>
                        <input id="emailsubscribe" class="search_textbox" type="text" autocomplete="off" placeholder="Type email address here" name="keyword" style="font-family: proxima_nova; font-size: 16px; float: left; width: 65%; height: 34px; border-style: none; outline: none; padding-left: 10px;">   
                        <input id="emailsubscribe_hidden" type="hidden" value="">   
                    </div>
                    <div id="subscribeOK" style="margin-top: 20px; padding: 10px; color: #9B9B9B; font-size: 18px;"><p>You're all set. You should receive an email shortly.</p></div>
                    <div id="subscribeFAIL" style="margin-top: 20px; padding: 10px; color: #D7394C; font-size: 18px;"><p>Your email is subscribed.</p></div>
                </div>
            </div>
        </section>
    
        <section class="line-wrapping" id="bottom_line_wrapping" style="height: 20px;">

        </section>
    </div>
    <footer>
        
    </footer>
</body>
</html>