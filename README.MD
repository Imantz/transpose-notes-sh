# Transpose Notes Script

This script transposes a collection of musical notes by a specified number of
semitones, handling octave adjustments and ensuring notes remain within valid
ranges.

---
![Run Tests](https://github.com/Imantz/transpose-notes-sh/actions/workflows/test.yml/badge.svg)

## Features

- Reads notes from a JSON file.
- Supports transposition by a positive or negative number of semitones.
- Automatically adjusts octaves when notes go beyond valid ranges.
- Ensures valid ranges for notes:
  - The first note is `[-3, 10]` (lowest note).
  - The last note is `[5, 1]` (highest note).

## Usage
1. Clone the Git repository
    ```bash
    git clone https://github.com/Imantz/transpose-notes-sh.git
    ```

2. Run the script
   ```bash
   php input_file.json n
   ```

## Arguments

1. **input_file.json**:

    The first argument is the name of the JSON file containing the notes to be transposed. The file should include an array of note pairs in the format:

    [[octave, note], [octave, note], ...]

2. **n**:

    The second argument specifies the number of semitones to transpose the notes:

    A negative value (e.g., -3) shifts the notes downward.

    A positive value (e.g., 3) shifts the notes upward.

    Example

    To transpose all notes in input.json downward by 3 semitones:

        php input_file.json -3

    This will adjust octaves and validate ranges accordingly.

## Output

    Output is generated in the transpose_output.json file.

## Testing

1. Run the script
   ```bash
   php test.php
   ```
   