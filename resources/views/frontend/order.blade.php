@extends('layouts.frontend.app')

@section('conten')
<title>บริษัท มารวยด้วยกัน จำกัด</title>

<div class="bg-whiteLight page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">{{ __('text.Home') }}</a></li>
                        <li class="breadcrumb-item active text-truncate" aria-current="page">{{ __('text.BuyProduct') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-box borderR10 mb-2 mb-md-0">
                    <div class="card-body">
                        <h4 class="card-title mb-0">{{ __('text.Productlist') }}</h4>
                        <hr class="mt-0">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            {{-- Tab "ทั้งหมด" --}}
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-all" data-bs-toggle="tab" data-bs-target="#pane-all" type="button" role="tab" aria-controls="pane-all" aria-selected="true">
                                    ทั้งหมด
                                </button>
                            </li>
                            {{-- Tab หมวดหมู่ --}}
                            @foreach ($categories as $category)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-{{ $category->id }}" data-bs-toggle="tab" data-bs-target="#pane-{{ $category->id }}" type="button" role="tab" aria-controls="pane-{{ $category->id }}" aria-selected="false">
                                        {{ $category->category_name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            {{-- Tab Pane "ทั้งหมด" --}}
                            <div class="tab-pane fade show active" id="pane-all" role="tabpanel" aria-labelledby="tab-all">
                                <div class="row p-2">
                                    @foreach ($product_all['product'] as $value)
                                        <div class="col-md-2 col-lg-2">
                                            <div class="row mb-2 box-product">
                                                <div class="col-6 col-md-12 text-center">
                                                    <img src="{{ asset($value->img_url . $value->product_img) }}" class="mw-100 mb-2">
                                                </div>
                                                <div class="col-12 col-md-12 text-start text-md-center">
                                                    <h6 class="mb-0">{{ $value->product_name }}</h6>
                                                    <p class="mb-1">{!! $value->icon !!} {{ number_format($value->member_price, 2) }} <span style="color:#00c454">[{{ $value->pv }} PV]</span></p>
                                                    <div class="row justify-content-center">
                                                        <div class="col-4 col-md-6">
                                                            <button type="button" onclick="view_detail({{ $value->products_id }});" class="btn btn-sm w-100 btn-p1 rounded-pill mb-2 justify-content-center">
                                                                {{ __('text.Addtocard') }} <i class="fa fa-cart-plus f-20"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Tab Pane หมวดหมู่แต่ละอัน --}}
                            @foreach ($categories as $category)
                                <div class="tab-pane fade" id="pane-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                                    <div class="row p-2">
                                        <?php
                                            $products = \App\Http\Controllers\Frontend\OrderController::product_list($category->id)['product'];
                                        ?>
                                        @foreach ($products as $product)
                                            <div class="col-md-2 col-lg-2">
                                                <div class="row mb-2 box-product">
                                                    <div class="col-6 col-md-12 text-center">
                                                        <img src="{{ asset($product->img_url . $product->product_img) }}" class="mw-100 mb-2">
                                                    </div>
                                                    <div class="col-12 col-md-12 text-start text-md-center">
                                                        <h6 class="mb-0">{{ $product->product_name }}</h6>
                                                        <p class="mb-1">{!! $product->icon !!} {{ number_format($product->member_price, 2) }} <span style="color:#00c454">[{{ $product->pv }} PV]</span></p>
                                                        <div class="row justify-content-center">
                                                            <div class="col-4 col-md-6">
                                                                <button type="button" onclick="view_detail({{ $product->products_id }});" class="btn btn-sm w-100 btn-p1 rounded-pill mb-2 justify-content-center">
                                                                    {{ __('text.Addtocard') }} <i class="fa fa-cart-plus f-20"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal รายละเอียดสินค้า --}}
<div class="modal fade" id="ProductDetail" tabindex="-1" aria-labelledby="ProductDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content borderR25">
            <div class="modal-header">
                <h5 class="modal-title" id="ProductDetailLabel">รายละเอียดสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4" id="img"></div>
                    <input type="hidden" id="product_id">
                    <div class="col-8">
                        <div id="descriptions" class="fs-12 text-muted mb-0"></div>
                        <p id="member_price" class="mb-0"></p>
                        <p id="pv" class="mb-0"></p>
                        <br>
                        <div class="plusminus horiz">
                            <button class="btnquantity"></button>
                            <input type="number" name="productQty" id="qty" class="numQty" value="1" min="1">
                            <button class="btnquantity sp-plus"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-p1 rounded-pill" onclick="addcart()">ยืนยัน</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#linkMenuTop .nav-item').eq(1).addClass('active');

    $('.page-content').css({
        'min-height': $(window).height() - $('.navbar').height()
    });

    $(".btnquantity").on("click", function() {
        var $button = $(this);
        var oldValue = $button.closest('.plusminus').find("input.numQty").val();
        var newVal = $button.hasClass("sp-plus") ? parseFloat(oldValue) + 1 : Math.max(1, parseFloat(oldValue) - 1);
        $button.closest('.plusminus').find("input.numQty").val(newVal);
    });

    function view_detail(product_id) {
        $('#qty').val(1);
        var url_asset = '{{asset('')}}';

        $.ajax({
            url: '{{ route('get_product') }}',
            type: 'GET',
            data: { product_id: product_id },
        })
        .done(function(data) {
            $('#ProductDetailLabel').html(data['product']['product_name']);
            $('#descriptions').html(data['product']['descriptions']);
            $('#member_price').html(data['product']['member_price'] + ' ' + data['product']['icon']);
            $('#pv').html(data['product']['pv'] + ' PV');
            $('#product_id').val(product_id);
            $('#img').html('<img id="img" src="'+ url_asset + data['product']['img_url'] + data['product']['product_img'] +'" class="mw-100 mb-2">');
            $('#ProductDetail').modal('show');
        })
        .fail(function() { console.log("error"); });
    }

    function addcart() {
        var id = $('#product_id').val();
        var quantity = $('#qty').val();

        $.ajax({
            url: '{{ route('add_cart') }}',
            type: 'get',
            data: { id: id, quantity: quantity },
        })
        .done(function(data) {
            $('#ProductDetail').modal('hide');
            $('#count_cart').html(data['qty']);
            Swal.fire({
                icon: 'success',
                title: 'Success !',
                text: "เพิ่มสินค้าลงตะกร้าสำเร็จ",
                timer: 4000,
                type: 'success'
            });
        })
        .fail(function() { console.log("error"); });
    }
</script>
@endsection
