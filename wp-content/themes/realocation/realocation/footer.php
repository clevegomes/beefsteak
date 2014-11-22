                                </div><!-- /.row -->
                            </div><!-- /.block-content-inner -->
                        </div><!-- /.block-content-small-padding -->
                    <?php dynamic_sidebar('bottom'); ?>
                </div><!-- /.container -->
            </div><!-- /#main-inner -->
        </div><!-- /#main -->
    </div><!-- /#main-wrapper -->

    <div id="footer-wrapper">
        <div id="footer">
            <div id="footer-inner">
                <?php if (aviators_active_sidebars(array('footer-left', 'footer-right', 'footer-lower-left', 'footer-lower-right'), AVIATORS_SIDEBARS_ANY)): ?>
                    <?php require_once 'templates/footer-top.php'; ?>
                <?php endif; ?>

                <?php if (aviators_active_sidebars(array('footer-bottom'))): ?>

                    <?php require_once 'templates/footer-bottom.php'; ?>
                <?php endif; ?>
            </div><!-- /#footer-inner -->
        </div><!-- /#footer -->
    </div><!-- /#footer-wrapper -->
</div><!-- /#wrapper -->

<?php wp_footer(); ?>
<?php aviators_footer(); ?>

</body>
</html>