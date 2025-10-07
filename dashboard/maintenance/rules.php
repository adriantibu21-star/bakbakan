<?php if($_settings->userdata('type') == 3): ?> 
<div class="row" style="color:white">
                <ol>
                    <li>
                        Minimum bet is <em>10</em>
                    </li>
                    <li>
                        Betting window is <em>3 to 5 mins</em>. There are 3 game status; <em>OPEN</em>, <em>LAST CALL</em> and <em>CLOSED</em>. Once betting is CLOSED, bets will not be accepted.</li>
                    <li>
                        Payout style: <em>Sports betting</em>.
                    </li>
                    <li>
                        Payout computation (odds):
                        <br />
                        % based on total bet per side
                        <br />
                        Example: 100 bet at 180% wins 180
                    </li>
                    <li>
                        If fight is a draw, bets are returned
                    </li>
                </ol>
</div>
<?php endif;?>