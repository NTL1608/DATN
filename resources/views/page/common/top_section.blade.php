<!-- Page top Section -->
<section class="page-top-section set-bg" data-setbg="{{ isset($link_img) ? asset($link_img) : '' }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 m-auto text-white">
                <h2>{{ isset($title) ? $title : '' }}</h2>
                <p>{{ isset($description) ? $description : '' }}</p>
            </div>
        </div>
    </div>
</section>
<!-- Page top Section end -->