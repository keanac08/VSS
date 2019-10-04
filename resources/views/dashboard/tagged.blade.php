@extends('_layouts.index')

@section('page_title', 'Tagged Units')
@section('header_title', 'Tagged Units')
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
                            <tr v-for="(tag, index) in tagged">
                                <td>@{{ index + 1 }}</td>        
                                <td>@{{ tag.cs_number }}</td>         
                                <td>@{{ tag.customer }}</td>         
                                <td>@{{ tag.sales_model }}</td>         
                                <td>@{{ tag.body_color }}</td>         
                                <td>@{{ tag.fleet_customer }}</td>         
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
                   tagged: []
                }
            },
            created(){
                this.fetchTagged();
            },
            mounted () {
               
            },
            methods:{
                fetchTagged: function() {
                    axios.get('/fetch-dashboard-tagged')
                    .then((response) => {
                        this.tagged = response.data
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