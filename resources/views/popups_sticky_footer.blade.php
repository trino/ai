@if(read("id"))
    <div class="fixed-action-btn hidden-sm-up" style="bottom: 45px; right: 10px;">
        <button class="fab bg-danger" onclick="window.scrollTo(0,document.body.scrollHeight);">
            <span style="color:white !important;" id="checkout-total"></span>
        </button>
    </div>
@endif
