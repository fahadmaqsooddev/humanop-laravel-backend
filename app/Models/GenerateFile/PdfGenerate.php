<?php

namespace App\Models\GenerateFile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfGenerate extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getPdfContent($assessmentId = null, $userId = null)
    {
        return self::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->get()
            ->toArray();
    }

    public static function createGenerateFile($assessmentId = null, $userId = null, $styles = null)
    {
        $pdfFile = self::getPdfContent($assessmentId, $userId);

        if (empty($pdfFile)) {
            foreach ($styles as $style) {
                $styleHeading = '';
                $styleText = '';
                $styleTextArray = [];

                if (isset($style[1])) {
                    // Determine the heading and corresponding text options based on style
                    switch ($style[1]) {
                        case 'Regal':
                            $styleHeading = 'Regal';
                            $styleTextArray = [
                                "The Regal trait in you provides you with a benevolent presence that exudes authority...",
                                "You possess a Regal and majestic aspect to your nature that naturally makes you a leader...",
                                "The Regal trait in you makes you a natural born leader. This is a serene and benevolent presence..."
                            ];
                            break;

                        case 'Energetic':
                            $styleHeading = 'Energetic';
                            $styleTextArray = [
                                "The Energetic trait in you makes you somewhat of a crusader by nature. Integrity becomes you...",
                                "Your Energetic trait is characterized by a strong sense of integrity, with a deep-rooted belief..."
                            ];
                            break;

                        case 'Absorptive':
                            $styleHeading = 'Absorptive';
                            $styleTextArray = [
                                "Your Absorptive trait is a passive part of your nature. It offers up a wise and reverent approach...",
                                "The Absorptive trait in you provides you with the natural ability to archive vast amounts of information..."
                            ];
                            break;

                        case 'Romantic':
                            $styleHeading = 'Romantic';
                            $styleTextArray = [
                                "Your Romantic trait is a part of you that is quiet and very passive by nature...",
                                "The Romantic trait in you is passive and gentle. This is the very devoted and loyal aspect of your nature..."
                            ];
                            break;

                        case 'Perceptive':
                            $styleHeading = 'Perceptive';
                            $styleTextArray = [
                                "The Perceptive trait in you processes information at the speed of light...",
                                "Your Perceptive trait operates at lightning speed and makes you quick to deliver an outcome..."
                            ];
                            break;

                        case 'Effervescent':
                            $styleHeading = 'Effervescent';
                            $styleTextArray = [
                                "Your Effervescent trait makes you vibrant and delightful and gives you access to possibility-filled views...",
                                "The Effervescent aspect of your nature provides you with a playful, delightful charm..."
                            ];
                            break;

                        default:
                            continue 2; // Skip to the next iteration of the outer loop
                    }

                    // Pick a random text from the available style text array
                    if (!empty($styleTextArray)) {
                        $randomKey = array_rand($styleTextArray);
                        $styleText = $styleTextArray[$randomKey];
                    }

                    // Create a new PDF entry with the generated data
                    self::create([
                        'assessment_id' => $assessmentId,
                        'user_id' => $userId,
                        'public_name' => $styleHeading,
                        'text' => $styleText,
                    ]);
                }
            }

            $getPdfFile = self::getPdfContent($assessmentId, $userId);

        } else {
            $getPdfFile = $pdfFile;
        }

        return $getPdfFile;
    }

}
