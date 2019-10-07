@extends('_layouts.index')

@section('page_title', 'Warranty Booklet - Allocation')
@section('header_title', 'Warranty Booklet')
@section('header_subtitle', '- Allocation')

@section('content')
    <div class="content pt-0">
        <div class="row">
            <div class="col-md-12">
                {{-- Success message v-if= "show_success_msg" --}}
                <div v-if= "show_success_msg" class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    <span class="font-weight-semibold">Success! </span>
                    Tagging of wb number has been saved.
                    You can now go to <span class="font-weight-semibold">[ Invoice / Print ]</span> to print the corresponding invoices.
                </div>
                <div class="card card-block card-1 d-none"></div>
                <div class="card card-block card-1">
                    <div class="card-body" style="padding-bottom: 0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-block"><strong>Customer</strong></label>
                                    <select class="form-control select2">
                                        <option value="">Nothing Selected</option>
                                        <option v-for="customer in customers" :value="customer.customer_id">
                                            @{{ (customer.customer_name + ' - ' + customer.account_name) }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 text-right" style="position:relative">
                                <div class="list-icons" style="right:10px;bottom: 20px;position:absolute;">
                                    <button :disabled="disable_save" class="btn bg-teal btn-sm" @click='saveWb' >
                                        Save
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">
                    <div class="card-body">
                        <div class="table-wrapper">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th align="center"></th>
                                        <th align="center">#</th>
                                        <th align="center">CS Number</th>
                                        <th align="center">WB Number</th>
                                        <th>Sales Model</th>
                                        {{-- <th>Invoice Number</th> --}}
                                        <th align="center">Invoice Date</th>
                                        <th align="center">CSR Number</th>
                                        <th>Fleet Customer</th>
                                    </tr>
                                </thead>
                                <tbody v-if="invoices.length > 0" >
                                    <tr v-for="(invoice, index) in invoices">
                                        <td align="center">
                                            <input type="checkbox" class="check-uniform" v-model="invoice.w_csr" @click="assignWb">
                                        </td>
                                        <td align="center">@{{ index + 1 }}</td>
                                        <td align="center">@{{ invoice.cs_number }}</td>
                                        <td align="center">@{{ invoice.wb_number }}
                                        </td>
                                        <td>@{{ invoice.sales_model }}</td>
                                        {{-- <td align="center">@{{ invoice.invoice_number }}</td> --}}
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
                    selected: [],
                    count: 0,

                    show_success_msg: false,
                    disable_save: true,
                }
            },
            created() {
               this.fetchCustomers();
            },
            watch:{
                customer_id: function () {
                    this.show_success_msg = false
                    this.fetchInvoices()
                },
            },
            mounted(){
                let self = this
                
                $('select.select2').select2({
                    width: 450,
                    placeholder: "Click to filter customer...",
                });
                
                $('select.select2').on('select2:select', function (e) {
                    self.customer_id = e.params.data.id
                });
            },
            methods:{
                fetchCustomers: function() {

                    this.blockUI('.card-block.card-1');

                    axios.get('/customer-list-allocation')
                    .then((response) => {
                        // console.log(response.data)
                        this.customers = response.data
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.unblockUI('.card-block.card-1');
                    })
                },
                fetchInvoices: function() {

                   axios.get('/invoice-fetch-allocation', {
                        params: {
                            customer_id: this.customer_id
                        }
                    })
                    .then((response) => {
                        this.invoices = response.data;
                        
                        if ( $.fn.dataTable.isDataTable( '.table' ) ) 
                        this.table.destroy();

                        if(this.invoices.length == 0)
                        throw new Error("No Data Found")

                    })
                    .then(() => {
                        
                        this.table = $('.table').DataTable({
                            columnDefs: [
                                { 
                                    width: "20px",
                                    targets: [0]
                                },
                                { 
                                    width: "80px",
                                    targets: [1,2]
                                },
                                { 
                                    width: "120px",
                                    targets: [3,4,5]
                                },
                                { 
                                    width: "200px",
                                    targets: [6]
                                },
                                {
                                    orderable: false,
                                    targets: [2]
                                }
                            ],
                            scrollX: true,
                            scrollY: '60vh',
                            scrollCollapse: true,
                            paging: false
                        });
                    })
                    .then(() => {
                        $('input.check-uniform').uniform();
                    })
                    .then(() => {
                        
                        let selected = [];
                        this.invoices.forEach(function (invoice, index) {
                            if(invoice.w_csr){
                                selected.push(index);
                            }
                        });
                        
                        if(selected.length > 0){
                            axios.get('/wb-fetch', {
                                params: {
                                    cnt: selected.length
                                }
                            })
                            .then((response) => {
                                if(response.data.length > 0){
                                    response.data.forEach((wb, index) => {
                                        this.$set(this.invoices[selected[index]], 'wb_number', wb.wb_number);
                                        this.$set(this.invoices[selected[index]], 'wb_id', wb.id);
                                    });
                                    this.disable_save = false;
                                }
                                else{
                                    this.disable_save = true;
                                }
                            })
                        }
                        else{
                            this.disable_save = true;
                        }
                        
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                },
                assignWb: function(){

                    setTimeout(() => {

                        const count = this.invoices.reduce((cnt, invoice) => cnt + (invoice.w_csr ? 1 : 0), 0)

                        if(count > 0){
                            axios.get('/wb-fetch', {
                                params: {
                                    cnt: count
                                }
                            })
                            .then((response) => {

                                // console.log(response.data)
                                if(response.data.length > 0){
                                    let wb = response.data
                                    let ctr = 0
                                    this.invoices.forEach((invoice, index) => {
                                        if(invoice.w_csr){
                                            if(typeof wb[ctr] !== 'undefined') {
                                                this.$set(this.invoices[index], 'wb_number', wb[ctr]['wb_number'])
                                                this.$set(this.invoices[index], 'wb_id', wb[ctr]['id'])
                                            }
                                            else {
                                                this.$set(this.invoices[index], 'wb_number', null)
                                                this.$set(this.invoices[index], 'wb_id', null)
                                            }
                                            ctr++
                                        }
                                        else{
                                            this.$set(this.invoices[index], 'wb_number', null)
                                            this.$set(this.invoices[index], 'wb_id', null)
                                        }
                                    });
                                    this.disable_save = false;
                                }
                                else{
                                    this.disable_save = true
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            })
                        }
                        else{
                            this.invoices.forEach((invoice, index) => {
                                this.$set(this.invoices[index], 'wb_number', null)
                                this.$set(this.invoices[index], 'wb_id', null)
                            });
                            this.disable_save = true;
                        }
                    }, 0);
                },
                saveWb: function(){

                    this.blockUI('.card-block');

                    axios.patch('/wb-update', {
                        invoices : this.invoices
                    })
                    .then((response) => {
                        console.log(response.data);

                        if(!response.data){
                            Swal.fire({
                                type: 'error',
                                text: 'Warranty booklet has been already used.',
                                customClass: {
                                    // confirmButton: 'btn bg-teal btn-sm'
                                },
                                // showConfirmButton: false,
                                // timer: 1500
                            })
                            throw new Error("WB not available")
                        }
                        else{
                            Swal.fire({
                                type: 'success',
                                text: 'Warranty booklet successfully tagged.',
                                customClass: {
                                    confirmButton: 'btn bg-teal btn-sm'
                                },
                                // showConfirmButton: false,
                                // timer: 1500
                            })
                            this.show_success_msg = true;
                        }
                        
                    })
                    .then(() => {
                        this.fetchInvoices()
                    })
                    .catch(() => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.unblockUI('.card-block');
                    });
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