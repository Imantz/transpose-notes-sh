#!/bin/bash

# Test cases for the transpose script
script="./task.sh"

run_test() {
    local test_name=$1       # Test description
    local expected=$2        # Expected output
    local command=$3         # Command to run the script
    local output_file=$4     # Captured output

    echo "$test_name"
    # Use eval to execute the command and capture the output
    local output_from_script=$(eval "$command")

    if [ -f "$output_file" ]; then
        local output_from_script=$(cat $output_file)
    fi

    if [ "$output_from_script" == "$expected" ]; then
        echo "Test passed!"
    else
        echo "Test failed. Expected: '$expected', Got: '$output_from_script'"
        exit 1
    fi
    echo -e "\n"
}

run_test "Test 1: No parameters" \
         "Illegal number of parameters" \
         "$script"
run_test "Test 2: Missing file" \
         "File not found" \
         "$script missing.json 2"

echo '' > test_input.json
run_test "Test 3: Invalid second parameter" \
         "Second parameter must be a number" \
         "$script test_input.json invalid"

echo '[[-1,1],[1,5],[2,11]]' > test_input.json

run_test "Test 4: Valid input, positive transposition" \
            "[[-1,3],[1,7],[3,1]]" \
            "$script test_input.json 2" \
            transpose_output.json

echo '[[2,1],[2,6],[1,6]]' > test_input.json

run_test "Test 5: Valid input, negative transposition" \
            "[[1,10],[2,3],[1,3]]" \
            "$script test_input.json -3" \
            transpose_output.json

echo '[[5,2]]' > test_input.json
run_test "Test 6: Out-of-range notes octave 5" \
            "Invalid note: octave 5 with note 3 is out of range." \
            "$script test_input.json 1"

echo '[[-3,11]]' > test_input.json
run_test "Test 6: Out-of-range notes octave -3" \
            "Invalid note: octave -3 with note 9 is out of range." \
            "$script test_input.json -2"

task_input="
[[2,1],[2,6],[2,1],[2,8],[2,1],[2,9],[2,1],[2,6],[2,1],[2,8],[2,1],[2,9],[2,1],[2,11],[2,1],[2,8],[2,1],[2,9],[2,1],[2,
11],[2,1],[3,1],[2,1],[2,9],[2,1],[2,11],[2,1],[3,1],[2,1],[3,2],[2,1],[2,11],[2,1],[3,1],[2,1],[2,9],[2,1],[2,11],[2,
1],[2,8],[2,1],[2,9],[2,1],[2,6],[2,1],[2,8],[2,1],[2,5],[2,1],[2,6],[2,1],[2,1],[2,1],[2,2],[2,1],[1,11],[2,1],[2,1],[
2,1],[1,9],[2,1],[1,11],[2,1],[1,8],[2,1],[1,9],[2,1],[1,6],[2,1],[1,11],[2,1],[1,8],[2,1],[1,9],[2,1],[1,6],[2,1],[1,
8],[2,1],[1,5],[2,1],[1,6]]"

task_output="[[1,10],[2,3],[1,10],[2,5],[1,10],[2,6],[1,10],[2,3],[1,10],[2,5],[1,10],[2,6],[1,10],[2,8],[1,10],[2,5],[1,10],[2,
6],[1,10],[2,8],[1,10],[2,10],[1,10],[2,6],[1,10],[2,8],[1,10],[2,10],[1,10],[2,11],[1,10],[2,8],[1,10],[2,10],[1,
10],[2,6],[1,10],[2,8],[1,10],[2,5],[1,10],[2,6],[1,10],[2,3],[1,10],[2,5],[1,10],[2,2],[1,10],[2,3],[1,10],[1,10],
[1,10],[1,11],[1,10],[1,8],[1,10],[1,10],[1,10],[1,6],[1,10],[1,8],[1,10],[1,5],[1,10],[1,6],[1,10],[1,3],[1,10],[1
,8],[1,10],[1,5],[1,10],[1,6],[1,10],[1,3],[1,10],[1,5],[1,10],[1,2],[1,10],[1,3]]"

# Remove newlines and spaces
task_output=$(echo $task_output| tr -d '\n' | tr -d ' ')

echo $task_input > test_input.json

run_test "Test 7: Task input and taks output" \
            "$task_output" \
            "$script test_input.json -3" \
            transpose_output.json

# Cleanup
rm -f test_input.json transpose_output.json
