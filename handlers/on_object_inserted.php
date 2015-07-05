<?php

function slack_handle_on_object_inserted($object) {

    if (defined('SLACK_API_TOKEN')) {

        if ($object instanceof Task) {

            $project = $object->getProject();
            if ($channel = $project->getCustomField1()) {

                $assignees  = $object->assignees();
                $id         = $object->getTaskId();
                $name       = $object->getName();
                $url        = $object->getViewUrl();

                $message    = "New task *<{$url}|#{$id}: {$name}>*";
                $user       = $assignees->getAssignee();

                if ($user) {

                    $user_name = $user->getName();
                    // @todo make this an object...
                    if ($slack_user = slack_get_user_by_email($user->getEmail())) {
                        $user_name = "@{$slack_user['name']}";
                    }
                    $message .= " assigned to {$user_name}";

                }

                slack_post_message($channel, $message);
            }
        }

        if ($object instanceof TaskComment) {

            $project = $object->getProject();
            if ($channel = $project->getCustomField1()) {

                $created_by = $object->getCreatedByName();
                $task       = $object->getParent();
                $task_id    = $task->getTaskId();
                $task_name  = $task->getName();
                $task_url   = $task->getViewUrl();

                $body       = strip_tags($object->getBody());
                $message    = "New comment by {$created_by} on task *<{$task_url}|#{$task_id}: {$task_name}>*\n>>>{$body}";

                slack_post_message($channel, $message);
            }
        }

    }

}
