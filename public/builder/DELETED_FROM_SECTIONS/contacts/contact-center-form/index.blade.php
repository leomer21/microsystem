<section id="contact-center-form" class="pt-125 pb-125 bg-2-color-light">
    <div class="container">
        <div class="title-group text-center">
            <h2>Drop us a line</h2>
            <h4 class="mb-50">You are very important to us, all information received will always remain confidential.</h4>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
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
                    <button type="submit" data-loading-text="&bull;&bull;&bull;" data-complete-text="Completed!" data-reset-text="Try again later..." class="btn btn-primary contact_submit"><i class="icon-paper-plane icon-position-left icon-size-m"></i><span class="spr-option-textedit-link">Send Message</span></button>
                </form>
            </div>
        </div>
    </div>
    <div class="bg"></div>
</section>