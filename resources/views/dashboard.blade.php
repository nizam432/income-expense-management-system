@extends('adminlte::page')

@section('title', 'ড্যাশবোর্ড')

@section('content_header')
    <h1><i class="fas fa-chart-line"></i>
        @php
            echo __('messages.dashboard');
        @endphp
    </h1>
@stop

@section('content')

@php
$totalIncome = $totalIncome ?? 0;
$totalExpense = $totalExpense ?? 0;
$currentBalance = $currentBalance ?? 0;
@endphp

{{-- Date Filter Dropdown --}}
<div class="dropdown mb-3">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dateFilterMenu"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Filter By Date
    </button>

    <div class="dropdown-menu" aria-labelledby="dateFilterMenu">
        <a class="dropdown-item date-option" data-type="today">Today</a>
        <a class="dropdown-item date-option" data-type="month">This Month</a>
        <a class="dropdown-item date-option" data-type="6month">Last 6 Months</a>
        <a class="dropdown-item date-option" data-type="year">Last Year</a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" id="singleDateBtn">Custom Single Date</a>
        <a class="dropdown-item" id="rangeDateBtn">Custom Date Range</a>

        <input type="text" id="singleDatePicker" hidden>
        <input type="text" id="rangeDatePicker" hidden>
    </div>
</div>

{{-- Summary Boxes --}}
<div class="row">
    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="income_box">{{ number_format($totalIncome,2) }} ৳</h3>
                <p>{{__('dashboard.total_income')}}</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-up"></i></div>
            <a href="#" class="small-box-footer">{{__('dashboard.details')}} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="expense_box">{{ number_format($totalExpense,2) }} ৳</h3>
                <p>{{__('dashboard.total_expense')}}</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-down"></i></div>
            <a href="#" class="small-box-footer">{{__('dashboard.details')}}  <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-{{ $currentBalance >= 0 ? 'info' : 'warning' }}">
            <div class="inner">
                <h3 id="balance_box">{{ number_format($currentBalance,2) }} ৳</h3>
                <p>{{__('dashboard.current_balance')}}</p>
            </div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
            <a href="#" class="small-box-footer">{{__('dashboard.details')}}  <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row">
    <div class="col-md-12">
        <div class="card p-4 shadow-lg">
            <h5 class="mb-3">Daily Expense</h5>
            <canvas id="dailyExpenseChart" style="height:350px;"></canvas>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card p-4 shadow-lg">
            <h5 class="mb-3">Category-wise Expense</h5>
            <canvas id="categoryExpenseChart" style="height:350px;"></canvas>
        </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function(){

    // Hidden date pickers
    $('#singleDatePicker').daterangepicker({
        singleDatePicker:true,
        showDropdowns:true,
        autoUpdateInput:false,
        locale:{ format:'YYYY-MM-DD' }
    }).on('apply.daterangepicker', function(ev,picker){
        let d = picker.startDate.format('YYYY-MM-DD');
        loadDashboard(d,d);
    });

    $('#rangeDatePicker').daterangepicker({
        autoUpdateInput:false,
        locale:{ format:'YYYY-MM-DD' }
    }).on('apply.daterangepicker', function(ev,picker){
        let start = picker.startDate.format('YYYY-MM-DD');
        let end   = picker.endDate.format('YYYY-MM-DD');
        loadDashboard(start,end);
    });

    // Quick filter dropdown
    $('.date-option').click(function(){
        let type = $(this).data('type');
        let today = moment();
        let start, end;
        if(type===''){
            start = today.clone().startOf('month'); 
            end   = today.clone().endOf('month');
        } 
        else if(type==='today'){
            start = end = today;
        } 
        else if(type==='month'){
            start = today.clone().startOf('month'); 
            end   = today.clone().endOf('month');
        } 
        else if(type==='6month'){
            start = today.clone().subtract(5,'months').startOf('month'); 
            end   = today.clone().endOf('month');
        } 
        else if(type==='year'){
            start = today.clone().subtract(1,'year').startOf('year');
            end   = today.clone().endOf('year');
        }

        loadDashboard(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });

    // Open custom pickers
    $('#singleDateBtn').click(function(){ $('#singleDatePicker').trigger('click'); });
    $('#rangeDateBtn').click(function(){ $('#rangeDatePicker').trigger('click'); });

    // Daily Expense Chart
    const ctxDaily = document.getElementById('dailyExpenseChart').getContext('2d');
    let dailyExpenseChart = new Chart(ctxDaily, {
        type:'line',
        data:{
            labels:{!! json_encode($dates ?? []) !!},
            datasets:[{
                label:'Daily Expense (৳)',
                data:{!! json_encode($totals ?? []) !!},
                borderColor:'rgba(54,162,235,1)',
                backgroundColor:'rgba(54,162,235,0.2)',
                fill:true,
                tension:0.3,
                borderWidth:2,
                pointRadius:4,
                pointHoverRadius:6
            }]
        },
        options:{
            responsive:true,
            plugins:{ legend:{display:true, position:'bottom'} },
            scales:{
                x:{ title:{ display:true, text:'Date' } },
                y:{ title:{ display:true, text:'Amount (৳)' }, beginAtZero:true }
            }
        }
    });

    // Category-wise Expense Chart
    const ctxCategory = document.getElementById('categoryExpenseChart').getContext('2d');
    let categoryExpenseChart = new Chart(ctxCategory, {
        type:'bar',
        data:{
            labels:{!! json_encode($categoryLabels ?? []) !!},
            datasets:[{
                label:'Category Expense (৳)',
                data:{!! json_encode($categoryTotals ?? []) !!},
                backgroundColor:'rgba(255,99,132,0.6)',
                borderColor:'rgba(255,99,132,1)',
                borderWidth:1
            }]
        },
        options:{
            responsive:true,
            plugins:{ legend:{display:true, position:'bottom'} },
            scales:{
                x:{ title:{ display:true, text:'Category' } },
                y:{ title:{ display:true, text:'Amount (৳)' }, beginAtZero:true }
            }
        }
    });

    // AJAX load dashboard
    function loadDashboard(start,end){
        $.ajax({
            url:"{{ route('dashboard.filter') }}",
            method:"GET",
            data:{ start_date:start, end_date:end },
            success:function(res){
                $('#income_box').text(parseFloat(res.totalIncome).toFixed(2)+' ৳');
                $('#expense_box').text(parseFloat(res.totalExpense).toFixed(2)+' ৳');
                $('#balance_box').text(parseFloat(res.currentBalance).toFixed(2)+' ৳');

                // Update charts
                dailyExpenseChart.data.labels = res.dates ?? [];
                dailyExpenseChart.data.datasets[0].data = res.totals ?? [];
                dailyExpenseChart.update();

                categoryExpenseChart.data.labels = res.categoryLabels ?? [];
                categoryExpenseChart.data.datasets[0].data = res.categoryTotals ?? [];
                categoryExpenseChart.update();
            }
        });
    }

});
</script>
@stop
