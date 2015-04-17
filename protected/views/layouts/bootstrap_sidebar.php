        <div class="row">
            <div class="large-2 columns">
            <div class="well sidebar-nav">
                <?php
/*
 $this->widget('bootstrap.widgets.BootMenu', array(
                    'type' => 'list',
                    'items' => $this->getMenu(),
                ));
*/
                ?>
            </div><!--/.well -->
            </div><!--/span-->
        
        <div class="large-10 columns">
            <?php // $this->widget('bootstrap.widgets.BootBreadcrumbs', array('links'=>$this->breadcrumbs));?>
            <?php //$this->widget('bootstrap.widgets.BootAlert'); ?>

            <?php echo $content; ?>
        </div><!--/span-->

      </div><!--/row-->

