<?php

function slack_handle_on_object_completed($object) {

    if (defined('SLACK_API_TOKEN')) {

        if ($object instanceof Task) {

            $project = $object->getProject();
            if ($channel = $project->getCustomField1()) {

                $assignees  = $object->assignees();
                $id         = $object->getTaskId();
                $name       = $object->getName();
                $url        = $object->getViewUrl();

                $message    = "Task completed *<{$url}|#{$id}: {$name}>*";
                $user       = $assignees->getAssignee();

                if ($user) {
                    $user_name = $user->getName();

                    // @todo make this an object...
                    if ($slack_user = slack_get_user_by_email($user->getEmail())) {
                        $user_name = "@{$slack_user['name']}";
                    }
                    $message .= " by {$user_name}";

                }

                slack_post_message($channel, $message);
            }
        }
    }
}