<?php
require_once '../grplib-php/init.php';
$pagetitle = "Messages".($dev_server ? ' ('.CONFIG_SRV_ENV.')' : '');
require_once 'lib/htm.php';
require_once '../grplib-php/user-helper.php';
$bodyClass = "add-post-page";
printHeader();
$me = $mysql->query("SELECT * FROM `people` WHERE `pid` = '".$mysql->real_escape_string($_SESSION["pid"])."'")->fetch_assoc();
$miime = getMii($me, false);
if(!empty($_GET['user_id']) || !empty($_GET['conversation_id'])) {
    if(empty($_GET['conversation_id'])) {
    $search_person = $mysql->query('SELECT * FROM people WHERE people.user_id = "'.$mysql->real_escape_string($_GET['user_id']).'"');
    if($search_person->num_rows == 0) { require '404.php';  exit(); } else { $person = $search_person->fetch_assoc(); $mii = getMii($person, false); }
    $relationship = getFriendRelationship($_SESSION["pid"], $person['pid']);
    if(!$relationship) { plainErr(403, '403 Forbidden');  exit(); }
    if(!empty($relationship['conversation_id'])) {
    $conversation_id = $relationship['conversation_id']; }
    else {
    $create_conversation = $mysql->query('INSERT INTO conversations(sender, recipient) VALUES("'.$_SESSION["pid"].'", "'.$person['pid'].'")');
    if(!$create_conversation) {
    plainErr(500, '500 Internal Server Error');  exit(); }
    $conversation_id = $mysql->query('SELECT * FROM conversations WHERE conversations.conversation_id = "'.$mysql->insert_id.'" LIMIT 1')->fetch_assoc()['conversation_id'];
    }
    } else {
    $mode = 1;
    if($me['privilege'] <= 6) { plainErr(403, '403 Forbidden');  exit(); }
    $search_conversation = $mysql->query('SELECT * FROM conversations WHERE conversations.conversation_id = "'.$mysql->real_escape_string($_GET['conversation_id']).'" LIMIT 1');
    if(!$search_conversation || $search_conversation->num_rows == 0) { plainErr(404, '404 Not Found');  exit(); }
    $conversation_id = $search_conversation->fetch_assoc()['conversation_id'];
    }
} else {
    header("Location: /news/dms");
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
if(!empty($_POST["screenshot"])){
    plainErr(403, 'nuh uh');
    exit();
  }
  if(!empty($_POST["painting"])){
    if($_POST["painting"] == "Qk1SEwAAAAAAAJIAAAB8AAAAQAEAAHgAAAABAAEAAAAAAMASAAASCwAAEgsAAAIAAAACAAAAAAD/AAD/AAD/AAAAAAAA/0JHUnOPwvUoUbgeFR6F6wEzMzMTZmZmJmZmZgaZmZkJPQrXAyhcjzIAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAP///wD/////////////////////+P/////8f/////////////////////////////////////////////j//////H/////////////////////////////////////////////4//////x/////////////////////////////////////////////+P/////8f////////////////////////////////////////4A///h//////H////////////////////////////////////////4AH//4f/////x////////////////////////////////////////+Hx///H/////8f////////////////////////////////////////j8H//x//////H////////////////////////////////////////4wB//8f/////x////////////////////////////////////////+AAP//D//h//8f////////////////////////////////////////wPh//4//4f//H////////////////////////////////////////8H4f/+P/+H//x/////////////////////////////////////////h/D//j/////8f////////////////////////////////////////4f4f/4//////H/////////////////////////////////////////D/D/+P/////x/////////////////////////////////////////w/w//j/////8f////////////////////////////////////////+H+H/4//////H/////////////////////////////////////////w/g/+P/////x/////////////////////////////////////////8P+H/j//A//8f/////////////////////////////////////////h/h/4//wP//H/////////////////////////////////////////8f8P+P/4B//x//////////////////////////////////////////D/D/j/8Mf/8f/////////////////////////////////////////4f4f4/+HD//H/////////////////////////////////////////+D+H+P/h4//x//////////////////////////////////////////4fwfj/w+H/8f/wB//////////////////////////////////////+H+H4/8Ph//H/wAP//////////////////////////////////////w/w+H+H4P/x/wPA//////////////////////////////////////+H+Hx/h+D/8fwH4H//////////////////////////////////////x/w8f4Pgf/HwP/wf/////////////////////////////////////8P8HH8D4H/x4H/8D//////////////////////////////////////h/wx/A8A/8YP//4H/////////////////////////////////////8f8EfgPEP/EH///B//////////////////////////////////////D/gH4jxj/wH///8H/////////////////////////////////////4f8B+I8Y/8D////g//////////////////////////////////////D/gfCHGH/D/8B/8D/////////////////////////////////////4f8Hwhxx/w/+AP/gf/////////////////////////////////////D/h8cccf///Bg/+D/////////////////////////////////////4f//HHHH///g8H/wf/////////////////////////////////////D//hxhw///w/gf+D/////////////////////////////////////wf/48YeP//4P+D/4f/////////////////////////////////////D/+PCPj//4P/wf/A/////////////////////////////////////w//Dwj4//8H//B/4H////////////////////////////////////+D/x+A+P/+D//4H/h/////////////////////////////////////w/8fgPj//D///g/4D////////////////////////////////////+D/GAAAf/B///+D8AP////////////////////////////////////w/xAAAH/g////wPDD////////////////////////////////////+H8ADAB/w/////BB4f////////////////////////////////////w/Aggwf4f////4A/H////////////////////////////////////+HweA/D4P/////gfh/////////////////////////////////////w4Pgfw8H/////8Hwf///////////////////////////////////+AAAAB+EH//////wAP//////////////////////////////////+AAAAAAAAAf/////8AH//////////////////////////////////gAB+P//gAAAAH////////////////////////////////////////4B///////H4AB////////////////////////////////////////+P//////////4f////////////////////////////////////////j//////////+H////////////////////////////////////////4///////////j////////////////////////////////////////+P//////////4/////////////////////////////////////////j//////////8P////////////////////////////////////////4///////////D////////////////////////////////////////+P//////////x/////////////////////////////////////////j//////////8f////////////////////////////////////////4////wD/////H////////////////////////////////////////+P///4AP////x/////////////////////////////////////////h///wPAH///8f////////////////////////////////////////4f//4H8Af///H//////////////////////////////////////8ADH//wP/+AP//x//////////////////////////////////////8AAB//wH//4B//8YB////////////////////////////////////+D/Af/wP///8B//AAAP///////////////////////////////////j/8H/4H////gH/wHgA/////////////////////////////////8AA//x/8P/////gP8f/8H///////AAAAAAAAP////////////////+AAP/8f+H/////+A/H//x///////wAAAAAAAD////////////////+D////H+D8P///H8Bx//4f//////////////w/////////////////h////x/B/D///x/wEf/8AH///////////////////////////////4////8fB/x///8f/gH//AAP//////////////////////////////+H////HA/8f///H/8B///+B/////////////////////////////8AA////xg//H///x//4f///8H////////////////////////////8AAP///8Q//x///8f//H////h////////////////////////////8D//////Af/4f///H//x////8f///////////////////////////+D//////wP/+P///x//8f////H////////////////////////////D//////8H//j///8f//H////h////////////////////////////h///////H//4////H//x////w////////////////////////////4///////x///////x//8D///gf///////////////////////////+P/////8Af//////////AH//4D////////////////////////////j/////AAH//////////xAP/+AD////////H//////////////////4/////ADj//////////8eB///Af///////w//////////////////+P///wA/4///////////H8H///D///////+H//////////////////h///wA/8P//////////x/gf//w//////g/g//////////////////8P//AP//D//////////8f+B//+P/////4P+P//////////////////D/+AP//w/gAAAAAAAAAH/4H//j/////+H/j//////////////////4P4A///8AAAAAAAAAAAB//gf/4////j/w/////////////////////BgB////gAf///////////+D/+P///4f+P////////////////////4AH///////////////////4H/D////D/h/4D//////////////////Af////////////////////Afg/x//w/AP+A////////////////////////////////////////+Awf8f/+MAB/h/////////////////////////////////////////wAP/D//gA4f8f/////////////////////////////////////////wP/4//8D/D///////////////////////////////////////////+D/+P//A/4///////////////////////////////////////////////j//4f+H//////////////////////////////////////////////4f//H/x///////////////////////////////////////////////H//w/////////////////////////////////////////////////w//8H////////////////////////////////////////////////+P//x/////////////////////////////////////////////////h////////////////////////////////////////////////////8P////////////////////////////////////////////////////D////////////////////////////////////////////////////4////////////////////////////////////////////////////+P////////////////////////////////////////////////////j////////////////////////////////////////////////////4//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8="){
        http_response_code(400); header('Content-Type: application/json'); print json_encode(array('success' => 0, 'errors' => [array(
            'message' => "use offdevice ya nerd :p",
            'error_code' => 4204200
            )], 'code' => 400));  exit();
    }
    $painting = base64_decode($_POST['painting']);
    $painting_name = rand(100000,999999).'.png';
    file_put_contents('../grp_portal-php/img/drawings/'.$painting_name, $painting);
    $posttype = "artwork";
    $_POST["screenshot"] = "https://rvqcportal.rverse.club/img/drawings/".$painting_name;
  }
  # If the method is POST, then post.
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once '../grplib-php/user-helper.php';
  if(!empty($_GET['conversation_id'])) {
  if($me['privilege'] <= 6) { dserror(1210403, "Forbidden"); } else    {
  $search_conversation = $mysql->query('SELECT * FROM conversations WHERE conversations.conversation_id = "'.$mysql->real_escape_string($_GET['conversation_id']).'" LIMIT 1');
  if(!$search_conversation || $search_conversation->num_rows == 0) { dserror(1210404, "Not found"); }
      }   
  } else {
  $user_id = userIDtoPID($mysql->real_escape_string($_GET['user_id']));
  if(!$user_id) { plainErr(404, '404 Not Found');  exit(); }
  $relationship = getFriendRelationship($_SESSION["pid"], $user_id);
  if(!$relationship) { plainErr(403, '403 Forbidden');  exit(); }
  }
  require_once '../grplib-php/post-helper.php';
  $is_post_valid = postValid($me, 'upload');
  if($is_post_valid != 'ok') {
  if($is_post_valid == 'blank') {
  $error_message[] = 'The content you have entered is blank.
  Please enter content into your post.';
  $error_code[] = 1515001; }
  elseif($is_post_valid == 'max') {
  $error_message[] = 'You have exceeded the amount of characters that you can send.';
  $error_code[] = 1515002; }
  } if(!empty($error_code)) {
  dserror($error_message[0], $error_code[0]);
  }
  
  require_once '../grplib-php/olv-url-enc.php';
  $gen_olive_url = genURL();
  
  if(empty($_POST['feeling_id']) || strval($_POST['feeling_id']) >= 6 || !is_numeric($_POST['feeling_id'])) { $_POST['feeling_id'] = 0; }
  if(!empty($_GET['conversation_id'])) {
  $conversation_id = $search_conversation->fetch_assoc()['conversation_id'];
  }
  else {
  if(!empty($relationship['conversation_id'])) {
  $conversation_id = $relationship['conversation_id']; }
  else {
  $create_conversation = $mysql->query('INSERT INTO conversations(sender, recipient) VALUES("'.$_SESSION["pid"].'", "'.$user_id.'")');
  if(!$create_conversation) {
    dserror(1600000, "An internal error has occurred."); }
  $conversation_id = $create_conversation->fetch_assoc()['conversation_id'];
  } }
  
          $stmt_message = $mysql->prepare('INSERT INTO messages(conversation_id, id, pid, feeling_id, platform_id, body, screenshot, is_spoiler, has_read, created_from)
                  VALUES(?, ?, ?, ?, "1", ?, ?, ?, "0", ?)');
          $scrnst = (empty($_POST['screenshot']) ? '' : $_POST['screenshot']); $isspr = (empty($_POST['is_spoiler']) ? 0 : $_POST['is_spoiler']);
          $stmt_message->bind_param('isiissis', $conversation_id, $gen_olive_url, $_SESSION["pid"], $_POST['feeling_id'], $_POST['body'], $scrnst, $isspr, $_SERVER['REMOTE_ADDR']); $exec_msg_stmt = $stmt_message->execute();
  
  if(!$exec_msg_stmt) {
  http_response_code(500);
  header('Content-Type: application/json');
  dserror(1600000, "An internal error has occurred.");
  } else {
      if(!empty($relationship)) {
          $updateFM = $mysql->query('UPDATE friend_relationships SET updated = NOW() WHERE relationship_id = "'.$relationship['relationship_id'].'"');
      }
      header("Location: /friend_messages/".$_GET["user_id"]);
         }
   exit();
  }
}
?>
    <div id="header">
		<div id="header-body">
            <span class="post-subtype-default">Message to <?=htmlspecialchars($person["screen_name"])?> (<?=htmlspecialchars($person["user_id"])?>)</span>
        </div>
    </div>
    <div class="body-content">
        <div id="form-content">
            <form method="post" data-redirect-to="/friend_messages/<?=$_GET["user_id"]?>" data-is-own-title="0" enctype="multipart/form-data">
                <input type="hidden" name="kind" value="message">
                <div class="add-post-page-content">
                    <div class="feeling-selector expression">
								<ul class="buttons"><li class="checked"><input type="radio" name="feeling_id" value="0" class="feeling-button-normal" checked="" data-sound="SE_OLV_OK_SUB" autocomplete="off" data-save-user-input="1"></li><li class=""><input type="radio" name="feeling_id" value="1" class="feeling-button-happy" data-sound="SE_OLV_OK_SUB" autocomplete="off" data-save-user-input="1"></li><li class=""><input type="radio" name="feeling_id" value="2" class="feeling-button-like" data-sound="SE_OLV_OK_SUB" autocomplete="off" data-save-user-input="1"></li><li class=""><input type="radio" name="feeling_id" value="3" class="feeling-button-surprised" data-sound="SE_OLV_OK_SUB" autocomplete="off" data-save-user-input="1"></li><li class=""><input type="radio" name="feeling_id" value="4" class="feeling-button-frustrated" data-sound="SE_OLV_OK_SUB" autocomplete="off" data-save-user-input="1"></li><li class=""><input type="radio" name="feeling_id" value="5" class="feeling-button-puzzled" data-sound="SE_OLV_OK_SUB" autocomplete="off" data-save-user-input="1"></li></ul>
					</div>
                    <input type="hidden" name="album_image_id" value="" data-save-user-input="1">
                    <!--<div class="image-selector dropdown disabled">
                        <input id="image-input-file" type="file" name="screenshot" disabled="" data-sound="SE_OLV_OK_SUB">
                        <a class="dropdown-toggle forbidden-image-selector" href="javascript:void(0)">
                            <img class="preview-image" src="/img/add-post-image-forbidden.png" data-forbidden-src="/img/add-post-image-forbidden.png" data-default-src="/img/add-post-no-image.png">
                        </a>
                        <div class="dropdown-menu">
                            <div class="image-selector-window">
                                <div class="image-selector-section-wrapper">
                                    <div class="image-content">
                                        <label class="capture-button upside" style="">
                                            <img class="capture-image">
                                            <input type="radio" name="screenshot_type" value="upside" data-sound="SE_OLV_OK_SUB" data-save-user-input="1" autocomplete="off">
                                        </label>
                                        <label class="capture-button downside" style="">
                                            <img class="capture-image">
                                            <input type="radio" name="screenshot_type" value="downside" data-sound="SE_OLV_OK_SUB" data-save-user-input="1" autocomplete="off">
                                        </label>
                                    </div>
                                    <div class="image-selector-section-options">
                                        <div class="image-selector-section-options-content">
                                            <label class="no-select-button">
                                                No Screenshot
                                                <input type="radio" name="screenshot_type" value="null" checked="" data-value="" data-src="/img/add-post-no-image.png" data-sound="SE_OLV_CANCEL" data-save-user-input="1" autocomplete="off">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class="textarea-with-menu active-text">
								<menu class="textarea-menu">
									<li>
										<label class="textarea-menu-text">
											<input type="radio" name="_post_type" value="body" checked="" data-sound="SE_OLV_TEXTBOX" data-save-user-input="1" autocomplete="off">
										</label>
									</li>
									<li>
										<label class="textarea-menu-memo">
											<input type="radio" name="_post_type" value="painting" data-sound="" data-save-user-input="1" autocomplete="off">
										</label>
									</li>
								</menu>
								<textarea name="body" id="textarea" class="textarea-text" value="" maxlength="200" cave_oninput="$(document.activeElement).trigger('input')" placeholder="Write a message to a friend here." data-save-user-input="1" autocomplete="off" data-preview-class="textarea-text-preview"></textarea>
								<div class="textarea-memo trigger" data-sound="">
									<div class="textarea-memo-preview" style="background-image: url(&quot;data:image/bmp;base64,Qk1SEwAAAAAAAJIAAAB8AAAAQAEAAHgAAAABAAEAAAAAAMASAAASCwAAEgsAAAIAAAACAAAAAAD/AAD/AAD/AAAAAAAA/0JHUnOPwvUoUbgeFR6F6wEzMzMTZmZmJmZmZgaZmZkJPQrXAyhcjzIAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAP///wD/////////////////////+P/////8f/////////////////////////////////////////////j//////H/////////////////////////////////////////////4//////x/////////////////////////////////////////////+P/////8f////////////////////////////////////////4A///h//////H////////////////////////////////////////4AH//4f/////x////////////////////////////////////////+Hx///H/////8f////////////////////////////////////////j8H//x//////H////////////////////////////////////////4wB//8f/////x////////////////////////////////////////+AAP//D//h//8f////////////////////////////////////////wPh//4//4f//H////////////////////////////////////////8H4f/+P/+H//x/////////////////////////////////////////h/D//j/////8f////////////////////////////////////////4f4f/4//////H/////////////////////////////////////////D/D/+P/////x/////////////////////////////////////////w/w//j/////8f////////////////////////////////////////+H+H/4//////H/////////////////////////////////////////w/g/+P/////x/////////////////////////////////////////8P+H/j//A//8f/////////////////////////////////////////h/h/4//wP//H/////////////////////////////////////////8f8P+P/4B//x//////////////////////////////////////////D/D/j/8Mf/8f/////////////////////////////////////////4f4f4/+HD//H/////////////////////////////////////////+D+H+P/h4//x//////////////////////////////////////////4fwfj/w+H/8f/wB//////////////////////////////////////+H+H4/8Ph//H/wAP//////////////////////////////////////w/w+H+H4P/x/wPA//////////////////////////////////////+H+Hx/h+D/8fwH4H//////////////////////////////////////x/w8f4Pgf/HwP/wf/////////////////////////////////////8P8HH8D4H/x4H/8D//////////////////////////////////////h/wx/A8A/8YP//4H/////////////////////////////////////8f8EfgPEP/EH///B//////////////////////////////////////D/gH4jxj/wH///8H/////////////////////////////////////4f8B+I8Y/8D////g//////////////////////////////////////D/gfCHGH/D/8B/8D/////////////////////////////////////4f8Hwhxx/w/+AP/gf/////////////////////////////////////D/h8cccf///Bg/+D/////////////////////////////////////4f//HHHH///g8H/wf/////////////////////////////////////D//hxhw///w/gf+D/////////////////////////////////////wf/48YeP//4P+D/4f/////////////////////////////////////D/+PCPj//4P/wf/A/////////////////////////////////////w//Dwj4//8H//B/4H////////////////////////////////////+D/x+A+P/+D//4H/h/////////////////////////////////////w/8fgPj//D///g/4D////////////////////////////////////+D/GAAAf/B///+D8AP////////////////////////////////////w/xAAAH/g////wPDD////////////////////////////////////+H8ADAB/w/////BB4f////////////////////////////////////w/Aggwf4f////4A/H////////////////////////////////////+HweA/D4P/////gfh/////////////////////////////////////w4Pgfw8H/////8Hwf///////////////////////////////////+AAAAB+EH//////wAP//////////////////////////////////+AAAAAAAAAf/////8AH//////////////////////////////////gAB+P//gAAAAH////////////////////////////////////////4B///////H4AB////////////////////////////////////////+P//////////4f////////////////////////////////////////j//////////+H////////////////////////////////////////4///////////j////////////////////////////////////////+P//////////4/////////////////////////////////////////j//////////8P////////////////////////////////////////4///////////D////////////////////////////////////////+P//////////x/////////////////////////////////////////j//////////8f////////////////////////////////////////4////wD/////H////////////////////////////////////////+P///4AP////x/////////////////////////////////////////h///wPAH///8f////////////////////////////////////////4f//4H8Af///H//////////////////////////////////////8ADH//wP/+AP//x//////////////////////////////////////8AAB//wH//4B//8YB////////////////////////////////////+D/Af/wP///8B//AAAP///////////////////////////////////j/8H/4H////gH/wHgA/////////////////////////////////8AA//x/8P/////gP8f/8H///////AAAAAAAAP////////////////+AAP/8f+H/////+A/H//x///////wAAAAAAAD////////////////+D////H+D8P///H8Bx//4f//////////////w/////////////////h////x/B/D///x/wEf/8AH///////////////////////////////4////8fB/x///8f/gH//AAP//////////////////////////////+H////HA/8f///H/8B///+B/////////////////////////////8AA////xg//H///x//4f///8H////////////////////////////8AAP///8Q//x///8f//H////h////////////////////////////8D//////Af/4f///H//x////8f///////////////////////////+D//////wP/+P///x//8f////H////////////////////////////D//////8H//j///8f//H////h////////////////////////////h///////H//4////H//x////w////////////////////////////4///////x///////x//8D///gf///////////////////////////+P/////8Af//////////AH//4D////////////////////////////j/////AAH//////////xAP/+AD////////H//////////////////4/////ADj//////////8eB///Af///////w//////////////////+P///wA/4///////////H8H///D///////+H//////////////////h///wA/8P//////////x/gf//w//////g/g//////////////////8P//AP//D//////////8f+B//+P/////4P+P//////////////////D/+AP//w/gAAAAAAAAAH/4H//j/////+H/j//////////////////4P4A///8AAAAAAAAAAAB//gf/4////j/w/////////////////////BgB////gAf///////////+D/+P///4f+P////////////////////4AH///////////////////4H/D////D/h/4D//////////////////Af////////////////////Afg/x//w/AP+A////////////////////////////////////////+Awf8f/+MAB/h/////////////////////////////////////////wAP/D//gA4f8f/////////////////////////////////////////wP/4//8D/D///////////////////////////////////////////+D/+P//A/4///////////////////////////////////////////////j//4f+H//////////////////////////////////////////////4f//H/x///////////////////////////////////////////////H//w/////////////////////////////////////////////////w//8H////////////////////////////////////////////////+P//x/////////////////////////////////////////////////h////////////////////////////////////////////////////8P////////////////////////////////////////////////////D////////////////////////////////////////////////////4////////////////////////////////////////////////////+P////////////////////////////////////////////////////j////////////////////////////////////////////////////4//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8=&quot;);"></div>
									<input type="hidden" name="painting" disabled="" value="Qk1SEwAAAAAAAJIAAAB8AAAAQAEAAHgAAAABAAEAAAAAAMASAAASCwAAEgsAAAIAAAACAAAAAAD/AAD/AAD/AAAAAAAA/0JHUnOPwvUoUbgeFR6F6wEzMzMTZmZmJmZmZgaZmZkJPQrXAyhcjzIAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAP///wD/////////////////////+P/////8f/////////////////////////////////////////////j//////H/////////////////////////////////////////////4//////x/////////////////////////////////////////////+P/////8f////////////////////////////////////////4A///h//////H////////////////////////////////////////4AH//4f/////x////////////////////////////////////////+Hx///H/////8f////////////////////////////////////////j8H//x//////H////////////////////////////////////////4wB//8f/////x////////////////////////////////////////+AAP//D//h//8f////////////////////////////////////////wPh//4//4f//H////////////////////////////////////////8H4f/+P/+H//x/////////////////////////////////////////h/D//j/////8f////////////////////////////////////////4f4f/4//////H/////////////////////////////////////////D/D/+P/////x/////////////////////////////////////////w/w//j/////8f////////////////////////////////////////+H+H/4//////H/////////////////////////////////////////w/g/+P/////x/////////////////////////////////////////8P+H/j//A//8f/////////////////////////////////////////h/h/4//wP//H/////////////////////////////////////////8f8P+P/4B//x//////////////////////////////////////////D/D/j/8Mf/8f/////////////////////////////////////////4f4f4/+HD//H/////////////////////////////////////////+D+H+P/h4//x//////////////////////////////////////////4fwfj/w+H/8f/wB//////////////////////////////////////+H+H4/8Ph//H/wAP//////////////////////////////////////w/w+H+H4P/x/wPA//////////////////////////////////////+H+Hx/h+D/8fwH4H//////////////////////////////////////x/w8f4Pgf/HwP/wf/////////////////////////////////////8P8HH8D4H/x4H/8D//////////////////////////////////////h/wx/A8A/8YP//4H/////////////////////////////////////8f8EfgPEP/EH///B//////////////////////////////////////D/gH4jxj/wH///8H/////////////////////////////////////4f8B+I8Y/8D////g//////////////////////////////////////D/gfCHGH/D/8B/8D/////////////////////////////////////4f8Hwhxx/w/+AP/gf/////////////////////////////////////D/h8cccf///Bg/+D/////////////////////////////////////4f//HHHH///g8H/wf/////////////////////////////////////D//hxhw///w/gf+D/////////////////////////////////////wf/48YeP//4P+D/4f/////////////////////////////////////D/+PCPj//4P/wf/A/////////////////////////////////////w//Dwj4//8H//B/4H////////////////////////////////////+D/x+A+P/+D//4H/h/////////////////////////////////////w/8fgPj//D///g/4D////////////////////////////////////+D/GAAAf/B///+D8AP////////////////////////////////////w/xAAAH/g////wPDD////////////////////////////////////+H8ADAB/w/////BB4f////////////////////////////////////w/Aggwf4f////4A/H////////////////////////////////////+HweA/D4P/////gfh/////////////////////////////////////w4Pgfw8H/////8Hwf///////////////////////////////////+AAAAB+EH//////wAP//////////////////////////////////+AAAAAAAAAf/////8AH//////////////////////////////////gAB+P//gAAAAH////////////////////////////////////////4B///////H4AB////////////////////////////////////////+P//////////4f////////////////////////////////////////j//////////+H////////////////////////////////////////4///////////j////////////////////////////////////////+P//////////4/////////////////////////////////////////j//////////8P////////////////////////////////////////4///////////D////////////////////////////////////////+P//////////x/////////////////////////////////////////j//////////8f////////////////////////////////////////4////wD/////H////////////////////////////////////////+P///4AP////x/////////////////////////////////////////h///wPAH///8f////////////////////////////////////////4f//4H8Af///H//////////////////////////////////////8ADH//wP/+AP//x//////////////////////////////////////8AAB//wH//4B//8YB////////////////////////////////////+D/Af/wP///8B//AAAP///////////////////////////////////j/8H/4H////gH/wHgA/////////////////////////////////8AA//x/8P/////gP8f/8H///////AAAAAAAAP////////////////+AAP/8f+H/////+A/H//x///////wAAAAAAAD////////////////+D////H+D8P///H8Bx//4f//////////////w/////////////////h////x/B/D///x/wEf/8AH///////////////////////////////4////8fB/x///8f/gH//AAP//////////////////////////////+H////HA/8f///H/8B///+B/////////////////////////////8AA////xg//H///x//4f///8H////////////////////////////8AAP///8Q//x///8f//H////h////////////////////////////8D//////Af/4f///H//x////8f///////////////////////////+D//////wP/+P///x//8f////H////////////////////////////D//////8H//j///8f//H////h////////////////////////////h///////H//4////H//x////w////////////////////////////4///////x///////x//8D///gf///////////////////////////+P/////8Af//////////AH//4D////////////////////////////j/////AAH//////////xAP/+AD////////H//////////////////4/////ADj//////////8eB///Af///////w//////////////////+P///wA/4///////////H8H///D///////+H//////////////////h///wA/8P//////////x/gf//w//////g/g//////////////////8P//AP//D//////////8f+B//+P/////4P+P//////////////////D/+AP//w/gAAAAAAAAAH/4H//j/////+H/j//////////////////4P4A///8AAAAAAAAAAAB//gf/4////j/w/////////////////////BgB////gAf///////////+D/+P///4f+P////////////////////4AH///////////////////4H/D////D/h/4D//////////////////Af////////////////////Afg/x//w/AP+A////////////////////////////////////////+Awf8f/+MAB/h/////////////////////////////////////////wAP/D//gA4f8f/////////////////////////////////////////wP/4//8D/D///////////////////////////////////////////+D/+P//A/4///////////////////////////////////////////////j//4f+H//////////////////////////////////////////////4f//H/x///////////////////////////////////////////////H//w/////////////////////////////////////////////////w//8H////////////////////////////////////////////////+P//x/////////////////////////////////////////////////h////////////////////////////////////////////////////8P////////////////////////////////////////////////////D////////////////////////////////////////////////////4////////////////////////////////////////////////////+P////////////////////////////////////////////////////j////////////////////////////////////////////////////4//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8=">
								</div>
							</div>
                    <div id="bottom-menu">
                        <input type="button" class="cancel-button fixed-bottom-button" data-sound="SE_OLV_CANCEL" value="Cancel" onClick="history.back()">
                        <input type="submit" class="fixed-bottom-button fixed-bottom-button-post" disabled="" value="Send" id="Send">
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
    function isEmptyOrSpaces(str){
        return str === null || str.match(/^ *$/) !== null;
    }
    setInterval(function(){
        if(!isEmptyOrSpaces(document.getElementById("textarea").value)){
            document.getElementById("Send").disabled = false;
        } else {
            document.getElementById("Send").disabled = true;
        }
    },1500);
</script>
<?php
printFooter();
?>