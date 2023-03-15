<?php
namespace Luminance\Legacy\sections\bonus\Blackjack;

class Game {
    const BLACKJACK = 21;

    public function __construct(int $bet) {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!isset($_SESSION['blackjack_activeGame'])) {
                $_SESSION['blackjack_activeGame'] = true;
                $_SESSION['blackjack_deck'] = Card::shuffle_deck();

                $_SESSION['blackjack_player'] = new Player;
                $_SESSION['blackjack_dealer'] = new Player('dealer');

                $_SESSION['blackjack_bet'] = $bet;
            }

            self::purge_flash();
            self::new();
        }
    }

    public static function new() {
        $_SESSION['blackjack_handOver'] = false;

        Player::get_player('player')->hit();
        Player::get_player('dealer')->hit();
        Player::get_player('player')->hit();
        Player::get_player('dealer')->hit();

        self::refresh();
    }

    public static function check_for_winner() {
        $player = Player::get_player();
        $dealer = Player::get_player('dealer');

        $player_score = $player->get_current_score();
        $dealer_score = $dealer->get_current_score(false);

        if ($player_score > self::BLACKJACK) {
            $dealer->win();
            $_SESSION['blackjack_winner']['who'] = 'dealer';
            $_SESSION['blackjack_winner']['how'] = 'player bust';
        } elseif ($dealer_score > self::BLACKJACK) {
            $player->win();
            $_SESSION['blackjack_winner']['who'] = 'player';
            $_SESSION['blackjack_winner']['how'] = 'dealer bust';
        } elseif ($player_score === self::BLACKJACK && $dealer_score === self::BLACKJACK) {
            // TODO: restart
        } elseif ($player_score === self::BLACKJACK) {
            $player->win();
            $_SESSION['blackjack_winner']['who'] = 'player';
            $_SESSION['blackjack_winner']['how'] = 'blackjack';
        } elseif ($dealer_score === self::BLACKJACK) {
            $dealer->win();
            $_SESSION['blackjack_winner']['who'] = 'dealer';
            $_SESSION['blackjack_winner']['how'] = 'blackjack';
        } elseif ($player_score > $dealer_score && $dealer_score < Player::DEALER_STANDS) {
            $dealer->dealers_turn();
        } elseif ($player_score > $dealer_score) {
            $player->win();
            $_SESSION['blackjack_winner']['who'] = 'player';
            $_SESSION['blackjack_winner']['how'] = 'score';
        } elseif ($dealer_score > $player_score) {
            $dealer->win();
            $_SESSION['blackjack_winner']['who'] = 'dealer';
            $_SESSION['blackjack_winner']['how'] = 'score';
        }
    }

    public static function end() {
        self::reset();
    }

    public static function flash(string $type, string $message) {
        $_SESSION['flash']['type'] = $type;
        $_SESSION['flash']['message'] = $message;

        self::hand_over();
    }

    public static function purge_flash() {
        unset($_SESSION['flash']);
    }

    public static function hand_over() {
        $_SESSION['blackjack_handOver'] = true;
    }

    public static function reset() {
        unset($_SESSION['blackjack_activeGame']);
        unset($_SESSION['blackjack_player']);
        unset($_SESSION['blackjack_dealer']);
        unset($_SESSION['blackjack_deck']);
        unset($_SESSION['blackjack_bet']);
        unset($_SESSION['blackjack_winner']);
    }
}
