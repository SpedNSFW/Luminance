<?php

function create_deck($suits, $values) {
    $deck = [];
    foreach ($values as $value) {
        foreach ($suits as $suit) {
            if (in_array($value, ['J', 'Q', 'K'])) {
                $weight = 10;
            } elseif ($value == 'A') {
                $weight = 11;
            } else {
                $weight = intval($value);
            }
            $deck[] = [
                'value' => $value,
                'suit' => $suit,
                'weight' => $weight,
            ];
        }
    }
    return $deck;
}

function start_blackjack_game() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        if (!isset($_SESSION['activeBlackjackGame'])) {
            $_SESSION['activeBlackjackGame'] = true;
        }
    }
}

