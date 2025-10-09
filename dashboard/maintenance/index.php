<?php
	require_once '../classes/UserBalance.php';

	$userBalance = new UserBalance($conn, $_settings->userdata('id'), $_settings->userdata('type'));
	$totalAgentBalanceUnderCurrentUser = $userBalance->getTotalAgentBalanceUnderCurrentUser();
	$totalPlayerBalanceUnderCurrentUser = $userBalance->getTotalPlayerBalanceUnderCurrentUser();
	$activePlayerUnderCurrrentUserCount = $userBalance->getActiveAgentUnderCurrrentUserCount();
	$activeAgentUnderCurrrentUserCount = $userBalance->getActivePlayerUnderCurrrentUserCount();
	$totalAgentCommissionUnderCurrentUser = $userBalance->getTotalAgentCommissionUnderCurrentUser();

?>


<?php if ($_settings->userdata('type') == 1 or $_settings->userdata('type') == 2) : ?>
	<nav aria-label="breadcrumb">
		
		<ol class="breadcrumb my-0 pb-0" style="background-color: transparent;">
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
	<!-- <hr class="bg-light"> -->
	<div class="container px-3">
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
			.card-bg {
				background-color: #343a40 !important;
				color: #fff;
			}


		</style>

		<div class="row">
			<div class="col-sm-12 mt-3">
				<div class="card card-bg text-dark dark-mode">
				<div class="card-body">
					<div class="card-body rounded" style="background-color:#FFF3CD">
				
					<div class="text-dark text-center" style="font-size: 14px; color: #000;text-transform:uppercase;">PLEASE TAKE NOTE OF YOUR REFFERAL LINK BELOW, ALL PLAYERS THAT WILL REGISTER UNDER THIS LINK WILL AUTOMATICALLY BE UNDER YOUR ACCOUNT.</div>	
						</br><div id="refer_link" class="text-danger text-center"><?php echo base_url . 'register.php?refcode=' . $_settings->userdata('refcode')?></div>
					</div>
					
				</br>
					<div class="col text-center">
						<button id="copy" onclick="myFunction()" class="btn btn-lg btn-danger text-bold">COPY YOUR LINK</button>
					</div>

				</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12 text-center mb-3">
				<a>
					<button type="button" id="post_data" class="btn btn-lg text-white post_data" style="background-color:#00bc8c !important; font-size: 18px !important" href="javascript:void(0)"
						data-id="<?php echo $_settings->userdata('id') ?>"
						agentid="<?php echo $_settings->userdata('id') ?>">
						<span class="nav-icon fas fa-coins"></span> CONVERT COMI TO WALLET
					</button>
				</a>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-sm-6 col-md-6">
				<div class="card">
					<div class="card-body rounded text-white" style="background-color:#3498db !important;">
						
						<h5 class="card-title"><b>TOTAL CURRENT WALLET:</b> <span id="twallet" style="display:none;">0</span></h5>

						<br/>
						<br/>
						<h2>
							Your points: 
							<span id="wallet">
								<?php
									$qry = $conn->query("SELECT * from users where id ='{$_settings->userdata('id')}' "); //$_settings->userdata('id')
									$row = $qry->fetch_assoc();
									echo number_format($row['amount'], 2);
								?>
							</span>
						</h2>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<div class="card">
					<div class="card-body rounded text-white" style="background-color:#00bc8c  !important;">

						<h5 class="card-title"><b>TOTAL CURRENT COMMISSION: </b> 
							<span class="text-bold" style="color: #d34242ff;">
								 (<?php echo number_format($_settings->userdata('rate'), 2) ?>% per bet ) 
							</span>
						</h5>

						<br/>
						<br/>
						<h2>
							Your points: 
							<span id="wallet">
								<?php
									$qry = $conn->query("SELECT com_amount_bal from users where id ='{$_settings->userdata('id')}' "); //$_settings->userdata('id')
									$row = $qry->fetch_assoc();
									echo number_format($row['com_amount_bal'], 2);
								?>
							</span>
						</h2>

					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<div class="card" style=" background: linear-gradient(to top, #c4c5c7 0%, #dcdddf 52%, #ebebeb 100%);color: black;">
					<div class="card-body rounded" style="color:black;">

						<h5 class="card-title"><b>TOTAL PLAYER WALLET: ( <?php echo $activePlayerUnderCurrrentUserCount ?> Active Player) </b> <span id="twallet" style="display:none;">0</span></h5>

						<br/>
						<br/>
						<h2>
							<span><?php echo $totalPlayerBalanceUnderCurrentUser ?></span>
						</h2>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<div class="card" style=" background: linear-gradient(to top, #c4c5c7 0%, #dcdddf 52%, #ebebeb 100%);color: black;">
					<div class="card-body rounded" style="color:black;">

						<h5 class="card-title"><b>TOTAL AGENT WALLET: ( <?php echo $activeAgentUnderCurrrentUserCount ?> Active Agent) </b> <span id="twallet" style="display:none;">0</span></h5>

						<br/>
						<br/>
						<h2>
							<span><?php echo $totalAgentBalanceUnderCurrentUser ?></span>
						</h2>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<div class="card" style=" background: linear-gradient(to top, #c4c5c7 0%, #dcdddf 52%, #ebebeb 100%);color: black;">
					<div class="card-body rounded" style="color:black;">

						<h5 class="card-title"><b>TOTAL AGENT COMMISSION: </b> <span id="twallet" style="display:none;">0</span></h5>

						<br/>
						<br/>
						<h2>
							<span><?php echo $totalAgentCommissionUnderCurrentUser ?></span>
						</h2>
					</div>
				</div>
			</div>

			<?php if($_settings->userdata('type') == 1): ?>
				
				<div class="col-12 col-sm-6 col-md-6">
					<div class="card">
						<div class="card-body rounded text-white" style="background-color:#bcb88a !important;">
							
							<h5 class="card-title"><b>TOTAL DOWNLINES COMMISSION:</b> <span id="twallet" style="display:none;">0</span></h5>

							<br/>
							<br/>
							<h2>
								Your downlines commission: 
								<span id="wallet">
									<?php
									if ($_settings->userdata('type') == 1){ //use admin priv
										$qry = $conn->query("SELECT sum(com_amount_bal) com_amount_bal from users where Type in (2,3) and id > {$_settings->userdata('id')}");
									}else{
										$qry = $conn->query("SELECT sum(com_amount_bal) com_amount_bal from users where parentid ='{$_settings->userdata('id')}' and id <> {$_settings->userdata('id')}");
									}

									$row = $qry->fetch_assoc();
									echo number_format($row['com_amount_bal'], 2);
									?>
								</span>
							</h2>

						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-6">
					<div class="card">
						<div class="card-body rounded text-white" style="background-color:#bcb88a !important;">
							
							<h5 class="card-title"><b>TOTAL DOWNLINES WALLET:</b> <span id="twallet" style="display:none;">0</span></h5>

							<br/>
							<br/>
							<h2>
								Your downlines wallet: 
								<span id="wallet">
									<?php
										if ($_settings->userdata('type') == 1){ //use admin priv
											$qry = $conn->query("SELECT SUM(amount) as total from users where Type in (2,3) and id <> {$_settings->userdata('id')} "); //$_settings->userdata('id')
										}else{
											$qry = $conn->query("SELECT SUM(amount) as total from users where parentid={$_settings->userdata('id')} and id <> {$_settings->userdata('id')} "); //$_settings->userdata('id')
										}
											$row = $qry->fetch_assoc();
											echo number_format($row['total'], 2);
									?>
								</span>
							</h2>

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
