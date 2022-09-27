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
                <div class="side-menu__title text-xs">
                    Customer Service <small
                        class="text-xs px-1   rounded-full bg-danger text-white ml-1">{{ App\Reportissue::where('status', 1)->count() + 1 }}</small>
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="user" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ระบบบริการสมาชิก </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('check_doc') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="file-text" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> ตรวจเอกสาร <small
                                class="text-xs px-1  rounded-full bg-danger text-white ml-1">1</small> </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('Issue') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="mail" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> แจ้งปัญหา <small
                                class="text-xs px-1  rounded-full bg-danger text-white ml-1">{{ App\Reportissue::where('status', 1)->count() }}</small>
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
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> จัดการสินค้า </div>
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
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> เบิกสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('receive') }}" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> รับเข้าสินค้า </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu">
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
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> จัดการสต็อกการ์ด </div>
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
                        <div class="side-menu__title"> รายการจัดส่ง </div>
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
            <a href="javascript:;" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="credit-card" class="menu__sub-icon "></i> </div>
                <div class="side-menu__title">
                    กระเป๋าเงิน
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> เติมเงิน </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> เงินเข้า - ออก </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> เงินคงเหลือสมาชิก </div>
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
        {{-- END ระบบสมาชิก --}}

        <li class="side-nav__devider my-6"></li>

        {{-- BEGIN ตั้งค่าเว็บไซต์ --}}
        <li>
            <a href="javascript:;.html" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="settings" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    ตั้งค่าเว็บไซต์
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-icon ml-4">
                <li>
                    <a href="{{ route('member') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> แบนเนอร์สไลด์ </div>
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
                    <a href="{{ route('member') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> Learning </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('member') }}" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> Ct </div>
                    </a>
                </li>
            </ul>
        </li>
        {{-- END ตั้งค่าเว็บไซต์ --}}



    </ul>
</nav>
