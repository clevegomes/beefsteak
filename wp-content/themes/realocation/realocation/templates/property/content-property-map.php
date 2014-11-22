<div class="infobox">
    <div class="infobox-header"><h3 class="infobox-title"><a href="<?php print get_permalink($property->ID); ?>"><?php print addslashes($property->post_title); ?></a></h3></div>
    <div class="infobox-picture"><a href="<?php print get_permalink($property->ID); ?>"><img
                src="<?php print aviators_get_featured_image($property->ID, 150, 150)?>" alt="#"></a>
        <div class="infobox-price"><?php print hydra_render_field($property->ID, 'price');?></div>
    </div>
</div>
