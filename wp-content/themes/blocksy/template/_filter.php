<?php
/* Template Name: filter */
get_header();

$all_options_color = get_field_object('couler')['choices'];
$selected_values_color = isset($_POST['couler']) ? $_POST['couler'] : array();

// Function to generate checkboxes
function generate_checkboxes($all_options, $selected_values){
    foreach ($all_options as $value => $label) {
        echo '<input type="checkbox" name="' . $selected_values . '[]" value="' . esc_attr($value) . '">' . esc_html($label) . '<br>';
    }
}
?>
<div id="filter-form">
    <h2>COULEUR:</h2>
    <?php
    if ($selected_values_color) {
        $selected_values_lower_color = array_map('strtolower', $selected_values_color);
        foreach ($all_options_color as $value => $label) {
            if (!in_array(strtolower($value), $selected_values_lower_color)) {
                echo '<div class="filter-item">';
                echo '<input type="checkbox" name="couler[]" value="' . esc_attr($value) . '">' . esc_html($label) . '<br>';
                echo '</div>';
            }
        }
    } else {
        generate_checkboxes($all_options_color, 'couler');
    }
    ?>
    <div class="filter_btn" id="product-filter">
        <a href="#" class="btn" id="Filterbtn">Appliquer des filtres</a>
    </div>
</div>
<div id="latest_post"></div>
<script>
    jQuery(document).ready(function($){
        jQuery('#Filterbtn').on("click", function(e) {
            e.preventDefault();
            var selectedValues = $('input[name="couler[]"]:checked').map(function(){
                return this.value;
            }).get();
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            $.ajax({
                type: "PRODUCT",
                dataType: "html",
                url: ajaxurl,
                data: {
                    action: 'apply_filters',
                    couler: selectedValues
                },
                success: function(response) {
                    $("#latest_post").html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
<?php get_footer(); ?>
