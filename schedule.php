<?php include('fragments/check_login.php')?>
<?php include('fragments/head.php')?>
<link rel="stylesheet" href="./style/schedule.css">
<?php include('fragments/menu.php') ?>

<div class="page-layout">
    <div class="page-title">
        <h1 class="title1">Schedule</h1>
    </div>
    <div class="page-searchbox">
        <input type="text" name="searchbox" placeholder="Search for title, author, etc...">
        <img src="./resources/icons/search_static.png"
            onmouseover="this.src='./resources/icons/search_anim.apng'"
            onmouseout="this.src='./resources/icons/search_static.png'">
    </div>
    <div class="page-content">
        <section class="calendar">
            <div class="calendar-header">
                <button class="calendar-nav-btn calendar-prev">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <h2 class="calendar-month-year title2"></h2>
                <button class="calendar-nav-btn calendar-next">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
            <div class="calendar-days">
                <h3 class="calendar-day">Mon</h3>
                <h3 class="calendar-day">Tue</h3>
                <h3 class="calendar-day">Wed</h3>
                <h3 class="calendar-day">Thu</h3>
                <h3 class="calendar-day">Fri</h3>
                <h3 class="calendar-day">Sat</h3>
                <h3 class="calendar-day">Sun</h3>
            </div>
            <div class="calendar-dates"></div>
            <script src="/biblioteca/javascript/calendar.js"></script>
        </section>
    </div>
</div>

<?php include('fragments/foot.php') ?>