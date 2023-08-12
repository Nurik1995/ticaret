@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Product Pictures</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('product-image-add/' . $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
            </form> <br>
            <div class="row container">
                @foreach ($images as $image)
                    <div id="product-images">
                        <button type="button" value="{{ $image->id }}" class="close">
                            <span>&times;</span>
                        </button>
                        <a value="{{ $image->id }}" href="{{ asset('assets/uploads/product/' . $image->image) }}"
                            target="_blank"><img src="{{ asset('assets/uploads/product/' . $image->image) }}"
                                alt="Product image"></a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('click', '#product-images .close', function() {
            let id = this.value;

            swal({
                    title: `Are you sure you want to delete this image?`,
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,
                    cancelButtonText: "No, cancel please!",
                })
                .then((result) => {
                    let dismiss = result.dismiss;

                    if (dismiss != 'cancel' && dismiss != 'overlay') {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "/product-image-delete",
                            method: "POST",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                if (response.result != undefined) {
                                    if (response.result == 'success') {
                                        swal("", response.text, "success");
                                        window.location.reload(true);
                                    } else {
                                        swal("", response.text, "warning");
                                    }
                                } else {
                                    swal("", 'Error occured...', "error");
                                }
                            }
                        });
                    }
                });
        });
    </script>
@endsection
