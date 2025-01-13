<?php

namespace App\Traits;

trait GeneratesPlateNumbers
{
    /**
     * Generate a random vehicle registration plate number.
     *
     * @return string
     */
    public function generateRandomPlate()
    {
        // Define the character sets for the letters
        $firstLetterSet = ['Ζ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ρ', 'Τ', 'Χ'];
        $secondThirdLetterSet = ['Ζ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ρ', 'Τ', 'Χ', 'Α', 'Ε', 'Η', 'Ι', 'Ο'];
        
        // Generate the first letter
        $firstLetter = $firstLetterSet[array_rand($firstLetterSet)];
        
        // Generate the second and third letters
        $secondLetter = $secondThirdLetterSet[array_rand($secondThirdLetterSet)];
        $thirdLetter = $secondThirdLetterSet[array_rand($secondThirdLetterSet)];
        
        // Generate a random four-digit number that does not contain 0
        do {
            $number = rand(1000, 9999); // Ensure it's a 4-digit number
        } while (strpos((string)$number, '0') !== false); // Check if it contains '0'
        
        // Combine to form the registration plate
        $plate = $firstLetter . $secondLetter . $thirdLetter . $number;
        
        return $plate;
    }
}
