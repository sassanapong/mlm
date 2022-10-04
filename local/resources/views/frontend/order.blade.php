    <title>บริษัท มารวยด้วยกัน จำกัด</title>

    @extends('layouts.frontend.app')
    @section('conten')
        <div class="bg-whiteLight page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">หน้าแรก</a></li>
                                <li class="breadcrumb-item active text-truncate" aria-current="page"> ซื้อสินค้า</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-box borderR10 mb-2 mb-md-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title mb-0">รายการสินค้า</h4>
                                        <div class="row gx-2">
                                            <label for="colFormLabel"
                                                class="col-md-auto col-lg-auto col-form-label fw-500">กรุณาเลือก:</label>
                                            <div class="col-md-5 col-lg-3">
                                                <select class="form-select mb-3">
                                                    <option>Size XL (20+1)</option>
                                                    <option>Size L (10)</option>
                                                    <option>Size M (5)</option>
                                                </select>
                                            </div>
                                            <div class="col-8 col-md-3 col-lg-2">
                                                <select class="form-select mb-3">
                                                    <option>Pure</option>
                                                    <option>Mix</option>
                                                </select>
                                            </div>
                                            <div class="col-4 col-md-1 col-lg-3">
                                                <button type="button" class="btn btn-p2 rounded-pill">กรอง</button>
                                            </div>
                                        </div>
                                        <hr class="mt-0">
                                        <div class="row mb-4">
                                            <div class="col-sm-6">
                                                {{-- <div class="row gx-2">
                                                    <label for="colFormLabel"
                                                        class="col-auto col-form-label col-form-label-sm fw-500">แสดง:</label>
                                                    <div class="col-6 col-md-4 col-xl-2">
                                                        <select class="form-select form-select-sm mb-3">
                                                            <option>8</option>
                                                            <option>16</option>
                                                            <option>40</option>
                                                        </select>
                                                    </div>
                                                    <label for="colFormLabel"
                                                        class="col-auto col-form-label col-form-label-sm fw-500">รายการ</label>
                                                </div> --}}
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row justify-content-md-end gx-2">
                                                    <label for="colFormLabel"
                                                        class="col-auto col-form-label col-form-label-sm fw-500">ค้นหา:</label>
                                                    <div class="col-9 col-md-6 col-xl-3">
                                                        <input type="text" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach ($product_all['product'] as $value)
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="row mb-2 box-product">
                                                        <div class="col-6 col-md-12 text-center">
                                                            <img src="{{ asset($value->img_url . '' . $value->product_img) }}"
                                                                class="mw-100 mb-2">
                                                        </div>
                                                        <div class="col-12 col-md-12 text-start text-md-center">
                                                            <h6 class="mb-0">{{ $value->product_name }}</h6>
                                                            <p class="mb-1"> {!! $value->icon !!}
                                                                {{ number_format($value->member_price, 2) }} <span
                                                                    style="color:#00c454">[{{ $value->pv }} PV]</span>
                                                            </p>

                                                            <div class="row justify-content-center">
                                                                {{-- <div class="col-8 col-md-12">
                                                                    <div class="plusminus horiz">
                                                                        <button class="btnquantity"></button>
                                                                        <input type="number" id="productQty_{{$value->products_id}}" name="productQty"
                                                                            class="numQty" value="0" min="0">
                                                                        <button class="btnquantity sp-plus"></button>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="col-4 col-md-6">
                                                                    <button type="button"
                                                                        onclick="view_detail({{ $value->products_id }});"
                                                                        class="btn btn-sm w-100 btn-p1 rounded-pill  mb-2 justify-content-center">
                                                                        เพิ่มสินค้า <i
                                                                            class="fa fa-cart-plus f-20"></i></button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach


                                        </div>
                                        {{-- <div class="row mb-2">
                                            <div class="col-sm-6">
                                                <p class="small mb-2">แสดง 1 ถึง 8 จาก 48 รายการ</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <nav aria-label="...">
                                                    <ul class="pagination pagination-sm justify-content-md-end">
                                                        <li class="page-item disabled">
                                                            <a class="page-link"><i class="fas fa-angle-left"></i></a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">1</a>
                                                        </li>
                                                        <li class="page-item active" aria-current="page">
                                                            <a class="page-link" href="#">2</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">3</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#"><i
                                                                    class="fas fa-angle-right"></i></a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->

        <!-- Modal -->


        <div class="modal fade" id="ProductDetail" tabindex="-1" aria-labelledby="ProductDetailLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content borderR25">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProductDetailLabel">รายละเอียดสินค้า</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-4">
                                   <div  id="img"></div>
                                   <input type="hidden" id="product_id">
                                </div>
                                <div class="col-8">
                                    {{-- <h6 id="size">Size XL (20+1), Pure</h6> --}}
                                    <div id="descriptions" class="fs-12 text-muted mb-0"></div>
                                    <p id="member_price" class="mb-0"></p>
                                    <p id="pv" class="mb-0"></p>
                                    <br>
                                    <div class="plusminus horiz">
                                        <button class="btnquantity"></button>
                                        <input type="number" name="productQty" id="qty" class="numQty" value="1"
                                            min="1">
                                        <button class="btnquantity sp-plus"></button>
                                    </div>

                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="modal-footer justify-content-center">


                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-p1 rounded-pill" onclick="addcart()">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>

            $('#linkMenuTop .nav-item').eq(1).addClass('active');
        </script>

        <script>
            $('.page-content').css({
                'min-height': $(window).height() - $('.navbar').height()
            });
        </script>
        <script>
            $(".btnquantity").on("click", function() {
                var $button = $(this);
                var oldValue = $button.closest('.plusminus').find("input.numQty").val();
                if ($button.hasClass("sp-plus")) {
                    var newVal = parseFloat(oldValue) + 1;
                } else {
                    if (oldValue > 1) {
                        var newVal = parseFloat(oldValue) - 1;
                    } else {
                        newVal = 1;
                    }
                }
                $button.closest('.plusminus').find("input.numQty").val(newVal);
            });


            function view_detail(product_id) {
                $('#qty').val(1);
                var url_asset = '{{asset('')}}';

                $.ajax({
                        url: '{{ route('get_product') }}',
                        type: 'GET',
                        data: {
                            product_id: product_id
                        },
                    })
                    .done(function(data) {
                        console.log(data);
                        // $('#modal_tree').html(data);
                        $('#ProductDetailLabel').html(data['product']['product_name']);
                        $('#descriptions').html(data['product']['descriptions']);
                        $('#member_price').html(data['product']['member_price'] + ' ' + data['product']['icon']);
                        $('#pv').html(data['product']['pv'] + ' PV');
                        $('#product_id').val(product_id);
                        uel_link= url_asset+''+data['product']['img_url']+''+data['product']['product_img'];
                        var url = '<img id="img" src="'+uel_link+'" class="mw-100 mb-2">';
                        $('#img').html(url);

                        $('#ProductDetail').modal('show');
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });

            }

            function addcart() {

                var id = $('#product_id').val();
            //  alert(id);
                var quantity = $('#qty').val();

                $.ajax({
                        url: '{{ route('add_cart') }}',
                        type: 'get',
                        // dataType: 'json',
                        data: {
                            id: id,
                            quantity: quantity
                        },
                    })
                    .done(function(data) {
                        $('#ProductDetail').modal('hide');
                        $('#count_cart').html(data['qty']);
                            //  $('#count_cart').html(data);
                            //  $('#m_count_cart').html(data);
                            //  var count_cart = '<i class="fa fa-shopping-cart"></i> '+data;
                            //  $('#count_cart_1').html(count_cart);
                            //  notify();
                            //         //console.log(data);

                             swal.fire({
                                icon: 'success',
                                title:'Success !',
                                text:"เพิ่มสินค้าลงตะกร้าสำเร็จ",
                                timer:4000,
                                type:'success'
                            }).then((value) => {
                            }).catch(swal.noop);
                                  })
                            .fail(function() {
                                console.log("error");
                            });
                        }
        </script>
    @endsection
