<?php if ($_settings->userdata('type') == 1 or $_settings->userdata('type') == 3 or $_settings->userdata('type') == 4): ?>

  <?php if ($_settings->chk_flashdata('success')): ?>
    <script>
      alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
  <?php endif; ?>

  <?php
  $currentsession = $_settings->userdata('id');
  $currentgame_id = '3';
  ?>

  <?php
  require_once('../config.php');
  //display url
  $url = $conn->query("SELECT name FROM `template` where game_id = '{$currentgame_id}'");
  $link = '';
  if ($url->num_rows > 0) {
    $row = $url->fetch_assoc();
    $link = $row['name'];
  } else {
    $link = '';
  }



  $event = $conn->query("SELECT id,name FROM `events` where active='Y' and game_id = '{$currentgame_id}'");
  $arena = '';
  $eventid = '';
  if ($event->num_rows > 0) {
    $row = $event->fetch_assoc();
    $arena = $row['name'];
    $eventid = $row['id'];
  } else {
    $arena = '';
    $eventid = '';
  }

  ?>


  <style style="text/css">
    .marquee {
      height: 30px;
      overflow: hidden;
      position: relative;
      background: #fefefe;
      color: #333;
      border: 1px solid #4a4a4a;
    }

    .marquee h5 {
      position: absolute;
      width: 100%;
      height: 100%;
      margin: 0;
      line-height: 30px;
      text-align: center;
      -moz-transform: translateX(100%);
      -webkit-transform: translateX(100%);
      transform: translateX(100%);
      -moz-animation: scroll-left 2s linear infinite;
      -webkit-animation: scroll-left 2s linear infinite;
      animation: scroll-left 8s linear infinite;
    }

    .btn-success {
      color: #000;
      background-color: #c0c0c0;
      border-color: #c0c0c0;
      box-shadow: none;
    }

    .btn-success:hover {
      color: #3e3e3e;
      background-color: #b5b4b4;
      border-color: #000000;
    }

    @-moz-keyframes scroll-left {
      0% {
        -moz-transform: translateX(100%);
      }

      100% {
        -moz-transform: translateX(-100%);
      }
    }

    @-webkit-keyframes scroll-left {
      0% {
        -webkit-transform: translateX(100%);
      }

      100% {
        -webkit-transform: translateX(-100%);
      }
    }

    @keyframes scroll-left {
      0% {
        -moz-transform: translateX(100%);
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
      }

      100% {
        -moz-transform: translateX(-100%);
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
      }
    }
  </style>


  <style>
    .btn-grad-red {
      background-color: red;
      margin: 10px;
      text-align: center;
      text-transform: uppercase;
      transition: 0.5s;
      background-size: 200% auto;
      color: white;
      border-radius: 5px;
      display: block;
      width: 90%;
      height: 95%
    }

    .btn-grad-red:hover {
      background-position: right center;
      /* change the direction of the change here */
      color: #fff;
      text-decoration: none;
    }

    .btn-grad-blue {
      background-color: blue;
      margin: 10px;
      text-align: center;
      text-transform: uppercase;
      transition: 0.5s;
      background-size: 200% auto;
      color: white;
      border-radius: 5px;
      display: block;
      width: 90%;
      height: 95%
    }

    .btn-grad-blue:hover {
      background-position: right center;
      /* change the direction of the change here */
      color: #fff;
      text-decoration: none;
    }

    .btn-grad-green {
      background-color: green;
      margin: 10px;
      text-align: center;
      text-transform: uppercase;
      transition: 0.5s;
      background-size: 200% auto;
      color: white;
      border-radius: 5px;
      display: block;
      width: 90%;
      height: 95%
    }

    .btn-grad-green:hover {
      background-position: right center;
      /* change the direction of the change here */
      color: #fff;
      text-decoration: none;
    }

    .btn-grad-silver {
      background-image: linear-gradient(to right, #403B4A 0%, #E7E9BB 51%, #403B4A 100%);
      margin: 10px;
      text-align: center;
      text-transform: uppercase;
      transition: 0.5s;
      background-size: 200% auto;
      color: white;
      box-shadow: 0 0 10px #eee;
      border-radius: 5px;
      display: block;
      width: 90%;
      height: 95%
    }

    .btn-grad-silver:hover {
      background-position: right center;
      /* change the direction of the change here */
      color: #fff;
      text-decoration: none;
    }

    .bg-red_dash {
      /* border-radius: 10px 10px 0px 0px; */
      /* background-image: linear-gradient(to right, #f34141 0%, #f34141 51%, #f34141 100%); */
      background-color: #c62828 !important;
      color: #fff !important;
      padding-top: .3rem;
      padding-bottom: .3rem;

      font-family: Doppio One, sans-serif !important;
      font-size: 1.5rem !important;
      font-weight: 700 !important;
      font-size: 1.5rem !important;
      line-height: 1.333;
      letter-spacing: normal !important;
      text-transform: none !important;
      text-shadow: #000 0px 2px;
    }

    .bg-blue_dash {
      /* border-radius: 10px 10px 0px 0px;
      background-image: linear-gradient(to right, #1f82ea 0%, #1f82ea 51%, #207ff1 100%); */
      background-color: #1565c0 !important;
      color: #fff !important;
      padding-top: .3rem;
      padding-bottom: .3rem;

      font-family: Doppio One, sans-serif !important;
      font-size: 1.5rem !important;
      font-weight: 700 !important;
      font-size: 1.5rem !important;
      line-height: 1.333;
      letter-spacing: normal !important;
      text-transform: none !important;
      text-shadow: #000 0px 2px;
    }

    .payout-text-gray {
      color: #bdbdbd !important;
      font-size: .875rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .payout-text-white {
      color: rgba(255, 255, 255) !important;
      font-size: .875rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .payout-text-green {
      color: #28a745 !important;
      font-size: .875rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .meron-bets-v1 {
      color: #ffeb3b !important;
      font-size: 1.5rem !important;
      font-weight: 700 !important;
      line-height: 1.333;
      letter-spacing: normal !important;
      text-transform: none !important;
      font-family: Doppio One, sans-serif !important;
      margin-bottom: 0;
    }

    .wala-bets-v1 {
      color: #ffeb3b !important;
      font-size: 1.5rem !important;
      font-weight: 700 !important;
      line-height: 1.333;
      letter-spacing: normal !important;
      text-transform: none !important;
      font-family: Doppio One, sans-serif !important;
      margin-bottom: 0;
    }

    .bet-pill {
      color: rgba(255, 255, 255) !important;
      background: rgba(68, 68, 68) !important;
      font-size: .7rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .bet-pill-meron {
      color: rgba(255, 255, 255) !important;
      background-color: #c62828 !important;
      font-size: .7rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .bet-pill-wala {
      color: rgba(255, 255, 255) !important;
      background-color: #1565c0 !important;
      font-size: .7rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .pays-pill {
      color: rgba(118, 118, 118) !important;
      background: rgba(56, 56, 56) !important;
      font-size: .875rem !important;
      font-weight: 500;
      line-height: 1.6;
      letter-spacing: .0071428571em !important;
      font-family: Be Vietnam Pro, sans-serif !important;
      text-transform: none !important;
    }

    .bet-btn {
      color: #fff !important;
      font-size: .875rem;
      font-family: Be Vietnam Pro, sans-serif !important;
      font-weight: 500;
      text-indent: .0892857143em;
      text-transform: uppercase;
      letter-spacing: .0892857143em;
      line-height: normal;
      border-radius: 0 !important;

    }
    .chip{
      transition: transform .3s ease,filter .3s ease;
    }
    .chip:hover{
      transform: scale(1.1);
      filter: drop-shadow(0px 0px 10px rgba(255,255,255,.6));
      cursor: pointer;
    }

    .col-3 {
      -ms-flex: 0 0 25%;
      flex: 0 0 25%;
      max-width: 25%;
    }

    .col-6{
      -ms-flex: 0 0 50% !important;
      flex: 0 0 50% !important;
      max-width: 50% !important;
    }
    
    .col-9 {
      -ms-flex: 0 0 75%;
      flex: 0 0 75%;
      max-width: 75%;
    }


  </style>

  <?php if ($_settings->userdata('type') == 1 or $_settings->userdata('type') == 4) : ?>
    <div class="card-header">
      <div class="card-tools">

        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
          ACTION
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">

          <a class="dropdown-item new" href="javascript:void(0)"> <span class="fas fa-plus-circle text-primary"></span> NEW</a>
          <div class="dropdown-divider"></div>

          <a class="dropdown-item status" href="javascript:void(0)"> <span class="fa fa-check-circle text-primary"></span> STATUS</a>
          <div class="dropdown-divider"></div>

          <a class="dropdown-item finish" href="javascript:void(0)"> <span class="fa fa-exclamation-circle text-primary"></span> FINISH/CANCEL</a>

          <div class="dropdown-divider"></div>

          <a class="dropdown-item redeclare" href="javascript:void(0)"> <span class="fa fa-exclamation-circle text-primary"></span> REDECLARE</a>

        </div>

      </div>
    </div>
  <?php endif; ?>
  <style>
    .iframe-container {
      height: 500px;
    }

    #betting-dashboard .iframe-container {
      padding-top: 56.25%;
      height: 0;
      position: relative;
    }

    #betting-dashboard .iframe-container .stream-iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0px #ffffff none;
    }

    .bg-success {
      background-color: #C0C0C0 !important;
      color: black !important;
    }

    .fight_trend .bg-success {
      background-color: #28a745 !important;
    }

    .bg-danger {
      background-color: #c21426 !important;
    }

    .bg-bet_dash {
      background-image: linear-gradient(to right, #9e9511 0%, #ffee05 51%, #9e9511 100%);
    }

    .bg-arena_dash {
      background-image: linear-gradient(#000405, #000405, #000405);
    }

    .bg-announcement-dash {
      background-image: linear-gradient(#610303, #b50707);
    }

    .bg-status_dash {
      background-color: #1d2021 !important;
    }

    .bg-announcement-dash2 {
      background-image: linear-gradient(#f0c200, #e7a300);
    }

    .bg-dark_bet {
      background: rgb(2, 49, 62);
      background: linear-gradient(180deg, rgba(2, 49, 62, 1) 0%, rgba(2, 49, 62, 1) 92%, rgba(0, 171, 28, 1) 96%, rgba(0, 171, 28, 1) 100%);
    }

    .color-red_dash {
      color: #f34141;
    }

    .color-blue_dash {
      color: #1f82ea;
    }

    .color-green_dash {
      color: #25b04b;
    }
  </style>
  <div class="site-wrapper">
    <div class="container-fluid">
      <div class="row mb-4 mt-2" id="betting-dashboard">

        <div class="col-sm-7">
          <div class="card mb-3">
            <div class="card-header bg-arena_dash bg-success py-1" style="background:rgb(33,33,33) !important">
              <div class="row justify-content-between align-items-center">
                <h5 class="text-white mb-0" style="color: #ff0 !important; font-size: 1rem !important; font-weight: 700; line-height: 1.75; letter-spacing: .009375em !important; font-family: Doppio One, sans-serif !important;text-transform: none !important;">
                  <?php echo $arena ?></h6>
                <h5 class="text-white mb-0" style="font-size: .875rem !important; font-weight: 500; line-height: 1.6; letter-spacing: .0071428571em !important; font-family: Be Vietnam Pro, sans-serif !important;text-transform: none !important;">
                  <?php echo $_settings->userdata('username'); ?></b></h7>
              </div>
            </div>
            <div class="card-body p-0 w-100" style="width: 100%">
              <div style="width: 100%" class="w-100">
                <div class="iframe-container" id="samp">
                  <iframe id="streamIframe" class="stream-iframe" name="stream1" scrolling="no" frameborder="1" marginheight="0px" marginwidth="0px" height="100%" width="100%" src=<?php echo $link; ?>>
                  </iframe>
                </div>
              </div>
            </div>
          </div>
          <div class="container-fluid p-0 fight_trend _desk" id="" style="position:relative;"></div>
        </div>

        <div id="app" class="col-sm-5">

          <div class="betting-console">
            <div class="row ">
              <div id="announcement-holder" class="col">
                <div class="bg-status_dash text-center">
                  <h7 style="color: orange;"><b>Payout with 140 and below shall be cancelled</b></h7>
                </div>
              </div>
            </div>

            <!-- Previous fight -->
            <div class="row">
              <div class="col">
                <table class="table bg-status_dash table-borderless text-center table-striped mb-0">
                  <thead>
                    <tr>
                      <th class="text-center pb-0 statusLabel" style="width: 33%;">
                        <h5 id="prev-fight-number" style="font-size:15px; color: white;"></h5>
                        <h5 id="prev-fight-result" class="mb-0" style="font-size:22px;"></h5>
                      </th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col">
                <table class="table bg-status_dash table-borderless text-center table-striped mb-0">
                  <thead>
                    <tr>
                      <th class="text-center pt-0 statusLabel" style="width: 50%; color: white;">
                        <h5 id="lbl_game_status"> </h5>
                      </th>
                      <th class="text-center pt-0 statusLabel" style="width: 50%; font-size:15px; color: white;">FIGHT # <strong id="lbl_fight_number" fightNoDisplay" style="color: orange; font-size:25px;"> </strong></th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-6 py-1 px-0 text-center pt-3 dark-bg">
                <div class="col p-0 text-center pt-0 " style="background-color: rgb(33, 33, 33);">
                  
                  <h3 id="meron_winner_label" class="bg-red_dash">MERON</h3>
                  <h3 id="total_meron_bets" class="meron-bets-v1 mt-4">0.00</h3>
                  <h4 id="payout_meron" class="payout-text-v1 mt-3">
                  </h4>

                  <div class="mt-3" id="meron_bet_div">
                    <span class="badge rounded-pill bg-secondary bet-pill">
                      <span>BET: ₱</span>
                      <span id="ur_meron_bets">0.00</span>
                    </span>
                  </div>

                  <div class="mt-3">
                    <button type="button" id="post-meron" class="btn bet-btn w-100 post-bet py-2" style="background-color: #c62828;" href="javascript:void(0)" betid=1>
                      <i class="fas fa-plus"></i><strong> BET MERON</strong>
                    </button>
                  </div>  

                </div>
              </div>

              <div class="col-6 py-1 px-0 text-center pt-3 dark-bg">
                <div class="col p-0 text-center pt-0 " style="background-color: rgb(26, 26, 26);">

                  <h3 id="wala_winner_label" class="bg-blue_dash">WALA</h3>
                  <h3 id="total_wala_bets" class="wala-bets-v1 mt-4">0.00</h3>
                  <h4 id="payout_wala" class="payout-text-v1 mt-3">
                  </h4>

                  <div class="mt-3" id="wala_bet_div">
                    <span class="badge rounded-pill bg-secondary bet-pill">
                      <span>BET: ₱</span> <span id="ur_wala_bets">0.00</span>
                    </span>
                  </div>

                  <div class="mt-3">
                    <button type="button" id="post-wala" class="btn bet-btn w-100 post-bet py-2" style="background-color: #1565c0;" href="javascript:void(0)" betid=2>
                      <i class="fas fa-plus"></i><strong> BET WALA</strong>
                    </button>
                  </div>

                </div>
              </div>

            </div>

          <div class="mt-2" style="background-image: url('<?php echo validate_image('/uploads/boardbg.jpg')?>');">

              <!-- Betting Chips and Amount -->
              <div class="row mt-2 align-items-end">
                <div class="col-9">
                  <div class="input-container position-relative" style="padding-top: 10px;">

                    <label class="floating-label"
                      style="position: absolute; top: 0px; left: 20px; background-color: rgba(19, 20, 23); /* Must match your site's background */padding: 0 5px; color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; z-index: 3;font-family: Be Vietnam Pro, sans-serif !important;
                              ">
                      Bet Amount
                    </label>

                    <i class="fas fa-coins fa-lg input-icon"
                      style="position: absolute; top: 58%; /* Moves top edge to the middle */left: 12px;transform: translateY(-50%); /* Shifts the element up by half its height */color: rgba(255, 255, 255, 0.5);z-index: 2;
                          ">
                    </i>

                    <input
                      name="bet_amount"
                      id="bet_amount" 
                      type="text"
                      class="form-control custom-transparent-input"
                      style="height: 48px; /* Set height for visual consistency */background-color: transparent !important;border: 1px solid rgba(255, 255, 255, 0.2) !important;color: #ffffff !important; padding-left: 35px !important; box-shadow: none !important;font-family: Be Vietnam Pro, sans-serif !important;">
                  </div>
                </div>

                <div class="col-3">
                  <button type="button" class="btn btn-primary w-100 shadow"
                    style="height: 48px; /* Match input height */font-family: Be Vietnam Pro, sans-serif !important;
                        ">
                    <i class="mdi mdi-poker-chip me-2"></i>
                    ALL IN!
                  </button>
                </div>
              </div>
              <!-- Chips -->
              <div class="row mt-3 align-items-start justify-content-between">

                <div class="col-5">
                  <div class="mt-2 mb-3 font-weight-bold d-flex align-items-center text-success" style="color: #76ff03 !important; font-size: 2.2rem !important; font-weight: 700 !important;">
                    <i class="fas fa-wallet"></i>
                    <span id="ur_points" class="ml-2"> ₱0.00</span>
                  </div>
                </div>

                <div class="col-7 ms-auto text-end">
                  <div class="d-flex flex-wrap justify-content-end">
                    
                      <svg onclick='copyValueManual(50)' width="64" height="64" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="chip mx-1" role="button">
                        <circle cx="100" cy="100" r="90" stroke="#f8bbd0" stroke-width="4" fill="#ad1457"></circle>
                        <circle cx="100" cy="100" r="70" stroke="#f8bbd0" stroke-width="0" stroke-dasharray="10,5" fill="#ad1457"></circle>
                        <circle cx="100" cy="100" r="60" stroke="#f8bbd0" stroke-width="6" fill="#ec407a"></circle>
                        <rect x="90" y="10" width="20" height="28" fill="#f8bbd0"></rect>
                        <rect x="90" y="162" width="20" height="28" fill="#f8bbd0"></rect>
                        <rect x="10" y="90" width="28" height="20" fill="#f8bbd0"></rect>
                        <rect x="162" y="90" width="28" height="20" fill="#f8bbd0"></rect>
                        <rect x="35" y="35" width="20" height="20" transform="rotate(-45 45 45)" fill="#f8bbd0"></rect>
                        <rect x="145" y="35" width="20" height="20" transform="rotate(45 155 45)" fill="#f8bbd0"></rect>
                        <rect x="35" y="145" width="20" height="20" transform="rotate(45 45 155)" fill="#f8bbd0"></rect>
                        <rect x="145" y="145" width="20" height="20" transform="rotate(-45 155 155)" fill="#f8bbd0"></rect>
                        <text style="font-family: Be Vietnam Pro, sans-serif !important; font-size: 42; font-weight: bold; text-anchor: middle; dominant-baseline: middle; fill: rgb(255, 255, 255); transform: scale(1, 1);" x="100" y="105" font-size="42" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" transform="scale(1, 1)">50</text>
                      </svg>
                    <svg onclick='copyValueManual(100)' width="64" height="64" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="chip mx-1" role="button">
                      <circle cx="100" cy="100" r="90" stroke="#fff9cc" stroke-width="4" fill="#b8860b"></circle>
                      <circle cx="100" cy="100" r="70" stroke="#fff9cc" stroke-width="0" stroke-dasharray="10,5" fill="#b8860b"></circle>
                      <circle cx="100" cy="100" r="60" stroke="#fff9cc" stroke-width="6" fill="#ffd700"></circle>
                      <rect x="90" y="10" width="20" height="28" fill="#fff9cc"></rect>
                      <rect x="90" y="162" width="20" height="28" fill="#fff9cc"></rect>
                      <rect x="10" y="90" width="28" height="20" fill="#fff9cc"></rect>
                      <rect x="162" y="90" width="28" height="20" fill="#fff9cc"></rect>
                      <rect x="35" y="35" width="20" height="20" transform="rotate(-45 45 45)" fill="#fff9cc"></rect>
                      <rect x="145" y="35" width="20" height="20" transform="rotate(45 155 45)" fill="#fff9cc"></rect>
                      <rect x="35" y="145" width="20" height="20" transform="rotate(45 45 155)" fill="#fff9cc"></rect>
                      <rect x="145" y="145" width="20" height="20" transform="rotate(-45 155 155)" fill="#fff9cc"></rect>
                      <text style="font-family: Be Vietnam Pro, sans-serif !important; font-size: 42; font-weight: bold; text-anchor: middle; dominant-baseline: middle; fill: rgb(184, 134, 11); transform: scale(1, 1);" x="100" y="105" font-size="42" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#b8860b" transform="scale(1, 1)">100</text>
                    </svg>

                    <svg onclick='copyValueManual(500)' width="64" height="64" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="chip mx-1" role="button">
                      <circle cx="100" cy="100" r="90" stroke="#c5e1a5" stroke-width="4" fill="#558b2f"></circle>
                      <circle cx="100" cy="100" r="70" stroke="#c5e1a5" stroke-width="0" stroke-dasharray="10,5" fill="#558b2f"></circle>
                      <circle cx="100" cy="100" r="60" stroke="#c5e1a5" stroke-width="6" fill="#33691e"></circle>
                      <rect x="90" y="10" width="20" height="28" fill="#c5e1a5"></rect>
                      <rect x="90" y="162" width="20" height="28" fill="#c5e1a5"></rect>
                      <rect x="10" y="90" width="28" height="20" fill="#c5e1a5"></rect>
                      <rect x="162" y="90" width="28" height="20" fill="#c5e1a5"></rect>
                      <rect x="35" y="35" width="20" height="20" transform="rotate(-45 45 45)" fill="#c5e1a5"></rect>
                      <rect x="145" y="35" width="20" height="20" transform="rotate(45 155 45)" fill="#c5e1a5"></rect>
                      <rect x="35" y="145" width="20" height="20" transform="rotate(45 45 155)" fill="#c5e1a5"></rect>
                      <rect x="145" y="145" width="20" height="20" transform="rotate(-45 155 155)" fill="#c5e1a5"></rect>
                      <text style="font-family: Be Vietnam Pro, sans-serif !important; font-size: 42; font-weight: bold; text-anchor: middle; dominant-baseline: middle; fill: rgb(255, 255, 255); transform: scale(1, 1);" x="100" y="105" font-size="42" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" transform="scale(1, 1)">500</text>
                    </svg>

                    <svg onclick='copyValueManual(1000)' width="64" height="64" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="chip mx-1" role="button">
                      <circle cx="100" cy="100" r="90" stroke="#64b5f6" stroke-width="4" fill="#0d47a1"></circle>
                      <circle cx="100" cy="100" r="70" stroke="#64b5f6" stroke-width="0" stroke-dasharray="10,5" fill="#0d47a1"></circle>
                      <circle cx="100" cy="100" r="60" stroke="#64b5f6" stroke-width="6" fill="#1976d2"></circle>
                      <rect x="90" y="10" width="20" height="28" fill="#64b5f6"></rect>
                      <rect x="90" y="162" width="20" height="28" fill="#64b5f6"></rect>
                      <rect x="10" y="90" width="28" height="20" fill="#64b5f6"></rect>
                      <rect x="162" y="90" width="28" height="20" fill="#64b5f6"></rect>
                      <rect x="35" y="35" width="20" height="20" transform="rotate(-45 45 45)" fill="#64b5f6"></rect>
                      <rect x="145" y="35" width="20" height="20" transform="rotate(45 155 45)" fill="#64b5f6"></rect>
                      <rect x="35" y="145" width="20" height="20" transform="rotate(45 45 155)" fill="#64b5f6"></rect>
                      <rect x="145" y="145" width="20" height="20" transform="rotate(-45 155 155)" fill="#64b5f6"></rect>
                      <text style="font-family: Be Vietnam Pro, sans-serif !important; font-size: 42; font-weight: bold; text-anchor: middle; dominant-baseline: middle; fill: rgb(255, 255, 255); transform: scale(1, 1);" x="100" y="105" font-size="42" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" transform="scale(1, 1)">1K</text>
                    </svg>

                    <svg onclick='copyValueManual(5000)' width="64" height="64" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="chip mx-1" role="button">
                      <circle cx="100" cy="100" r="90" stroke="#ffcc80" stroke-width="4" fill="#6d4c41"></circle>
                      <circle cx="100" cy="100" r="70" stroke="#ffcc80" stroke-width="0" stroke-dasharray="10,5" fill="#6d4c41"></circle>
                      <circle cx="100" cy="100" r="60" stroke="#ffcc80" stroke-width="6" fill="#a1887f"></circle>
                      <rect x="90" y="10" width="20" height="28" fill="#ffcc80"></rect>
                      <rect x="90" y="162" width="20" height="28" fill="#ffcc80"></rect>
                      <rect x="10" y="90" width="28" height="20" fill="#ffcc80"></rect>
                      <rect x="162" y="90" width="28" height="20" fill="#ffcc80"></rect>
                      <rect x="35" y="35" width="20" height="20" transform="rotate(-45 45 45)" fill="#ffcc80"></rect>
                      <rect x="145" y="35" width="20" height="20" transform="rotate(45 155 45)" fill="#ffcc80"></rect>
                      <rect x="35" y="145" width="20" height="20" transform="rotate(45 45 155)" fill="#ffcc80"></rect>
                      <rect x="145" y="145" width="20" height="20" transform="rotate(-45 155 155)" fill="#ffcc80"></rect>
                      <text style="font-family: Be Vietnam Pro, sans-serif !important; font-size: 42; font-weight: bold; text-anchor: middle; dominant-baseline: middle; fill: rgb(255, 255, 255); transform: scale(1, 1);" x="100" y="105" font-size="42" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" transform="scale(1, 1)">5K</text>
                    </svg>

                    <svg onclick='copyValueManual(10000)' width="64" height="64" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="chip mx-1" role="button">
                      <circle cx="100" cy="100" r="90" stroke="#ffe082" stroke-width="4" fill="#fb8c00"></circle>
                      <circle cx="100" cy="100" r="70" stroke="#ffe082" stroke-width="0" stroke-dasharray="10,5" fill="#fb8c00"></circle>
                      <circle cx="100" cy="100" r="60" stroke="#ffe082" stroke-width="6" fill="#ef6c00"></circle>
                      <rect x="90" y="10" width="20" height="28" fill="#ffe082"></rect>
                      <rect x="90" y="162" width="20" height="28" fill="#ffe082"></rect>
                      <rect x="10" y="90" width="28" height="20" fill="#ffe082"></rect>
                      <rect x="162" y="90" width="28" height="20" fill="#ffe082"></rect>
                      <rect x="35" y="35" width="20" height="20" transform="rotate(-45 45 45)" fill="#ffe082"></rect>
                      <rect x="145" y="35" width="20" height="20" transform="rotate(45 155 45)" fill="#ffe082"></rect>
                      <rect x="35" y="145" width="20" height="20" transform="rotate(45 45 155)" fill="#ffe082"></rect>
                      <rect x="145" y="145" width="20" height="20" transform="rotate(-45 155 155)" fill="#ffe082"></rect>
                      <text style="font-family: Be Vietnam Pro, sans-serif !important; font-size: 42; font-weight: bold; text-anchor: middle; dominant-baseline: middle; fill: rgb(255, 255, 255); transform: scale(1, 1);" x="100" y="105" font-size="42" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#ffffff" transform="scale(1, 1)">10K</text>
                    </svg>
                  </div>
                </div>
              </div>

          </div>

          <div id="btn_game_fight_draw" style="display: none;">
            <div class="row">
              <div class="col p-1 text-center pt-0 dark-bg">
                <button type="button" id="post-draw" class="btn btn-grad-green btn-success btn-sm btn-block post-bet" href="javascript:void(0)" betid=3><i class="fas fa-plus-circle"></i> BET DRAW</button>
              </div>
              <div class="col p-0 text-center pt-3 dark-right-border dark-bg mt-3">
                <h6><strong id="ur_draw_bets" class="my-bets text-success">0<span></span></strong></h6>
              </div>
            </div>
            <div class="row">
              <div class="col p-1 pt-0 pl-2 pb-0 dark-right-border"><strong>DRAW WINS x 8. Max. bet per player: 20000/fight</strong>
              </div>


            </div>
          </div>

          <div class="container-fluid p-0 fight_trend _mobile" id="" style="position:relative;"></div>

          </div>
        </div>

      </div>

    </div>
  </div>


  <script>
    $(document).ready(function() {

      $('.post-bet').click(function() {
        uni_modal("<i class='fa fa-coins'></i> Confirmation", 'transactions/manage_transaction.php?betid=' + $(this).attr('betid') + '&bet=' + $('#bet_amount').val() + '&eventid=<?php echo $eventid ?>')
      })
      $('.finish').click(function() {
        uni_modal("<i class='fa fa-coins'></i> Select Winner/Cancel Fight", 'transactions/manage_winner.php?game_id=<?php echo $currentgame_id ?>')
      })
      $('.redeclare').click(function() {
        uni_modal("<i class='fa fa-coins'></i> Redeclare", 'transactions/manage_redeclare.php?eventid=<?php echo $eventid ?>')
      })
      $('.new').click(function() {
        uni_modal("<i class='fa fa-plus'></i> Add New Fight", 'transactions/new_transaction.php?game_id=<?php echo $currentgame_id ?>')
      })
      $('.status').click(function() {
        uni_modal("<i class='fa fa-coins'></i> Update Fight Status", 'transactions/manage_status.php?eventid=<?php echo $eventid ?>')
      })

    })
  </script>

  <script>
    function copyValueManual(value) {
      $("#bet_amount").val(value);
    }

    function clearValueManual(value) {
      $("#bet_amount").val("");
    }

    try {
      trends();
      balance();
      get_fight_status()
    } catch (e) {
      console.log(e);
    }

    //initial load of trends
    function trends() {

      $.ajax({
        url: _base_url_ + "classes/fight_trend.php?game_id=<?php echo $currentgame_id ?>",
        success: function(result) {
          $('.fight_trend').html(result);
        },
        error: function(result) {
          console.log(result);
        }
      });
    }

    function balance() {
      $.ajax({
        url: _base_url_ + "classes/balance.php",
        success: function(result) {
          $('#ur_points').html(result);
        },
        error: function(result) {
          console.log(result);
        }
      });
    }

    function get_fight_status() {

      $.ajax({
        url: _base_url_ + "classes/get_fight_status.php?eventid=<?php echo $eventid ?>",
        dataType: 'json',
        data: {
          red: '',
          red_payout: '',
          blue: '',
          blue_payout: ''
        },
        success: function(result) {

          if (result.red !== redchecker) {
            // $('#total_meron_bets').html(result.red);
                  console.log(result.red);

            animateValueUpdate('total_meron_bets', result.red);
          }

          if (result.red_payout !== redpayoutchecker) {
            var red_payout_replaced = result.red_payout.replace('PAYOUT: ', '');
            setMeronPayout(red_payout_replaced);
          }

          if (result.blue !== bluechecker) {
            // $('#total_wala_bets').html(result.blue);
            animateValueUpdate('total_wala_bets', result.blue);
          }

          if (result.blue_payout !== bluepayoutchecker) {
            var blue_payout_replaced = result.blue_payout.replace('PAYOUT: ', '');
            setWalaPayout(blue_payout_replaced);
          }
        },
        error: function(data) {
          console.log('error: fight status');
        }
      });

    }

    function setWalaBet(value) {
      const bet_wala = parseFloat(value);
      console.log(bet_wala);
      if(bet_wala > 0) {
        var html = 
        '<span class="badge rounded-pill bg-secondary bet-pill-wala">'+
          '<span>BET: ₱</span>' +
          '' +
          '<span id="ur_wala_bets">'+value+'</span>' +
        '</span>';
        $('#wala_bet_div').html(html);
      }else{
        var html = 
        '<span class="badge rounded-pill bg-secondary bet-pill">'+
          '<span>BET: ₱</span>' +
          '' +
          '<span id="ur_wala_bets">'+0.00+'</span>' +
        '</span>';
        $('#wala_bet_div').html(html);
      }
    }

    function setMeronBet(value) {
      const bet_meron = parseFloat(value);
      if(bet_meron > 0) {
        var html = 
        '<span class="badge rounded-pill bg-secondary bet-pill-meron">'+
          '<span>BET: ₱</span>' +
          '' +
          '<span id="ur_meron_bets">'+value+'</span>' +
        '</span>';
        $('#meron_bet_div').html(html);
      }else{
        var html = 
        '<span class="badge rounded-pill bg-secondary bet-pill">'+
          '<span>BET: ₱</span>' +
          '' +
          '<span id="ur_meron_bets">'+0.00+'</span>' +
        '</span>';
        $('#meron_bet_div').html(html);
      }
    }

    function setMeronPayout(value) {
      const payout_meron = parseFloat(value);
      if(payout_meron > 0) {
        var html = 
        '<span class="badge border border-success rounded-pill bg-transparent">'+
          '<span id="payout_meron_text" class="payout-text-green">' +
          '    PAYOUT =' +
          '</span>' +
          ' ' +
          '<span id="payout_meron_value" class="payout-text-green">' +
          payout_meron.toFixed(2) +
          '</span>' +
        '</span>';
        $('#payout_meron').html(html);
      }else{
        var html = 
        '<span class="badge border border-secondary rounded-pill bg-transparent">'+
          '<span id="payout_meron_text" class="payout-text-gray">' +
          '    PAYOUT =' +
          '</span>' +
          ' ' +
          '<span id="payout_meron_value" class="payout-text-white">' +
          '</span>' +
        '</span>';
        $('#payout_meron').html(html);
      }
    }

    function setWalaPayout(value) {
      const payout_wala = parseFloat(value);
      if(payout_wala > 0) {
        var html = 
        '<span class="badge border border-success rounded-pill bg-transparent">'+
          '<span id="payout_wala_text" class="payout-text-green">' +
          '    PAYOUT =' +
          '</span>' +
          ' ' +
          '<span id="payout_wala_value" class="payout-text-green">' +
          payout_wala.toFixed(2) +
          '</span>' +
        '</span>';
        $('#payout_wala').html(html);
      }else{
        var html = 
        '<span class="badge border border-secondary rounded-pill bg-transparent">'+
          '<span id="payout_wala_text" class="payout-text-gray">' +
          '    PAYOUT =' +
          '</span>' +
          ' ' +
          '<span id="payout_wala_value" class="payout-text-white">' +
          '</span>' +
        '</span>';
        $('#payout_wala').html(html);
      }
    }

    function animateValueUpdate(elementId, newValue) {
      const $element = $(`#${elementId}`);
      const currentValue = parseFloat($element.html().replace(/[,₱]/g, ''));
      const targetValue = parseFloat(newValue.replace(/,/g, ''));

      if (currentValue === targetValue) {
        return;
      }

      if (targetValue > currentValue) {
        $({
          num: currentValue
        }).animate({
          num: targetValue
        }, {
          duration: 3000,
          easing: 'swing',
          step: function(now) {
            $element.html('₱' + formatNumber(now));
          },
          complete: function() {
            $element.html('₱' + formatNumber(targetValue));
          }
        });
      } else {
        $element.html(targetValue)
      }
    }

    function formatNumber(number) {
      const rounded = Math.round(number * 100) / 100;
      return rounded.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }


    controller_fight_status();

    var trendchecker = statuschecker = callchecker = balancechecker = numberchecker = winnermeronchecker = winnerwalachecker = myredchecker = mybluechecker = myyellowchecker = bluepayoutchecker = redpayoutchecker = redchecker = bluechecker = myredcheckerfin = mybluecheckerfin = myyellowcheckerfin = '';

    function controller_fight_status() {

      $.ajax({
        url: _base_url_ + "classes/controller_fight_status.php?eventid=<?php echo $eventid ?>",
        success: function(result) {
          if (result == 1) {

            $.ajax({
              url: _base_url_ + "classes/get_fight_status.php?eventid=<?php echo $eventid ?>",
              dataType: 'json',
              data: {
                red: '',
                red_payout: '',
                blue: '',
                blue_payout: ''
              },
              success: function(result) {
                if (result.red !== redchecker) {
                  // $('#total_meron_bets').html(result.red);
                  animateValueUpdate('total_meron_bets', result.red);
                }
                redchecker = result.red;

                if (result.red_payout !== redpayoutchecker) {
                  var red_payout_replaced = result.red_payout.replace('PAYOUT: ', '');
                  setMeronPayout(red_payout_replaced);
                }
                redpayoutchecker = result.red_payout;


                if (result.blue !== bluechecker) {
                  // $('#total_wala_bets').html(result.blue);
                  animateValueUpdate('total_wala_bets', result.blue);
                }
                bluechecker = result.blue;


                if (result.blue_payout !== bluepayoutchecker) {
                  var blue_payout_replaced = result.blue_payout.replace('PAYOUT: ', '');
                  setWalaPayout(blue_payout_replaced);
                }
                bluepayoutchecker = result.blue_payout;


              },
              error: function(result) {
                console.log('error: fight status');
              }
            });

            $.ajax({
              url: _base_url_ + "classes/my_bet_status.php?eventid=<?php echo $eventid ?>",
              dataType: 'json',
              data: {
                red: '',
                blue: '',
                yellow: ''
              },
              success: function(result) {

                if (result.yellow !== myyellowchecker) {
                  $('#ur_draw_bets').html(result.yellow);
                }
                myyellowchecker = result.yellow;

                if (result.blue !== mybluechecker) {
                  setWalaBet(result.blue);
                }
                mybluechecker = result.blue;

                if (result.red !== myredchecker) {
                  setMeronBet(result.red);
                }
                myredchecker = result.red;


              },
              error: function(result) {
                console.log('error: fight status');
              }
            });

          }


          if (result == 2) {

            $.ajax({
              url: _base_url_ + "classes/get_fight_status.php?eventid=<?php echo $eventid ?>",
              dataType: 'json',
              data: {
                red: '',
                red_payout: '',
                blue: '',
                blue_payout: ''
              },
              success: function(result) {
                if (result.red !== redchecker) {
                  $('#total_meron_bets').html(result.red);
                }
                redchecker = result.red;

                if (result.red_payout !== redpayoutchecker) {
                  var red_payout_replaced = result.red_payout.replace('PAYOUT: ', '');
                  setMeronPayout(red_payout_replaced);
                }
                redpayoutchecker = result.red_payout;


                if (result.blue !== bluechecker) {
                  $('#total_wala_bets').html(result.blue);
                }
                bluechecker = result.blue;


                if (result.blue_payout !== bluepayoutchecker) {
                  var blue_payout_replaced = result.blue_payout.replace('PAYOUT: ', '');
                  setWalaPayout(blue_payout_replaced);
                }
                bluepayoutchecker = result.blue_payout;


              },
              error: function(result) {
                console.log('error: fight status');
              }
            });

            $.ajax({
              url: _base_url_ + "classes/my_bet_fin.php?eventid=<?php echo $eventid ?>",
              dataType: 'json',
              data: {
                red: '',
                blue: '',
                yellow: ''
              },
              success: function(result) {

                if (result.yellow !== myyellowchecker) {
                  $('#ur_draw_bets').html(result.yellow);
                }
                myyellowchecker = result.yellow;

                if (result.blue !== mybluechecker) {
                  setWalaBet(result.blue);
                }
                mybluechecker = result.blue;

                if (result.red !== myredchecker) {
                  setMeronBet(result.red);
                }
                myredchecker = result.red;

              },
              error: function(result) {
                console.log('error: fight finish');
              }
            });

          }



          if (result == 3 || result == 1) {
            $.ajax({
              url: _base_url_ + "classes/winner_wala.php?eventid=<?php echo $eventid ?>",
              success: function(result) {
                if (result !== winnerwalachecker) {
                  $('#wala_winner_label').html("<b>" + result + "</b>");
                }
                winnerwalachecker = result;
              },
              error: function(result) {
                console.log(result);
              }
            });

            $.ajax({
              url: _base_url_ + "classes/winner_meron.php?eventid=<?php echo $eventid ?>",
              success: function(result) {
                if (result !== winnermeronchecker) {
                  $('#meron_winner_label').html("<b>" + result + "</b>");
                }
                winnermeronchecker = result;
              },
              error: function(result) {
                console.log(result);
              }
            });

            // Previous fight
            $.ajax({
              url: _base_url_ + "classes/get_previous_fight.php?eventid=<?php echo $eventid ?>",
              dataType: 'json',
              success: function(result) {
                if (result.winner != '') {

                  $('#prev-fight-number').html("<b>FIGHT #: " + result.prev_fight_no + "</b>");
                  if (result.winner == "1") {
                    $('#prev-fight-result').removeClass().addClass('mb-0 color-red_dash').html("<b>MERON</b>");
                  } else if (result.winner == "2") {
                    $('#prev-fight-result').removeClass().addClass('mb-0 color-blue_dash').html("<b>WALA</b>");
                  } else if (result.winner == "3") {
                    $('#prev-fight-result').removeClass().addClass('mb-0 color-green_dash').html("<b>DRAW</b>");
                  } else if (result.winner == "4") {
                    $('#prev-fight-result').addClass('mb-0 text-white').html("<b>CANCELLED</b>");
                  }
                }
              },
              error: function(result) {
                console.log(result);
              }
            });

          }
          if (result == 3) {
            $.ajax({
              url: _base_url_ + "classes/fight_trend.php?game_id=<?php echo $currentgame_id ?>",
              success: function(result) {
                if (result !== trendchecker) {
                  $('.fight_trend').html(result);
                }
                trendchecker = result;
              },
              error: function(result) {
                console.log(result);
              }
            });
          }
          if (result == 1 || result == 2) {
            $.ajax({
              url: _base_url_ + "classes/fight_number.php?eventid=<?php echo $eventid ?>",
              success: function(result) {
                if (result !== numberchecker) {
                  $('#lbl_fight_number').html(result);
                }
                numberchecker = result;
              },
              error: function(result) {
                console.log(result);
              }
            });
          }
          if (result == 1 || result == 3) {
            $.ajax({
              url: _base_url_ + "classes/balance.php",
              success: function(result) {
                var a = parseInt($('#ur_meron_bets').text());
                var b = parseInt($('#ur_wala_bets').text());
                var c = parseInt($('#ur_draw_bets').text());
                var d = parseInt(result.replace(/,/g, '')); //remove commas
                //check if there is balance to view video
                if ((a + b + c + d) < 0) {
                  $('#samp').hide();
                  $('#post-draw').prop("disabled", true);
                  $('#post-meron').prop("disabled", true);
                  $('#post-wala').prop("disabled", true);

                } else {
                  $('#samp').show();
                  $('#post-draw').removeAttr('disabled');
                  $('#post-meron').removeAttr('disabled');
                  $('#post-wala').removeAttr('disabled');
                }

                if (result !== balancechecker) {
                  $('#ur_points').html(result);
                }
                balancechecker = result;
              },
              error: function(result) {
                console.log(result);
              }
            });
          }
          $.ajax({
            url: _base_url_ + "classes/fight_status.php?eventid=<?php echo $eventid ?>",
            success: function(result) {
              if (result !== statuschecker) {
                $('#lbl_game_status').html(result);
              }
              statuschecker = result;
            },
            error: function(result) {
              console.log(result);
            }
          });

          $.ajax({
            url: _base_url_ + "classes/game_call.php?eventid=<?php echo $eventid ?>",
            success: function(result) {
              if (result !== callchecker) {
                $('#game_call').html(result);
              }
              callchecker = result;
            },
            error: function(result) {
              console.log(result);
            }
          });


          $.ajax({
            url: _base_url_ + "classes/session_checker.php",
            success: function(result) {
              if (result == 0) {
                location.replace(_base_url_);
              }
            },
            error: function(result) {
              console.log(result);
            }
          });

          setTimeout(function() {
            controller_fight_status();
          }, 5000);

        },

        error: function(result) {
          setTimeout(function() {
            controller_fight_status();
          }, 5000);
        }
      });
    }
  </script>

  <script>
    /** 
     * Disable right-click of mouse, F12 key, and save key combinations on page 
     */
    document.addEventListener("contextmenu", function(e) {
      e.preventDefault();
    }, false);
    document.addEventListener("keydown", function(e) {
      //document.onkeydown = function(e) { 
      // "I" key 
      if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        disabledEvent(e);
      }
      // "J" key 
      if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        disabledEvent(e);
      }
      // "S" key + macOS 
      if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        disabledEvent(e);
      }
      // "U" key 
      if (e.ctrlKey && e.keyCode == 85) {
        disabledEvent(e);
      }
      // "F12" key 
      if (event.keyCode == 123) {
        disabledEvent(e);
      }
      // "C" key 
      if (e.ctrlKey && event.keyCode == 67) {
        disabledEvent(e);
      }
    }, false);

    function disabledEvent(e) {
      if (e.stopPropagation) {
        e.stopPropagation();
      } else if (window.event) {
        window.event.cancelBubble = true;
      }
      e.preventDefault();
      return false;
    }
  </script>
<?php endif; ?>