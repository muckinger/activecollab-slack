<?php

function slack_handle_on_object_inserted($object) {

  if (defined('SLACK_API_TOKEN')) {

    $slack = new Slack(SLACK_API_TOKEN);

    if ($object instanceof Task) {

      $project = $object->getProject();
      if ($channel = $project->getCustomField1()) {
        $id = $object->getTaskId();
        $url = $object->getViewUrl();
        $name = $object->getName();
        $slack->call('chat.postMessage', array(
          'channel' => $channel,
          'text' => "New task *<{$url}|#{$id}: {$name}>*",
          'username' => 'ActiveCollab',
          'as_user' => FALSE,
        ));
      }
    }
    if ($object instanceof TaskComment) {

      $project = $object->getProject();
      if ($channel = $project->getCustomField1()) {

        $created_by = $object->getCreatedByName();
        $url = $object->getViewUrl();

        $task = $object->getParent();
        $task_id = $task->getTaskId();
        $task_name = $task->getName();
        $task_url = $task->getViewUrl();

        $body = strip_tags($object->getBody());

        $slack->call('chat.postMessage', array(
          'channel' => $channel,
          'text' => "New comment by *$created_by* on task *<{$task_url}|#{$task_id}: {$task_name}>*",
          'username' => 'ActiveCollab',
          'as_user' => FALSE,
        ));
        $slack->call('chat.postMessage', array(
          'channel' => $channel,
          'text' => ">>>{$body}",
          'username' => 'ActiveCollab',
          'as_user' => FALSE,
        ));
      }
    }

  }

}
