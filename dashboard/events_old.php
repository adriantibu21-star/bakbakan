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

    </style>


     <?php if($_settings->userdata('type') == 3 or $_settings->userdata('type') == 1): ?> 
		<?php $page = '?page=arena'; $page2 = '?page=pula_asul'; $page3 = '?page=arena3';?>
     <?php elseif($_settings->userdata('type') == 4): ?> 
		<?php $page = '?page=arena_declarator';  $page2 = '?page=pula_asul_declarator';  $page3 = '?page=arena3_declarator';  ?>
     <?php else: ?>
     		<?php $page = isset($_GET['page']) ? $_GET['page'] : 'maintenance';  $page2 = isset($_GET['page']) ? $_GET['page'] : 'maintenance';  ?>
     <?php endif; ?>

    <div class="container">

        <div class="eventsHeader">
            <div class="title yellow-text-color">Home</div>

        </div>


	<div class="row justify-content-center">
            <div class="currentPoints">
                <h4 text class="orange-text-color">Current Points: </h4>

                <h3 text style="text-align:center;" class="orange-text-color"><strong id="ur_points"></strong></h3>
  
	          </div>
	</div>

	<div class="row justify-content-center">
	<div class="col-md-4">
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
	</div>

	</div>
    </div>

<script>

    try{

	balance();
	events();
	events2();
	events3();

    }catch(e){
      console.log(e);
      setTimeout(function(){
        balance();
      },2000);
    }

function balance(){
  $.ajax({
    url: _base_url_+"classes/balance.php",
    success: 
    function(result){
      $('#ur_points').html(result);
      setTimeout(function(){
      },2000);
    },
    error: function(result){
      console.log(result);
      setTimeout(function(){
        balance();
      },2000);
    }
  });

}
function events(){
            $.ajax({
              url: _base_url_+"classes/events.php?game_id=1",
        	dataType: 'json',
        	data: {
            		name: '',
			description: ''
        	},
              success: function(result){
        

                        $('#event_name').html(result.name);
    
                        $('#event_description').html(result.description);
   


              },
              error: function(result){
                console.log('error: events');
      		setTimeout(function(){
      			events();
      		},2000);
              }
            });

}

function events2(){
            $.ajax({
              url: _base_url_+"classes/events.php?game_id=2",
        	dataType: 'json',
        	data: {
            		name: '',
			description: ''
        	},
              success: function(result){
        

                        $('#event_name2').html(result.name);
    
                        $('#event_description2').html(result.description);
   


              },
              error: function(result){
                console.log('error: events');
      		setTimeout(function(){
      			events();
      		},2000);
              }
            });

}


function events3(){
            $.ajax({
              url: _base_url_+"classes/events.php?game_id=3",
        	dataType: 'json',
        	data: {
            		name: '',
			description: ''
        	},
              success: function(result){
        

                        $('#event_name3').html(result.name);
    
                        $('#event_description3').html(result.description);
   


              },
              error: function(result){
                console.log('error: events');
      		setTimeout(function(){
      			events();
      		},2000);
              }
            });

}

</script>



