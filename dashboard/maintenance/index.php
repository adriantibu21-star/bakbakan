<?php if ($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2) : ?>
  <!-- <h6 style="color:white">
    <?php if ($_settings->userdata('role') == 1) : ?>
      <?php echo 'Home / Dashboard Financer Account' ?>
    <?php endif; ?>
    <?php if ($_settings->userdata('role') == 2) : ?>
      <?php echo 'Home / Dashboard Operator' ?>
    <?php endif; ?>
    <?php if ($_settings->userdata('role') == 3) : ?>
      <?php echo 'Home / Dashboard sub Operator' ?>
    <?php endif; ?>
    <?php if ($_settings->userdata('role') == 4) : ?>
      <?php echo 'Home / Dashboard Master Agent' ?>
    <?php endif; ?>
    <?php if ($_settings->userdata('role') == 5) : ?>
      <?php echo 'Home / Dashboard Player' ?>
    <?php endif; ?>
  </h6> -->
  	<nav aria-label="breadcrumb">
		<ol class="breadcrumb my-0" style="background-color: transparent;">
			<?php if ($_settings->userdata('role') == 1) : ?>
			<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Dashboard Financer Account</li>
			<?php endif; ?>
			<?php if ($_settings->userdata('role') == 2) : ?>
			<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Dashboard Operator</li>
			<?php endif; ?>
			<?php if ($_settings->userdata('role') == 3) : ?>
			<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Dashboard sub Operator</li>
			<?php endif; ?>
			<?php if ($_settings->userdata('role') == 4) : ?>
			<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Dashboard Master Agent</li>
			<?php endif; ?>
			<?php if ($_settings->userdata('role') == 5) : ?>
			<li class="breadcrumb-item" style="font-size: 1rem"><a href="#">Home</a></li>
			<li class="breadcrumb-item active" style="font-size: 1rem" aria-current="page">Dashboard Player</li>
			<?php endif; ?>
		</ol>
	</nav>
  <hr class="bg-light">
  <div class="container">
    <style>
		.breadcrumb-item.active {
			color: #fff;
		}
		.breadcrumb-item a {
			color: #ffec00;
		}
		.breadcrumb-item li {
			font-size: 1rem !important;
		}
		.bg-success {
			background-color: #C0C0C0 !important;
			color: black !important;
		}
		.bg-refer {
			background-color: #b3d3fc !important;
			color: black !important;
		}


    </style>

    <div class="row">
      <div class="col-sm-12 mt-3">
        <div class="card bg-white text-dark">
          <div class="card-body">
            <div class="card-body rounded" style="background-color:#fffcdc">
	      
	        <div class="text-dark text-center">PLEASE TAKE NOTE OF YOUR REFFERAL LINK BELOW, ALL PLAYERS THAT WILL REGISTER UNDER THIS LINK WILL AUTOMATICALLY BE UNDER YOUR ACCOUNT.</div>	
             	</br><div id="refer_link" class="text-danger text-center"><?php echo base_url . 'register.php?refcode=' . $_settings->userdata('refcode')?></div>
            </div>
            
		</br>
              <div class="col text-center">
                <button id="copy" onclick="myFunction()" class="btn btn-sm btn-danger">COPY YOUR LINK</button>
              </div>
  
          </div>
        </div>
      </div>
    </div>

	<div class="row">
		<div class="col-12 text-center mb-3">
			<a>
				<button type="button" id="post_data" class="btn btn-success btn-sm  post_data" href="javascript:void(0)"
					data-id="<?php echo $_settings->userdata('id') ?>"
					agentid="<?php echo $_settings->userdata('id') ?>">
					<span class="nav-icon fas fa-coins"></span> Convert Comi to Wallet
				</button>
			</a>
		</div>
	</div>


    <div class="row">
      <div class="col-12 col-sm-6 col-md-6">
        <div class="card">
          <div class="card-body rounded" style="background-color:#1e90ff  !important;  height:120px">
    		<table>
 
        		<td valign="top">
				<table style="color:white">
				<tr>
					<td>
              				<h7>TOTAL CURRENT WALLET</h7>
					</td>
				</tr>
				<tr>
					<td>
              					<br><h5>Your Points: <b><?php
                  				$qry = $conn->query("SELECT * from users where id ='{$_settings->userdata('id')}' "); //$_settings->userdata('id')
                  				$row = $qry->fetch_assoc();
                  				echo number_format($row['amount'], 2);
						?></b></h5>
					</td>
				</tr>
				<tr>
					<td>
              				<p></p>
					</td>
				</tr>
				</table>
			</td>
    		</table>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6">
        <div class="card">
          <div class="card-body rounded" style="background-color:#03c03c !important; height:120px">
   
    		<table>
        		<td valign="top">
				<table style="color:white">
				<tr>
					<td>
              				<h7>TOTAL CURRENT COMMISSION (<?php echo number_format($_settings->userdata('rate'), 2) ?>% per bet)</h7>
					</td>
				</tr>
				<tr>
					<td>
              				<br><h5>Your Commission: <b><?php
                  			$qry = $conn->query("SELECT com_amount_bal from users where id ='{$_settings->userdata('id')}' "); //$_settings->userdata('id')
                  			$row = $qry->fetch_assoc();
                  			echo number_format($row['com_amount_bal'], 2);
                  			?></b></h5>
					</td>
				</tr>
				<tr>
					<td>
              				<p></p>
					</td>
				</tr>
				</table>
			</td>
    		</table>
  
          </div>
        </div>
      </div>




<?php if($_settings->userdata('type') == 1): ?> 

      <div class="col-12 col-sm-6 col-md-6">
        <div class="card">
          <div class="card-body rounded" style="background-color:#c3b091 !important; height:120px">
    		<table style="color:black">
        		<td valign="top">
				<table style="color:white">
				<tr>
					<td>
              				<h7>TOTAL DOWNLINES COMMISSION</h7>
					</td>
				</tr>
				<tr>
					<td>
              					<h5>Your Downlines Commission: <b><?php
						if ($_settings->userdata('type') == 1){ //use admin priv

                  					$qry = $conn->query("SELECT sum(com_amount_bal) com_amount_bal from users where Type in (2,3) and id > {$_settings->userdata('id')}");

						}else{


                  					$qry = $conn->query("SELECT sum(com_amount_bal) com_amount_bal from users where parentid ='{$_settings->userdata('id')}' and id <> {$_settings->userdata('id')}");
						}

                  				$row = $qry->fetch_assoc();
                  				echo number_format($row['com_amount_bal'], 2);
                  				?></b></h5>
					</td>
				</tr>
				<tr>
					<td>
              				<p></p>
					</td>
				</tr>
				</table>
			</td>
    		</table>
              <!-- <p>(<?php echo number_format($_settings->userdata('rate'), 2) ?>% per bet)</p> -->

          </div>
        </div>
      </div>


      <div class="col-12 col-sm-6 col-md-6">
        <div class="card">
          <div class="card-body rounded" style="background-color:#bcb88a  !important; height:120px">
    		<table style="color:black">
        		<td valign="top">
				<table style="color:white">
				<tr>
					<td>
              				<h7>TOTAL DOWNLINES WALLET</h7>
					</td>
				</tr>
				<tr>
					<td>
              					<br><h5>Your Downlines Wallet: <b><?php
						if ($_settings->userdata('type') == 1){ //use admin priv

                  					$qry = $conn->query("SELECT SUM(amount) as total from users where Type in (2,3) and id <> {$_settings->userdata('id')} "); //$_settings->userdata('id')

						}else{

                  					$qry = $conn->query("SELECT SUM(amount) as total from users where parentid={$_settings->userdata('id')} and id <> {$_settings->userdata('id')} "); //$_settings->userdata('id')

						}

                  				$row = $qry->fetch_assoc();
                  				echo number_format($row['total'], 2);
                  				?></b></h5>
					</td>
				</tr>
				<tr>
					<td>
              				<p></p>
              				<!-- <p>(<?php echo number_format($_settings->userdata('rate'), 2) ?>% per bet)</p> -->
					</td>
				</tr>
				</table>
			</td>
    		</table>
          </div>
        </div>
      </div>
      <!-- /.col -->

<?php endif; ?>

    </div>

  </div>

	<script>
		$(document).ready(function(){
			$('.post_data').click(function(){
				$id = $(this).attr('data-id')
				$agentid = $(this).attr('agentid')
					uni_modal("<i class='fa fa-coins'></i> Commission to Wallet", 'transactions/post_commission.php?id=' + $id + '&agentid=' + $agentid)
			})
		})
	</script>

  <script>
    // Tooltip

    $('#copy').tooltip({
      trigger: 'click',
      placement: 'bottom'
    });

    function setTooltip(message) {
      $('#copy').tooltip('hide')
        .attr('data-original-title', message)
        .tooltip('show');
    }

    function hideTooltip() {
      setTimeout(function() {
        $('#copy').tooltip('hide');
      }, 1000);
    }




    function copyToClipboard(text) {
      var sampleTextarea = document.createElement("textarea");
      document.body.appendChild(sampleTextarea);
      sampleTextarea.value = text; //save main text in it
      sampleTextarea.select(); //select textarea contenrs
      document.execCommand("copy");
      document.body.removeChild(sampleTextarea);
    }

    function myFunction() {
      var copyText = document.getElementById("refer_link");
      copyToClipboard(copyText.innerText);
      setTooltip('Link copied!');
      hideTooltip();
    }


  </script>
<?php endif; ?>
