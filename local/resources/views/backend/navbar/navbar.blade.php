<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4 mt-3">
        <img alt="Midone - HTML Admin Template" class="w-6" src="{{ asset('backend/dist/images/logo.svg') }}">
        <span class="hidden xl:block text-white text-lg ml-3"> Tinker </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>


        {{-- BEGIN จัดการสินค้า --}}
        <li>
            <a href="javascript:;.html" class="side-menu side-menu--active">
                <div class="side-menu__icon"> <i data-lucide="box" class="menu__sub-icon"></i> </div>
                <div class="side-menu__title">
                    จัดการสินค้า
                    <div class="side-menu__sub-icon transform rotate-180"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="side-menu__sub-open ml-4">
                <li>
                    <a href="" class="side-menu side-menu--active">
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
                    สต็อกสินค้า
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
        {{-- END สต็อกสินค้า --}}

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
                        <div class="side-menu__title"> การขายสมาชิก </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> การขายลูกค้าทั่วไป </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> แจ้งโอนเงิน </div>
                    </a>
                </li>
                <li>
                    <a href="" class="side-menu ">
                        <div class="side-menu__icon"> <i data-lucide="activity" class="menu__sub-icon "></i> </div>
                        <div class="side-menu__title"> การแจงยอด </div>
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


        {{-- BEGIN จัดการผู้ใช้งาน --}}
        <li>
            <a href="javascript:;" class="side-menu ">
                <div class="side-menu__icon"> <i data-lucide="user" class="menu__sub-icon "></i> </div>
                <div class="side-menu__title">
                    จัดการผู้ใช้งาน
                </div>
            </a>
        </li>
        {{-- BERGIN จัดการผู้ใช้งาน --}}



    </ul>
</nav>
