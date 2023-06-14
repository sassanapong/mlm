<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="{{ route('admin_home') }}" class="flex mr-auto">
            <img alt="Midone - HTML Admin Template" class="w-12" src="{{ asset('frontend/images/logo.png') }}">
        </a>
        <a href="javascript:;" id="mobile-menu-toggler"> <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
    </div>
    <ul class="border-t border-white/[0.08] py-5 hidden">
        {{-- <li>
            <a href="javascript:;.html" class="menu menu--active">
                <div class="menu__icon"> <i data-lucide="home"></i> </div>
                <div class="menu__title"> Dashboard <i data-lucide="chevron-down" class="menu__sub-icon transform rotate-180"></i> </div>
            </a>
            <ul class="menu__sub-open">
                <li>
                    <a href="side-menu-light-dashboard-overview-1.html" class="menu">
                        <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="menu__title"> Overview 1 </div>
                    </a>
                </li>

            </ul>
        </li>

            <li>
            <a href="side-menu-light-inbox.html" class="menu">
                <div class="menu__icon"> <i data-lucide="inbox"></i> </div>
                <div class="menu__title"> Inbox </div>
            </a>
        </li>

        --}}

        <li>

            <a href="javascript:;" class="menu">
                <div class="menu__icon"> <i data-lucide="home"></i> </div>
                <div class="menu__title">  Customer Service  <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{route('CustomerAll')}}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> ระบบบริการสมาชิก </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('check_doc') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> ตรวจเอกสาร </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('Issue') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="mail" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> แจ้งปัญหา
                            {{-- <small class="text-xs px-1  rounded-full bg-danger text-white ml-1">{{ App\Reportissue::where('status', 1)->count() }}</small> --}}
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('promotion_help') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="heart" class="menu__sub-icon "></i> </div>
                        <div class="menu__title "> โปรโมชั่นเพื่อนช่วยเพื่อน
                            {{-- <small class="text-xs px-1  rounded-full bg-danger text-white ml-1"></small> --}}
                        </div>
                    </a>
                </li>
            </ul>
        </li>

        <li>

            <a href="javascript:;" class="menu">
                <div class="menu__icon"> <i data-lucide="home"></i> </div>
                <div class="menu__title"> จัดการสินค้า  <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> กลุ่มสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> จัดการสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product_category') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> หมวดหมู่สินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product_unit') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> หน่วยสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('product_size') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> ขนาดสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('materials') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> วัตถุดิบ </div>
                    </a>
                </li>
                {{-- <li>
                    <a href="" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> สินค้าโปรโมชั่น </div>
                    </a>
                </li> --}}
            </ul>
        </li>

        <li>

            <a href="javascript:;" class="menu">
                <div class="menu__icon"> <i data-lucide="package"></i> </div>
                <div class="menu__title"> คลังสินค้า  <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="">
                <li>
                    <a href="{{ route('receive') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> รับเข้าสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('takeout') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> ออกสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> รายงานสต็อกสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('branch') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> สาขา </div>
                    </a>
                </li>

            </ul>
        </li>

        <li>
            <a href="javascript:;" class="menu">
                <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i> </div>
                <div class="menu__title">กระเป๋าเงิน <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('eWallet') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> รายการ ฝากเงิน </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('withdraw') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> รายการ ถอนเงิน </div>
                    </a>
                </li>
                <li>

                    <a href="{{ route('transfer') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> รายการโอนเงิน </div>
                    </a>
                </li>

            </ul>
        </li>

        {{-- END กระเป๋าเงิน --}}


        {{-- BEGIN ระบบสมาชิก --}}
        <li>
            <a href="javascript:;" class="menu">
                <div class="side-menu__icon"> <i data-lucide="user" class="menu__sub-icon"></i> </div>
                <div class="menu__title">ระบบสมาชิก <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('member') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> สมาชิก </div>
                    </a>
                </li>
                <li>
                    <a href="" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> กำหนดสิทธ์ (Role) </div>
                    </a>
                </li>
                <li>
                    <a href="" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> เบิกแต้มสะสม </div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="menu">
                <div class="side-menu__icon"> <i data-lucide="sliders" class="menu__sub-icon"></i> </div>
                <div class="menu__title"> ตั่งค่าระบบ <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('shipping_location') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> พื้นที่ห่างไกล </div>
                    </a>
                </li>
                {{-- <li>
                    <a href="" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> กำหนดสิทธ์ (Role) </div>
                    </a>
                </li>
                <li>
                    <a href="" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> เบิกแต้มสะสม </div>
                    </a>
                </li> --}}
            </ul>
        </li>
        {{-- END ระบบสมาชิก --}}



        {{-- BEGIN ตั้งค่าเว็บไซต์ --}}
        <li>
            <a href="javascript:;" class="menu">
                <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon"></i> </div>
                <div class="menu__title"> รวมรายงาน <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('ReportOrders') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="shopping-cart" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานยอดขาย </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ReportWallet') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานอนุมัติ E-Wallet </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('ReporJangPV') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานการแจง PV </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('bonus_active_report') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> โบนัสบริหาร team สายชั้น </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('log_uplavel') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานการปรับตำแหน่ง </div>
                    </a>
                </li>

                     <li>
                    <a href="{{ route('report_xvvip') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานการสร้างทีม XVVIP </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report_copyright') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานโบนัสลิขสิทธิ์ </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report_warning_copyright') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานโบนัสลิขสิทธิ์ 10 ชั้น </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report_active') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานแจ้งลูกค้าประจำ 10 ชั้น </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('report_register') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> รายงานโบนัสสมัครสมาชิก </div>
                    </a>
                </li>


            </ul>
        </li>

        <li>
            <a href="javascript:;" class="menu">
                <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon"></i> </div>
                <div class="menu__title">
                    รายงานโบนัสตามรอบ <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>

            </a>
            <ul class="side-menu__sub-icon ml-4">

                <li>
                    <a href="{{ route('easy_report') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> Easy Bonus Report </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('easy_report_new') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> Easy Bonus Report (New) </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('allsale_report') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> All Sale Bonus Report </div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('cashback_report') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i>
                        </div>
                        <div class="menu__title"> Cashback Bonus Report </div>
                    </a>
                </li>

            </ul>
        </li>
        {{-- END ตั้งค่าเว็บไซต์ --}}

        {{-- BEGIN ประกาศข่าวสาร --}}
        <li>
            <a href="{{ route('news_manage') }}" class="menu">
                <div class="side-menu__icon"> <i data-lucide="send" class="menu__sub-icon"></i> </div>
                <div class="menu__title">
                    ประกาศข่าวสาร
                </div>
            </a>
        </li>


        {{-- END ประกาศข่าวสาร --}}

        {{-- BEGIN ตั้งค่าเว็บไซต์ --}}
        <li>
            <a href="javascript:;" class="menu">
                <div class="side-menu__icon"> <i data-lucide="book-open" class="menu__sub-icon"></i> </div>
                <div class="menu__title">
                    การเรียนรู้ <i data-lucide="chevron-down" class="menu__sub-icon"></i> </div>
                </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('mdk_learning') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> Learning </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mdk_ct') }}" class="menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="menu__title"> Ct </div>
                    </a>
                </li>
            </ul>
        </li>


    </ul>
</div>

