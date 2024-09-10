<?php
/**
 * Queues personalized emails for champions based on their downloaded materials.
 *
 * This function checks champions who have requested material tips more than 30 minutes ago and
 * less than 7 days ago, but who haven't yet received tips for those requests. It then constructs
 * and queues personalized emails for these champions based on the categories of the materials they 
 * downloaded. The emails include specific tips related to the materials.
 *
 * Steps:
 * 1. Fetch all champions who have requested tips more than 30 minutes ago and less than 7 days ago, 
 *    but haven't yet received the tips.
 * 2. For each champion, retrieve the materials they downloaded.
 * 3. If materials from different categories have been downloaded:
 *    - Construct and queue an email with specific tips related to those categories.
 *    - Track the email in `hl_email_tracking`.
 *    - Insert the email into the `hl_email_que` for sending.
 * 4. Check if the champion is enrolled in the email sequence for each category. If not, enroll them.
 * 5. Ensure that specific tips related to the downloaded materials are included in the email.
 * 6. Update the `hl_downloads` table to indicate that the tips have been sent.
 *
 * @return string Status message indicating the operation is complete.
 */
function hl_email_request_initial_material_tips() {
    // Set timezone and calculate time thresholds
    date_default_timezone_set('UTC');
    $tips_time = strtotime('- 30 minutes');
    $last_time = strtotime('- 7 days');
    $three_months = strtotime('- 90 days');

    // Query to find champions who requested tips but haven't received them
    $query = db_select('hl_downloads', 'd')
        ->distinct()
        ->fields('d', array('champion_id'))
        ->condition('d.requested_tips', $tips_time, '<')
        ->condition('d.requested_tips', $last_time, '>')
        ->isNotNull('d.requested_tips')
        ->isNull('d.sent_tips');
    $result = $query->execute();

    // Loop through each champion and process their downloads
    foreach($result as $champion) {
        // Retrieve the materials downloaded by this champion
        $query = db_select('hl_downloads', 'd');
        $query->innerjoin('hl_materials', 'm', 'd.file_id = m.id');
        $query->fields('m', array('tips', 'category', 'lang1', 'audience'))
            ->fields('d', array('id', 'champion_id'))
            ->isNotNull('d.requested_tips')
            ->isNull('d.sent_tips')
            ->condition('d.champion_id', $champion->champion_id, '=')
            ->orderBy('d.champion_id')
            ->orderBy('m.category');
        $result = $query->execute();

        // Variables to track email content and category
        $previous_category = NULL;
        $email = NULL;
        $nid = NULL;

        // Process each downloaded material
        foreach($result as $data) {
            $specific_tip = '';
            $champion_id = $data->champion_id;

            // If a new category is encountered, queue the previous email and start a new one
            if ($data->category != $previous_category) {
                $previous_category = $data->category;

                if ($email) {
                    // Track and queue the email
                    db_insert('hl_email_tracking')
                        ->fields(array(
                            'email_id' => $nid,
                            'champion_id' => $champion_id,
                            'date_sent' => Now()
                        ))
                        ->execute();

                    $body = str_replace('<p>|specific tips|</p>', $specific_tips, $body);
                    db_insert('hl_email_que')
                        ->fields(array(
                            'delay_until' => Now(),
                            'champion_id' => $champion_id,
                            'email_id' => $email_id,
                            'body' => $body,
                            'subject' => $subject,
                        ))
                        ->execute();
                }

                $email = NULL;

                // Check if the champion is enrolled in the email series for this category
                $tid = db_query('SELECT tid FROM taxonomy_term_data WHERE name = :name', array(':name' => $data->category))->fetchField();
                $enrolled = db_query('SELECT cid FROM hl_email_series_members WHERE cid = :cid AND tid = :tid', array(':cid' => $champion_id, ':tid' => $tid))->fetchField();

                if (!$enrolled) {
                    db_insert('hl_email_series_members')
                        ->fields(array(
                            'tid' => $tid,
                            'cid' => $champion_id,
                            'sequence' => 1,
                            'sent' => Now()
                        ))
                        ->execute();
                }

                // Fetch the email node for the initial tip based on category
                $query = new EntityFieldQuery();
                $query->entityCondition('entity_type', 'node')
                    ->entityCondition('bundle', 'tips_for_material_types')
                    ->propertyCondition('status', NODE_PUBLISHED)
                    ->fieldCondition('field_material_category', 'tid', $tid, '=')
                    ->fieldCondition('field_sequence', 'value', '1', '=')
                    ->range(0, 1);
                $result = $query->execute();

                if (isset($result['node'])) {
                    $nid = array_keys($result['node']);
                    $email_id = $nid[0];

                    // Check if this tip was sent in the past three months
                    $previous_sent = db_query('SELECT id FROM hl_downloads WHERE champion_id = :champion_id AND tip = :tip AND sent_tips > :sent_tips', array(':champion_id' => $champion_id, ':tip' => $nid, ':sent_tips' => $three_months))->fetchField();

                    if ($previous_sent) {
                        $nid = 0;
                    } else {
                        // Fetch the email body and subject if the tip hasn't been sent recently
                        $email_node = entity_metadata_wrapper('node', $nid[0]);
                        $body = $email_node->body->value->value();
                        if (strpos($body, '<p>|specific tips|</p>') === FALSE) {
                            $subject = NULL;
                            $body = NULL;
                        } else {
                            $body = $email_node->body->value->value();
                            $subject = $email_node->field_subject->value();
                        }
                        $specific_tips = NULL;
                        $specific_nodes = array();
                    }
                }
            }

            // Fetch specific tips if the category is 'Tracts'
            if ($data->category == 'Tracts' && isset($body)) {
                $query = new EntityFieldQuery();
                $query->entityCondition('entity_type', 'node')
                    ->entityCondition('bundle', 'tips_for_tract_language')
                    ->propertyCondition('status', NODE_PUBLISHED)
                    ->fieldCondition('field_tract_language', 'value', $data->lang1 . '%', 'like');
                $result = $query->execute();

                if (isset($result['node'])) {
                    $nid = array_keys($result['node']);
                    $specific = entity_metadata_wrapper('node', $nid[0]);
                    $specific_tip = $specific->body->value->value();
                    if (!isset($specific_nodes[$nid[0]])) {
                        $specific_tips .= $specific->body->value->value();
                        $specific_nodes[$nid[0]] = $nid[0];
                    }
                }
            }

            // Update the hl_downloads table to indicate the tips were sent
            db_update('hl_downloads')
                ->fields(array(
                    'sent_tips' => Now(),
                    'tip' => $nid,
                    'tip_detail' => isset($specific_nodes[$nid[0]]) ? $specific_nodes[$nid[0]] : NULL,
                ))
                ->condition('id', $data->id)
                ->execute();
        }

        // Process the last email in the queue
        if ($email) {
            db_insert('hl_email_tracking')
                ->fields(array(
                    'email_id' => $nid,
                    'champion_id' => $champion_id,
                    'date_sent' => Now()
                ))
                ->execute();

            $body = str_replace('<p>|specific tips|</p>', $specific_tips, $body);
            db_insert('hl_email_que')
                ->fields(array(
                    'delay_until' => Now(),
                    'champion_id' => $champion_id,
                    'email_id' => $email_id,
                    'body' => $body,
                    'subject' => $subject,
                ))
                ->execute();
        }
    }

    return 'tips';
}
