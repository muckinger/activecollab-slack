<?php

function slack_handle_on_object_completed($object) {

    if (defined('SLACK_API_TOKEN')) {

        if ($object instanceof Task) {

            $project = $object->getProject();
            if ($channel = $project->getCustomField1()) {

                $task_id        = $object->getTaskId();
                $task_name      = $object->getName();
                $task_url       = $object->getViewUrl();

                // There's no object named getCompletedBy()...
                $user_name      = $object->getCompletedByName();

                // @todo make this an object...
                if ($slack_user = slack_get_user_by_email($object->getCompletedByEmail())) {
                    $user_name = "<@{$slack_user['id']}|{$user_name}>";
                }

                $message = "Task completed *<{$task_url}|#{$task_id}: {$task_name}>* by *{$user_name}*";

                slack_post_message($channel, $message);
            }
        }
    }
}