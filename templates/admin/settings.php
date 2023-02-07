<div class="wrap">
    <h2>Settings for AI Thing</h2>

    <form method="post" action="options.php">
        <?php

        settings_fields( 'wpaitb_settings' );

        do_settings_sections( 'wp-ai-toolbox-settings' );

        submit_button();
        ?>
    </form>
</div>