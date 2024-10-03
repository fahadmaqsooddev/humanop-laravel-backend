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

    public static function createGenerateFile($assessmentId = null, $userId = null, $styles = null)
    {
        $pdfFile = self::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->get()
            ->toArray();

        if (empty($pdfFile)) {
            foreach ($styles as $style) {
                $styleHeading = '';
                $styleText = '';
                $randomKey = 0;
                if (isset($style[1])) {
                    switch ($style[1]) {
                        case 'Regal':
                            $styleHeading = 'Regal';
                            $styleTextArray = [
                                "The Regal trait in you provides you with a benevolent presence that exudes authority. This part of your nature makes you a natural born leader; a noble quality in you that requires respect for you to realize success. Your approachable manner helps you maintain that respect.  This natural leader quality in you provides you with the ability to access a panoramic perspective of any environment enabling you to see whole situations in an instant. In addition, you search for the most diplomatic solution for any moment's dilemma. Because of this you will rarely act impulsively. This part of your nature makes you ponderous in your actions as you convey a sense of purposefulness in your manner.",
                                "You possess a Regal and majestic aspect to your nature that naturally makes you a leader. This noble quality gives you the ability to take charge of a situation without having to campaign for authority. It is the part of you that is benevolent and reliable, and it’s what inspires others to trust you and naturally look to you for direction. This aspect of your nature also enables you to take on a panoramic perspective of life, giving you the ability to size up the big picture and see whole situations in an instant. Because of this, you set the standards and the tone for the benefit of all concerned.",
                                "The Regal trait in you makes you a natural born leader. This is a serene and benevolent presence that naturally becomes you when you need to hold court or take on a more prominent role. This majestic quality in you requires respect and purpose. It often presents you with permission to proceed where others might have to prove themselves several times over before inspiring others to trust them. It has others seeing you as a necessary decision maker when reaching a consensus. You are able to see the 'big' picture in most circumstances and able to fashion a solution for the benefit of all concerned. People listen to you and because of this you naturally take on responsibility.",
                            ];
                            break;

                        case 'Energetic':
                            $styleHeading = 'Energetic';
                            $styleTextArray = [
                                "The Energetic trait in you makes you somewhat of a crusader by nature. Integrity becomes you and truth and justice serves as the impetus for many of your actions. This part of you is where physical energy resides. Depending on the size (or significance) of your Energetic trait, your desire to fix situations and circumstances will prevail accordingly. Typically, your physicality is always on a mission and wanting to 'save the day'. You naturally know that the shortest distance between two points is a straight line, and you attack the challenges of life head on. Your intention is to get the job done without interruption. This quick-to-respond aspect of your nature can, at times, have you leap before you look and speak before you think.",
                                "Your Energetic trait is characterized by a strong sense of integrity, with a deep-rooted belief in truth and justice. This aspect of your nature manifests as enthusiasm, passion, and honor.As the most dynamic and energetic part of your physicality, you thrive on taking action and will take the shortest distance between A and B when doing so. This active part of your nature will strive to fix what’s not working in a situation and forge on to correct things.",
                            ];
                            break;

                        case 'Absorptive':
                            $styleHeading = 'Absorptive';
                            $styleTextArray = [
                                "Your Absorptive trait is a passive part of your nature. It offers up a wise and reverent approach to sharing memories and a precognitive understanding of life. You are engaging and good-natured; a joyful person who naturally invokes the confidence of others through your exuberant and cheerful disposition. This provides you with an availability factor that affords you an attractive quality at social events. Your absorptive nature affords you proficiency in consuming and archiving vast amounts of information. You are able to recapitulate content and experiences with very little effort and this lends itself to a deep understanding of the world and those in it.  You are well liked and needed by others.",
                                "The Absorptive trait in you provides you with the natural ability to archive vast amounts of information. You are able to draw from experiences and concepts and permanently hold onto content.  This makes you somewhat of a walking encyclopedia when it comes to anything of interest or applied necessity. Providing you make healthy choices for yourself, this aspect in you lends itself to extreme intelligence. It affords you the opportunity to process, store and deliver information at will. Because of this combination, your understanding of love and life is very deep as opposed to being superficial. You are also an extremely entertaining individual who makes others feel warm and welcome. This availability factor invites others to be in your presence.  This is just one part of you and is very passive in nature.",
                            ];
                            break;

                        case 'Romantic':
                            $styleHeading = 'Romantic';
                            $styleTextArray = [
                                "Your Romantic trait is a part of you that is quiet and very passive by nature. You tend to revel in silence and serenity and have a reclusive side to you that often has you hiding your inner feelings. At times you may come across as being depressed, nostalgic or melancholy when in truth you are just being yourself. This part of you often resists change and draws you to areas of solitude over public states; you can be quite monastic at times. You have a great desire to experience an intimate rapport with your surroundings.",
                                "The Romantic trait in you is passive and gentle. This is the very devoted and loyal aspect of your nature. Typically non-confrontational, you tend to regard arguing as a waste of time, seldom blow up and are somewhat resistant to change. At the same time, you are incredibly reliable and will work long hours without complaint. You also enjoy being alone at times and go inward for relaxation purposes while enjoying the silence and serenity. You have a great desire for an intimate rapport with your surroundings and you will seek out those environments that make you feel safe and secure. Because of this you are often drawn to areas of solitude over public states as this aspect of your nature tends to be monastic in nature.",
                            ];
                            break;

                        case 'Perceptive':
                            $styleHeading = 'Perceptive';
                            $styleTextArray = [
                                "The Perceptive trait in you processes information at the speed of light, allowing you to think fast and move quickly. You have a laser focus when it comes to identifying what is not working. This is the mark of a surgical strategist. You choose what information needs to be revealed for the completion of your agenda and you’re very good at keeping secrets. This part of you can be somewhat cunning, clever and even sneaky at times. You frequently enlist this characteristic when trying to get things done behind the scenes without too much unnecessary energy expense on the part of you or others. This is an asset in generating and maintaining efficiency. You also naturally engage in quick-witted conversation that can shift one's attention from one thing to another without taking a topic to any great level. This entertaining, investigative, exploratory aspect to your nature is curious and creates a mysterious quality about you that continues to pique the interest of others. You can size up situations quickly, providing just enough information to get the job done.",
                                "Your Perceptive trait operates at lightning speed and makes you quick to deliver an outcome in terms of registering an opinion or suggestion. This is the critical thinker in you that allows you to quickly determine what is not working (first) within a relationship environment and has you assuming the role of troubleshooter.  Because the Perceptive trait in you naturally takes a covert approach to business and life, it can make you an easy target for criticism. You can come across as mysterious and withholding at times. This attribute is great for keeping the troops calm until it is necessary for them to know. The impression from others of you being sneaky (and perhaps even deceptive at times) may prevail, but this aspect of your nature simply wants to make sure that all aspects of dis-ease are identified before sounding the alarm. This allows for pleasant surprises and revolutionary displays of breaking news in business and in life. Additionally, your mysterious and curious nature can be very alluring and attractive. It keeps the curious drawn to you.",
                            ];
                            break;

                        case 'Effervescent':
                            $styleHeading = 'Effervescent';
                            $styleTextArray = [
                                "Your Effervescent trait makes you vibrant and delightful and gives you access to possibility-filled views of reality. You are also naturally kind and considerate. This genuine, accepting aspect of your nature allows for an inviting presence that makes you very approachable in business and in life. When performing or connecting with others you maintain a comfortable ease that makes your presentation naturally believable and entertaining. Your Effervescent trait is only one aspect of your nature, and it is responsible for your moments of playfulness and creativity. You cultivate many interests and tend to access brilliance through innocence. You also process information at lightning speed from an active and positive perspective. Your tendency is to address what is working first in an environment or situation. This positive approach coupled with your radiance allows you to simply light up a room!",
                                "The Effervescent aspect of your nature provides you with a playful, delightful charm. You are accepting, approachable and timeless. This trait is something you can access easily and call on for making friendly connections with others. Life is your audience, and you naturally uplift the spirits of others. It simply makes you a pleasure to be around. You process information at lightning speed extracting what is working while overlooking dysfunction. This permits you the ability to instantaneously disseminate information into a digestible and friendly solution that contributes to cultivating healthy conversation and relationships. Fragility accompanies this part of your nature as your desire to trust often supersedes an obvious threat. Take special care in picking people, places and opportunities wisely.",
                            ];
                            break;

                        default:
                            continue 2;
                    }

                    if (!empty($styleTextArray)) {
                        $randomKey = array_rand($styleTextArray);
                        $styleText = $styleTextArray[$randomKey];
                    }

                    $getPdfFile = self::create([
                        'assessment_id' => $assessmentId,
                        'user_id' => $userId,
                        'public_name' => $styleHeading,
                        'text' => $styleText,
                    ]);
                }
            }
        }
        else {
            $getPdfFile = $pdfFile;
        }

        return $getPdfFile;
    }
}
