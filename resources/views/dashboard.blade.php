@extends('_layouts.index')

@section('page_title', 'Home - Dashboard')
@section('header_title', 'Home')
@section('header_subtitle', '- Dashboard')

@section('content')
    <?php 
    // echo '<pre>';
    // print_r(session()->all()); 
    // echo '</pre>';
    // echo session('user.first_name');
    ?>
    <div class="content pt-0">
        <div class="row">
            
            <div class="card card-1 card-2 d-none"></div>

            <div class="col-lg-3">
                <div class="card card-1">
                    <div class="card-body text-center">
                    <i class="icon-file-check icon-2x text-success-400 border-success-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="card-title">Vehicle Invoice - Today</h5>
                        <h1>@{{ today }}</h1>
                        <a href="{{ route('dashboard.today') }}" class="btn bg-success-400">MORE DETAILS</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-1">
                    <div class="card-body text-center">
                        <i class="icon-file-plus icon-2x text-warning-400 border-warning-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="card-title">Pending for Invoice</h5>
                        <h1>@{{ for_invoice }}</h1>                  
                        <a href="{{ route('dashboard.pending') }}" class="btn bg-warning-400">MORE DETAILS</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-1">
                    <div class="card-body text-center">
                        <i class="icon-truck icon-2x text-blue border-blue border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="card-title">Tagged Units</h5>
                        <h1>@{{ tagged }}</h1>                       
                        <a href="{{ route('dashboard.tagged') }}" class="btn bg-blue">MORE DETAILS</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card card-1">
                    <div class="card-body text-center">
                        <i class="icon-book icon-2x text-teal border-teal border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="card-title">Available Warranty Booklet</h5>
                        <h1>@{{ avail_wb }}</h1>                       
                        <a href="{{ route('dashboard.wb') }}" class="btn bg-teal">MORE DETAILS</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card card-2">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Monthly Summary<span class="font-size-base text-muted ml-2">Vehicle Invoice</span></h5>
                    </div>

                    <div class="card-body">
                        <div class="chart-container">
                            <div class="chart has-fixed-height" id="columns_stacked"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection


@push('scripts')
    <script type="text/javascript">
        var vm = new Vue({
            el: '#app',
            data() {
                return {
                    today: 0,
                    for_invoice: 0,
                    tagged: 0,
                    avail_wb: 0,

                    month: [],
                    fleet: [],
                    vehicle: []
                }
            },
            created() {
                this.fetchDashboardData();
                this.fetchDashboardMonthlySummary();
               
            },
            mounted(){
                
            },
            watch:{
               
            },
            computed:{
            
            },
            methods:{
                fetchDashboardData: function(){

                    this.blockUI('.content .card-1');

                    axios.get('/dashboard-data')
                    .then((response) => {

                        this.today = response.data.today
                        this.for_invoice = response.data.for_invoice
                        this.tagged = response.data.tagged,
                        this.avail_wb = response.data.avail_wb
                    })
                    .catch(function (error){
                        console.log(error);
                    })
                    .finally(() => {
                        this.unblockUI('.content .card-1');
                    })
                },
                fetchDashboardMonthlySummary: function(){
                    
                    this.blockUI('.content .card-2');

                    axios.get('/fetch-dashboard-monthly')
                    .then((response) => {
                        this.month = response.data.month
                        this.vehicle = response.data.vehicle
                        this.fleet = response.data.fleet
                    })
                    .then(() => {
                        this.showChart();
                    })
                    .catch(function (error){
                        console.log(error);
                    })
                    .finally(() => {
                        this.unblockUI('.content .card-2');
                    })
                },
                blockUI: function(object){
                    $(object).block({ 
                        message: '<i class="icon-spinner2 spinner"></i>',
                        overlayCSS: {
                            backgroundColor: '#fff',
                            opacity: 0.8,
                            cursor: 'wait',
                            'box-shadow': '0 0 0 1px #ddd'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'none'
                        }
                    });
                },
                unblockUI: function(object){
                    $(object).unblock(); 
                },
                showChart: function(){

                    var columns_stacked_element = document.getElementById('columns_stacked');
                    var columns_stacked = echarts.init(columns_stacked_element);

                    series =
                        [
                            {
                                name: 'Vehicle',
                                type: 'bar',
                                stack: 'sales',
                                // barWidth: 50,
                                data: this.vehicle
                            },
                            {
                                name: 'Fleet',
                                type: 'bar',
                                stack: 'sales',
                                // barWidth: 50,
                                data: this.fleet
                            }
                        ]

                    genFormatter = (series) => {
                        return (param) => {
                            // console.log(param);
                            let sum = 0;
                            series.forEach(item => {
                                sum += parseInt(item.data[param.dataIndex]);
                            });
                            return sum
                        }
                    };


                    columns_stacked.setOption({

                        // Define colors
                        color: ['#00796B','#4DB6AC'],

                        // Global text styles
                        textStyle: {
                            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                            fontSize: 13
                        },

                        // Chart animation duration
                        animationDuration: 750,

                        // Setup grid
                        grid: {
                            left: 0,
                            right: 10,
                            top: 35,
                            bottom: 0,
                            containLabel: true
                        },

                        // Add legend
                        legend: {
                            data: ['Vehicle', 'Fleet'],
                            itemHeight: 8,
                            itemGap: 20
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'axis',
                            backgroundColor: 'rgba(0,0,0,0.75)',
                            padding: [10, 15],
                            textStyle: {
                                fontSize: 13,
                                fontFamily: 'Roboto, sans-serif'
                            },
                            axisPointer: {
                                type: 'shadow',
                                shadowStyle: {
                                    color: 'rgba(0,0,0,0.025)'
                                }
                            }
                        },

                        // Horizontal axis
                        xAxis: [{
                            type: 'category',
                            data: this.month,
                            axisLabel: {
                                color: '#333'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#999'
                                }
                            },
                            splitLine: {
                                show: true,
                                lineStyle: {
                                    color: '#eee',
                                    type: 'dashed'
                                }
                            }
                        }],

                        // Vertical axis
                        yAxis: [{
                            type: 'value',
                            axisLabel: {
                                color: '#333'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#999'
                                }
                            },
                            splitLine: {
                                lineStyle: {
                                    color: '#eee'
                                }
                            },
                            splitArea: {
                                show: true,
                                areaStyle: {
                                    color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                                }
                            }
                        }],

                        // Add series
                        series: series.map((item, index) => Object.assign(item, {
                            type: 'bar',
                            stack: true,
                            label: {
                                show: index == series.length - 1 ? true : false,
                                formatter: genFormatter(series),
                                fontSize: 10,
                                color: '#333333',
                                position: 'top'
                            },
                        }))
                    });
                    
                    var triggerChartResize = function() {
                        columns_stacked_element && columns_stacked.resize();
                    };

                    // On sidebar width change
                    $(document).on('click', '.sidebar-control', function() {
                        setTimeout(function () {
                            triggerChartResize();
                        }, 0);
                    });

                    // On window resize
                    var resizeCharts;
                    window.onresize = function () {
                        clearTimeout(resizeCharts);
                        resizeCharts = setTimeout(function () {
                            triggerChartResize();
                        }, 200);
                    };
                },

            }
        });
    </script>
@endpush
