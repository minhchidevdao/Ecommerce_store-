@extends('admin.layouts.app')
@section('title', 'Create Product')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="products.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <!-- Default box -->
        <form action="" method="POST" name="productForm" id="productForm">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="Slug">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" value="No" name="track_qty" id="hidden_track_qty">
                                                <input class="custom-control-input" type="checkbox" value="Yes" id="track_qty"
                                                     checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>

                                        @if ($category->isNotEmpty())
                                            @foreach ($category as $item)
                                                <option  value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach

                                        @endif

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select a Sub Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a brand</option>
                                        @if ($brand->isNotEmpty())
                                            @foreach ($brand as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
@endsection
@section('js')
    <script>
        $("#title").change(function() {
            let element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('getSlug') }}', // Đảm bảo route đúng
                type: 'GET',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] === true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });

        $("#productForm").submit(function(event){
            event.preventDefault();
            let element = $(this);

            $.ajax({
                url: `{{ route("product.store") }}`,
                type: 'POST',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);
                    if(response['status'] == true){

                    }else{
                        let errors = response['errors'];
                        if(errors['title']){
                            $("#title").addClass('is-invalid').sibling('p.invalid-feedback').html(errors['title']); 

                            /*
                                tìm phần tử có id= title và thêm vào class thuộc tính:'is-invalid'
                                tìm phần tử anh em(sibling) của phần tử title có thẻ p và class là: invalid-feedback
                                điều này giả định rằng phần tử thông báo lỗi nằm ngay sau phần tử title, sau đó cập nhật
                                nội dung phần tử thông báo lỗi với lỗi: errors['title']
                            */
                        }else {
                            $("#title").removeClass('is-invalid').sibling('p.invalid-feedback').html("");
                            // Nếu không có lỗi thì Xóa thuộc tính: is-invalid, làm trống nội dung phần tử báo lỗi và xóa bất kỳ thông báo lỗi nào trước đó
                        }
                    }

                },
                error: function(){
                    console.log("Some thing went wrong");
                }
            });
        });

        // phát hiện xem thẻ có id = hidden_track_qty có thay đổi value hay không
        $("#track_qty").ready(function(){
            $("#track_qty").change(function() {
                if($(this).is(':checked')){
                    $("#hidden_track_qty").val('Yes');
                }else{
                    $("#hidden_track_qty").val('No');
                }
            });
        });

        $("#category").change(function(){
            let category_id = $(this).val();
            $.ajax({
                url: '{{ route("product-subcategories") }}',
                type: 'GET',
                data: {category_id:category_id},//element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("#sub_category").find("option").not(":first").remove();    //Sử dụng jQuery để chọn phần tử HTML có ID là sub_category sau đó tìm các phần tử option bên trong, lọc ra các phần tử ngoại trừ phần tử đầu tiên và xóa chúng
                    $.each(response["subCategories"], function(key, item){    // Duyệt qua mảng subCategories trả về từ phản hồi AJAX,  chọn
                        $("#sub_category").append(`<option = "${item.id}"> ${item.name} </option>`) //  chọn sub_category, tạo một phần tử <option> mới và thêm vào bên trong phần tử sub_category
                    });

                },
                error: function(){
                    console.log("Some thing went wrong");
                }
            });
        })
    </script>

@endsection
