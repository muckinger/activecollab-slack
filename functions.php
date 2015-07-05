<?php

function slack_get_user_by_email($email) {

  static $users;

  if (defined('SLACK_API_TOKEN')) {

    if (!$users) {
      // @todo cache users.list
      $slack = new Slack(SLACK_API_TOKEN);
      $response = $slack->call('users.list');
      if ($response['ok']) {
        $users = $response['members'];
      }
    }

    foreach ($users as $user) {
      if ($user['profile']['email'] == $email) {
        return $user;
      }
    }

  }

}

function slack_post_message($channel, $message) {
    $slack = new Slack(SLACK_API_TOKEN);

    $slack->call('chat.postMessage', array(
        'channel'   => $channel,
        'text'      => $message,
        'username'  => 'ActiveCollab',
        'as_user'   => FALSE,
        'icon_url'  => defined('ASSETS_URL') ? ASSETS_URL . '/images/system/default/application-branding/logo.40x40.png'  : ''
    ));
}