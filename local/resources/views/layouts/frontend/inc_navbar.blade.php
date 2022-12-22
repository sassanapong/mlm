<nav class="navbar navbar-expand-lg navbar-dark bg-Gpurple fixed-top">
    <div class="container-fluid">
        <div class="order-0">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('frontend/images/logo.png') }}" alt="">
            </a>
        </div>
        <div class="order-2 order-md-3 d-inline-flex align-items-center">


            <a href="{{route('cart')}}" class="btn btn-outline-light rounded-circle btn-icon position-relative" style="margin-right: 10px">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="count_cart">
                    {{Cart::session(1)->getTotalQuantity() }}
                  {{-- <span class="visually-hidden">unread messages</span> --}}
                </span>
              </a>
{{--
              <a type="button" class="btn btn-outline-light me-1 border-0" href="">
                <i class='bx bxs-cart me-1 bx-sm'></i> <span class="badge bg-white text-p1 font-weight-light">4</span>
              </a> --}}



            <input type="radio" class="btn-check changeLang" name="options" value="th" id="option1" autocomplete="off" {{ session()->get('locale') == 'th' ? 'checked' : '' }}>
            <label class="btn btnFlag" for="option1"><img src="{{ asset('frontend/images/thailand.png') }}"
                    class="flagS" alt="" /></label>

            <input type="radio" class="btn-check changeLang" name="options" value="en" id="option2" autocomplete="off"  {{ session()->get('locale') == 'en' ? 'checked' : '' }}>
            <label class="btn btnFlag" for="option2"><img src="{{ asset('frontend/images/united-kingdom.png') }}"
                    class="flagS" alt="" /></label>
            <button class="btn btn-wp" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight"><span class="d-inline-flex d-md-none"><i
                        class='bx bxs-user-circle'></i></span><span class="d-none d-md-inline-flex">
                    {{ Auth::guard('c_user')->user()->name }}
                    {{ Auth::guard('c_user')->user()->last_name }}</span></button>
            <!--
            <div class="dropdown">
                <button class="btn btn-wp dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    HI! <span class="d-inline-flex d-md-none"><i class='bx bxs-user-circle'></i></span><span class="d-none d-md-inline-flex">ตำแหน่ง - ชื่อ สกุล</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-start" id="dropUser" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="editprofile.php">แก้ไขข้อมูล</a></li>
                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePassModal">เปลี่ยนรหัสผ่าน</a></li>
                    <li><a class="dropdown-item" href="login.php"><i class="fas fa-power-off me-1 text-danger"></i>ออกจากระบบ</a></li>
                </ul>
            </div>
-->
        </div>
        <div class="order-3 order-md-2 collapse navbar-collapse" id="linkMenuTop">
            <ul class="navbar-nav me-auto my-2 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{__('text.Home')}}</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{__('text.BuyProduct')}}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('Order') }}">{{__('text.BuyProduct')}}</a></li>
                        <li><a class="dropdown-item" href="{{ route('order_history') }}">{{__('text.OrderHistory')}}</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('Learning') }}">{{__('text.MdkLerning')}}</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('Contact') }}">{{__('text.Contact')}}</a>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class='bx bxs-user-circle bx-lg text-p1'></i>
            </div>
            <div class="flex-grow-1 ms-3">
                {{__('text.Position')}}<br>
                <h6>{{ Auth::guard('c_user')->user()->name }}
                    {{ Auth::guard('c_user')->user()->last_name }}</h6>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('editprofile') }}">{{__('text.Editprofile')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="modal" data-bs-target="#changePassModal">{{__('text.Changepassword')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"><i
                        class="fas fa-power-off me-1 text-danger"></i>{{__('text.Logout')}}</a>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">

    var url = "{{ route('changeLang') }}";

    $(".changeLang").change(function(){
        window.location.href = url + "?lang="+ $(this).val();
    });

</script>

@incluse('frontend.modal.modal-changePassword')
