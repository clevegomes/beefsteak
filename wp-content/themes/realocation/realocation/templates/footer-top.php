<div class="footer-top">
    <div class="container">

        <?php if(aviators_active_sidebars('footer-first-column', 'footer-second-column', 'footer-third-column', AVIATORS_SIDEBARS_ANY)): ?>
            <div class="row">
                <?php if(is_active_sidebar('footer-first-column')): ?>
                    <div class="region col-sm-4">
                        <?php dynamic_sidebar('footer-first-column'); ?>
                    </div><!-- /.region -->
                <?php endif; ?>

                <?php if(is_active_sidebar('footer-second-column')): ?>
                    <div class="region col-sm-4">
                        <?php dynamic_sidebar('footer-second-column'); ?>
                    </div><!-- /.region-->
                <?php endif; ?>

                <?php if(is_active_sidebar('footer-third-column')): ?>
                    <div class="region col-sm-4">
                        <?php dynamic_sidebar('footer-third-column'); ?>
                    </div><!-- /.region-->
                <?php endif; ?>
            </div><!-- /.row -->
        <?php endif; ?>

        <?php if(aviators_active_sidebars('footer-lower-left', 'footer-lower-right', AVIATORS_SIDEBARS_ANY)): ?>
            <hr>

            <div class="row">
                <div class="footer-lower clearfix">
                    <?php if(is_active_sidebar('footer-lower-left')): ?>
                        <div class="footer-lower-left">
                            <div class="col-sm-9"><?php dynamic_sidebar('footer-lower-left'); ?></div>
                        </div><!-- /.footer-lower-left -->
                    <?php endif; ?>

                    <?php if(is_active_sidebar('footer-lower-right')): ?>
                        <div class="footer-lower-right">
                            <div class="col-sm-3"><?php dynamic_sidebar('footer-lower-right'); ?></div>
                        </div><!-- /.footer-lower-right -->
                    <?php endif; ?>
                </div><!-- /.footer-lower -->
            </div><!-- /.row -->
        <?php endif; ?>
    </div><!-- /.container -->
</div><!-- /.footer-top -->