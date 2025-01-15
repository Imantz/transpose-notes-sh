<?php

$script = "php task.php";

function run_test($test_name, $expected, $command, $output_file = null)
{
    echo "$test_name\n";

    // Execute the command and capture the output
    exec($command, $output_from_script_lines, $return_var);
    $output_from_script = implode("\n", $output_from_script_lines);

    // If an output file is specified and exists, read its content
    if ($output_file && file_exists($output_file)) {
        $output_from_script = file_get_contents($output_file);
    }

    // Compare the output to the expected value
    if (trim($output_from_script) === trim($expected)) {
        echo "Test passed!\n\n";
    } else {
        echo "Test failed. Expected: '$expected', Got: '$output_from_script'\n";
        exit(1);
    }
}

// Test 1: No parameters
run_test(
    "Test 1: No parameters",
    "Illegal number of parameters",
    "$script"
);

// Test 2: Missing file
run_test(
    "Test 2: Missing file",
    "File not found",
    "$script missing.json 2"
);

// Test 3: Invalid second parameter
file_put_contents("test_input.json", "");
run_test(
    "Test 3: Invalid second parameter",
    "Second parameter must be a number",
    "$script test_input.json invalid"
);

// Test 4: Valid input, positive transposition
file_put_contents("test_input.json", '[[-1,1],[1,5],[2,11]]');
run_test(
    "Test 4: Valid input, positive transposition",
    "[[-1,3],[1,7],[3,1]]",
    "$script test_input.json 2",
    "transpose_output.json"
);

// Test 5: Valid input, negative transposition
file_put_contents("test_input.json", '[[2,1],[2,6],[1,6]]');
run_test(
    "Test 5: Valid input, negative transposition",
    "[[1,10],[2,3],[1,3]]",
    "$script test_input.json -3",
    "transpose_output.json"
);

// Test 6: Out-of-range notes octave 5
file_put_contents("test_input.json", '[[5,2]]');
run_test(
    "Test 6: Out-of-range notes octave 5",
    "Invalid note: octave 5 with note 3 is out of range.",
    "$script test_input.json 1"
);

// Test 7: Out-of-range notes octave -3
file_put_contents("test_input.json", '[[-3,11]]');
run_test(
    "Test 7: Out-of-range notes octave -3",
    "Invalid note: octave -3 with note 9 is out of range.",
    "$script test_input.json -2"
);

// Test 8: Task input and task output
$task_input = "
[[2,1],[2,6],[2,1],[2,8],[2,1],[2,9],[2,1],[2,6],[2,1],[2,8],[2,1],[2,9],[2,1],[2,11],[2,1],[2,8],[2,1],[2,9],[2,1],[2,
11],[2,1],[3,1],[2,1],[2,9],[2,1],[2,11],[2,1],[3,1],[2,1],[3,2],[2,1],[2,11],[2,1],[3,1],[2,1],[2,9],[2,1],[2,11],[2,
1],[2,8],[2,1],[2,9],[2,1],[2,6],[2,1],[2,8],[2,1],[2,5],[2,1],[2,6],[2,1],[2,1],[2,1],[2,2],[2,1],[1,11],[2,1],[2,1],[
2,1],[1,9],[2,1],[1,11],[2,1],[1,8],[2,1],[1,9],[2,1],[1,6],[2,1],[1,11],[2,1],[1,8],[2,1],[1,9],[2,1],[1,6],[2,1],[1,
8],[2,1],[1,5],[2,1],[1,6]]";

$task_output = "
[[1,10],[2,3],[1,10],[2,5],[1,10],[2,6],[1,10],[2,3],[1,10],[2,5],[1,10],[2,6],[1,10],[2,8],[1,10],[2,5],[1,10],[2,
6],[1,10],[2,8],[1,10],[2,10],[1,10],[2,6],[1,10],[2,8],[1,10],[2,10],[1,10],[2,11],[1,10],[2,8],[1,10],[2,10],[1,
10],[2,6],[1,10],[2,8],[1,10],[2,5],[1,10],[2,6],[1,10],[2,3],[1,10],[2,5],[1,10],[2,2],[1,10],[2,3],[1,10],[1,10],
[1,10],[1,11],[1,10],[1,8],[1,10],[1,10],[1,10],[1,6],[1,10],[1,8],[1,10],[1,5],[1,10],[1,6],[1,10],[1,3],[1,10],[1
,8],[1,10],[1,5],[1,10],[1,6],[1,10],[1,3],[1,10],[1,5],[1,10],[1,2],[1,10],[1,3]]";

// Remove newlines and spaces
$task_output = preg_replace('/\s+/', '', $task_output);
file_put_contents("test_input.json", $task_input);

run_test(
    "Test 8: Task input and task output",
    $task_output,
    "$script test_input.json -3",
    "transpose_output.json"
);

// Cleanup
unlink("test_input.json");
unlink("transpose_output.json");

echo "All tests completed successfully!\n";
