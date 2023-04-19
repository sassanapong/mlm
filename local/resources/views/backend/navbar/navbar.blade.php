<nav class="side-nav">
    <a href="{{ route('admin_home') }}" class="intro-x flex items-center pl-5 pt-4 mt-3">
        <img alt="Midone - HTML Admin Template" class="w-24 mx-auto" src="{{ asset('frontend/images/logo.png') }}">

    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>


        {{-- BEGIN ระบบสมาชิก --}}
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="users" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    Customer Service
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{route('CustomerAll')}}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ระบบบริการสมาชิก </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('check_doc') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ตรวจเอกสาร </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('Issue') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="mail" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> แจ้งปัญหา
                            {{-- <small class="text-xs px-1  rounded-full bg-danger text-white ml-1">{{ App\Reportissue::where('status', 1)->count() }}</small> --}}
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('promotion_help') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="heart" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title">โปรโมชั่นเพื่อนช่วยเพื่อน <small
                                class="text-xs px-1  rounded-full bg-danger text-white ml-1"></small>
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- END ระบบสมาชิก --}}
        <li class="side-nav__devider my-6"></li>
        {{-- BEGIN จัดการสินค้า --}}
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="box" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    จัดการสินค้า
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> กลุ่มสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> จัดการสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product_category') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> หมวดหมู่สินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product_unit') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> หน่วยสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product_size') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ขนาดสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('materials') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> วัตถุดิบ </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> สินค้าโปรโมชั่น </div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- END จัดการสินค้า --}}

        {{-- BEGIN สต็อกสินค้า --}}
        <li>
            <a href="javascript:;" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="package" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    คลังสินค้า
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('receive') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รับเข้าสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('takeout') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ออกสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายงานสต็อกสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('branch') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> สาขา </div>
                    </a>
                </li>

            </ul>
        </li>
        {{-- END สต็อกสินค้า --}}
        <li class="side-nav__devider my-6"></li>
        {{-- BEGIN ระบบการขาย --}}
        <li>
            <a href="javascript:;" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="shopping-cart" class="menu__sub-icon "></i> </div>
                <div class="side-menu__title">
                    จัดการขาย
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('orders_list') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายการสั่งซื้อ </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders_success') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายการจัดส่ง </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> สรุปยอดขาย </div>
                    </a>
                </li>


                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ออกใบกำกับภาษี </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ระบบตัดรอบการจัดส่ง </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายการแจ้งโอนเงิน </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายงาน (แจงJP.) </div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- END ระบบการขาย --}}


        {{-- BEGIN กระเป๋าเงิน --}}
        <li>
            <a href="javascript:;" class="side-menu  ">
                <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i> </div>
                <div class="side-menu__title">
                    กระเป๋าเงิน
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('eWallet') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายการ ฝากเงิน </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('withdraw') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายการ ถอนเงิน </div>
                    </a>
                </li>
                <li>

                    <a href="{{ route('transfer') }}" class="side-menu  ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รายการโอนเงิน </div>
                    </a>
                </li>

            </ul>
        </li>

        {{-- END กระเป๋าเงิน --}}
        <li class="side-nav__devider my-6"></li>

        {{-- BEGIN ระบบสมาชิก --}}
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="user" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    ระบบสมาชิก
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('member') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> สมาชิก </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> กำหนดสิทธ์ (Role) </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> เบิกแต้มสะสม </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="sliders" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    ตั่งค่าระบบ
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('shipping_location') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> พื้นที่ห่างไกล </div>
                    </a>
                </li>
                {{-- <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> กำหนดสิทธ์ (Role) </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> เบิกแต้มสะสม </div>
                    </a>
                </li> --}}
            </ul>
        </li>
        {{-- END ระบบสมาชิก --}}

        <li class="side-nav__devider my-6"></li>

        {{-- BEGIN ตั้งค่าเว็บไซต์ --}}
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    รวมรายงาน
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('ReportOrders') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="shopping-cart" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานยอดขาย </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ReportWallet') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานอนุมัติ E-Wallet </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('ReporJangPV') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานการแจง PV </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('bonus_active_report') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> โบนัสบริหารทีมลูกค้าประจำ </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('log_uplavel') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานการปรับตำแหน่ง </div>
                    </a>
                </li>

                     <li>
                    <a href="{{ route('report_xvvip') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานการสร้างทีม XVVIP </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report_copyright') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานโบนัสลิขสิทธิ์ </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report_warning_copyright') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> รายงานโบนัสลิขสิทธิ์ 10 ชั้น </div>
                    </a>
                </li>


            </ul>
        </li>

        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    รายงานโบนัสตามรอบ
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">

                <li>
                    <a href="{{ route('easy_report') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> Easy Bonus Report </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('allsale_report') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> All Sale Bonus Report </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('cashback_report') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="side-menu__title"> Cashback Bonus Report </div>
                    </a>
                </li>

            </ul>
        </li>
        {{-- END ตั้งค่าเว็บไซต์ --}}

        {{-- BEGIN ประกาศข่าวสาร --}}
        <li>
            <a href="{{ route('news_manage') }}" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="send" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    ประกาศข่าวสาร
                </div>
            </a>
        </li>
        {{-- END ประกาศข่าวสาร --}}

        {{-- BEGIN ตั้งค่าเว็บไซต์ --}}
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="book-open" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    การเรียนรู้
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('mdk_learning') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> Learning </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mdk_ct') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> Ct </div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- END ตั้งค่าเว็บไซต์ --}}



        {{-- --}}



    </ul>
</nav>
