<?php
$inst_omnitags = new omnitags();
$json_settings = file_get_contents(__DIR__."/_settings.json");
$settings = json_decode($json_settings);
$saved_config = $inst_omnitags->omnitags_select();
?>

    <h1>Omnitags</h1>
    <br>



<?php
if (isset($settings)) {
?>
<div id="menu_omnitags">
    <ul>
        <?php
        echo '<ul class="nav nav-tabs" role="tablist">';
        foreach ($settings as $setting) {
            echo '<li' . (isset($setting->isActive) && $setting->isActive == "true" ? " class='active'" : "") . '><a href="#tab_' . $setting->ID . '" role="tab" data-toggle="tab">' . __( $setting->name, 'omnitags' ) . '</a></li>';
        }
        echo '</ul>';
        ?>
    </ul>
</div>
<?php
    echo '<div class="tab-content">';
    foreach ($settings as $setting) {
        echo '<div id="tab_' . $setting->ID . '" role="tabpanel" class="tab-pane' . (isset($setting->isActive) && $setting->isActive == "true" ? " active" : "") . '">';
        echo '<h3>' . __( $setting->name, 'omnitags' ) . '</h3>';

        foreach ($setting->services as $service) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo __( $service->name, 'omnitags' ) ?></h3>
                    <br>
                    <p><?php echo __( $service->description, 'omnitags' ) ?><?php echo ((isset($service->url) && $service->url != "") ? __( 'Visit', 'omnitags' ) . " <a href='" . $service->url . "'' target='_blank'>" . __( $service->name, 'omnitags' ) ."</a>" : "") ?></p>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                    <?php
                    foreach ($service->params as $param) {
//                        var_dump($param);

                        $param_id = $param->ID;
                        $param_name = $param->name;
                        $param_field_key = $param->field_key;
                        $param_field_type = $param->field_type;
                        $param_placeholder = htmlentities($param->placeholder);
                        $param_wp_hook = $param->wp_hook;

                        $param_value = "";

                        $value = $inst_omnitags->searchForFieldValue($param_field_key, $saved_config);
                        if (isset($value)) {
                            $param_value = htmlentities($value);
                        }

                        ?>
                        <div class="form-group">
                            <label for="param_<?php echo $param_id ?>" class="col-sm-2 control-label"><?php echo __( $param_name, 'omnitags' ) ?></label>
                            <div class="col-sm-5">
                                <?php
                                if ($param_field_type == "text" || $param_field_type == "number") {
                                    ?>
                                    <input type="<?php echo $param_field_type ?>" class="form-control"
                                           id="param_<?php echo $param_id ?>"
                                           name="param_<?php echo $param_id ?>"
                                           placeholder="<?php echo $param_placeholder ?>"
                                           value="<?php echo __( $param_value, 'omnitags' ) ?>"
                                           onchange="jsSaveConfigValue('<?php echo $param_field_key ?>', this.value, '<?php echo $param_wp_hook ?>');">
                                    <?php
                                }
                                if ($param_field_type == "textarea") {
                                    ?>
                                    <textarea rows="4" class="form-control"
                                           id="param_<?php echo $param_id ?>"
                                           name="param_<?php echo $param_id ?>"
                                           placeholder="<?php echo $param_placeholder ?>"
                                           onchange="jsSaveConfigValue('<?php echo $param_field_key ?>', this.value, '<?php echo $param_wp_hook ?>');"><?php echo __( $param_value, 'omnitags' ) ?></textarea>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
<?php
        }
        echo '</div>';
    }
    echo '</div>';
}
?>

<div id="loading"></div>
