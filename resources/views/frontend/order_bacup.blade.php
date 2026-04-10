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
                    <div class="col-md-8">
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
                                                <div class="col-md-6 col-lg-3">
                                                    <div class="row mb-2 box-product">
                                                        <div class="col-3 col-md-12 text-center">
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
                                                                    <button type="button" onclick="view_detail({{$value->products_id}});"
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
                    <div class="col-md-4 ">
                        <div class="card card-box borderR10 mb-2 mb-md-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <h4 class="card-title mb-0">ที่อยู่จัดส่ง</h4>
                                    </div>
                                    <div class="col-sm-7 text-md-end">
                                        <button type="button" class="btn btn-p2 rounded-pill mb-1" data-bs-toggle="modal"
                                            data-bs-target="#addAddressModal"><i class="fas fa-plus me-1"></i>
                                            เพิ่มที่อยู่ใหม่</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card cardAddress p-2 mb-2">
                                            <h6>ชัยพัทธ์ ศรีสดุดี</h6>
                                            <p>77777 ซ.กกกกก ถ.ปปปปปปปปปปปปป ต.ฟฟฟฟฟฟ อ.กกกกกกก 33333</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h5>วิธีการชำระเงิน</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                id="flexRadioDefault1">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                หักเงิน eWallet
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault"
                                                id="flexRadioDefault2">
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                สแกน QR code
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="cardL-cart">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="{{ asset('frontend/images/product_sample1.jpg') }}"
                                                class="mw-100 mb-2">
                                        </div>
                                        <div class="col-9">
                                            <p class="fs-12 text-muted mb-0">Size XL (20+1), Pure</p>
                                            <h6>XL2</h6>
                                            <div class="text-md-end">
                                                <button type="button" class="btn btn-outline-secondary px-2 py-1"
                                                    data-bs-toggle="modal" data-bs-target="#adjNumModal">x1</button>
                                                <p class="mb-0">100.00 บาท</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cardL-cart">
                                    <div class="row">
                                        <div class="col-3">
                                            <img src="{{ asset('frontend/images/product_sample1.jpg') }}"
                                                class="mw-100 mb-2">
                                        </div>
                                        <div class="col-9">
                                            <p class="fs-12 text-muted mb-0">Size XL (20+1), Pure</p>
                                            <h6>XL2</h6>
                                            <div class="text-md-end">
                                                <button type="button" class="btn btn-outline-secondary px-2 py-1"
                                                    data-bs-toggle="modal" data-bs-target="#adjNumModal">x1</button>
                                                <p class="mb-0">100.00 บาท</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">ราคา</p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <p class="mb-2">200.00 บาท</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">Pont รวม</p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <p class="mb-2">20</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">ค่าส่ง</p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <p class="mb-2">0.00 บาท</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">ราคาสุทธิ</p>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <p class="mb-2 text-purple1"><span class="text-p1 h5">100.00</span> บาท</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button"
                                        class="btn btn-p1 rounded-pill w-100 mb-2 justify-content-center">ยืนยันคำสั่งซื้อ</button>
                                    <button type="button"
                                        class="btn btn-outline-dark rounded-pill w-100 mb-2 justify-content-center">ยกเลิก</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content borderR25">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAddressModalLabel">เพิ่มที่อยู่ใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3">
                            <div class="col-md-6">
                                <label for="" class="form-label">ชื่อผู้รับ</label>
                                <input type="text" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="">
                            </div>
                            <div class="col-md-12">
                                <label for="" class="form-label">ที่อยู่</label>
                                <input type="text" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">รหัสไปรษณีย์</label>
                                <input type="" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">จังหวัด</label>
                                <select class="form-select" id="">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">อำเภอ</label>
                                <select class="form-select" id="">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">ตำบล</label>
                                <select class="form-select" id="">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">เบอร์โทรติดต่อ</label>
                                <input type="" class="form-control" id="">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-p1 rounded-pill">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="adjNumModal" tabindex="-1" aria-labelledby="adjNumModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content borderR25">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adjNumModalLabel">แก้ไขจำนวน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="plusminus horiz">
                            <button class="btnquantity"></button>
                            <input type="number" name="productQty" class="numQty" value="0" min="0">
                            <button class="btnquantity sp-plus"></button>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-p1 rounded-pill">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ProductDetail" tabindex="-1" aria-labelledby="ProductDetailLabel"
            aria-hidden="true">
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
                                <img src="http://localhost/mlm/frontend/images/product_sample1.jpg" class="mw-100 mb-2">
                            </div>
                            <div class="col-8">
                                <h6>Size XL (20+1), Pure</h6>
                                <div id="descriptions" class="fs-12 text-muted mb-0"></div>
                                <p id="member_price" class="mb-0">100.00 บาท</p>
                                <p id="pv" class="mb-0">100.00 บาท</p>
                                <br>
                                <div class="plusminus horiz">
                                    <button class="btnquantity"></button>
                                    <input type="number" name="productQty" class="numQty" value="0" min="0">
                                    <button class="btnquantity sp-plus"></button>
                                </div>

                            </div>
                        </div>


                        </div>

                    </div>
                    <div class="modal-footer justify-content-center">


                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-p1 rounded-pill">ยืนยัน</button>
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

                $.ajax({
                        url: '{{route('get_product')}}',
                        type: 'GET',
                        data: {
                            product_id: product_id
                        },
                    })
                    .done(function(data) {
                        //console.log(data);
                        // $('#modal_tree').html(data);
                        $('#ProductDetailLabel').html(data['product']['product_name']);
                        $('#descriptions').html(data['product']['descriptions']);
                        $('#member_price').html(data['product']['member_price']+' '+data['product']['icon']);
                        $('#pv').html(data['product']['pv']+' PV');
                        // $('#img').html(data['product']['img_url']+' '+data['product']['product_img']);

                        $('#ProductDetail').modal('show');
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });

            }
        </script>
    @endsection
