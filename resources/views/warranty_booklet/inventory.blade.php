@extends('_layouts.index')

@section('page_title', 'Warranty Booklet - Inventory')
@section('header_title', 'Warranty Booklet')
@section('header_subtitle', '- Inventory')

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
                                <th>Status</th>
				                <th>CS Number</th>
				                <th>Uploaded Date</th>
				                <th>Uploaded By</th>
				                <th>Tagged Date</th>
				                <th>Tagged By</th>
				               
				            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td align="center">{{ $row->cnt }}</td>
                                    <td align="center">{{ $row->wb_number }}</td>
                                    <td>
                                        @if ($row->cs_number == null)
                                            <span class="badge badge-success">Available</span>
                                        @else
                                            <span class="badge badge-danger">Issued</span>
                                        @endif
                                    </td>
                                    <td align="center">{{ $row->cs_number }}</td>
                                    <td align="center">{{ $row->uploaded_date }}</td>
                                    <td>{{ $row->uploaded_by }}</td>
                                    <td align="center">{{ $row->tagged_date }}</td>
                                    <td>{{ $row->tagged_by }}</td>
                                    
                                </tr>
                            @endforeach
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
            mounted () {
                $('.table-dt').DataTable();
                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    dropdownAutoWidth: true,
                    width: 'auto'
                });
            }
        });
    </script>
@endpush