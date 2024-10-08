@extends('admin.layouts.app')
@section('title', 'Edit')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('shipping.create') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="" id="shippingForm" name="shippingForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Country</label>
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select a country</option>

                                        @if (!empty($countries))
                                            @foreach ($countries as $country)
                                                <option {{( $country->id == $shippingCharges->country_id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>

                                            @endforeach
                                            <option {{( $shippingCharges->country_id == 'rest_of_world') ? 'selected' : ''}} value="rest_of_world">Rest of the world</option>
                                        @endif
                                    </select>
                                    <p class="invalid-feedback"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="name">Amount</label>
                                    <input type="text" name="amount" id="amount" value="{{ $shippingCharges->amount}}" class="form-control" placeholder="Amount">
                                    <p class="invalid-feedback"></p>
                                </div>
                            </div>
                            <div class="col-md-4" name="" id="">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    {{-- <a href="{{ route('shipping.show') }}" class="btn btn-outline-dark ml-3">Cancel</a> --}}
                                </div>
                            </div>

                        </div>
                    </form>
                    {{-- <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                        @if (!empty($countries))
                                        @foreach ($shippingCharges as $shippingCharge)
                                        <tr>
                                            <td>{{$shippingCharge->id}}</td>
                                            <td>{{ ($shippingCharge->country_id == 'Rest of the world') ? 'Rest of the world' : $shippingCharge->country_id}}</td>
                                            <td>{{ $shippingCharge->amount}}</td>
                                            <td>
                                                <a href=""  class="text-success w-4 h-4 mr-1 ">
                                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                        </path>
                                                    </svg>
                                                </a>


                                                <a href=""
                                                    class="text-danger w-4 h-4 mr-1 ">
                                                    <svg wire:loading.remove.delay="" wire:target=""
                                                        class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path ath fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>

                                            </td>

                                        </tr>

                                        @endforeach

                                        @endif

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection
@section('js')
    <script>
        $("#shippingForm").submit(function(event) {
            event.preventDefault(); // Ngăn chặn hành vi mặc định của form
            let element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: `{{ route('shipping.update', $shippingCharges->id) }}`, // Đảm bảo route đúng
                type: 'PUT',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    // console.log(response); // Kiểm tra phản hồi từ server

                    if (response["status"] === true) {


                        $('#country').removeClass('is-invalid').siblings('p.invalid-feedback')
                            .html("");
                        $('#amount').removeClass('is-invalid').siblings('p.invalid-feedback')
                            .html("");

                        // Hiển thị thông báo thành công
                        alert(response.message);
                        window.location.href=`{{ route('shipping.create')}}`;
                        // Xóa giá trị trong form
                        element[0].reset();

                    } else {
                        var errors = response['errors'];
                        if (errors['country']) {
                            $('#country').addClass('is-invalid').siblings('p.invalid-feedback')
                                .html(errors['country']);
                        } else {
                            $('#country').removeClass('is-invalid').siblings('p.invalid-feedback')
                                .html("");
                        }
                        if (errors['amount']) {
                            $('#amount').addClass('is-invalid').siblings('p.invalid-feedback')
                                .html(errors['amount']);
                        } else {
                            $('#amount').removeClass('is-invalid').siblings('p.invalid-feedback')
                                .html("");
                        }


                    }
                },

                error: function(jqXHR, exception) {
                    console.log("something went wrong");
                    // Hiển thị thông báo lỗi chi tiết
                    alert('Failed to create category. Please try again.');
                }
            });

        });

    </script>

@endsection
