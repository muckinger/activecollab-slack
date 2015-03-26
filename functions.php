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