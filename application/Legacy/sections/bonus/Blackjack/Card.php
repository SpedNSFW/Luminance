<?php
namespace Luminance\Legacy\sections\bonus\Blackjack;

class Card {
    const SUITS = ['Hearts', 'Spades', 'Diamonds', 'Clubs'];
    const VALUES = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

    protected $suit;
    protected $value;

    public function __construct(string $suit, string $value) {
        if (!in_array($suit, self::SUITS)) {
            throw new \Exception("An invalid card suit was specified: $suit.");
        }
        if (!in_array($value, self::VALUES)) {
            throw new \Exception("An invalid card value was specified: $value.");
        }

        $this->suit = $suit;
        $this->value = $value;
    }

    public function __toString() {
        return "$this->value of $this->suit";
    }

    public function get_card_value(): int {
        if ($this->value === 'A') {
            return 11;
        } else {
            return is_numeric($this->value)
                ? intval($this->value)
                : 10;
        }
    }

    public function get_card_rank() {
        return $this->value;
    }

    public static function shuffle_deck(): array {
        foreach (self::SUITS as $suit) {
            foreach (self::VALUES as $value) {
                $deck[] = new static($suit, $value);
            }
        }
        shuffle($deck);

        return $deck;
    }

    public static function draw_card(): self {
        if (!isset($_SESSION['blackjack_deck'])) {
            throw new \Exception('There is no active deck.');
        }

        if (!count($_SESSION['blackjack_deck'])) {
            $_SESSION['blackjack_deck'] = self::shuffle_deck();
            Game::flash('warning', 'Ran out of cards. Reshuffling');
        }

        return array_shift($_SESSION['blackjack_deck']);
    }
}
