

<?php $__env->startSection('heading', 'Reports'); ?>
<?php $__env->startSection('title', 'report'); ?>

<?php $__env->startSection('main-content'); ?>

<div class="col-md-12">
    <div class="card card-secondary" id="generatepanel">
      <div class="card-header p-3">
        <h3 class="card-title font-weight-bolder">Report</h3>

      </div>
      <div class="card-body">
        <form action="<?php echo e(route('admin.view.report')); ?>" method="get">
            <?php echo csrf_field(); ?>
        <div class="row">
        <div class="col-sm-4">
        <div class="form-group">
        <label>&nbsp;&nbsp; Month</label>
        <div class="col-md-12 col-sm-12">
        <select class="form-control select2bs4" name="month" required="" id="month">
        <option value="" data-select2-id="2">--Select Month--</option>
        <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Convert short month names to full month names
                $monthMap = [
                    'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                    'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                    'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                ];
                $displayMonth = $monthMap[$meal->month] ?? $meal->month;
                // Use full month name for value if it was short, otherwise use original
                $valueMonth = $monthMap[$meal->month] ?? $meal->month;
            ?>
        <option value="<?php echo e($valueMonth); ?>"><?php echo e($displayMonth); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select> 
        </div>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="form-group">
        <label>&nbsp;&nbsp; From Month</label>
        <div class="col-md-12 col-sm-12">
        
         <input type="date" required="" name="from_date" id="from_date" class="form-control" placeholder="From Date For Count Meal">
        </div>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="form-group">
        <label>&nbsp;&nbsp; To Month</label>
        <div class="col-md-12 col-sm-12">
        <input type="date" required="" id="to_date" class="form-control" name="to_date" placeholder="To Date For Count Meal">
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="card-footer">
            <input type="submit" id="generate" class="btn btn-success" name="submit" value="Generate">
            </div>
        </form>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_js'); ?>
<script>
$(document).ready(function() {
    // Month name to number mapping
    const monthMap = {
        'January': 1,
        'February': 2,
        'March': 3,
        'April': 4,
        'May': 5,
        'June': 6,
        'July': 7,
        'August': 8,
        'September': 9,
        'October': 10,
        'November': 11,
        'December': 12
    };

    // Function to get last day of month
    function getLastDayOfMonth(year, month) {
        return new Date(year, month, 0).getDate();
    }

    // Function to set dates based on selected month
    function setDatesForMonth(selectedMonth) {
        if (selectedMonth && monthMap[selectedMonth]) {
            const currentYear = new Date().getFullYear();
            const monthNumber = monthMap[selectedMonth];
            
            // Set from date to 1st of the month (format: YYYY-MM-DD for HTML date input)
            // Example: January -> 2026-01-01
            const fromDate = currentYear + '-' + String(monthNumber).padStart(2, '0') + '-01';
            $('#from_date').val(fromDate);
            
            // Set to date to last day of the month
            // Example: January -> 2026-01-31
            const lastDay = getLastDayOfMonth(currentYear, monthNumber);
            const toDate = currentYear + '-' + String(monthNumber).padStart(2, '0') + '-' + String(lastDay).padStart(2, '0');
            $('#to_date').val(toDate);
        } else {
            // Clear dates if no month is selected
            $('#from_date').val('');
            $('#to_date').val('');
        }
    }

    // Handle month dropdown change (works with both regular select and Select2)
    $('#month').on('change', function() {
        const selectedMonth = $(this).val();
        setDatesForMonth(selectedMonth);
    });

    // Also handle Select2 specific events for better compatibility
    $('#month').on('select2:select', function(e) {
        const selectedMonth = $(this).val();
        setDatesForMonth(selectedMonth);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/report/index.blade.php ENDPATH**/ ?>