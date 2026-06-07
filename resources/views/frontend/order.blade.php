@extends('layouts.frontend.app')

@section('css')
<style>
    .order-premium-wrap {
        background:
            radial-gradient(circle at top left, rgba(146, 0, 215, .08), transparent 34%),
            linear-gradient(180deg, #fff 0%, #fafbfe 100%);
    }

    .order-premium-card {
        border: 1px solid rgba(31, 41, 55, .06) !important;
        border-radius: 22px;
        box-shadow: 0 22px 55px rgba(21, 19, 34, .08);
        overflow: hidden;
    }

    .order-premium-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 6px 2px 18px;
    }

    .order-premium-title {
        color: #1f1f2f;
        font-weight: 700;
        letter-spacing: 0;
    }

    .order-search {
        position: relative;
        flex: 1 1 280px;
        max-width: 420px;
    }

    .order-search i {
        color: #9aa0aa;
        left: 17px;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    .order-search input {
        border: 1px solid #e8e9ef;
        border-radius: 999px;
        box-shadow: 0 12px 28px rgba(21, 19, 34, .05);
        height: 46px;
        padding-left: 44px;
    }

    .order-category-tabs {
        border: 0;
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 2px 0 16px;
        scrollbar-width: thin;
    }

    .order-category-tabs .nav-item {
        flex: 0 0 auto;
    }

    .order-category-tabs .nav-link {
        background: #f5f6fb;
        border: 1px solid transparent;
        border-radius: 999px;
        color: #5c6270;
        font-weight: 600;
        min-height: 42px;
        padding: 9px 18px;
        white-space: nowrap;
    }

    .order-category-tabs .nav-link.active {
        background: #241b2f;
        border-color: #241b2f;
        color: #fff;
        box-shadow: 0 12px 26px rgba(36, 27, 47, .22);
    }

    .order-category-select {
        display: none;
        margin-bottom: 14px;
    }

    .order-category-select label {
        color: #5c6270;
        font-size: .85rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .order-category-select .form-select {
        border: 1px solid #e8e9ef;
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(21, 19, 34, .05);
        font-weight: 600;
        min-height: 46px;
    }

    .product-gallery {
        margin-left: -8px;
        margin-right: -8px;
    }

    .product-gallery > [class*="col-"] {
        padding-left: 8px;
        padding-right: 8px;
    }

    .premium-product-card {
        align-items: center;
        background: #dbcfb1;
        border: 1px solid rgba(31, 41, 55, .08);
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(17, 24, 39, .06);
        cursor: pointer;
        display: flex;
        justify-content: center;
        margin-bottom: 16px;
        min-height: 210px;
        overflow: hidden;
        padding: 14px;
        position: relative;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .premium-product-card:focus,
    .premium-product-card:hover {
        border-color: rgba(146, 0, 215, .28);
        box-shadow: 0 20px 42px rgba(17, 24, 39, .12);
        outline: 0;
        transform: translateY(-3px);
    }

    .premium-product-card img {
        height: 180px;
        max-width: 100%;
        object-fit: contain;
        transition: transform .2s ease;
        width: 100%;
    }

    .premium-product-card:hover img {
        transform: scale(1.03);
    }

    .order-empty {
        color: #7d8490;
        display: none;
        padding: 34px 12px;
        text-align: center;
        width: 100%;
    }

    .order-loading {
        color: #7d8490;
        padding: 34px 12px;
        text-align: center;
        width: 100%;
    }

    .product-detail-dialog {
        max-width: 1080px;
    }

    .product-detail-modal {
        border: 0;
        border-radius: 26px;
        box-shadow: 0 30px 80px rgba(17, 24, 39, .24);
        overflow: hidden;
    }

    .product-detail-modal .modal-header {
        border: 0;
        padding: 22px 24px 10px;
    }

    .product-detail-modal .modal-title {
        color: #1f1f2f;
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .product-detail-modal .btn-close {
        background-color: #f4f5f8;
        border-radius: 999px;
        opacity: 1;
        padding: 10px;
    }

    .product-detail-body {
        padding: 12px 24px 24px;
    }

    .product-hero-image {
        align-items: center;
        background: linear-gradient(180deg, #fafbfe 0%, #fff 100%);
        border: 1px solid rgba(31, 41, 55, .06);
        border-radius: 22px;
        display: flex;
        justify-content: center;
        min-height: 430px;
        overflow: hidden;
        padding: 18px;
    }

    .product-hero-image img {
        max-height: 620px;
        max-width: 100%;
        object-fit: contain;
        width: 100%;
    }

    .product-detail-info {
        background: #fff;
        border: 1px solid rgba(31, 41, 55, .08);
        border-radius: 22px;
        box-shadow: 0 14px 36px rgba(17, 24, 39, .07);
        height: 100%;
        padding: 22px;
    }

    .product-detail-info #descriptions {
        color: #687080 !important;
        font-size: .95rem !important;
        line-height: 1.7;
    }

    .product-meta-grid {
        display: grid;
        gap: 10px;
        margin: 18px 0;
    }

    .product-meta-item {
        align-items: center;
        background: #f7f8fb;
        border-radius: 14px;
        display: flex;
        justify-content: space-between;
        min-height: 46px;
        padding: 10px 14px;
    }

    .product-meta-label {
        color: #7d8490;
        font-size: .85rem;
        font-weight: 600;
    }

    .product-meta-value {
        color: #201827;
        font-weight: 700;
        text-align: right;
    }

    .product-quantity-card {
        background: #fbfbfd;
        border: 1px solid #eceef4;
        border-radius: 18px;
        padding: 16px;
    }

    .product-quantity-card .plusminus.horiz {
        height: 46px;
        width: 100%;
    }

    .product-quantity-card .plusminus button {
        border-radius: 999px;
        height: 46px;
        width: 46px;
    }

    .product-quantity-card .plusminus.horiz input[type="number"] {
        border-radius: 999px;
        height: 46px;
        left: 52px;
        width: calc(100% - 104px);
    }

    .product-detail-modal .modal-footer {
        border: 0;
        gap: 10px;
        padding: 0 24px 24px;
    }

    .product-detail-modal .modal-footer .btn {
        min-height: 44px;
        min-width: 132px;
        padding-left: 22px;
        padding-right: 22px;
    }

    @media (max-width: 575.98px) {
        .order-premium-head {
            display: block;
        }

        .order-search {
            margin-top: 14px;
            max-width: 100%;
        }

        .order-category-tabs {
            display: none;
        }

        .order-category-select {
            display: block;
        }

        .order-category-tabs .nav-link {
            min-height: 38px;
            padding: 8px 14px;
        }

        .product-gallery {
            margin-left: 0;
            margin-right: 0;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .product-gallery > [class*="col-"] {
            flex: 0 0 100%;
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .premium-product-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(17, 24, 39, .06);
            min-height: 0;
            padding: 8px;
        }

        .premium-product-card img {
            height: auto;
            max-height: none;
            width: 100%;
        }

        .product-detail-modal {
            border-radius: 22px 22px 0 0;
        }

        .product-detail-modal .modal-header {
            padding: 18px 18px 8px;
        }

        .product-detail-modal .modal-title {
            font-size: 1.1rem;
        }

        .product-detail-body {
            padding: 10px 18px 18px;
        }

        .product-hero-image {
            border-radius: 18px;
            min-height: 0;
            padding: 8px;
        }

        .product-hero-image img {
            max-height: none;
            width: 100%;
        }

        .product-detail-info {
            border: 0;
            box-shadow: none;
            padding: 16px 0 0;
        }

        .product-detail-modal .modal-footer {
            padding: 0 18px 18px;
        }

        .product-detail-modal .modal-footer .btn {
            flex: 1 1 auto;
            min-width: 0;
        }
    }
</style>
@endsection

@section('conten')
<title>{{ __('text.CompanyName') }}</title>

<div class="bg-whiteLight page-content order-premium-wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">{{ __('text.Home') }}</a></li>
                        <li class="breadcrumb-item active text-truncate" aria-current="page">{{ __('text.BuyProduct') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-box order-premium-card mb-2 mb-md-0">
                    <div class="card-body">
                        <div class="order-premium-head">
                            <div>
                                <h4 class="card-title order-premium-title mb-1">{{ __('text.Productlist') }}</h4>
                                <small class="text-muted">{{ __('text.OrderProductIntro') }}</small>
                            </div>
                            <div class="order-search">
                                <i class="fas fa-search"></i>
                                <input type="search" id="productSearch" class="form-control" placeholder="{{ __('text.SearchProductName') }}">
                            </div>
                        </div>

                        <div class="order-category-select">
                            <label for="mobileCategorySelect">{{ __('text.ProductCategory') }}</label>
                            <select class="form-select" id="mobileCategorySelect">
                                <option value="all">{{ __('text.All') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <ul class="nav nav-tabs order-category-tabs" id="myTab" role="tablist">
                            {{-- Tab "ทั้งหมด" --}}
                            {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-all" data-category-id="" data-bs-toggle="tab" data-bs-target="#pane-all" type="button" role="tab" aria-controls="pane-all" aria-selected="false">
                                    {{ __('text.All') }}
                                </button>
                            </li> --}}
                            {{-- Tab หมวดหมู่ --}}
                         @foreach ($categories as $category)
                            <li class="nav-item" role="presentation">
                                <button 
                                    class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="tab-{{ $category->id }}" 
                                    data-category-id="{{ $category->id }}"
                                    data-bs-toggle="tab" 
                                    data-bs-target="#pane-{{ $category->id }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="pane-{{ $category->id }}" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    
                                    {{ $category->category_name }}
                                </button>
                            </li>
                        @endforeach
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            {{-- Tab Pane "ทั้งหมด" --}}
                            {{-- <div class="tab-pane fade" id="pane-all" role="tabpanel" aria-labelledby="tab-all">
                                <div class="row p-2 product-gallery" data-category-id=""></div>
                            </div> --}}

                            {{-- Tab Pane หมวดหมู่แต่ละอัน --}}
                            @foreach ($categories as $category)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pane-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                                    <div class="row p-2 product-gallery" data-category-id="{{ $category->id }}"></div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal รายละเอียดสินค้า --}}
<div class="modal fade" id="ProductDetail" tabindex="-1" aria-labelledby="ProductDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered product-detail-dialog">
        <div class="modal-content product-detail-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="ProductDetailLabel">{{ __('text.ProductDetail') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body product-detail-body">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="product-hero-image" id="img"></div>
                    </div>
                    <input type="hidden" id="product_id">
                    <div class="col-lg-5">
                        <div class="product-detail-info">
                            <div id="descriptions" class="fs-12 text-muted mb-0"></div>
                            <div class="product-meta-grid">
                                <div class="product-meta-item">
                                    <span class="product-meta-label">{{ __('text.Price') }}</span>
                                    <span class="product-meta-value" id="member_price"></span>
                                </div>
                                <div class="product-meta-item">
                                    <span class="product-meta-label">PV</span>
                                    <span class="product-meta-value" id="pv"></span>
                                </div>
                            </div>
                            <div class="product-quantity-card">
                                <div class="product-meta-label mb-2">{{ __('text.Quantity') }}</div>
                                <div class="plusminus horiz">
                                    <button class="btnquantity"></button>
                                    <input type="number" name="productQty" id="qty" class="numQty" value="1" min="1">
                                    <button class="btnquantity sp-plus"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                {{-- <button type="button" class="btn btn-outline-dark rounded-pill text-center" data-bs-dismiss="modal"
                 style="padding-left: 52px;  padding-right: 52px;">{{ __('text.Cancel') }}</button> --}}
                <button type="button" class="btn btn-p1 rounded-pill text-center" onclick="addcart()"
                style="padding-left: 52px;  padding-right: 52px;">{{ __('text.Confirm') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#linkMenuTop .nav-item').eq(1).addClass('active');

    $('.page-content').css({
        'min-height': $(window).height() - $('.navbar').height()
    });

    $(".btnquantity").on("click", function() {
        var $button = $(this);
        var oldValue = $button.closest('.plusminus').find("input.numQty").val();
        var newVal = $button.hasClass("sp-plus") ? parseFloat(oldValue) + 1 : Math.max(1, parseFloat(oldValue) - 1);
        $button.closest('.plusminus').find("input.numQty").val(newVal);
    });

    var productCache = {};
    var productRequest = null;
    var searchTimer = null;
    var orderText = {
        noProductFound: @json(__('text.NoProductFound')),
        loadingProducts: @json(__('text.LoadingProducts')),
        loadingProductsFailed: @json(__('text.LoadingProductsFailed')),
        successTitle: @json(__('text.SuccessTitle')),
        addCartSuccess: @json(__('text.AddCartSuccess')),
    };

    function getActiveProductPane() {
        return $('.tab-content .tab-pane.show.active, .tab-content .tab-pane.active').first();
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function renderProducts($gallery, products) {
        if (!products.length) {
            $gallery.html('<div class="order-empty d-block">' + orderText.noProductFound + '</div>');
            return;
        }

        var html = products.map(function(product) {
            var name = escapeHtml(product.product_name);

            return '' +
                '<div class="col-6 col-sm-4 col-md-3 col-xl-2 product-item" data-product-name="' + name + '">' +
                    '<button type="button" class="premium-product-card w-100" onclick="view_detail(' + product.products_id + ');" aria-label="' + name + '" title="' + name + '">' +
                        '<img src="' + product.image_url + '" alt="' + name + '" loading="lazy">' +
                    '</button>' +
                '</div>';
        }).join('');

        $gallery.html(html);
    }

    function loadProducts(categoryId, keyword) {
        var $activePane = getActiveProductPane();
        var $gallery = $activePane.find('.product-gallery');
        var cacheKey = (categoryId || 'all') + '|' + (keyword || '');

        if (productCache[cacheKey]) {
            renderProducts($gallery, productCache[cacheKey]);
            return;
        }

        if (productRequest) {
            productRequest.abort();
        }

        $gallery.html('<div class="order-loading">' + orderText.loadingProducts + '</div>');

        productRequest = $.ajax({
            url: '{{ route('order_products') }}',
            type: 'GET',
            data: {
                category_id: categoryId,
                keyword: keyword
            },
        })
        .done(function(data) {
            var products = data.products || [];
            productCache[cacheKey] = products;
            renderProducts($gallery, products);
        })
        .fail(function(xhr) {
            if (xhr.statusText === 'abort') {
                return;
            }

            $gallery.html('<div class="order-empty d-block">' + orderText.loadingProductsFailed + '</div>');
        })
        .always(function() {
            productRequest = null;
        });
    }

    function loadActiveProducts() {
        var keyword = ($('#productSearch').val() || '').toString().trim();
        var $activeTab = $('#myTab .nav-link.active');
        var categoryId = keyword ? '' : ($activeTab.data('category-id') || '');

        loadProducts(categoryId, keyword);
    }

    function showAllProductsTab(callback) {
        var $allTab = $('#tab-all');

        if (!$allTab.length || $allTab.hasClass('active')) {
            callback();
            return;
        }

        $allTab.one('shown.bs.tab', callback);

        if (window.bootstrap && bootstrap.Tab) {
            bootstrap.Tab.getOrCreateInstance($allTab[0]).show();
            return;
        }

        if ($.fn.tab) {
            $allTab.tab('show');
            return;
        }

        $('#myTab .nav-link').removeClass('active').attr('aria-selected', 'false');
        $('.tab-content .tab-pane').removeClass('show active');
        $allTab.addClass('active').attr('aria-selected', 'true');
        $('#pane-all').addClass('show active');
        callback();
    }

    function showCategoryById(categoryId) {
        var selector = categoryId === 'all' || categoryId === ''
            ? '#tab-all'
            : '#tab-' + categoryId;
        var $tab = $(selector);

        if (!$tab.length) {
            return;
        }

        if ($tab.hasClass('active')) {
            loadActiveProducts();
            return;
        }

        if (window.bootstrap && bootstrap.Tab) {
            bootstrap.Tab.getOrCreateInstance($tab[0]).show();
            return;
        }

        if ($.fn.tab) {
            $tab.tab('show');
            return;
        }

        $('#myTab .nav-link').removeClass('active').attr('aria-selected', 'false');
        $('.tab-content .tab-pane').removeClass('show active');
        $tab.addClass('active').attr('aria-selected', 'true');
        $($tab.attr('data-bs-target')).addClass('show active');
        loadActiveProducts();
    }

    $('#productSearch').on('input keyup search change', function() {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(function() {
            var hasKeyword = ($('#productSearch').val() || '').toString().trim().length > 0;

            if (hasKeyword) {
                showAllProductsTab(loadActiveProducts);
                return;
            }

            loadActiveProducts();
        }, 350);
    });

    $('#myTab button[data-bs-toggle="tab"], #myTab button[data-toggle="tab"]').on('shown.bs.tab', loadActiveProducts);
    document.querySelectorAll('#myTab button[data-bs-toggle="tab"], #myTab button[data-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', loadActiveProducts);
    });

    $('#myTab button[data-bs-toggle="tab"], #myTab button[data-toggle="tab"]').on('shown.bs.tab', function() {
        $('#mobileCategorySelect').val($(this).data('category-id') || 'all');
    });

    $('#mobileCategorySelect').on('change', function() {
        $('#productSearch').val('');
        showCategoryById($(this).val());
    });

    loadActiveProducts();

    function view_detail(product_id) {
        $('#qty').val(1);
        var url_asset = '{{asset('')}}';

        $.ajax({
            url: '{{ route('get_product') }}',
            type: 'GET',
            data: { product_id: product_id },
        })
        .done(function(data) {
            $('#ProductDetailLabel').html(data['product']['product_name']);
            $('#descriptions').html(data['product']['descriptions']);
            $('#member_price').html(data['product']['member_price'] + ' ' + data['product']['icon']);
            $('#pv').html(data['product']['pv'] + ' PV');
            $('#product_id').val(product_id);
            $('#img').html('<img src="'+ url_asset + data['product']['img_url'] + data['product']['product_img'] +'" alt="'+ data['product']['product_name'] +'">');
            $('#ProductDetail').modal('show');
        })
        .fail(function() { console.log("error"); });
    }

    function addcart() {
        var id = $('#product_id').val();
        var quantity = $('#qty').val();

        $.ajax({
            url: '{{ route('add_cart') }}',
            type: 'get',
            data: { id: id, quantity: quantity },
        })
        .done(function(data) {
            $('#ProductDetail').modal('hide');
            $('#count_cart').html(data['qty']);
            Swal.fire({
                icon: 'success',
                title: orderText.successTitle,
                text: orderText.addCartSuccess,
                timer: 4000,
                type: 'success'
            });
        })
        .fail(function() { console.log("error"); });
    }
</script>
@endsection
