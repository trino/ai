<nav id="checkout-btn" class="navbar-default bg-danger navbar-fixed-bottom navbar hidden-sm-up" style="z-index: 1;">
    <button class="btn bg-danger text-white" onclick="window.scrollTo(0,document.body.scrollHeight);">
        <strong id="checkout-total"></strong> View
    </button>
    <button class="btn btn-warning pull-right" onclick="showcheckout();">
        CHECKOUT
    </button>
    <button class="btn bg-black pull-right"
            ONCLICK="confirm2('Are you sure you want to clear your order?', 'Clear Order', function(){clearorder();});">
        CLEAR
    </button>
</nav>