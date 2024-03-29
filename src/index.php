<?php 

session_start();


require_once __DIR__ . '/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '655059764895423', // Replace {app-id} with your app id
  'app_secret' => '4b707c03323c84228bbad7bdb8108e02',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();
if (isset($_GET['code'])) {
	$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
// echo '<h3>Access Token</h3>';
// print_r($accessToken->getValue());
/*code   */

$id = $fb->get(
 	'me?fields=id',
    $accessToken->getValue()
   );
	 $response = $fb->get(
 	''.$id->getGraphNode()->getField('id').'/accounts',
    $accessToken->getValue()
   );
		 echo "<pre>";
		 // foreach ($response->getGraphEdge() as $graphNode) {
		 // 	print_r($graphNode);
		 // }
		 $res = json_decode($response->getGraphEdge());
		 ?>

		 <select id="colorselector">
		 	<option disabled selected>Choose Page</option>
		 <?php
		 foreach ($res as $key => $value) {
		 	// print_r(++$key.' '.'Page Name: '.$value->name.'<br>'.'Access Token: '.$value->access_token.'<br><br><br>');

		 	echo '<option value="'.$key.'">'.$value->name.'</option>';
		 }
		 ?>
		 </select>

		 <?php
		 foreach ($res as $key => $value) {
		 	// print_r(++$key.' '.'Page Name: '.$value->name.'<br>'.'Access Token: '.$value->access_token.'<br><br><br>');

		 	echo '<div id="'.$key.'" class="token" style="display:none">'.'Page Name: '.$value->name.'<br>'.'Access Token: '.$value->access_token.'</div>';
		 }
		 ?>
		 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		 <script type="text/javascript">
		 	$(function() {
        $('#colorselector').change(function(){
            $('.token').hide();
            	index = $('#colorselector').val();
            	$('#'+index).css('display','block');

            // $('#' + $(this).val()).show();
        });
    });
		 </script>
		 <?php
		 echo "</pre>";

/*// code*/

// var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
// echo '<h3>Metadata</h3>';
// var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('655059764895423'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }

  // echo '<h3>Long-lived</h3>';
  // var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
}
else{
	$permissions = ['manage_pages', 'pages_show_list', 'publish_pages', 'public_profile'];
$loginUrl = $helper->getLoginUrl('http://localhost/facebook_graph/src/', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

}

 // Optional permissions

?>
