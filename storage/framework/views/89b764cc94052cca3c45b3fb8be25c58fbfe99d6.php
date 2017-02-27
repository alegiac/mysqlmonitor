<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

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
                        <h2>Engines</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered jambo_table">
                            <thead>
                            <tr>

                                <th>Engine</th>
                                <th>Support</th>
                                <th>Is Default</th>
                                <th>Transactions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $engines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eng): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <tr>
                                <td><?php echo e($eng->ENGINE); ?></td>
                                <td><?php echo $eng->SUPPORT == 'YES' || $eng->SUPPORT == 'DEFAULT' ? '<span class="label label-success">YES</span>' :'<span class="label label-danger">NO</span>'; ?></td>
                                <td><?php echo $eng->SUPPORT == 'DEFAULT' ? '<span class="label label-success">YES</span>' :'<span class="label label-danger">NO</span>'; ?></td>
                                <td><?php echo $eng->TRANSACTIONS == 'YES' ? '<span class="label label-success">YES</span>' :'<span class="label label-danger">NO</span>'; ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="clearfix"></div>


        </div>


        <div class="row">





            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Engines Size</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered jambo_table">
                            <thead>
                            <tr>

                                <th>Engine</th>
                                <th>Total Size</th>
                                <th>Table Number</th>
                                <th>Data Size</th>
                                <th>Index Size</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $engine_size; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $es): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <tr>
                                    <td><?php echo e($es->ENGINE); ?></td>
                                    <td><?php echo e(\App\Classes\Helpers::format_size($es->dli)); ?></td>
                                    <td><?php echo e($es->ce); ?></td>
                                    <td><?php echo e(\App\Classes\Helpers::format_size($es->dl)); ?></td>
                                    <td><?php echo e(\App\Classes\Helpers::format_size($es->il)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="clearfix"></div>


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Fragmented Tables</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-bordered jambo_table">
                            <thead>
                            <tr>

                                <th>Table</th>
                                <th>Free</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_free = 0 ;?>
                            <?php $__currentLoopData = $fragmented_tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fr): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <?php $total_free += $fr->DATA_FREE; ?>
                                <tr>
                                    <td><?php echo e($fr->table_name); ?></td>
                                    <td><?php echo e(\App\Classes\Helpers::format_size($fr->DATA_FREE)); ?></td>

                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            <tr>
                                <td style="text-align: right; font-weight: bold">Total Free : </td>
                                <td style="font-weight: bold"><?php echo e(\App\Classes\Helpers::format_size($total_free)); ?></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>

    </div>

</div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout_index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>