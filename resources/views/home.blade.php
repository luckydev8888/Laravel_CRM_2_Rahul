@extends('layouts.app')

@section('custom_styles')
<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    <!--begin::Index-->
    <div class="card card-page">
        <!--begin::Card body-->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="chart1" class="mh-400px"></div>
                </div>
                <div class="col-md-12">
                    <div id="chart2" class="mh-400px"></div>
                </div>
                <div class="col-md-12">
                    <div id="chart3" class="mh-400px"></div>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Index-->
</div>
@endsection

@section('custom_scripts')
<script src="assets/js/scripts.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // var sam = document.getElementById('sam');
    // var nosam = document.getElementById('nosam');
    // var samandin = document.getElementById('samandin');

    // Define colors
    var nocontact = '#9ee2e6';
    var cockroach = '#6c757d';
    var not_interesting = '#fd7e14';
    var not_interested = '#dee2e6';
    var interested = '#198754';
    var calls = '#ffc107';
    var standby = '#0dcaf0';
    var almost = '#fd7e14';
    var customer = '#20c997';

    // Define fonts
    var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');
    var colors = ['#9ee2e6', '#6c757d', '#fd7e14', '#dee2e6', '#198754', '#ffc107', '#0dcaf0', '#fd7e14', '#20c997'];
    var datas = [
        { name: 'No Contact', data: [{{$nocontact}}]}, 
        { name: 'COCKROACH', data: [{{$cockroach}}]}, 
        { name: 'Not Interesting', data: [{{$not_interesting}}]}, 
        { name: 'NO INTERESTED', data: [{{$not_interested}}]},
        { name: 'Interested', data: [{{$interested}}]}, 
        { name: 'CALLS', data: [{{$calls}}]}, 
        { name: 'STANDBY', data: [{{$standby}}]}, 
        { name: 'ALMOST', data: [{{$almost}}]}, 
        { name: 'CUSTOMER', data: [{{$customer}}]}
    ];
    
    var options = {
        series: [datas[0], datas[1], datas[2]],
        colors: [colors[0], colors[1], colors[2]],
        dataLabels: {
          enabled: true,
        //   textAnchor: 'start',
          formatter: function (val, opt) {
            console.log(val, opt);
            return opt.globals.seriesNames[opt.seriesIndex] + ': ' + Math.round(val) + '%   ' + opt.globals.seriesTotals[opt.seriesIndex];
          },
          offsetX: 0,
          dropShadow: {
            enabled: true
          }
        },
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            stackType: '100%'
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        stroke: {
            width: 1,
            colors: ['#fff']
        },
        title: {
            text: 'Without Samples'
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val;
                }
            }
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40
        }
    }
    
    var chart1 = new ApexCharts(document.querySelector("#chart1"), options);
    chart1.render();

    options.series = [ datas[1], datas[3], datas[2], datas[4] ];
    options.colors = [ colors[1], colors[3], colors[2], colors[4] ];
    options.title = { text: 'With Samples' }
    var chart2 = new ApexCharts(document.querySelector("#chart2"), options);
    chart2.render();
    
    options.series = [ datas[5], datas[6], datas[7], datas[8] ];
    options.colors = [ colors[5], colors[6], colors[7], colors[8] ];
    options.title = { text: 'With Samples & Interests' }
    var chart3 = new ApexCharts(document.querySelector("#chart3"), options);
    chart3.render();
    
</script>
@endsection