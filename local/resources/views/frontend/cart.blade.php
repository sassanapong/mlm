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
                                <li class="breadcrumb-item"><a href="{{route('Order')}}">รายการสินค้า</a></li>
                                <li class="breadcrumb-item active text-truncate" aria-current="page"> ยืนยันรายการสินค้า </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-box borderR10 mb-2 mb-md-0">

                            <div class="card-body">

                                <div class="row">

                                        <div class="col-md-8 col-sm-12">
                                            <h4 class="card-title">ยืนยันรายการสินค้า</h4>


                                            <div class="card card-box borderR10 mb-2 mb-md-0">
                                                <div class="card-body">

                                                    @foreach($bill['data'] as $value)

                                                    <div class="cardL-cart">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <img src="{{asset($value['attributes']['img'])}}"
                                                                    class="mw-100 mb-2">
                                                            </div>
                                                            <div class="col-6">
                                                                <h6 class="mb-0">{{ $value['name'] }}</h6>
                                                                {!! $value['attributes']['descriptions'] !!}


                                                                    <p class="mb-0"> {{ number_format($value['price'],2) }} บาท</p>
                                                                    <p class="mb-0"> {{ number_format($value['attributes']['pv'],2) }} PV</p>

                                                            </div>
                                                            <div class="col-3">

                                                                <div class="text-md-end">
                                                                    <button type="button" class="btn btn-outline-secondary px-2 py-1"
                                                                        data-bs-toggle="modal" data-bs-target="#adjNumModal">จำนวน {{ $value['quantity'] }} กล่อง</button>
                                                                        <button type="button" class="btn btn-p2 rounded-pill mb-1" onclick="cart_delete('{{ $value['id'] }}')"> <i class="fa fa-trash" aria-hidden="true"></i> </button>
                                                                    <p class="mb-0">รวม {{ number_format($value['quantity']*$value['price'],2) }} บาท</p>
                                                                    <p class="mb-0">รวม {{ number_format($value['quantity']*$value['attributes']['pv'],2) }} PV</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">

                                            <div class="card card-box borderR10 mb-2 mb-md-0">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <h4 class="card-title mb-0">ที่อยู่จัดส่ง</h4>
                                                        </div>
                                                        <div class="col-sm-7 text-md-end">
                                                            <button type="button" class="btn btn-p2 rounded-pill mb-1" data-bs-toggle="modal"
                                                                data-bs-target="#addAddressModal"><i class="fas fa-plus me-1"></i>
                                                                แก้ไขที่อยู่ใหม่</button>
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
                    </div>

                </div>
            </div>
        </div>


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

    <form action="" method="POST" id="cart_delete">
        @csrf
        <input type="hidden" id="data_id" name="data_id">

    </form>



    @endsection

    @section('script')
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

    function cart_delete(item_id){
        var url = '{{ route('cart_delete') }}';
        Swal.fire({
          title: 'ลบสินค้าออกจากตะกร้า',
          // text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'ยืนยัน',
          cancelButtonText: 'ยกเลิก'
      }).then((result) => {
          if (result.isConfirmed){
            $("#cart_delete" ).attr('action',url);
            $('#data_id').val(item_id);
            $("#cart_delete" ).submit();
            // Swal.fire(
            //   'Deleted!',
            //   'Your file has been deleted.',
            //   'success'
            //   )

        }
    })
  }


    </script>


    @endsection
