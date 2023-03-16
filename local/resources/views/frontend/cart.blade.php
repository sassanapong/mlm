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
                                                                     onclick="quantity_change({{$value['id']}},{{$value['quantity']}})">จำนวน {{ $value['quantity'] }} ชิ้น</button>
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
                                                            <p class="mb-2">{{$bill['shipping']}}  บาท</p>
                                                        </div>

                                                        {{-- <div class="col-md-6">
                                                            <p class="mb-2">ส่วนลดประจำตำแหน่ง( {{$bill['position']}} {{$bill['bonus']}} %)</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2">{{number_format($bill['discount'])}} บาท</p>
                                                        </div> --}}
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="mb-2">ราคาสุทธิ</p>
                                                        </div>
                                                        <div class="col-md-6 text-md-end">
                                                            <p class="mb-2 text-purple1"><span class="text-p1 h5">{{ number_format(Cart::session(1)->getTotal()+$bill['shipping']) }}</span> บาท</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">

                                                        <a href="{{route('confirm_cart')}}" type="button"
                                                            class="btn btn-p1 rounded-pill w-100 mb-2 justify-content-center">ยืนยันคำสั่งซื้อ</a>
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

    <!-- Modal -->
    <div class="modal fade" id="adjNumModal" tabindex="-1" aria-labelledby="adjNumModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="adjNumModalLabel">แก้ไขจำนวน</h5>

                </div>
                <form action="{{ route('quantity_change') }}" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <div class="plusminus horiz">
                            <button type="button" class="btnquantity"></button>
                            <input type="number" name="productQty" id="productQty" class="numQty" value="1" min="1">
                            <input type="hidden" name="product_id" id="product_id">
                            <button type="button" class="btnquantity sp-plus"></button>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-p1 rounded-pill">บันทึก</button>
                    </div>
                </form>

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

  function quantity_change(item_id,qyt){
    $('#product_id').val(item_id);
    $('#productQty').val(qyt);
     $('#adjNumModal').modal('show');

  }


    </script>


    @endsection
