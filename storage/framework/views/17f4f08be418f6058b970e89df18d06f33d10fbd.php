<div class="table-responsive">

    <table class="table table-striped table-bordered jambo_table bulk_action" id="datatable-buttons">
        <thead>
        <tr class="headings">

            <th class="column-title">User </th>
            <th class="column-title">Host </th>
            <th class="column-title">Db </th>
            <th class="column-title">Command </th>
            <th class="column-title">Time </th>
            <th class="column-title">State </th>

            <th class="column-title last">Info</th>
        </tr>
        </thead>

        <tbody>

        <?php $__currentLoopData = $proccesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <tr class="even pointer">

            <td><?php echo e($pr->User); ?></td>
            <td><?php echo e($pr->Host); ?></td>
            <td><?php echo e($pr->db); ?></td>
            <td><?php echo e($pr->Command); ?></td>
            <td><?php echo e($pr->Time); ?></td>
            <td><?php echo e($pr->State); ?></td>
            <td><?php echo e($pr->Info); ?></td>


        </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>


        </tbody>
    </table>

</div>