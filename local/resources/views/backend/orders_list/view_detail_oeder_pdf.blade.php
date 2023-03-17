<style>
    @page {
        header: page-header;
        footer: page-footer;
        margin-top: 2.54cm;
        margin-bottom: 2.54cm;
        /* margin-left: 2.54cm; */
        /* margin-right: 2.54cm; */

    }

    .row {
        width: 100%;

    }

    .text-content {
        font-size: 16px;
        line-height: 8px;
    }

    .text-content_product {
        font-size: 20px;
        line-height: 8px;
    }

    .col-1,
    .col-2,
    .col-3,
    .col-4,
    .col-5,
    .col-6,
    .col-7,
    .col-8,
    .col-9,
    .col-10,
    .col-11,
    .col-12 {
        float: left;
    }

    .col-12 {
        width: 100%;
    }

    .col-11 {
        width: 91.66666667%;
    }

    .col-10 {
        width: 83.33333333%;
    }

    .col-9 {
        width: 75%;
    }

    .col-8 {
        width: 66.66666667%;
    }

    .col-7 {
        width: 58.33333333%;
    }

    .col-6 {
        width: 50%;
    }

    .col-5 {
        width: 41.66666667%;
    }

    .col-4 {
        width: 33.33333333%;
    }

    .col-3 {
        width: 25%;
    }

    .col-2 {
        width: 16.66666667%;
    }

    .col-1 {
        width: 8.33333333%;
    }




    /*-- text utilities --*/
    .text-left {
        text-align: left !important;
    }

    .text-right {
        text-align: right !important;
    }

    .text-center {
        text-align: center !important;
    }


    /*-- spacing utilities --*/
    .m-0 {
        margin: 0 !important;
    }

    .mt-0 {
        margin-top: 0 !important;
    }

    .mr-0 {
        margin-right: 0 !important;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .ml-0 {
        margin-left: 0 !important;
    }

    .mx-0 {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }

    .my-0 {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }

    .m-1 {
        margin: 0.25rem !important;
    }

    .mt-1 {
        margin-top: 0.25rem !important;
    }

    .mr-1 {
        margin-right: 0.25rem !important;
    }

    .mb-1 {
        margin-bottom: 0.25rem !important;
    }

    .ml-1 {
        margin-left: 0.25rem !important;
    }

    .mx-1 {
        margin-right: 0.25rem !important;
        margin-left: 0.25rem !important;
    }

    .my-1 {
        margin-top: 0.25rem !important;
        margin-bottom: 0.25rem !important;
    }

    .m-2 {
        margin: 0.5rem !important;
    }

    .mt-2 {
        margin-top: 0.5rem !important;
    }

    .mr-2 {
        margin-right: 0.5rem !important;
    }

    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    .ml-2 {
        margin-left: 0.5rem !important;
    }

    .mx-2 {
        margin-right: 0.5rem !important;
        margin-left: 0.5rem !important;
    }

    .my-2 {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .m-3 {
        margin: 1rem !important;
    }

    .mt-3 {
        margin-top: 1rem !important;
    }

    .mr-3 {
        margin-right: 1rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .ml-3 {
        margin-left: 1rem !important;
    }

    .mx-3 {
        margin-right: 1rem !important;
        margin-left: 1rem !important;
    }

    .my-3 {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }

    .m-4 {
        margin: 1.5rem !important;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }

    .mr-4 {
        margin-right: 1.5rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .ml-4 {
        margin-left: 1.5rem !important;
    }

    .mx-4 {
        margin-right: 1.5rem !important;
        margin-left: 1.5rem !important;
    }

    .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    .m-5 {
        margin: 3rem !important;
    }

    .mt-5 {
        margin-top: 3rem !important;
    }

    .mr-5 {
        margin-right: 3rem !important;
    }

    .mb-5 {
        margin-bottom: 3rem !important;
    }

    .ml-5 {
        margin-left: 3rem !important;
    }

    .mx-5 {
        margin-right: 3rem !important;
        margin-left: 3rem !important;
    }

    .my-5 {
        margin-top: 3rem !important;
        margin-bottom: 3rem !important;
    }

    .m-auto {
        margin: auto !important;
    }

    .mt-auto {
        margin-top: auto !important;
    }

    .mr-auto {
        margin-right: auto !important;
    }

    .mb-auto {
        margin-bottom: auto !important;
    }

    .ml-auto {
        margin-left: auto !important;
    }

    .mx-auto {
        margin-right: auto !important;
        margin-left: auto !important;
    }

    .my-auto {
        margin-top: auto !important;
        margin-bottom: auto !important;
    }

    .p-0 {
        padding: 0 !important;
    }

    .pt-0 {
        padding-top: 0 !important;
    }

    .pr-0 {
        padding-right: 0 !important;
    }

    .pb-0 {
        padding-bottom: 0 !important;
    }

    .pl-0 {
        padding-left: 0 !important;
    }

    .px-0 {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }

    .py-0 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .p-1 {
        padding: 0.25rem !important;
    }

    .pt-1 {
        padding-top: 0.25rem !important;
    }

    .pr-1 {
        padding-right: 0.25rem !important;
    }

    .pb-1 {
        padding-bottom: 0.25rem !important;
    }

    .pl-1 {
        padding-left: 0.25rem !important;
    }

    .px-1 {
        padding-right: 0.25rem !important;
        padding-left: 0.25rem !important;
    }

    .py-1 {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }

    .p-2 {
        padding: 0.5rem !important;
    }

    .pt-2 {
        padding-top: 0.5rem !important;
    }

    .pr-2 {
        padding-right: 0.5rem !important;
    }

    .pb-2 {
        padding-bottom: 0.5rem !important;
    }

    .pl-2 {
        padding-left: 0.5rem !important;
    }

    .px-2 {
        padding-right: 0.5rem !important;
        padding-left: 0.5rem !important;
    }

    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .p-3 {
        padding: 1rem !important;
    }

    .pt-3 {
        padding-top: 1rem !important;
    }

    .pr-3 {
        padding-right: 1rem !important;
    }

    .pb-3 {
        padding-bottom: 1rem !important;
    }

    .pl-3 {
        padding-left: 1rem !important;
    }

    .px-3 {
        padding-right: 1rem !important;
        padding-left: 1rem !important;
    }

    .py-3 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    .p-4 {
        padding: 1.5rem !important;
    }

    .pt-4 {
        padding-top: 1.5rem !important;
    }

    .pr-4 {
        padding-right: 1.5rem !important;
    }

    .pb-4 {
        padding-bottom: 1.5rem !important;
    }

    .pl-4 {
        padding-left: 1.5rem !important;
    }

    .px-4 {
        padding-right: 1.5rem !important;
        padding-left: 1.5rem !important;
    }

    .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    .p-5 {
        padding: 3rem !important;
    }

    .pt-5 {
        padding-top: 3rem !important;
    }

    .pr-5 {
        padding-right: 3rem !important;
    }

    .pb-5 {
        padding-bottom: 3rem !important;
    }

    .pl-5 {
        padding-left: 3rem !important;
    }

    .px-5 {
        padding-right: 3rem !important;
        padding-left: 3rem !important;
    }

    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }

    .box_detail {
        border: 0.5px solid #000;
        padding: 5px;
        border-radius: 10px;
    }

    .text-address {
        line-height: 20px;
    }

    .text-total {
        line-height: 4px;
    }
</style>





{{-- หัวกระดาษ --}}
{{-- <div class="row">
    <div class="col-3">
        <h3>บริษัท มารวยด้วยกัน จำกัด</h3>
    </div>
    <div class="col-9 text-right text-content">
        <p> Lorem ipsum dolor sit amet consectetur, adipisicing
            est rem inventore </p>
        <p> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur, modi!</p>
        <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. A modi consequuntur fugit!</p>
    </div>
</div> --}}
{{-- หัวกระดาษ --}}







@foreach ($orders_detail as $key => $item)
    <div class="row">
        <div class="col-12 text-center">
            <h3>รายละเอียดสินค้า</h3>
        </div>
    </div>
    <div class="row box_detail ">
        <div class="col-12">
            <div class="row">
                <div class="col-5 text-content ml-2">
                    <p style="font-size: 20px; font-weight: bold;">รหัสการสั่งซื้อ : {{ $item->code_order }}</p>

                    <p>ชื่อผู้รับสินค้า : {{ $item->customers_name }}</p>

                    @foreach ($item->address as $address)
                        <p class="text-address">
                            <span class="text_head">ที่อยู่จัดส่ง :
                                <span class="text_info"> {{ $item->customers_name }} </span>
                                <span class="text_info"> {{ $address->house_no }}</span>
                                <span class="text_info">หมู่ {{ $address->moo }}</span><br>
                                <span class="text_info">ซอย {{ $address->soi }}</span>
                                <span class="text_info">ถนน {{ $address->road }}</span>
                                <span class="text_info">ตำบล {{ $address->tambon }}</span>
                                <span class="text_info">อำเภอ {{ $address->district }}</span>
                                <span class="text_info"> {{ $address->province }} {{ $address->zipcode }}</span>
                            </span>
                        </p>
                    @endforeach
                    <p>รายการสินค้า</p>
                </div>

                <div class="col-4 text-content mt-4 ml-3">

                    @php
                        $date = date('d-m-Y H:i:s', strtotime($item->created_at));
                    @endphp

                    <p>วันที่สั่งซื้อ : {{ $date }} </p>
                    @foreach ($item->address as $address)
                        <p>เบอร์โทร : {{ $address->tel }}</p>
                    @endforeach
                    <p style="font-size: 20px; font-weight: bold;"> บริการขนส่ง {{ $item->tracking_type }} </p>
                </div>
                <div class="col-1 text-center">
                    <p style="border: 1px solid black;">
                        {{ $item->tracking_no_sort }}

                    </p>
                </div>
            </div>



        </div>
        <div class="row ">
            <div class="col-8 ">
                <h6 class="ml-3">ชื่อสินค้า</h6>
                @foreach ($item->product_detail as $product)
                    <p class="text-content_product ml-3">
                        <span> {{ $product->product_name }}</span>
                    </p>
                @endforeach
            </div>
            <div class="col-1 ml-5">
                <h6>จำนวน</h6>
                @foreach ($item->product_detail as $product)
                    <p class="text-content_product">
                        <span> {{ $product->amt }}</span>
                    </p>
                @endforeach
            </div>
            <div class="col-1 ml-5 ">
                <h6>หน่วย</h6>
                @foreach ($item->product_detail as $product)
                    <p class="text-content_product">
                        <span> {{ $product->product_unit }}</span>
                    </p>
                @endforeach
            </div>
        </div>
        <div class="row text-total text-content">
            <div class="col-10 text-right">
                <p>ราคารวม </p>
                <p>PV รวม </p>
                <p>ค่าจัดส่ง </p>

                {{-- <p>ประเภทการจัดส่ง</p> --}}
                <p>ส่วนลดประจำตำแหน่ง({{ $item->position }} {{ $item->bonus_percent }} %)
                </p>
                <p>ราคารวมสุทธิ</p>
            </div>
            <div class="col-2 mr-2  text-right  ">
                <p> {{ number_format($item->sum_price) }} บาท</p>
                <p>{{ number_format($item->pv_total) }} PV</p>
                <p>{{ number_format($item->shipping_price) }} บาท</p>
                {{-- <p>{{ $item->shipping_type }}</p> --}}

                <p>{{ number_format($item->discount) }} บาท</p>
                <p>{{ number_format($item->ewallet_price) }} บาท</p>
            </div>
        </div>

    </div>
@endforeach
