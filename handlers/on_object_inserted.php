<?php

function slack_handle_on_object_inserted($object) {

    if (defined('SLACK_API_TOKEN')) {

        if ($object instanceof Task) {

            $project = $object->getProject();
            if ($channel = $project->getCustomField1()) {

                $task_id    = $object->getTaskId();
                $task_name  = $object->getName();
                $task_url   = $object->getViewUrl();

                $message    = "New task *<{$task_url}|#{$task_id}: {$task_name}>*";
                $user       = $object->assignees()->getAssignee();

                // Tasks can be without assignees
                if ($user) {
                    $user_name = $user->getName();

                    // @todo make this an object...
                    if ($slack_user = slack_get_user_by_email($user->getEmail())) {
                        $user_name = "<@{$slack_user['name']}|{$user_name}>";
                    }

                    $message .= " assigned to {$user_name}";
                }

                slack_post_message($channel, $message);
            }
        }

        if ($object instanceof TaskComment) {

            $project = $object->getProject();
            if ($channel = $project->getCustomField1()) {

                $task       = $object->getParent();
                $task_id    = $task->getTaskId();
                $task_name  = $task->getName();
                $task_url   = $task->getViewUrl();

                // Every comment must have a user name
                $user       = $object->getCreatedBy();
                $user_name  = $user->getName();

                // @todo make this an object...
                if ($slack_user = slack_get_user_by_email($user->getEmail())) {
                    $user_name = "<@{$slack_user['name']}|{$user_name}>";
                }

                $message    = "New comment by {$user_name} on task *<{$task_url}|#{$task_id}: {$task_name}>*\n>>>" . strip_tags($object->getBody());

                slack_post_message($channel, $message);
            }
        }
    }
}