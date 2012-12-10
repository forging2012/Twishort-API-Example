// Settings
$twitter_auth = array(
  'consumer_key'    => 'consumer_key of your Twitter app',
  'consumer_secret' => 'consumer_secret of your Twitter app',
  'user_token'      => 'user_token',
  'user_secret'     => 'user_secret',
);
$twishort_key = 'your Twishort API key'; // get your API key at http://twishort.com/page/api

$x_auth_service_provider = 'https://api.twitter.com/1.1/account/verify_credentials.json';
$twishort_post_url = 'http://api.twishort.local/1.1/post.json';
// End settings

// Params
$text = 'text to post';
$reply_to_twitter_id = '';
// End params


// Let's start
require('tmhOAuth.php'); // we are using tmhOAuth library in this example
$tmhOAuth = new tmhOAuth($twitter_auth);

// generate the verify crendentials header -- BUT DON'T SEND
// we prevent the request because we're not the ones sending the verify_credentials request, the delegator is

$tmhOAuth->config['prevent_request'] = true;
$tmhOAuth->request('GET', $x_auth_service_provider);
$tmhOAuth->config['prevent_request'] = false;

// create the headers for the echo
$tmhOAuth->headers = array(
  'X-Auth-Service-Provider'            => $x_auth_service_provider,
  'X-Verify-Credentials-Authorization' => $tmhOAuth->auth_header,
);

// prepare the request to the delegator (Twishort)
$params = array(
  'api_key' => $twishort_key,
  'text' => $text,
);  

// make the request, no auth, custom headers
$code = $tmhOAuth->request('POST', $twishort_post_url, $params, false);

if($code != 200) { // error
  print_r($tmhOAuth);
  die();
} 

$post = json_decode($tmhOAuth->response['response'], 1);

print_r($post);

/*
$post = Array
(
    [id] => cbbbc
    [url] => http://twishort.com/cbbbc
    [created_at] => Fri, 07 Dec 2012 14:27:28 +0000
    [text_to_tweet] => text to post… http://twishort.com/cbbbc
    [user] => Array
        (
            [id] => 835057694
            [id_str] => 835057694
            [screen_name] => test_user
        )
)
*/