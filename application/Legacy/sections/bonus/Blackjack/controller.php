<?php
use Luminance\Legacy\sections\bonus\Blackjack\Game;
use Luminance\Legacy\sections\bonus\Blackjack\Player;

$blackjack_continue = true;
if (!isset($_REQUEST['blackjack'])) {
    include(SERVER_ROOT.'/Legacy/sections/bonus/blackjack.php');
    $blackjack_continue = false;
} else {
    switch ($_REQUEST['blackjack']) {
        case 'new':
            new Game((int) $_POST['bet']);
            break;
        case 'end':
            Game::end();
            break;
        case 'hit':
            Player::get_player()->hit();
            break;
        case 'stand':
            Player::get_player()->stand();
            break;
        default:
            include(SERVER_ROOT.'/Legacy/sections/bonus/blackjack.php');
            $blackjack_continue = false;
            break;
    }
}

if ($blackjack_continue) {
    $blackjack = [];
    if (isset($_SESSION['blackjack_player']) && isset($_SESSION['blackjack_dealer'])) {
        if (isset($_SESSION['blackjack_winner']) && $_SESSION['blackjack_winner']['who'] === 'player') {

        }
    } else {

    }
}