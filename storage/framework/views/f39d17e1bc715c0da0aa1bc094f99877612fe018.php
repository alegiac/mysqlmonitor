<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script>
    $(document).ready(function() {
        speedX._ajax_processes_list();
        setInterval(function(){  speedX._ajax_processes_list(); }, 5000);
    });
</script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">

            </div>

            <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">

                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">



            <div class="col-md-12 col-sm-12 col-xs-12">



                <div class="x_panel">
                    <div class="x_title">
                        <h2>Processes </h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content" id="processes_list">



                    </div>
                </div>
            </div>

            <div class="clearfix"></div>


        </div>

    </div>

</div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout_index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>