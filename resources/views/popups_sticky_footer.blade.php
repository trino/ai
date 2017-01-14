@if(read("id"))
    <div class="fixed-action-btn hidden-sm-up" style="bottom: 45px; right: 10px;">
        <button class="fab bg-danger" onclick="window.scrollTo(0,document.body.scrollHeight);">
            <h6 id="checkout-total"></h6>
        </button>
    </div>
@endif
