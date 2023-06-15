@extends('layouts.backend.app_new')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('css')
@endsection
@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการการขาย</a></li>
        <li class="breadcrumb-item active" aria-current="page"> รายการ คำสั่งซื้อ</li>
    </ol>
</nav>
@endsection
@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')

            <h2 class="text-lg font-medium mr-auto mt-2">รายการ คำสั่งซื้อ</h2>



            @foreach ($orders_detail as $item)
                <div class="px-2 sm:px-16  my-5">
                    <h1 class="text-2xl mb-3 box p-3">
                        รหัสการสั่งซื้อ : {{ $item->code_order }} <br>
                        <p class="text-xl mt-1">
                            ผู้สั่งซื้อ : {{ $item->customers_user_name }} {{ $item->customers_name }}
                            {{ $item->last_name }}
                            ({{ $item->position }})
                        </p>
                        <p class="text-xl mt-1">
                            วันที่สั่งซื้อ : {{ date('d/m/Y H:i น.', strtotime($item->created_at)) }}</p>

                    </h1>
                    <div class="grid grid-cols-3">
                        <div class="bg-white py-3 px-2">
                            <div class="ml-5">
                                <div class="text-xl text-primary">
                                    ที่อยู่การส่ง
                                </div>
                                @foreach ($item->address as $address_val)
                                    ผู้รับ {{ $item->name }}

                                    <div class="mt-1">{{ $address_val->house_no }} {{ $address_val->house_name }}
                                        <div class="mt-1">หมู่ที่ {{ $address_val->moo }} ซอย{{ $address_val->soi }}
                                            ถนน {{ $address_val->road }}
                                        </div>
                                        <div class="mt-1">
                                            ตำบล {{ $address_val->tambon }}
                                            อำเภอ {{ $address_val->district }}
                                        </div>
                                        <div class="mt-1">
                                            {{ $address_val->province }}
                                            {{ $address_val->zipcode }}
                                        </div>
                                        <div class="mt-1">
                                            {{ $address_val->tel }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1">

                        <div class="mt-2">
                            <h1 class="text-2xl mb-3">
                                รายการสินค้า
                            </h1>
                            <table class="table bg-white rounded">
                                <thead class="font-bold">
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center"></th>
                                        <th class="text-center">ชื่อสินค้า</th>
                                        <th class="text-right">ราคา</th>
                                        <th class="text-right">จำนวน</th>
                                        <th class="text-right">ราคารวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->product_detail as $key => $product)
                                        <tr>
                                            <td class="w-10 text-center">{{ $key + 1 }}</td>
                                            <td class="w-28 h-16 text-center">
                                                <img
                                                    src="{{ asset('') }}{{ $product->img_url }}{{ $product->product_img }}">
                                            </td>
                                            <td class="text-left">{{ $product->product_name }} ({{ $product->title }})
                                            </td>
                                            <td class="text-right">{{ number_format($product->selling_price, 2) }} บาท</td>
                                            <td class="text-right"> {{ number_format($product->amt) }}</td>
                                            <td class="text-right">{{ number_format($product->total_price, 2) }} บาท</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="">
                        <div class="bg-white flex justify-end">
                            <div class="text-right px-5 py-3 text-lg">
                                <div class="grid grid-cols-2 mt-1">
                                    <div class="">ราคารวม</div>



                                    <div class="ml-8">{{ number_format($item->sum_price, 2) }} บาท</div>
                                </div>


                                <div class="grid grid-cols-2 mt-1">
                                    <div class="">PV รวม</div>
                                    <div class="ml-8">{{ number_format($item->pv_total, 2) }} บาท</div>
                                </div>
                                <div class="grid grid-cols-2 mt-1">
                                    <div class="">ค่าจัดส่ง</div>
                                    <div class="ml-8">{{ number_format($item->shipping_price, 2) }} บาท</div>
                                </div>

                                <div class="grid grid-cols-2 mt-1">
                                    <div class="">ประเภทการจัดส่ง</div>
                                    <div class="ml-8">{{ $item->shipping_cost_name }}</div>
                                </div>
                                {{-- <div class="grid grid-cols-2 mt-1 font-bold underline decoration-double">
                                    <div class="">ส่วนลดประจำตำแหน่ง({{ $item->position }}
                                        {{ number_format($item->bonus_percent) }} %)</div>
                                    <div class="ml-8">
                                        {{ number_format($item->discount) }} บาท</div>
                                </div> --}}

                                <div class="grid grid-cols-2 mt-1 font-bold underline decoration-double">
                                    <div class="">ราคารวมสุทธิ</div>
                                    <div class="ml-8">
                                        {{ number_format($item->total_price) }} บาท</div>
                                </div>


                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
@endsection



@section('script')
@endsection
