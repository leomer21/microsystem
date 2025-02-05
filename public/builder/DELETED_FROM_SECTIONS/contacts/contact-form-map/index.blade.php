<section id="contact-form-map" class="pt-150 pb-150 bg-3-color-light">
    <div class="container">
        <div class="row flex-md-vmiddle">
            <div class="col-md-5">
                <h2 class="mb-50">Make an appointment</h2>
                <form action="./scripts/request.php" class="contact_form">
                    <div class="form-group">
                        <input type="text" class="form-control contact_name" placeholder="Full name" name="name">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control contact_email" placeholder="Email Address" name="email">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control contact_message" rows="6" placeholder="Your message or question" name="message"></textarea>
                    </div>
                    <button type="submit" data-loading-text="&bull;&bull;&bull;" data-complete-text="Completed!" data-reset-text="Try again later..." class="btn btn-block btn-primary contact_submit"><span class="spr-option-textedit-link">Send</span></button>
                </form>
                <small class="desc-text">You are very important to us, all information received will always remain confidential.</small>
            </div>
            <div class="col-md-6 col-md-offset-1">
                <div id="map" class="embed-responsive embed-responsive-4by3 g-map"></div>
            </div>
        </div>
    </div>
    <div class="bg"></div>
</section>