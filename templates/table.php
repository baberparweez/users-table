<?php

// Render the header block template
if (has_blocks('header')) {
    $header_template = get_block_template('header', 'header');
    $header_content = $header_template->render();
    echo $header_content;
} else {
    echo get_header();
}

if (!empty($users)) {
    echo '<table class="users__table">';
    echo '<thead>
        <tr>
            <th>' . esc_html__('ID', 'users-table') . '</th>
            <th>' . esc_html__('Name', 'users-table') . '</th>
            <th>' . esc_html__('Username', 'users-table') . '</th>
        </tr>
    </thead>';
    echo '<tbody>';
    foreach ($users as $user) {
        echo sprintf(
            '<tr>
            <td><a href="#" class="users__table--user" data-user-id="%1$s">%2$s</a></td>
            <td><a href="#" class="users__table--user" data-user-id="%1$s">%3$s</a></td>
            <td><a href="#" class="users__table--user" data-user-id="%1$s">%4$s</a></td>
        </tr>',
            esc_attr($user['id']),
            esc_html($user['id']),
            esc_html($user['name']),
            esc_html($user['username'])
        );
    }
    echo '</tbody></table>';


    // Container for displaying the fetched user details
    echo '<div id="user-details" aria-live="polite" class="users__table--details"></div>';
} else {
    echo 'No data available.';
}

if (has_blocks('footer')) {
    $footer_template = get_block_template('footer', 'footer');
    $footer_content = $footer_template->render();
    echo $footer_content;
} else {
    echo get_footer();
}
