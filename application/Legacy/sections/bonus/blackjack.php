<?php
enforce_login();

if (!check_perms('site_play_blackjack')) {
    error('You do not have permission to play blackjack!');
}

include(SERVER_ROOT.'/Legacy/sections/bonus/blackjack_functions.php');

show_header('Blackjack', 'blackjack');

$bet_amount = 10;
?>
<script>const cards = JSON.parse(`<?= "[]" ?>`)</script>



<?php
show_footer();
