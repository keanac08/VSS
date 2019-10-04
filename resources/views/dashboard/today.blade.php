@extends('_layouts.index')

@section('page_title', 'Vehicle Invoice - Today')
@section('header_title', 'Vehicle Invoice')
@section('header_subtitle', ' - Today')

@section('content')
    <div class="content pt-0">
        <div class="row">
            <div class="col-md-12">
                <!-- Basic card -->
                <div class="card">
                   <table class="table table-dt">
						<thead>
							<tr>
                                <th>#</th>
                                <th>CS Number</th>
                                <th>WB Number</th>
				                <th>Invoice Number</th>
				                <th>Invoice Date</th>
                                <th>Customer</th>
                                <th>Sales Model</th>
				                <th>Body Color</th>
				                <th>Fleet Customer</th>
				               
				            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(invoice, index) in invoices">
                                <td>@{{ index + 1 }}</td>        
                                <td>@{{ invoice.cs_number }}</td>        
                                <td>@{{ invoice.wb_number }}</td>        
                                <td>@{{ invoice.invoice_number }}</td>        
                                <td>@{{ invoice.invoice_date }}</td>        
                                <td>@{{ invoice.account_name }}</td>        
                                <td>@{{ invoice.sales_model }}</td>        
                                <td>@{{ invoice.body_color }}</td>        
                                <td>@{{ invoice.fleet_name }}</td>        
                            </tr>
                        </tbody>
					</table>
                </div>
            <!-- /basic card -->
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
                   invoices: []
                }
            },
            created(){
                this.fetchinvoices();
            },
            mounted () {
               
            },
            methods:{
                fetchinvoices: function() {
                    axios.get('/fetch-dashboard-today')
                    .then((response) => {
                        this.invoices = response.data
                        // console.log(response.data)
                    })
                    .then(() => {
                        $('.table-dt').DataTable({
                            scrollX: true
                        });
                        $('.dataTables_length select').select2({
                            minimumResultsForSearch: Infinity,
                            dropdownAutoWidth: true,
                            width: 'auto'
                        });
                    })
                    .catch(function (error){
                        console.log(error);
                    })
                }
            }
        });
    </script>
@endpush