@extends('_layouts.index')

@section('page_title', 'Warranty Booklet - Batch Entry')
@section('header_title', 'Warranty Booklet')
@section('header_subtitle', '- Batch Entry')

@section('content')
    <div class="content pt-0">
        <div class="row">
            <div class="col-md-6">
                {{-- success message --}}
                <div v-if= "show_success_msg" class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    <span class="font-weight-semibold">Success! </span>
                    WB series <span class="font-weight-semibold">@{{ last_batch_name }}</span> has been saved.
                </div>
                {{-- info message --}}
                <div class="alert alert-info alert-styled-left alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                    <span class="font-weight-semibold">Heads up!</span> Duplicate entry of wb number will be disregarded.
                </div>
                <!-- Basic card -->
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Entry Form</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form -->
                        <form @submit.prevent="submitForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <div class="form-group">
                                            <label><strong>Batch Name:</strong></label>
                                            <input Readonly type="text" class="form-control" placeholder="" v-model="wb_batch_name">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label><strong>Prefix:</strong></label>
                                                    <input type="text" class="form-control" placeholder="" v-model="wb_prefix">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label><strong>WB Number Series :</strong></label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend">
                                                            <span class="input-group-text">From</span>
                                                        </span>
                                                        <input type="number" class="form-control" v-model="wb_from">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div class="input-group">
                                                        <span class="input-group-prepend">
                                                            <span class="input-group-text">To</span>
                                                        </span>
                                                        <input type="number" class="form-control" v-model="wb_to">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn bg-teal btn-md">Save </button>
                            </div>
                        </form>
                        <!-- /form -->
                    </div>
                </div>
            <!-- /basic card -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var vm = new Vue({
            el: '#app',
            data() {
                return {
                    wb_prefix : 'A',
                    wb_from : '',
                    wb_to : '',
                    show_success_msg: false,
                    last_batch_name : ''
                }
            },
            computed:{
                wb_batch_name: function () {
                    return this.wb_prefix + this.wb_from + ' - ' + this.wb_prefix + this.wb_to
                }
            },
            methods: {
                submitForm() {

                    this.show_success_msg = false
                    this.last_batch_name = ''

                    if(parseInt(this.wb_from) > parseInt(this.wb_to) || !this.wb_from || !this.wb_to){
                        Swal.fire({
                            type: 'error',
                            text: 'Invalid warranty booklet series.',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                    else{
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'Upload Batch ' + this.wb_prefix+this.wb_from + '-' + this.wb_prefix+this.wb_to + ' ('+ (parseInt(this.wb_to) - parseInt(this.wb_from)) +')',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            // cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, upload it!'
                        }).then((result) => {
                            if (result.value) {
                                axios.post('/wb-store', {
                                    wb_batch_name : this.wb_batch_name,
                                    wb_prefix : this.wb_prefix,
                                    wb_from : this.wb_from,
                                    wb_to : this.wb_to
                                })
                                .then(() => {
                                    Swal.fire({
                                        type: 'success',
                                        text: 'Warranty booklet series has been saved.',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })

                                    this.last_batch_name = this.wb_batch_name
                                })
                                .then(() => {
                                    this.show_success_msg = true
                                    this.wb_from = ''
                                    this.wb_to = ''
                                })
                                .catch((error) => {
                                    console.log(error);
                                });
                            }
                        })
                    }
                    
                }
            }
        });
    </script>
@endpush
