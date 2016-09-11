#!/usr/bin/php
<?php

/**
 * txt2mp3
 *
 * Reads a text file and converts each line from text to speech adding some silence.
 * The result is a single mp3 audio file with each line separated by that silence.
 *
 * Designed to run on Mac but requires ffmpeg for conversion from aiff to mp3.
 *
 * Syntax: txt2mp3.php <text-file> <repeat-times>
 */

// The amount of silence for each recording (1 minute)
$delay = 15 * 1000;

// If a file exists with the name specified
if (!file_exists($argv[1])) {
    echo "Error. File not found.\n";
    exit;
}

// Filename without extension
$filename = pathinfo($argv[1], PATHINFO_FILENAME);

// Read the lines
$lines = file($argv[1], FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

// Loop through the lines, reading them into aiff files
foreach ($lines as $number => $line) {

    // Create the aiff file
    $command = "say -o {$number}.aiff \"{$line}\"[[slnc {$delay}]]";
    exec($command);

    // Convert the aiff to an mp3
    $command = "ffmpeg -i {$number}.aiff {$number}.mp3";
    exec($command);

    // Remove the aiff
    unlink($number . '.aiff');

}