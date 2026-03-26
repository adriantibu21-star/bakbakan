   <style>
       .bg-success {
           background-color: #C0C0C0 !important;
           color: black !important;
       }

       .content-wrapper.bg-dark {
           background: #000 !important;
       }

       .cover-wrapper {
           box-sizing: border-box;
           overflow: auto;
           max-width: 100%;
       }

       .cover-img {
           width: 100%;
           background-size: cover;
           background-position: center;
           background-repeat: no-repeat;
           display: block;
           border-radius: 5px;
           padding-bottom: 59.2%;
           position: relative;
       }

       .room:hover {
           color: #fff;
       }

       .room {
           margin: auto;
           width: 340px;
           background-color: #222;
           border-radius: 5px;
           color: #fff;
           display: block;
           margin-top: 15px;
       }

       .room-name {
           margin: 10px;
           margin-bottom: 5px;
           font-size: 1.3rem;
           font-weight: bold;
       }

       .room-bet {
           margin: 10px;
           background-color: #222;
           overflow: auto;
           padding-bottom: 15px;
           color: rgba(255, 255, 255, 0.411);
       }

       .eventsHeader {
           display: -webkit-box;
           display: -ms-flexbox;
           display: flex;
           -webkit-box-pack: justify;
           -ms-flex-pack: justify;
           justify-content: space-between;
           -webkit-box-align: end;
           -ms-flex-align: end;
           align-items: flex-end;
           width: 100%;
           border-bottom: 1px solid rgba(255, 255, 255, .288);
           margin: 2rem 0;
           gap: 1rem;
           padding-bottom: .5rem;
           -ms-flex-wrap: wrap;
           flex-wrap: wrap;
       }

       .eventsHeader .title {
           font-size: 1.5rem;
           letter-spacing: 1px;
           font-weight: 700;
           color: white;
       }

       .eventsHeader .currentPoints {
           color: white;
           letter-spacing: 1px;
       }

       a.disabled {
           pointer-events: none;
           cursor: default;
       }

       .yellow-text-color {
           color: #FBE724 !important;
       }

       .orange-text-color {
           color: #FA9E15 !important;
       }

       .blue-green-btn-color {
           color: #FFFFFF;
           background-color: #00BC8C;
       }

       .currency-box {
           border: 2px solid #5b6982;
           border-radius: 10px;
           font-family: 'Orbitron', sans-serif;
           letter-spacing: 1px;
       }

       .currency-box {
           font-family: 'Orbitron', sans-serif;
           border: 2px solid #00aed1;
           border-radius: 12px;
           box-shadow: 0 0 10px rgba(0, 212, 255, 0.5),
               inset 0 0 5px rgba(0, 212, 255, 0.2);
           transition: 0.3s;
       }

       .text-glow {
           color: #ffffff;
           font-size: 2rem;
           text-shadow: 0 0 8px rgba(255, 255, 255, 0.8),
               0 0 15px rgba(0, 212, 255, 0.5);
       }

       .coin-icon {
           color: #ffd700;
           text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
       }

       .game-card {
           perspective: 1000px;
           transform-style: preserve-3d;
       }

       .arena-card {
           position: relative;
           width: 100%;
           height: 280px;
           border-radius: 12px;
           overflow: hidden;
           font-family: 'Orbitron', sans-serif;
           border: 1px solid rgba(229, 231, 235, 0.2);
           box-shadow: 0 5px 5px #003944;
       }

       .arena-content {
           width: 100% !important;
           height: 100% !important;
           background-size: cover !important;
           transition: transform 0.6s ease !important;
       }

       .arena-card:hover .arena-content {
           transform: scale(1.05);
       }

       .arena-overlay {
           position: absolute;
           bottom: 0;
           left: 0;
           right: 0;
           padding: 24px;
           background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0) 100%);
           display: flex;
           flex-direction: column;
           justify-content: flex-end;
       }

       .arena-title {
           color: #ffffff;
           font-size: 24px;
           font-weight: 700;
           margin-bottom: 12px;
           text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
       }

       .arena-btn {
           background-color: #000000;
           color: #00aed1;
           border: 1px solid #00aed1;
           border-radius: 12px;
           padding: 12px 0;
           font-weight: 700;
           text-transform: uppercase;
           letter-spacing: 1px;
           transition: all 0.3s ease;
           box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
       }

       .arena-btn:hover {
           background-color: #00aed1;
           color: #ffffff;
           box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
           transform: translateY(-2px);
       }
   </style>


   <?php if ($_settings->userdata('type') == 3 or $_settings->userdata('type') == 1): ?>
       <?php $page = '?page=arena';
        $page2 = '?page=pula_asul';
        $page3 = '?page=arena3'; ?>
   <?php elseif ($_settings->userdata('type') == 4): ?>
       <?php $page = '?page=arena_declarator';
        $page2 = '?page=pula_asul_declarator';
        $page3 = '?page=arena3_declarator';  ?>
   <?php else: ?>
       <?php $page = isset($_GET['page']) ? $_GET['page'] : 'maintenance';
        $page2 = isset($_GET['page']) ? $_GET['page'] : 'maintenance';  ?>
   <?php endif; ?>

   <div class="container">

       <div class="d-flex justify-content-center px-4 py-2 rounded-3 border-tech shadow-sm mb-3">
           <div class="currency-box d-inline-flex align-items-center px-4 py-2">
               <div class="mr-3">
                   <i class="fas fa-coins fa-lg coin-icon"></i>
               </div>
               <div class="text-glow h4 mb-0">
                   <span class="mr-1" id="ur_points"></span>
               </div>
           </div>
       </div>

       <div class="row justify-content-center">

           <div class="col-md-4 mb-3 mx-2">
               <div class="arena-card">
                   <a href="<?php echo $page; ?>">
                       <div class="arena-content" id="event_img" style="background: url('<?php echo validate_image('uploads/event-img/wcg.jpg') ?>') no-repeat center center;">
                           <div class="arena-overlay">
                               <h3 class="arena-title text-center" id="event_name"></h3>
                               <button class="btn arena-btn">
                                   <i class="fas fa-play-circle mr-2"></i> ENTER ARENA
                               </button>
                           </div>
                       </div>
                   </a>
               </div>
           </div>

           <div class="col-md-4 mb-3 mx-2">
               <div class="arena-card">
                   <a href="<?php echo $page; ?>">
                       <div class="arena-content" id="event_img2" style="background: url('<?php echo validate_image('uploads/event-img/lucky.jpg') ?>') no-repeat center center;">
                           <div class="arena-overlay">
                               <h3 class="arena-title text-center" id="event_name2"></h3>
                               <button class="btn arena-btn">
                                   <i class="fas fa-play-circle mr-2"></i> ENTER ARENA
                               </button>
                           </div>
                       </div>
                   </a>
               </div>
           </div>

           <div class="col-md-4 mb-3 mx-2">
               <div class="arena-card">
                   <a href="<?php echo $page; ?>">
                       <div class="arena-content" id="event_img3" style="background: url('') no-repeat center center;">
                           <div class="arena-overlay">
                               <h3 class="arena-title text-center" id="event_name3"></h3>
                               <button class="btn arena-btn">
                                   <i class="fas fa-play-circle mr-2"></i> ENTER ARENA
                               </button>
                           </div>
                       </div>
                   </a>
               </div>
           </div>

           <!-- <div class="col-md-4">
               <a href="<?php echo $page; ?>" class="room pb-2" style="background-color: #353A40;">
                   <div id="event_name" style="text-align:center; font-size:20px;"></div>
                   <div id="event_description" style="text-align:center"></div>
                   <div class="btn blue-green-btn-color m-0 d-block"><i class="nav-icon fas fa-play"></i> &nbsp ENTER TO PLAY</div>
               </a>
           </div>

           <div class="col-md-4">
               <a href="<?php echo $page2; ?>" class="room pb-2" style="background-color: #353A40;">
                   <div id="event_name2" style="text-align:center; font-size:20px;"></div>
                   <div id="event_description2" style="text-align:center"></div>
                   <div class="btn blue-green-btn-color m-0 d-block"><i class="nav-icon fas fa-play"></i> &nbsp ENTER TO PLAY</div>
               </a>
           </div>

           <div class="col-md-4">
               <a href="<?php echo $page3; ?>" class="room pb-2" style="background-color: #353A40;">
                   <div id="event_name3" style="text-align:center; font-size:20px;"></div>
                   <div id="event_description3" style="text-align:center"></div>
                   <div class="btn blue-green-btn-color m-0 d-block"><i class="nav-icon fas fa-play"></i> &nbsp ENTER TO PLAY</div>
               </a>
           </div> -->

       </div>
   </div>

   <script>
       try {

           balance();
           events();
           events2();
           events3();

       } catch (e) {
           console.log(e);
           setTimeout(function() {
               balance();
           }, 2000);
       }

       function balance() {
           $.ajax({
               url: _base_url_ + "classes/balance.php",
               success: function(result) {
                   $('#ur_points').html('₱' + result);
                   setTimeout(function() {}, 2000);
               },
               error: function(result) {
                   console.log(result);
                   setTimeout(function() {
                       balance();
                   }, 2000);
               }
           });

       }

       function events() {
           $.ajax({
               url: _base_url_ + "classes/events.php?game_id=1",
               dataType: 'json',
               data: {
                   name: '',
                   description: ''
               },
               success: function(result) {

                   $('#event_name').html(result.name);
                   $('#event_description').html(result.description);

               },
               error: function(result) {
                   console.log('error: events');
                   setTimeout(function() {
                       events();
                   }, 2000);
               }
           });

       }

       function events2() {
           $.ajax({
               url: _base_url_ + "classes/events.php?game_id=2",
               dataType: 'json',
               data: {
                   name: '',
                   description: ''
               },
               success: function(result) {

                   $('#event_name2').html(result.name);
                   $('#event_description2').html(result.description);

               },
               error: function(result) {
                   console.log('error: events');
                   setTimeout(function() {
                       events();
                   }, 2000);
               }
           });

       }


       function events3() {
           $.ajax({
               url: _base_url_ + "classes/events.php?game_id=3",
               dataType: 'json',
               data: {
                   name: '',
                   description: ''
               },
               success: function(result) {

                   $('#event_name3').html(result.name);
                   $('#event_description3').html(result.description);

               },
               error: function(result) {
                   console.log('error: events');
                   setTimeout(function() {
                       events();
                   }, 2000);
               }
           });

       }
   </script>