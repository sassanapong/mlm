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
                                                                     onclick="quantity_change({{$value['id']}},{{$value['quantity']}})">จำนวน {{ $value['quantity'] }} กล่อง</button>
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
                                                    <h4>สรุปรายการสั่งซื้อ</h4>
                                                    <hr>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-2">มูลค่าสินค้า ({{Cart::session(1)->getTotalQuantity()}}) ชิ้น</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{ number_format(Cart::session(1)->getTotal()/1.07,2) }} บาท</p>
                                                        </div>




                                                        <div class="col-md-6">
                                                            <p class="mb-2">Vat 7%</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{ number_format(Cart::session(1)->getTotal() - Cart::session(1)->getTotal()/1.07,2) }} บาท</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-2">มูลค่าสินค้า + Vat</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{ number_format(Cart::session(1)->getTotal()) }} บาท</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-2">PV รวม</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <?php
                                                            $cartCollection = Cart::session(1)->getContent();
                                                            $data = $cartCollection->toArray();
                                                            if ($data) {
                                                                foreach ($data as $value) {
                                                                    $pv[] = $value['quantity'] * $value['attributes']['pv'];
                                                                }
                                                                $pv_total = array_sum($pv);
                                                            } else {
                                                                $pv_total = 0;
                                                            }

                                                            ?>
                                                            <p class="mb-2">{{number_format($pv_total)}} PV</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-2">ค่าส่ง</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">0 บาท</p>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-2">ราคาสุทธิ</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2 text-purple1"><span class="text-p1 h5">{{ number_format(Cart::session(1)->getTotal()) }}</span> บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="button"
                                                            class="btn btn-p1 rounded-pill w-100 mb-2 justify-content-center">ยืนยันคำสั่งซื้อ</button>
                                                        <a href="{{route('cancel_order')}}" type="button"
                                                            class="btn btn-outline-dark rounded-pill w-100 mb-2 justify-content-center">ยกเลิก</a>
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


        <div class="modal fade " id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
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






    @endsection

    @section('script')
    <script>


    </script>


    @endsection
