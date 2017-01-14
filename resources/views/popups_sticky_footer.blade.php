@if(read("id"))
    <div class="fixed-action-btn hidden-sm-up" style="bottom: 45px; right: 10px;">
        <button class="fab bg-danger" onclick="window.scrollTo(0,document.body.scrollHeight);">
            <h2 style="color:white !important;" id="checkout-total"></h2>
        </button>
    </div>
@endif
