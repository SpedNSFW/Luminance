<?php
namespace Luminance\Legacy\sections\bonus\Blackjack;

class Player {
    const DEALER_STANDS = 17;

    protected $type;
    protected $current_hand;
    protected $current_score;

    public function __construct(string $type = 'player') {
        $this->type = $type;
        $this->current_hand = [];
        $this->current_score = 0;
    }

    public function hit() {
        $pulled_card = Card::draw_card();

        $this->current_hand[] = $pulled_card;
        $this->current_score = self::get_hand_value($this->current_hand);

        $this->check_for_player_blackjack();
    }

    public function stand() {
        Game::hand_over();
        Game::check_for_winner();
    }

    public function check_for_player_blackjack() {
        if (strtolower($this->type) === 'player' && $this->current_score >= Game::BLACKJACK) {
            $this->stand();
        } else {
            Game::refresh();
        }
    }

    public function win() {
        // TODO: reward player
    }

    public function clear_current_hand() {
        $this->current_hand = [];
        $this->current_score = 0;
    }

    public function get_current_hand($active_hand = true) {
        // TODO: returns list of player's hand
    }

    public function get_current_score($active_hand = true): int {
        if (strtolower($this->type) === 'player' || (strtolower($this->type) === 'dealer' && !$active_hand)) {
            return $this->current_score;
        } else {
            return $this->current_hand[1]->get_card_value();
        }
    }

    public function dealers_turn() {
        if (strtolower($this->type) !== 'dealer') {
            throw new \Exception('This method is intended for the Dealer.');
        }

        while ($this->current_score <= self::DEALER_STANDS) {
            $this->hit();
        }

        Game::check_for_winner();
    }

    public static function get_player(string $type = 'player'): self {
        Game::check_for_valid_session();

        if (!isset($_SESSION["blackjack_$type"])) {
            throw new \Exception("Player $type not recognized.");
        }
        return $_SESSION["blackjack_$type"];
    }

    public static function hand_ace_count(array $hand): int {
        return count(array_filter($hand, fn($card) => $card->get_card_rank() === 'A'));
    }

    public static function get_hand_values(array $hand): array {
        $values = [];
        $last_value = array_sum(array_map(fn($card) => $card->get_card_value(), $hand));
        $values[] = $last_value;
        for ($i = 0; $i < self::hand_ace_count($hand); $i++) {
            $last_value -= 10;
            $values[] = $last_value;
        }
        return $values;
    }

    public static function get_hand_value(array $hand, $blackjack = Game::BLACKJACK) {
        $values = self::get_hand_values($hand);
        $filtered = array_filter($values, fn($value) => $value <= $blackjack);
        return count($filtered) > 0
            ? $filtered[0]
            : $values[0];
    }
}
