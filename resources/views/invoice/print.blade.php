@extends('_layouts.index')

@section('page_title', 'Invoice - Printing')
@section('header_title', 'Invoice')
@section('header_subtitle', '- Printing')

@section('content')
    <div class="content pt-0">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block card-1 d-none"></div>
                <div class="card card-block">
                    <div class="card-body" style="padding-bottom: 0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-block"><strong>Customer</strong></label>
                                    <select class="form-control select2">
                                        <option value="">Nothing Selected</option>
                                        <option v-for="customer in customers" :value="customer.customer_id">@{{ (customer.customer_name + ' - ' + customer.account_name) }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
								<div class="form-group">
									<label class="d-block"><strong>Invoice Date</strong></label>
									<input type="text" class="form-control daterange" :value="from_date + ' - ' + to_date"> 
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline padding-bottom-0">
                        <h6 class="card-title">&nbsp;</h6>
                        <div class="header-elements">
                            <button :disabled="disable_print" type="button" class="btn btn-sm bg-teal" data-toggle="modal" data-target="#exampleModal">Print</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-wrappers">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>CS Number</th>
                                        <th>WB Number</th>
                                        <th>Sales Model</th>
                                        <th>Invoice Number</th>
                                        <th>Invoice Date</th>
                                        <th>CSR Number</th>
                                        <th>Fleet Customer</th>
                                    </tr>
                                </thead>
                                <tbody v-if="invoices.length > 0" >
                                    <tr v-for="(invoice, index) in invoices">
                                        <td align="center">
                                            <input type="checkbox" class="check-uniform" v-model="invoice.print">
                                        </td>
                                        <td align="center">@{{ index + 1 }}</td>
                                        <td align="center">@{{ invoice.cs_number }}</td>
                                        <td align="center">@{{ invoice.wb_number }}
                                        </td>
                                        <td>@{{ invoice.sales_model }}</td>
                                        <td align="center">@{{ invoice.invoice_number }}</td>
                                        <td align="center">@{{ invoice.invoice_date }}</td>
                                        <td align="center">@{{ invoice.csr_number }}</td>
                                        <td>@{{ invoice.fleet_customer }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td align="center" colspan="8">- No Available Data -</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="icon-menu7 mr-2"></i> &nbsp;Print Reports</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <p><button @click="printInvoice" style="width:100%;" type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-printer"></i></b> Sales Invoice</button></p>
                    <p><button @click="printDelivery" style="width:100%;" type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-printer"></i></b> Delivery Receipt</button></p>
                    <p><button @click="printCrc" style="width:100%;" type="button" class="btn bg-teal btn-labeled btn-labeled-left"><b><i class="icon-printer"></i></b> Dealers Receiving Copy</button></p>
                    <p><button @click="printCqc" style="width:100%;" type="button" class="btn bg-teal-600 btn-labeled btn-labeled-left"><b><i class="icon-printer"></i></b> Certificate of Quality COntrol</button></p>
                </div>
            </div>
        </div>
    </div>
    <form ref="form" :action="form_action" target="_blank" method="POST">
        @csrf
        <input type="hidden" name="invoice_id[]" v-for="invoice in selected" :value="invoice">
    </form>
@endsection

@push('scripts')
    <script type="text/javascript">
        var vm = new Vue({
            el: '#app',
            data() {
                return {
                    customers: [],
                    invoices: [],
                    customer_id : '',
                    from_date: moment().format("MM/DD/YYYY"),
                    to_date: moment().format("MM/DD/YYYY"),
                    form_action: '',
                    selected: [],
                    disable_print: true
                }
            },
            created() {
               this.fetchCustomers();
            },
            watch:{
                customer_id: function () {
                   this.fetchInvoices();
                },
                from_date: function () {
                   this.fetchInvoices();
                },
            },
            mounted(){
                let self = this
                
                $('select.select2').select2({
                    placeholder: "Click to filter customer...",
                });
                
                $('select.select2').on('select2:select', function (e) {
                    self.customer_id = e.params.data.id
                });

                $('.daterange').daterangepicker({
                    opens: 'left',
                    applyClass: 'bg-slate-600',
                    cancelClass: 'btn-light'
                });

                $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                    self.from_date = picker.startDate.format('MM/DD/YYYY')
                    self.to_date = picker.endDate.format('MM/DD/YYYY')
                });
            },
            methods:{
                fetchCustomers: function() {

                    this.blockUI('.card-block');

                    axios.get('/customer-list-print')
                    .then((response) => {
                        this.customers = response.data
                    })
                    .catch(function (error){
                        console.log(error);
                    })
                    .finally(() => {
                        this.unblockUI('.card-block');
                    });
                },
                fetchInvoices: function(){

                    axios.get('/invoice-fetch-print', {
                        params: {
                            customer_id: this.customer_id,
                            from_date: this.from_date,
                            to_date: this.to_date
                        }
                    })
                    .then((response) => {
                        this.invoices = response.data;
                        
                        if(this.invoices.length == 0){
                            this.disable_print = true
                            throw new Error("No Data Found")

                        }
                        

                        if ( $.fn.dataTable.isDataTable( '.table' ) ) 
                        this.table.destroy();
                        
                    })
                    .then(() => {
                        
                        this.table = $('.table').DataTable({
                            columnDefs: [
                                { 
                                    width: "20px",
                                    targets: [0,1]
                                },
                                { 
                                    width: "60px",
                                    targets: [2,3]
                                },
                                { 
                                    width: "100px",
                                    targets: [4,5,6]
                                },
                                {
                                    className: "dt-head-center text-center", 
                                    targets: [ 0,1,2,3,5,6,7 ]
                                },
                                
                            ],
                            scrollX: true,
                            scrollY: '60vh',
                            scrollCollapse: true,
                            paging: false
                        });
                    })
                    .then(() => {
                        $('input.check-uniform').uniform();
                        this.disable_print = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                },
                printCrc: function(){
                    this.form_action = '{{ route("pdf.receiving") }}'
                    this.submitForm()
                },
                printCqc: function(){
                    this.form_action = '{{ route("pdf.quality") }}' 
                    this.submitForm()
                },
                printInvoice: function(){
                    this.form_action = '{{ route("pdf.invoice") }}' 
                    this.submitForm()
                },
                printDelivery: function(){
                    this.form_action = '{{ route("pdf.delivery") }}' 
                    this.submitForm()
                },
                submitForm: function(){
                   
                    this.selected = []
                    this.invoices.forEach((invoice, index) => {
                        if(invoice.print){
                            this.selected.push(invoice.invoice_id)
                        }
                    })

                    this.$nextTick(() => {
                        this.$refs.form.submit()
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

            }
        });
    </script>
@endpush