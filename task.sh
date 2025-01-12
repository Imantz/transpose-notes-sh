#!/bin/bash

# Validate if have 2 parameters
if [ "$#" -ne 2 ]; then
    echo "Illegal number of parameters"
    exit 1
fi

# Validate if file exists
if [ ! -f "$1" ]; then
    echo "File not found"
    exit 1
fi

# Validate if second parameter is a number. Can be negative.
if ! [[ $2 =~ ^-?[0-9]+$ ]]; then
    echo "Second parameter must be a number"
    exit 1
fi

transposition_range=$2

# Read file content
# json structure 
# [
#   [octave_number, note_number],
#   [octave_number, note_number],
#    ...
# ]
json=$(cat tinput.json)

# # # Step 1: Remove unnecessary spaces and newlines
json=$(echo $json| tr -d '\n' | tr -d ' ')

# # Step 2: Split JSON string into sub-arrays
subarrays=$(echo $json | sed 's/\],\[/\n/g' | tr -d '[]')

transposed_notes=""

while IFS=',' read -r octave_number note_number; do
    # Calculate the sum of the note number and the transposition range
    note_sum=$(($note_number + $transposition_range))

    # Adjust the octave and note number for overflow and underflow
    if [ $note_sum -gt 12 ]; then
        octave_number=$(($octave_number + 1))
        note_number=$(($note_sum - 12))
    elif [ $note_sum -lt 1 ]; then
        octave_number=$(($octave_number - 1))
        note_number=$((12 + $note_sum))
    else
        note_number=$note_sum
    fi

    # Validate the note range
    if [ $octave_number -eq -3 ] && [ $note_number -lt 10 ]; then
        echo "Invalid note: octave -3 with note $note_number is out of range."
        continue
    fi
    if [ $octave_number -eq 5 ] && [ $note_number -gt 1 ]; then
        echo "Invalid note: octave 5 with note $note_number is out of range."
        continue
    fi
    if [ $octave_number -lt -3 ] || [ $octave_number -gt 5 ]; then
        echo "Invalid note: octave $octave_number with note $note_number is out of range."
        continue
    fi

    # Append the valid pair as an array to the string
    transposed_notes+="[$octave_number,$note_number],"
done <<< "$subarrays"

# Remove the trailing comma and wrap in square brackets
transposed_notes="[${transposed_notes%,}]"


echo $transposed_notes > transpose_output.json

