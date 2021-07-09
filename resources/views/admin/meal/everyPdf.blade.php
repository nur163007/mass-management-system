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

        .card-body .table{
            font-family: Arial, Helvetica, sans-sarif;
            border-collapse: collapse;
            width: 100%;
        }
        .card-body .table .table-head{
            background-color: lightblue;
        }
        .card-body .table th ,.card-body .table td{
            border:1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .table tr:nth-child(even){
            background-color: #cbd9d4;
        }
        .card-body .table th{
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .card .rightcard .top-table{
            /*width:100%;*/
            text-align: left;
            margin-bottom: 25px;
        }
        .card .rightcard .top-table th{
          
            text-align: left;
        }

    </style>
</head>
<body>

    <div class="card">
     <div class="head">
      <h3>Mess Management System</h3>
      <h4>Meal Summary Sheet</h4>
      <hr>
  </div>
  <div class="row">

    <div class="rightcard">

        <table class="top-table">

            <tbody>
                <tr>
                    <th style="width:20%;">Name :</th>
                    <td>{{ $members->full_name }}</td>
                </tr>  
                <tr>
                    <th style="width: 20%">Total Meals :</th>
                    @foreach ($total as $to)
                    <td> {{ $to->total_meal ? $to->total_meal : 0}}</td>
                    @endforeach
                </tr>  
                <tr>
                    <th style="width: 20%">Meal Month :</th>
                    <td> {{ $members->month }}</td>
                </tr>               
            </tbody>
        </table>
    </div>
</div>
<div class="card-body">
    <table class="table">
        <thead class="table-head">
            <tr>
                <th>SL NO</th>
                <th>Breakfast</th>

                <th>Lunch</th>
                <th>Dinner</th>
                <th>Date</th>

            </tr>
        </thead>
        <tbody id="tbody">

          @foreach ($meals as $details)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $details->breakfast }}</td>

            <td>{{ $details->lunch }}</td>
            <td>{{ $details->dinner }}</td>
            <td>{{ date('M d,Y',strtotime($details->date)) }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>



</body>
</html>
