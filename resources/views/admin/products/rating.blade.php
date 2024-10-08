@extends('admin.layouts.app')
@section('title', 'Product')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Ratings</h1>
            </div>

        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            @include('admin.message')
            <div class="card-header">
                <div class="card-title">
                    <button onclick="window.location.href='{{ route('product.index') }}' "
                        class="btn btn-default btn-sm">Back</button>
                </div>
                <div class="card-tools">
                    <form action="{{ route('product.index') }}" method="GET">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" value="{{ request('keyword') }}" name="keyword"
                                class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">#</th>

                            <th>Product</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Rated by</th>

                            <th width="100">Status</th>
                            <th width="100">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ratings as $rating)

                            <tr>
                                <td>{{$rating->id}}</td>
                                <td><a href="">{{$rating->productTitle}}</a></td>
                                <td>{{ $rating->rating }}*</td>
                                <td>{{ $rating->comment }}</td>
                                <td>{{ $rating->username }}</td>

                                <td>
                                    @if ($rating->status == 1)
                                    <a href="javascript:void(0);" onclick="changeStatus(0,{{$rating->id}})">
                                        <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    @else
                                    <a  href="javascript:void(0);" onclick="changeStatus(1,{{$rating->id}})">
                                        <svg class="text-danger h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                </td>
                                <td>
                                    {{-- Delete --}}
                                    <a href="javascript:void(0);" onclick="deleteRating({{$rating->id}})" class="text-danger w-4 h-4 mr-1 delete-product">
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
               <div class="float-right">
                    {{-- {{ $products->links('pagination::bootstrap-4')}} --}}
               </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection


@section('js')
    <script>
        function deleteRating(id){
            if(confirm('Are you sure want to delete status?')){
                $.ajax({
                    url: `{{ route('product.deleteRating')}}`,
                    type: 'DELET',
                    data: {id: id},
                    dataType: 'json',
                    success: function(response){
                        if(response.status === true){
                            location.reload();

                        }
                    }
                });
            }
        }

        function changeStatus(status, id){
            if(confirm('Are you sure want to change status?')){
                    $.ajax({
                        url: `{{ route('product.changeRatingStatus')}}`,
                        type: 'PUT',
                        data: {status: status, id: id},
                        dataType: 'json',
                        success: function(response){
                            if(response.status === true){
                                location.reload();


                            }
                            // else{
                            //     if(response.notFound === true){
                            //         location.reload();
                            //         return false;
                            //     }


                            // }
                        }

                    });
                }
        }
    </script>

@endsection
