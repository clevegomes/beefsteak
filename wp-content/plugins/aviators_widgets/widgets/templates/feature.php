<?php if($title): ?>
    <h2><?php echo $title; ?></h2>
<?php endif; ?>

<div class="row">
<?php foreach($chunks as $features): ?>
    <div class="<?php print $class; ?>">
        <?php foreach($features as $feature): ?>
            <div class="feature">
            <?php if($feature['icon']): ?>
                <div class="feature-icon col-xs-2 col-sm-2">
                    <div class="feature-icon-inner">
                        <i class="fa <?php print $feature['icon']; ?>"></i>
                    </div><!-- /.feature-icon-inner -->
                </div><!-- /.feature-icon -->
            <?php endif; ?>
                <div class="feature-content <?php if($feature['icon']) { echo "col-xs-10 col-sm-10"; } else { echo "col-xs-12 col-sm-12"; } ?>">

                    <?php if($feature['title']): ?>
                        <h3 class="feature-title"> <?php print $feature['title']; ?></h3>
                    <?php endif; ?>

                    <?php if($feature['content']): ?>
                        <p class="feature-body">
                            <?php print $feature['content']; ?>
                        </p>
                    <?php endif; ?>
                </div><!-- /.feature-content -->
            </div><!-- /.feature -->
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
</div>