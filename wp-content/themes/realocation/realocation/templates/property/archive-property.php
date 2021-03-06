<?php
$display = "row";
if (isset($_GET['display'])) {
    $display = $_GET['display'];
}
else {
    $display = aviators_settings_get('property', 'archive', 'display_type');
}

$properties_horizontal = hydra_form_filter('properties_horizontal');
if ($properties_horizontal->getFormRecord()) {
    $query_args = $properties_horizontal->getQueryArray();
}

$properties_vertical = hydra_form_filter('properties_vertical');
if ($properties_vertical->getFormRecord()) {
    $tmp_args = $properties_vertical->getQueryArray();
    if (count($tmp_args['meta_query'])) {
        $query_args['meta_query'] = $tmp_args['meta_query'];
    }
}

$paged = get_query_var('paged');
if (isset($paged)) {
    $query_args['paged'] = get_query_var('paged');
}

$sort = aviators_settings_get('property', 'archive', 'sort');
if (isset($sort) && $sort) {
    aviators_properties_sort_get_query_args(get_the_ID(), $query_args);
}

query_posts($query_args);
$fullwidth = !is_active_sidebar('sidebar-1');
switch ($display) {
    case "row":
        $class = empty($fullwidth) ? "col-sm-12" : "col-sm-offset-1 col-sm-10";
        break;
    case "grid":
        $class = empty($fullwidth) ? "col-sm-6 col-md-4" : "col-sm-4 col-md-3";
        break;
    case "default":
        $class = 'post col-sm-12';
        break;
}


$resolutions = array(
    'xs' => 12,
    'sm' => 6,
    'md' => 4,
    'lg' => 4,
);

if($fullwidth) {
    $resolutions = array(
        'xs' => 12,
        'sm' => 6,
        'md' => 3,
        'lg' => 3,
    );
}

?>
<h1><?php echo aviators_settings_get('property', 'archive', 'title'); ?></h1>

<?php if (isset($sort) && $sort): ?>
    <?php aviators_get_template('sort', 'property'); ?>
<?php endif; ?>

<div class="items-list row">
    <?php $count = 0; ?>
    <?php while (have_posts()) : the_post(); ?>

        <?php
        $end_line = '';
        if($display == 'grid') {
            foreach ($resolutions as $resolution => $columns) {
                if ($count % (12 / $columns) == 0) {
                    $end_line .= ' new-line-' . $resolution;
                }
            }
        }
        ?>

        <div class="<?php print $class; ?> <?php echo $end_line; ?>">
            <?php aviators_get_content_template('property', $display); ?>
        </div>
        <?php $count++; ?>
    <?php endwhile; ?>
</div><!-- /.items-list -->