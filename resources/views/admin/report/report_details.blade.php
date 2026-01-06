@extends('admin.master')
@section('heading', 'Report Sheet')
@section('title', 'report')

@section('main-content')
<div class="col-md-12">
  <div class="card" id="printpanel">
    <div class="head text-center mb-4">
      <h3 class="text-info font-weight-bolder mt-4">Mess Management System</h3>
      <h4 class="text-success font-italic">Meal Summary Sheet</h4>
      <h4 class="text-success font-weight-normal"><span class="text-dark">From: {{ date('M d, Y', strtotime($start_month)) }}</span>&nbsp;&nbsp;-&nbsp;&nbsp;<span class="text-dark">To: {{ date('M d, Y', strtotime($finish_month)) }}</span></h4>
      <div class="text-right">
        <a href="{{route('user.report.downloadPdf',['one'=>$start_month,'two'=>$finish_month])}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
      </div>
      <hr>
    </div>

    <div class="row">
      <div class="col-12 col-sm-2">
        <div class="info-box bg-light">
          <div class="info-box-content">
            <span class="info-box-text text-center text-muted">Total Meal</span>

            <span class="info-box-number text-center text-muted mb-0">{{ $total_meal }}</span>

          </div>
        </div>
      </div> 
      <div class="col-12 col-sm-3">
        <div class="info-box bg-light">
          <div class="info-box-content">
            <span class="info-box-text text-center text-muted">Total Amount</span>

            <span class="info-box-number text-center text-muted mb-0"> Tk.{{ $fund }}</span>

          </div>
        </div>
      </div>
      <div class="col-12 col-sm-3">
        <div class="info-box bg-light">
          <div class="info-box-content">
            <span class="info-box-text text-center text-muted">Total Expanses</span>

            <span class="info-box-number text-center text-muted mb-0">Tk.{{ $total_amount }}</span>

          </div>
        </div>
      </div>
      <div class="col-12 col-sm-2">
        <div class="info-box bg-light">
          <div class="info-box-content">
            <span class="info-box-text text-center text-muted">Total Meal Rate</span>
            <span class="info-box-number text-center text-muted mb-0">Tk {{ number_format($meal_rate ,2)}}</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-2">
        <div class="info-box bg-light">
          <div class="info-box-content">
           @if($total_fund > 0)
           <span class="info-box-text text-center text-muted">Fund(+)</span>
           <span class="info-box-number text-center text-success mb-0">Tk.
             {{ $total_fund }}
           </span>
           @elseif ($total_fund < 0)
           <span class="info-box-text text-center text-muted ttext-danger">Fund(-)</span>
           <span class="info-box-number text-center text-muted mb-0">Tk.
            {{ $total_fund }}
          </span>
          @else
          <span class="info-box-text text-center text-muted">Fund</span>
          <span class="info-box-number text-center text-muted mb-0">Tk.
           0.00
         </span>
         @endif
       </div>
     </div>
   </div>
 </div>

 <div class="card-body">
  <table id="all-category" class="table table-bordered table-hover">
    <thead class="text-dark">
      <tr class="text-center">
        <th>SL NO</th>
        <th>Member's Name</th>
        <th>Total Meal</th>
        <th>Total Expanses</th>
        <th>Payment</th>
        <th>Dues / Cashback</th>
      </tr>

    </thead>
    <tbody id="tbody">

  
      @foreach ($data as $total)
      @php
      // Exclude Super Admin (role_id = 1) from meals
      $meal =  \DB::select("SELECT meals.* FROM meals 
          INNER JOIN members ON meals.members_id = members.id 
          WHERE meals.status = '1' and members.role_id != 1 and meals.date BETWEEN '$start_month' and '$finish_month' and meals.members_id = '$total->member_id'");
      $br = 0;
      $ln = 0;
      $dn = 0;
      foreach($meal as $ml){
      $br += $ml->breakfast;
      $ln += $ml->lunch;
      $dn += $ml->dinner;
    }
    $all_meal = $br +  $ln + $dn;
    // dd($all_meal);
    @endphp
    <tr class="text-center">
     <td>{{ $loop->iteration }}</td>
     <td>{{ $total->member_name }}</td>

     <td>{{ $all_meal }}</td>
     <td>Tk. {{ number_format(($all_meal * $meal_rate),2)}}</td>
     <td>Tk. {{ $total->total_amount ? $total->total_amount : '0.00' }}</td>
     <td>Tk. {{ number_format(($total->total_amount -($all_meal * $meal_rate)),2) }}</td>
   </tr>
   @endforeach
 </tbody>
</table>
</div>

</div>
</div>
@endsection


@section('custom_js')
<script>
 $(function() {
  $("#all-category").DataTable();
  // scrollY:'50vh',
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
        @endsection