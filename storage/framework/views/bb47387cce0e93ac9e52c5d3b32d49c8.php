

<?php $__env->startSection('heading', 'All members'); ?>
<?php $__env->startSection('title', 'view member'); ?>

<?php $__env->startSection('main-content'); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Members</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <?php if(session('role') == 1): ?>
                            
                            <a href="<?php echo e(route('admin.add-member')); ?>" class="viewall"><i class="far fa-user"></i> Add Member</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php echo $__env->make('admin.includes.message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-cyan">
                            <tr>
                                <th>SL NO</th>
                                <th>Name</th>
                                <th>Phone no</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Photo</th>
                                <th>NID Photo</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($member->full_name); ?>

                                    <?php if($member->role_id == 2): ?>
                                        <span class="badge badge-warning">Manager</span>
                                    <?php endif; ?>
                                </td>
                                <td> 0<?php echo e($member->phone_no); ?></td>
                                <td> <?php echo e($member->email); ?></td>
                                <td>
                                    <?php if(session('role') == 1): ?>
                                        
                                        <select class="form-control form-control-sm changeRole" data-id="<?php echo e($member->id); ?>" data-old-role="<?php echo e($member->role_id); ?>" style="min-width: 100px;">
                                            <option value="3" <?php echo e($member->role_id == 3 ? 'selected' : ''); ?>>User</option>
                                            <option value="2" <?php echo e($member->role_id == 2 ? 'selected' : ''); ?>>Manager</option>
                                        </select>
                                    <?php else: ?>
                                        
                                        <span class="badge <?php echo e($member->role_id == 2 ? 'badge-warning' : 'badge-info'); ?>">
                                            <?php echo e($member->role_id == 2 ? 'Manager' : 'User'); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><img style="width: 60px; height:60px"
                                            src="<?php echo e(asset('uploads/members/profile/' . $member->photo)); ?>" alt=""></td>
                                <td><img style="width: 60px; height:60px"
                                            src="<?php echo e(asset('uploads/members/nid/' . $member->nid_photo)); ?>" alt=""></td>
                                
                                 <td> 
                                    <?php if(session('role') == 1): ?>
                                        
                                        <input type="checkbox" data-size="mini" data-toggle="toggle" data-on="Active" data-off="Inactive" id="memberStatus" data-id="<?php echo e($member->id); ?>" <?php echo e($member->status == 1 ? 'checked' : ''); ?> >
                                    <?php else: ?>
                                        
                                        <?php if($member->status == 1): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactive</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td style="width: 120px">
                                    <?php if(session('role') == 1): ?>
                                        
                                        <a href="<?php echo e(route('admin.edit-member',$member->id)); ?>" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                        <a href="<?php echo e(route('admin.delete',$member->id)); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this member?')"> <i class="fa fa-trash-alt"></i> </a>
                                    <?php else: ?>
                                        
                                        <span class="text-muted">No actions</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('custom_js'); ?>
    <script>
        var base_url = window.location.origin;
            //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmButton: false,
                        timer:3000
                    });

          $(document).ready(function(){
            // Store initial role values when page loads
            $('.changeRole').each(function(){
                $(this).data('old-value', $(this).val());
            });

           $('body').on('change','#memberStatus',function(){
            var id=$(this).attr('data-id');
            var $checkbox = $(this);
            var isChecked = this.checked;
            
          if(isChecked){
            var status = 1;
          }
          else{
            var status = 0;
          }
        
        $.ajax({
          url:'memberStatus/'+id+'/'+status,
          method:'get',
          dataType: 'json',
          success:function(response){
            console.log(response);
            if(response.message){
                Toast.fire({
                    type: 'success',
                    title: 'Status updated successfully'
                });
            }
          },
          error: function(xhr){
            console.error('Error:', xhr);
            // Revert checkbox state
            $checkbox.prop('checked', !isChecked);
            var errorMsg = 'Failed to update status';
            if(xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            }
            Toast.fire({
                type: 'error',
                title: errorMsg
            });
          }
        });

        });

        // Change role functionality
        $('body').on('change', '.changeRole', function(){
            var memberId = $(this).attr('data-id');
            var $select = $(this);
            var oldRole = $select.attr('data-old-role') || $select.val();
            var newRole = $(this).val();
            
            // Don't proceed if role hasn't changed
            if(oldRole == newRole) {
                $select.val(oldRole);
                return;
            }
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'Changing member role between Manager and User.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                // If user confirmed, proceed with role change
                if (result.isConfirmed || result.value === true) {
                    $.ajax({
                        url: base_url + '/admin/member/changeRole/' + memberId,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            role_id: parseInt(newRole)
                        },
                        dataType: 'json',
                        success: function(response){
                            Toast.fire({
                                type: 'success',
                                title: response.message || 'Role changed successfully'
                            });
                            // Reload table body via AJAX
                            $.ajax({
                                url: '<?php echo e(route("admin.view-member")); ?>',
                                method: 'GET',
                                success: function(html) {
                                    // Extract tbody from response
                                    var $response = $(html);
                                    var $newTbody = $response.find('#all-category tbody');
                                    
                                    if($newTbody.length > 0) {
                                        // Destroy DataTable
                                        $('#all-category').DataTable().destroy();
                                        // Replace tbody
                                        $('#all-category tbody').html($newTbody.html());
                                        // Reinitialize DataTable
                                        $('#all-category').DataTable({
                                            "drawCallback": function(settings) {
                                                $('.changeRole').each(function(){
                                                    if(!$(this).data('old-value')) {
                                                        $(this).data('old-value', $(this).val());
                                                    }
                                                });
                                            }
                                        });
                                    }
                                },
                                error: function() {
                                    // Fallback to page reload if AJAX fails
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr){
                            var errorMsg = 'Failed to change role';
                            if(xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            Toast.fire({
                                type: 'error',
                                title: errorMsg
                            });
                            $select.val(oldRole);
                        }
                    });
                } else {
                    // User cancelled, revert selection
                    $select.val(oldRole);
                }
            });
        });

        });

     
        $(function() {
            $("#all-category").DataTable({
                "drawCallback": function(settings) {
                    // Store initial role values after DataTable draws
                    $('.changeRole').each(function(){
                        if(!$(this).data('old-value')) {
                            $(this).data('old-value', $(this).val());
                        }
                    });
                }
            });
            
            // Also store on initial load
            $('.changeRole').each(function(){
                $(this).data('old-value', $(this).val());
            });
            //   $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "searching": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //   });
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Herd\mass-management-system\resources\views/admin/member/view_members.blade.php ENDPATH**/ ?>