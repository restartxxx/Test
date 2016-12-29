<?php

namespace bansystem\util\date;

use DateTime;
use InvalidArgumentException;

class Countdown {
    
    /** @var DateTime */
    private $date;
    
    public function __construct(string $format) {
        if (is_numeric($format)) {
            if (intval($format) <= 0) {
                throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
            }
            $dateTime = new DateTime();
            $dateTime->setTimestamp($dateTime->getTimestamp() + intval($format));
            $this->date = $dateTime;
            return;
        }
        $this->date = new DateTime();
        $second = 0;
        $minute = 0;
        $hour = 0;
        $day = 0;
        $week = 0;
        $month = 0;
        $year = 0;
        $decade = 0;
        $century = 0;
        $currentCharacters = "";
        $formatChars = str_split($format);
        for ($i = 0; $i < count($formatChars); $i++) {
            if (is_numeric($formatChars[$i])) {
                $currentCharacters .= $formatChars[$i];
                continue;
            }
            switch (strtolower($formatChars[$i])) {
                case "s":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    $second = intval($currentCharacters);
                    $currentCharacters = "";
                    break;
                case "m":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    if (isset($formatChars[$i + 1])) {
                        if (!is_numeric($formatChars[$i + 1])) {
                            switch (strtolower($formatChars[$i + 1])) {
                                case "o":
                                    if (intval($currentCharacters) <= 0) {
                                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                                    }
                                    $month = intval($currentCharacters);
                                    $currentCharacters = "";
                                    break;
                                default:
                                   throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                            }
                            $i += 1;
                            break;
                        }
                    }
                    $minute = intval($currentCharacters);
                    $currentCharacters = "";
                    break;
                case "h":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    $hour = intval($currentCharacters);
                    $currentCharacters = "";
                    break;
                case "d":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    if (isset($formatChars[$i + 1])) {
                        if (!is_numeric($formatChars[$i + 1])) {
                            switch (strtolower($formatChars[$i + 1])) {
                                case "c":
                                    if (intval($currentCharacters) <= 0) {
                                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                                    }
                                    if ($currentCharacters == "") {
                                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                                    }
                                    $decade = intval($currentCharacters);
                                    $currentCharacters = "";
                                    break;
                                default:
                                    throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                            }
                            $i += 1;
                            break;
                        }
                    }
                    $day = intval($currentCharacters);
                    $currentCharacters = "";
                    break;
                case "w":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    $week = intval($currentCharacters);
                    $currentCharacters = "";
                    break;
                case "y":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    $year = intval($currentCharacters);
                    $currentCharacters = "";
                    break;
                case "c":
                    if ($currentCharacters == "") {
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    if (intval($currentCharacters) <= 0) {
                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                    }
                    if (isset($formatChars[$i + 1])) {
                        if (!is_numeric($formatChars[$i + 1])) {
                            switch (strtolower($formatChars[$i + 1])) {
                                case "t":
                                    if ($currentCharacters == "") {
                                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                                    }
                                    if (intval($currentCharacters) <= 0) {
                                        throw new InvalidArgumentException("0 und negative Zeiten sind nicht erlaubt.");
                                    }
                                    $century = intval($currentCharacters);
                                    $currentCharacters = "";
                                    break;
                                default:
                                    throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                            }
                            $i += 1;
                            break;
                        }
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
                    }
                    default:
                        throw new InvalidArgumentException("Bitte benutze ein richtiges Zeitformat.");
            }
        }
        while ($second >= 60) {
            $minute++;
            $second -= 60;
        }
        while ($minute >= 60) {
            $hour++;
            $minute -= 60;
        }
        while ($hour >= 24) {
            $day++;
            $hour -= 24;
        }
        while ($week >= 1) {
            $day += 7;
            $week--;
        }
        while ($day >= 30) {
            $month++;
            $day -= 30;
        }
        while ($month >= 12) {
            $year++;
            $month -= 12;
        }
        while ($decade >= 1) {
            $year += 10;
            $decade--;
        }
        while ($century >= 1) {
            $year += 100;
            $century--;
        }
        $newSecond = intval($this->date->format("s")) + $second;
        $newMinute = intval($this->date->format("i")) + $minute;
        $newHour = intval($this->date->format("H")) + $hour;
        $newDay = intval($this->date->format("d")) + $day;
        $newMonth = intval($this->date->format("m")) + $month;
        $newYear = intval($this->date->format("Y")) + $year;
        $newDate = new DateTime();
        $newDate = $newDate->setDate($newYear, $newMonth, $newDay);
        $newDate = $newDate->setTime($newHour, $newMinute, $newSecond);
        $this->date = $newDate;
    }
    
    public function getDate() : DateTime {
        return $this->date;
    }
    
    public static function expirationTimerToString(DateTime $from, DateTime $to) : string {
        $string = "";
        $second = intval($from->format("s")) - intval($to->format("s"));
        $minute = intval($from->format("i")) - intval($to->format("i"));
        $hour = intval($from->format("H")) - intval($to->format("H"));
        $day = intval($from->format("d")) - intval($to->format("d"));
        $month = intval($from->format("n")) - intval($to->format("n"));
        $year = intval($from->format("Y")) - intval($to->format("Y"));
        if ($second <= -1) {
            $second = 60 + $second;
            $minute--;
        }
        if ($minute <= -1) {
            $minute = 60 + $minute;
            $hour--;
        }
        if ($hour <= -1) {
            $hour = 24 + $hour;
            $day--;
        }
        if ($day <= -1) {
            $day = 30 + $day;
            $month--;
        }
        if ($month <= -1) {
            $month = 12 + $month;
            $year--;
        }
        $string .= $year >= 1 ? strval($year) . " " . ($year >= 2 ? "Jahre " : "Jahr ") : "";
        $string .= $month >= 1 ? strval($month) . " " . ($month >= 2 ? "Monate " : "Monat ") : "";
        $string .= $day >= 1 ? strval($day) . " " . ($day >= 2 ? "Tage " : "Tage") : "";
        $string .= $hour >= 1 ? strval($hour) . " " . ($hour >= 2 ? "Stunden " : "Stunde ") : "";
        $string .= $minute >= 1 ? strval($minute) . " " . ($minute >= 2 ? "Minuten " : "Minute ") : "";
        $string .= $second >= 1 ? strval($second) . " " . ($second >= 2 ? "Sekunden " : "Sekunde ") : "";
        $string = substr($string, 0, strlen($string) - 1);
        return $string;
    }
}