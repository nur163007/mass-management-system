<!DOCTYPE html>
<html lang="en">
<head>
    <title>Download</title>
    <style>
        .card{
            width: 90%;
            margin:-2% 5% 0 5%;
        }
        .card .head h3{
            text-align: center;
            color: green;
            font-size: 25px;
            font-weight: bold;
        }
        .card .head h4{
            text-align: center;
            color: red;
            font-size: 20px;
            font-weight: bold;
            margin-top:-15px;
        }
        .card .head .month{
            text-align: center;
            color: green;
            font-size: 17px;
            font-weight: bold;
        }

        .card-body .table{
            /*font-family: Arial, Helvetica, sans-sarif;*/
            border-collapse: collapse;
            width: 100%;
        }
        .card-body .table .table-head{
            background-color: lightblue;

        }
        .card-body .table th ,.card-body .table td{
            border:1px solid #ddd;
            /*padding: 8px;*/
            text-align: center;
        }
        .table tr:nth-child(even){
            background-color: #cbd9d4;
        }
        .card-body .table th{
            padding-top: 12px;
            padding-bottom: 12px;
            font-size: 14px!important;
        }
        .card-body .table td{
           font-size: 13px!important;
           padding-bottom: 7px;
       }
       .card .row .col-12{
        margin-bottom: 6px;
        font-weight: bold;
    }
    .card .row{
        margin-bottom: 20px;
    }
</style>
</head>
<body>

    <div class="card">
       <div class="head">
          <h3>Mess Management System</h3>
          <h4>Total Report Sheet</h4>
          <h4 class="month"><span class="left">From: {{ date('M d, Y', strtotime($start_month)) }}</span>&nbsp;&nbsp;-&nbsp;&nbsp;<span class="right">To: {{ date('M d, Y', strtotime($finish_month)) }}</span></h4>
          <hr>
      </div>
      <div class="row">
          <div class="col-12 col-sm-2">
            <div class="info-box bg-light">
              <div class="info-box-content">
                <span class="info-box-text text-center text-muted">Total Meal :</span>

                <span class="info-box-number text-center text-muted mb-0">{{ $total_meal }}</span>

            </div>
        </div>
    </div> 
    <div class="col-12 col-sm-3">
        <div class="info-box bg-light">
          <div class="info-box-content">
            <span class="info-box-text text-center text-muted">Total Amount :</span>

            <span class="info-box-number text-center text-muted mb-0"> Tk.{{ $fund }}</span>

        </div>
    </div>
</div>
<div class="col-12 col-sm-3">
    <div class="info-box bg-light">
      <div class="info-box-content">
        <span class="info-box-text text-center text-muted">Total Expanses :</span>

        <span class="info-box-number text-center text-muted mb-0">Tk.{{ $total_amount }}</span>

    </div>
</div>
</div>
<div class="col-12 col-sm-2">
    <div class="info-box bg-light">
      <div class="info-box-content">
        <span class="info-box-text text-center text-muted">Total Meal Rate :</span>
        <span class="info-box-number text-center text-muted mb-0">Tk {{ number_format($meal_rate ,2)}}</span>
    </div>
</div>
</div>
<div class="col-12 col-sm-2">
    <div class="info-box bg-light">
      <div class="info-box-content">
         @if($total_fund > 0)
         <span class="info-box-text text-center text-muted">Fund(+) :</span>
         <span class="info-box-number text-center text-success mb-0">Tk.
           {{ $total_fund }}
       </span>
       @elseif ($total_fund < 0)
       <span class="info-box-text text-center text-muted ttext-danger">Fund(-) :</span>
       <span class="info-box-number text-center text-muted mb-0">Tk.
        {{ $total_fund }}
    </span>
    @else
    <span class="info-box-text text-center text-muted">Fund :</span>
    <span class="info-box-number text-center text-muted mb-0">Tk.
     0.00
 </span>
 @endif
</div>
</div>
</div>
</div>
<div class="card-body">
    <table class="table">
        <thead class="table-head">
            <tr>
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
            $meal =  \DB::select("SELECT * FROM meals WHERE status = '1' and date BETWEEN '$start_month' and '$finish_month' and members_id = '$total->member_id'");
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



</body>
</html>
