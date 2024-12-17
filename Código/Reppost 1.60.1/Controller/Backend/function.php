<?php
function generateHash($member_id, $content, $date_posted, $previous_hash)
{
    return hash('sha256', $member_id . $content . $date_posted . $previous_hash);
}
