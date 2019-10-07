@extends('_layouts.index')

@section('page_title', 'Pending for Invoice')
@section('header_title', 'Pending for Invoice')
@section('header_subtitle', '')

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
                                <th>Customer</th>
                                <th>Sales Model</th>
                                <th>Body Color</th>
                                <th>Fleet Customer</th>
				               
				            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pending, index) in pending_invoices">
                                <td>@{{ index + 1 }}</td>        
                                <td>@{{ pending.cs_number }}</td>         
                                <td>@{{ pending.customer }}</td>         
                                <td>@{{ pending.sales_model }}</td>         
                                <td>@{{ pending.body_color }}</td>         
                                <td>@{{ pending.fleet_customer }}</td>         
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
                   pending_invoices: []
                }
            },
            created(){
                this.fetchPendingInvoices();
            },
            mounted () {
               
            },
            methods:{
                fetchPendingInvoices: function() {
                    axios.get('/fetch-dashboard-pending')
                    .then((response) => {
                        this.pending_invoices = response.data
                        // console.log(response.data)
                    })
                    .then(() => {
                        $('.table-dt').DataTable({
                            // scrollX: true
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