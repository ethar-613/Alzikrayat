<!-- about the project page content -->

<!-- small description about the project -->
<section class="inner-hero">
    <div class="container">
        <h1>About Alzikrayat</h1>
        <p class="lead mb-0">Alzikrayat means "memories". It's photo sharing site built for the Advanced Web Technologies course project.</p>
    </div>
</section>

<!-- what the project and what the users can do -->
<section class="container section-padding">
    <div class="row g-5 align-items-start">
        <div class="col-lg-7">
            <h2 class="section-title">What is this project?</h2>
            <p>Alzikrayat lets registered users upload photos with a title and description, browse a shared gallery, and leave comments on other people's photos.</p>
            <p>The whole project is built from scratch with PHP and MySQL using a hand-written MVC structure. It shows how routing, sessions, validation, file uploads, and database access all work under the hood.</p>
        </div>
        <div class="col-lg-5">
            <div class="about-card">
                <h3>Upload &amp; Share</h3>
                <p>Add a photo with a title and a short story, and it shows up in the community gallery right away.</p>
            </div>
            <div class="about-card">
                <h3>Comment</h3>
                <p>Any logged-in user can leave a comment on any photo.</p>
            </div>
        </div>
    </div>
</section>

<!-- about project building -->
<section class="container section-padding pt-0">
    <h2 class="section-title mb-3">How it's built</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <h4>Views</h4>
            <p>HTML pages styled with Bootstrap, plus a bit of JavaScript for form validation.</p>
        </div>
        <div class="col-md-4">
            <h4>Controllers</h4>
            <p>Handle each request: routing, login/logout, file uploads, and checking that a user owns the photo before deleting it.</p>
        </div>
        <div class="col-md-4">
            <h4>Models</h4>
            <p>Talk to the MySQL database directly with prepared SQL statements (users, photos, comments tables).</p>
        </div>
    </div>
</section>
