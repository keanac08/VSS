@extends('_layouts.index')

@section('page_title', 'Warranty Booklet - Available')
@section('header_title', 'Warranty Booklet')
@section('header_subtitle', '- Available')

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
                                <th>WB Number</th>
				                <th>Uploaded Date</th>
				                <th>Uploaded By</th>
                                <th>Batch Name</th>
                                <th>Sales Model</th>
                                <th>Status</th>
				               
				            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(wb, index) in wbs">
                                <td>@{{ index + 1 }}</td>        
                                <td>@{{ wb.prefix + wb.wb_number }}</td>      
                                <td>@{{ wb.uploaded_date }}</td>         
                                <td>@{{ wb.uploaded_by }}</td>         
                                <td>@{{ wb.batch_name }}</td>
                                <td>@{{ wb.sales_model }}</td>
                                <td><span class="badge badge-success">Available</span></td>     
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



        function updateFunction (el, binding) {
            // get options from binding value. 
            // v-select="THIS-IS-THE-BINDING-VALUE"
            let options = binding.value || {};

            // set up select2
            $(el).select2(options).on("select2:select", (e) => {
            // v-model looks for
            //  - an event named "change"
            //  - a value with property path "$event.target.value"
            el.dispatchEvent(new Event('change', { target: e.target }));
            });
        }

        Vue.directive('select', {
            inserted: updateFunction ,
            componentUpdated: updateFunction,
        });



        var vm = new Vue({
            el: '#app',
            data() {
                return {
                   wbs: []
                }
            },
            created(){
                this.fetchWb();
            },
            mounted () {
               
            },
            methods:{
                fetchWb: function() {
                    axios.get('/fetch-dashboard-wb')
                    .then((response) => {
                        this.wbs = response.data
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