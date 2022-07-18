<div class="wrapper">
    <h1 class="text-center">Mirakl API Settings</h1>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'mirakl_plugin_settings' );
        do_settings_sections( 'mirakl_plugin' );
        ?>
        <input
            type="submit"
            name="submit"
            class="button button-primary"
            value="<?php esc_attr_e( 'Save' ); ?>"
            />

    </form>



    <form action="options.php" method="post">
        <?php
        settings_fields( 'mirakl_plugin_settings_2' );
        do_settings_sections( 'mirakl_plugin_2' );
        ?>
        <input
            type="submit"
            name="submit"
            class="button button-primary"
            value="<?php esc_attr_e( 'Update' ); ?>"
            />
    </form>

    <form action="options.php" method="post">
        <?php
        settings_fields( 'mirakl_plugin_settings_3' );
        do_settings_sections( 'mirakl_plugin_3' );
        ?>
        <input
            type="submit"
            name="submit"
            class="button button-primary"
            value="<?php esc_attr_e( 'Clean date' ); ?>"
            />
    </form>
</div>

