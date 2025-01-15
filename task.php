<?php

if ($argc < 3) {
    echo "Illegal number of parameters\n";
    exit(1);
}

$inputFile = $argv[1];
$transpositionRange = $argv[2];
$outputFile = "transpose_output.json"; // This could be $argv[3]

// Validate if file exists
if (!file_exists($inputFile)) {
    echo "File not found\n";
    exit(1);
}

// Validate if second parameter is a number (can be negative)
if (!is_numeric($transpositionRange)) {
    echo "Second parameter must be a number\n";
    exit(1);
}
$transpositionRange = (int)$transpositionRange;

// Read file content
$json = file_get_contents($inputFile);

// Step 1: Remove unnecessary spaces and newlines
$json = preg_replace('/\s+/', '', $json);

// Step 2: Decode JSON into an array
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Invalid JSON structure\n";
    exit(1);
}

$transposedNotes = [];

foreach ($data as $note) {
    if (!is_array($note) || count($note) !== 2) {
        echo "Invalid note structure\n";
        exit(1);
    }

    [$octaveNumber, $noteNumber] = $note;

    // Calculate the sum of the note number and the transposition range
    $noteSum = $noteNumber + $transpositionRange;

    // Adjust the octave and note number for overflow and underflow
    if ($noteSum > 12) {
        $octaveNumber += 1;
        $noteNumber = $noteSum - 12;
    } elseif ($noteSum < 1) {
        $octaveNumber -= 1;
        $noteNumber = 12 + $noteSum;
    } else {
        $noteNumber = $noteSum;
    }

    // Validate the note range
    if (($octaveNumber === -3 && $noteNumber < 10) ||
        ($octaveNumber === 5 && $noteNumber > 1) ||
        $octaveNumber < -3 || $octaveNumber > 5) {
        echo "Invalid note: octave $octaveNumber with note $noteNumber is out of range.\n";
        exit(1);
    }

    // Add the valid pair to the transposed notes array
    $transposedNotes[] = [$octaveNumber, $noteNumber];
}

// Encode the transposed notes back to JSON
$transposedJson = json_encode($transposedNotes);

if ($transposedJson === false) {
    echo "Failed to encode transposed notes to JSON\n";
    exit(1);
}

// Write the output to a file
file_put_contents($outputFile, $transposedJson);