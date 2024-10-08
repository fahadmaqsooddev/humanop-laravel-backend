<!DOCTYPE html>
<html>
<body>
<div class="row">
    <div class="col-lg-12 position-relative z-index-2">
        <div class="container-fluid">
            <section>
                <div class="row mt-lg-4 mt-2">
                    <div class="col-12">
                        <div class="card" style="text-align: center">
                            <div class="card-body p-3 ">
                                <div>
                                    <img
                                        src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/HumanOp.png'))) }}"
                                        style="background:#351a0d; padding: 0px; max-width: 500px;">
                                </div>

                                <h3 class="text-white text-bold">“Advanced Human Assessment Technology for a Better
                                    World.”</h3>
                                <h1 class="text-white">HumanOp Summary Report</h1>
                                <h2 class="text-white text-bold">{{$user_name}}</h2>
                                <div class="text-white mt-4" style="text-align: justify">
                                    The HumanOp Summary Report serves to identify those aspects about you that define
                                    and direct your best performance qualities. Since your physical being is
                                    respectively the assigned vehicle transporting you through life, it's often helpful
                                    to know what kind of vehicle you are. The Greeks have been insisting we "Know
                                    Thyself" for centuries. This simple request answered can facilitate success in all
                                    aspects of life, including one's performance in conducting business, and creating
                                    healthy relationships at work and in life.<br/><br/>
                                    The HumanOp Assessment is a patented instrument grounded in physical laws and
                                    objective scientific understanding. It collects and quantifies information in a
                                    user-friendly format, providing easily comprehensible results.
                                    <br/><br/>
                                    Your personal HumanOp Summary Report provides you with your own operating manual.
                                    These operating guidelines support you in making conscious choices that keep you
                                    energized and optimized. When you use your natural talents versus learned talents
                                    you gain energy. Maximizing your fuel efficiency allows you to access your true self
                                    and enjoy life in the process.
                                    <br/><br/>
                                    <h2 class="mt-4" style="color: #f2661c">The HumanOp Summary Report proves valuable
                                        in various contexts:</h2>

                                    <ul class="text-white">
                                        <li>Employer and agency recruitment</li>
                                        <li>Relationship management</li>
                                        <li>Psychotherapy</li>
                                        <li>Career guidance</li>
                                        <li>Premarital counseling</li>
                                        <li>Roommate selection</li>
                                        <li>Self-understanding and interpersonal relationships</li>
                                    </ul>
                                    <br/>

                                    By leveraging the natural understanding you gain about yourself, you can strengthen
                                    relationships, make informed career choices, and gain deeper insights into yourself
                                    and others.<br/><br/>

                                    <h2 class="mt-4" style="color: #f2661c">Your HumanOp Summary Report addresses the
                                        following:</h2>

                                    <ul class="text-white">
                                        <li>Your unique natural expression of self</li>
                                        <li>Talents that motivate and prompt you to participate in life</li>
                                        <li>What you can tolerate in terms of people, places and things</li>
                                        <li>How you connect, learn and commit experiences to memory</li>
                                        <li>Your perception of life that defines your physical reality</li>
                                        <li>How much energy you currently have available to succeed</li>
                                    </ul>
                                    <br/>

                                </div>

                                <h2 class="mt-4" style="color: #f2661c;text-align: justify">YOUR TRAITS</h2>
                                <p style="text-align: justify">Your physical "TRAITS" determine how nature shows up in
                                    you. These traits assist in providing unique insight into your capabilities and
                                    natural talents.</p>

                                @foreach($allStyles as $style)
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $style['public_name'] }}</h2>
                                    <p class="text-white" style="text-align: justify">{{ $style['text'] }}</p>
                                @endforeach

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR MOTIVATION</h2>
                                <p class="text-white" style="text-align: justify">Your "MOTIVATION" addresses what
                                    “DRIVES” you, what must be fed and honored so that you can successfully move forward
                                    in life. There are 12 “DRIVERS” in everyone’s “vehicle of self”. These drivers are
                                    all chattering at the same time, but only some are licensed to drive. Knowing how to
                                    keep these legally authorized drivers in the front seat and motivated allows for
                                    efficient travel. These driving forces represent specific laws of nature that show
                                    up in all living things. These drivers express themselves in strong states and weak
                                    states. It is your personal responsibility to come from a place of strength.
                                    Strength transmits intelligence while weakness produces ignorance. Choosing
                                    opportunities that feed the strong states of each of your “drivers”, will bring you
                                    closer to states of intelligence. </p>

                                @foreach($topTwoFeatures as $index => $feature)
                                    <?php
                                    $featureHeading = '';
                                    $featureText = '';
                                    ?>
                                    @switch($feature[1])
                                        @case('Initiates Change')
                                        <?php
                                        $featureHeading = 'Initiating Change';
                                        $featureTextArray = [
                                            "0" => "INITIATING CHANGE is a motivating force for you, as you possess a natural talent for bringing about transformation. This driving force within you contributes to humankind by dismantling existing structures and rebuilding them, thus paving the way for new beginnings. This transformative process is essential for you, as it opens doors to fresh opportunities.
                                                      <br/><br/>
                                                      To thrive, it's crucial for you to seek environments that allow you to experience this transformative process. Much like demolishing an old house to construct an improved home, when you transform something into something new, you experience a sense of betterment that energizes and motivates you.
                                                      <br/><br/>
                                                      It's vital to remain aware of your intentions and maintain integrity in your pursuits and actions. To keep this drive in check, seek opportunities to remodel, restructure, or refurbish. However, if you find yourself deprived of chances to initiate change, there may be a tendency to suppress this 'driver' rather than appropriately 'feeding' it, which could potentially lead to toxic behavior. When this driver becomes toxic, it often manifests as anger or violent outbursts.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break
                                        @case('Creating Order')
                                        <?php
                                        $featureHeading = 'Creating Order';
                                        $featureTextArray = [
                                            "0" => "CREATING ORDER is a motivator for you. While this 'driver' might be misinterpreted as 'controlling,' it actually stems from an innate need to organize various aspects of life for productive progress. Your orderly nature is a valuable asset in leadership roles, allowing you to quickly assess and address requirements through organization, optimization, and delegation.
            <br/><br/>
            Your energy thrives when you surround yourself with opportunities to create order across all life domains. It's crucial to recognize the importance of living and working in an organized environment, as this process invigorates you and provides a sense of personal fulfillment. However, it's equally important to communicate this innate need to family and colleagues, as your desire to establish efficiency and correct situations may be taken personally. Emphasize that your actions are never about them, but rather about satisfying your internal drive for order.<br/><br/>
Intelligently managing your expectations of others is essential, as their needs may differ from yours. Remember that manipulation is unnecessary to achieve the order you require for realizing your objectives. To maintain your energy and forward momentum, consistently focus on creating order in all areas of your life. <br/><br/>
Be cautious if you find yourself running out of things to organize or address, as this may lead to manipulative behavior. Instead, proactively seek out new opportunities for organization to continue fueling your personal growth and effectiveness. This approach will help you harness the strong state of your orderly nature while maintaining healthy relationships and achieving successful outcomes.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Creates Protection')
                                        <?php
                                        $featureHeading = 'Creating Protection';
                                        $featureTextArray = [
                                            "0" => "CREATING PROTECTION is a motivator for you. Your innate ability to perceive threats and take immediate preventative measures is a defining characteristic. This drive compels you to constantly look out for the best interests of yourself and others, striving to create safe environments for all.
            <br/><br/>
           When faced with difficult-to-resolve issues, you may experience a tendency to panic. It's important to recognize that this panic can be contagious, potentially affecting others even if they don't share your strong drive for protection. This phenomenon is often reflected in news stories that amplify uncertainty and fear in the world.
<br/><br/>
Given your heightened sensitivity to safety concerns, it's crucial to develop a personal litmus test or reality check that you can apply daily to make informed decisions. Your drive to create protection plays a vital role in maintaining a civilized society. Indeed, individuals like yourself are largely responsible for the existence of laws that protect us all.
<br/><br/>
When you're not actively engaged in creating protection – whether it's advocating for a new crosswalk, ensuring everyone is prepared for inclement weather, or performing preventative maintenance – you may find your energy levels depleting. To maintain your vitality and fulfill your innate drive, consistently seek ways to improve upon civilization's safety measures.
<br/><br/>
Remember, your protective talents are a valuable asset to society. By channeling this motivation constructively and maintaining a balanced perspective, you can continue to make significant contributions to the safety and well-being of yourself and for those around you.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Monetary Discernment')
                                        <?php
                                        $featureHeading = 'Monetary Discernment';
                                        $featureTextArray = [
                                            "0" => "MONETARY DISCERNMENT is a motivating force for you. This driver manifests as a deliberate approach to spending money, with financial security and meeting your needs being of paramount importance. Thoughts about money frequently occupy your mind throughout the day, reflecting the central role it plays in your decision-making processes.
<br/><br/>
            Your natural inclination towards Monetary Discernment provides you with an innate ability to create and cultivate substantial wealth. However, it's crucial to be aware that during periods of fatigue, addiction, or illness, you may be prone to overspending, potentially leading to financial difficulties.
<br/><br/>
To harness the full potential of this driving force, it's essential to exercise your natural ability to initiate abundance. You possess an inherent understanding of prosperity that not everyone shares. Embrace this gift and put it to use. Remember that true prosperity manifests through the harmonious flow of money in and out of your life.
<br/><br/>
Remain aware of the FLOW of money and all factors that support its significance. Embrace the principle that giving leads to receiving. Be cautious of any tendency to hoard or excessively conserve, as this can create a financial 'congestion' that may hinder your ability to cultivate great wealth.
<br/><br/>
By maintaining a harmonious approach to monetary matters and keeping the flow of resources active, you can leverage your Monetary Discernment to achieve financial success while avoiding potential pitfalls. This will allow you to make the most of your natural affinity for financial management and prosperity.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Visionary')
                                        <?php
                                        $featureHeading = 'The Visionary';
                                        $featureTextArray = [
                                            "0" => "The Visionary is one of your “drivers” (or motivators), and it’s characterized by an embrace of the unexpected and a natural alignment with spontaneity. This driver is energized by surprises and equips you with the remarkable ability to envision the seemingly impossible. Your spontaneous imaginings and sudden inner impulses are the wellspring of your genius, granting you effortless access to startlingly original, out-of-the-box ideas and creativity.
<br/><br/>
            Your visionary talents manifest in creative and colorful expressions, often leading you to experience extremes in how you feel. You may find yourself oscillating between moments of great excitement and profound sadness. It's crucial to recognize and accept this aspect of your nature, understanding that these fluctuations are intrinsic to your creative process rather than a flaw.
<br/><br/>
You share this driving force with numerous individuals who have brought revolutionary ideas and inventions to the world. The key to harnessing your visionary abilities lies in acknowledging the natural swing of your imagination and finding ways to harmonize these fluctuations. By doing so, you can avoid becoming stuck in either extreme…the high-high or the low-low of the emotional spectrum.
<br/><br/>
Embracing this inherent ebb and flow of your creative process is essential for maintaining your intelligence, energy, and optimal performance. Remember that it's within the swing of your imagination that true genius emerges. By cultivating awareness of these oscillations and developing strategies to harmonize them, you can fully leverage your visionary capabilities while maintaining emotional equilibrium.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Optimistic')
                                        <?php
                                        $featureHeading = 'Optimism';
                                        $featureTextArray = [
                                            "0" => "Your optimism driver allows you to tap into the wondrous aspects of life. This innate ability to view the world through rose-colored lenses provides you with a limitless perspective, where possibilities seem endless. Fun and playfulness are not just enjoyable for you; they are essential sources of energy.
<br/><br/>
          Your natural optimistic nature also grants you a remarkable adaptability to change, making new experiences not just desirable but necessary for your personal growth and fulfillment. Your lighthearted, open, and trusting demeanor creates an attraction factor that naturally draws others to you.
<br/><br/>
It’s important to recognize that when circumstances fail to provide sufficient laughter and joy, or when you allow external factors to dampen your optimism, your energy can quickly deplete, potentially leading to immature behavior. Understanding your vulnerability to deflation when your optimism 'bubble' is popped will help you ensure that you continue to fuel the optimism that keeps you energized and functioning at your best.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Humility')
                                        <?php
                                        $featureHeading = 'Humility';
                                        $featureTextArray = [
                                            "0" => "HUMILITY is one of your driving forces. This driver in you provides you with the natural ability to go into your own space and separate yourself from others. You are very easily able to just be in the moment without personal ego getting in the way. It's one of the things that is so attractive about you. People are drawn to this peaceful nature that you exude because it naturally provides a grounded space for them to step into. Overall, it just makes you really easy to be around. You speak when you have something to say and because of this people tend to listen more intently when you do speak. You are a great listener, and you make an excellent diplomat, friend, confidant.
<br/><br/>At times others will exhaust your energy and you will find yourself needing to take time alone to recharge your batteries. This is necessary for you as long as you recognize that you are simply refueling and not escaping. In order for this particular driver to stay optimized, it’s important to find the harmony every day between stepping in to be present and hold the space for others, and then stepping away to take a alone time to recharge. Neglect occurs when you stay too long in recharge mode, and this ends up turning into escape mode… and now you are neglecting your responsibilities, and you're also neglecting your presence in the lives of others. Always remember the harmony between stepping in and being present, and stepping away to recharge, is the key to your humility driver staying, energized and optimized.",];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Accomplishment')
                                        <?php
                                        $featureHeading = 'Accomplishment';
                                        $featureTextArray = [
                                            "0" => "ACCOMPLISHMENT is a powerful motivating force in your life. This driver compels you to achieve goals, with or without the consensus of others. You have a natural disregard for others' opinions about you and even thrive on pushing boundaries. Goal accomplishment is as essential as oxygen to you, making it crucial to incorporate reasonable daily objectives into your routine.
<br/><br/>
Your innate ability to make things happen can lead to restlessness when no goals are in sight or frustration when goals are set unrealistically high. This driver can be relentless in pursuing desired outcomes, which is both a strength and a potential pitfall.
<br/><br/>
It's important to be mindful of setting reasonable benchmarks for yourself. Pursuing unrealistic goals can bring out an intimidating quality that may be off-putting to others and ultimately hinder your success in healthy and natural ways. However, when you harness this drive effectively, your accomplishments can inspire others by demonstrating what's possible.
<br/><br/>
The key to leveraging this driver lies in maintaining attainable expectations for yourself. By doing so, you ensure that intelligence prevails in all your pursuits. Your accomplishments not only satisfy your personal drive but also serve as a beacon of possibility for those around you.
<br/><br/>
Remember that while your ability to achieve goals independently is a strength, balancing this with awareness of its impact on others can enhance your overall effectiveness. By channeling your drive for accomplishment wisely, you can continue to push boundaries and achieve great things while maintaining empowered relationships with those around you.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Compassion')
                                        <?php
                                        $featureHeading = 'Compassion';
                                        $featureTextArray = [
                                            "0" => "COMPASSION motivates you, instilling in you a deep personal concern for others. Your sense of alignment and fulfillment is closely tied to being of service, and you possess a natural ability to uplift others through your inviting and caring demeanor.
<br/><br/>
To maintain your vitality, it's crucial to consistently surround yourself with opportunities and environments that allow you to express your loving attentions. Your compassionate nature thrives when directed outward, towards helping and supporting others.
<br/><br/>
However, when deprived of chances to give or share your talents and deeds, you may risk turning this compassionate energy inward. This inward focus can potentially lead to sorrowful and self-centered behaviors. For your own INTELLIGENT survival, it's essential to maintain an outward focus, prioritizing the needs of others.
<br/><br/>
This outward-oriented approach is not universally necessary for everyone, but it is particularly vital for your well-being. Failing to actively seek service-oriented opportunities may result in feelings of self-pity, which can be detrimental to your well-being and personal growth.
<br/><br/>
By consciously cultivating and engaging in compassionate acts towards others, you not only fulfill your innate drive but also contribute positively to the world around you. This balance of giving and personal fulfillment is key to harnessing the full potential of your compassionate nature, ensuring your continued growth, vitality, and positive impact on those around you.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('The Traveler')
                                        <?php
                                        $featureHeading = 'The Traveler';
                                        $featureTextArray = [
                                            "0" => "The TRAVELER is a motivating force in your life, embodying your unique free spirit. You thrive on new experiences and welcome unpredictability, embracing many things while committing to few. This driver propels you to seek innovative and explorative opportunities constantly.
<br/><br/>
Variety is essential for you… in all aspects of your life – from your pursuits and activities to physical engagements. You find energy in dining at diverse places, indulging in different types of pursuits, and traveling freely. These elements of adventure and new experiences are crucial for maintaining the healthy, strong state of this driver.
<br/><br/>
When your life lacks consistent variety in both professional and personal spheres, monotony and predictability can set in, leading to a sense of deprivation. This deprivation directly impacts your relationships, essentially leaving them unfulfilled. The free spirit within you feels stifled and even claustrophobic when faced with routine, potentially causing you to seek vitality elsewhere
<br/><br/>
It's crucial to ensure that both your professional and personal life are infused with variety. Exciting, interesting, and new daily dynamics are key to staying energized and optimized. By consciously incorporating diverse experiences into your days, you nourish your Traveler driver and maintain a sense of freedom and engagement.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Aesthetic Sensibility')
                                        <?php
                                        $featureHeading = 'Aesthetic Sensibility';
                                        $featureTextArray = [
                                            "0" => "AESTHETIC SENSIBILITY is a motivating force for you. It drives you to create and maintain beautiful environments where you can both give and receive attention. This driver plays a crucial role in adding vibrancy and appeal to the world around you.
<br/><br/>
Your presentation holds great significance, not only for you but for others as well. It becomes the driving force behind everything important to you, as you naturally gravitate towards opportunities to present yourself to the world, whether in family, work, or broader contexts. The attention, feedback, acknowledgment and recognition you receive when showcasing your talents is energizing for you.
<br/><br/>
While the desire for attention is natural for all human beings, your Aesthetic Sensibility driver intensifies this need. It's essential that you take personal responsibility for creating ample opportunities to share your wisdom and display your gifts. This doesn't necessarily mean pursuing fame, but it does require regular, meaningful exchanges with colleagues, friends, and family as part of your daily routine.
<br/><br/>
Your approach to personal presentation is thoughtful and deliberate rather than haphazard. Compliments nourish you, and in relationships, daily praise keeps you content and energized.
<br/><br/>
To maintain and nourish the strong state of this driver, it's crucial to exercise your creative talents regularly. If you're not currently doing so, make time for these pursuits. Be aware that a lack of attention or opportunities to shine can lead to self-absorption. Proactively seek out assignments that require your aesthetic sensibility and performance potential.
<br/><br/>
Remember, your need for aesthetic expression, and the natural acknowledgement, feedback, attention or recognition received when you express your gifts, is a unique and valuable aspect of your nature. By embracing and accessing this drive constructively, you not only fulfill your innate needs, but also contribute to creating beauty and inspiration in the world around you.
",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break

                                        @case('Perseverance')
                                        <?php
                                        $featureHeading = 'Perseverance';
                                        $featureTextArray = [
                                            "0" => "Perseverance is a motivator for you, with task performance serving as its primary fuel. You have the natural ability to complete almost any assignment, even in the face of numerous and seemingly insurmountable obstacles. This driver within you is unfamiliar with the concept of giving up, and once you commit to something, you're determined to see it through to completion.
<br/><br/>
This tenacious, persevering drive naturally produces reliability as a supportive, consistent, and virtuous by-product. Your unwavering commitment makes you a dependable individual in various aspects of life.
<br/><br/>
However, the challenge with your Perseverance driver can arise during periods of fatigue, addiction, or illness. In these instances, stubborn behavior often emerges. You might find yourself digging in your heels, rejecting necessary change, blocking new growth, or becoming close-minded. These are predictable responses when your perseverance is misdirected or your energy is depleted.
<br/><br/>
To mitigate these potential pitfalls, it's crucial to take personal responsibility for maintaining clarity and focus. Engage in nurturing activities that promote rest and well-deserved relaxation. This self-care approach will allow you to navigate tasks smoothly, stay on purpose, and enhance the lives of others through your unwavering commitment.
<br/><br/>
By balancing your innate drive to persevere with conscious efforts to recharge and remain open-minded, you can harness the full potential of this motivating force. This balance will enable you to maintain your remarkable ability to complete tasks and overcome obstacles while avoiding the negative aspects of stubbornness or “digging in your heels”. Remember, your perseverance is a valuable asset when channeled properly, contributing significantly to your success and the well-being of those around you.",
                                        ];
                                        $randomKey = array_rand($featureTextArray); // Randomly select a text from array
                                        $featureText = $featureTextArray[$randomKey]; // Assign random text to $featureText
                                        ?>
                                        @break
                                    @endswitch

                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $index == 0 ? 'Pilot ' : 'Co-pilot ' }} {{$featureHeading}}</h2>
                                    <p class="text-white" style="text-align: justify">{!! $featureText !!}</p>
                                @endforeach

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR BOUNDARIES</h2>
                                <p class="text-white" style="text-align: justify">“ALCHEMY” addresses your personal
                                    preferences; whether you are meticulous, practical, messy and the analogy of ore is
                                    used to exemplify states of refinement… specifically Gold, Silver and Copper.
                                    “ALCHEMY” determines where your "BOUNDARIES” begin and end. This range identifies
                                    what you can tolerate in terms of people, places and things and how to best manage
                                    your choices in maximizing your energy potential. Alchemical incompatibility is the
                                    number one reason for challenges in relationships. Not addressing boundary issues in
                                    any relationship can result in relationship challenges. In business and in life it
                                    is vital to know what your personal alchemical range of tolerance is so that you can
                                    better understand your own boundaries and those around you.</p>

                                @if($boundary)
                                    <?php
                                    $boundaryHeading = '';
                                    $boundaryImage = '';
                                    $boundaryText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($boundary['public_name'])
                                        @case('Gold')
                                        <?php
                                        $boundaryHeading = 'Gold';
                                        $boundaryImage = 'img/gold.png';

                                        $boundaryTextArray = [
                                            "0" => "You possess a Gold Alchemy, which is fundamentally characterized by a pursuit of quality. This manifests in your tendency to surround yourself with people, places, and things of excellence and distinction. Your choices in dining, living arrangements, and creature comforts typically reflect high quality or at least emulate it.
            <br/><br/>
            Your approach to life is marked by meticulousness and fastidiousness, which may sometimes be perceived by others as obsessive. However, this is simply a reflection of your intense focus on maintaining high standards for yourself and your environment. The more pronounced your Gold Alchemy, the narrower the range of people and environments you can comfortably tolerate before experiencing energy depletion.
            <br/><br/>
            It's crucial to be aware of the various dynamics in your life and recognize when certain people or environments begin to drain your energy...",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Gold-Silver')
                                        <?php
                                        $boundaryHeading = 'Gold-Silver';
                                        $boundaryImage = 'img/gold-silver.png';

                                        $boundaryTextArray = [
                                            "0" => "You possess a Gold-Silver Alchemy, characterized by a unique blend of quality and practicality. This combination influences your tendency to surround yourself with people, places, and things of excellence and distinction. Your choices in dining, living arrangements, travel destinations, and creature comforts typically reflect high quality or at least emulate it.
<br/><br/>
Your approach to life is marked by meticulousness, with great attention paid to details that maintain high standards for yourself and your environment. You prefer things to have their specific place in your world, which is crucial for maintaining your energy levels. This extends to how you manage your environment, including considerations for pets and children. Establishing boundaries and rules related to potential messes is important for your well-being.
<br/><br/>
While the Gold aspect of your Alchemy aligns with high standards, the Silver component in your Alchemy introduces a layer of practicality to your approach. This unique combination allows you to maintain excellence while adapting to real-world constraints.
<br/><br/>
For instance, you might place important papers or items into neat piles, typically stored out of sight in closed cupboards or bins. However, your practical side ensures that you maintain a clear mental map of where everything is located within these spaces.
<br/><br/>
Overall, your Gold-Silver Alchemy propels you to seek and align with the best of the best. However, the Silver aspect provides an adaptability factor, allowing you to flex when necessary to accommodate immediate needs or situations. This balance between striving for excellence and maintaining practicality defines your approach to life, enabling you to navigate various situations with both refinement and flexibility.
",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Silver-Gold')
                                        <?php
                                        $boundaryHeading = 'Silver-Gold';
                                        $boundaryImage = 'img/silver-gold.png';

                                        $boundaryTextArray = [
                                            "0" => "You possess a Silver-Gold Alchemy, which provides you with a realistic and feasible approach to life and interactions. This unique combination allows you to maintain quality standards without compromising practicality, as the practical aspects take precedence in your worldview.
<br/><br/>
While you appreciate and are drawn to the best of the best, you don't necessarily require it to feel fulfilled. You can derive just as much energy and satisfaction from something that emulates high quality. This flexibility extends to your habits as well. You maintain a neat environment where everything has its place, but you're not averse to creating piles of papers in your home or office to address when time permits, rather than insisting on immediate perfection.
<br/><br/>
Your Silver-Gold Alchemy grants you the ability to adapt easily to various situations. For instance, you can comfortably leave a rinsed dish in the sink temporarily if you're in a hurry, demonstrating your capacity to prioritize practicality when necessary. This flexibility allows you to adjust to the immediate circumstances and the people involved without losing sight of maintaining overall standards.
<br/><br/>
Despite your adaptability, you remain attentive to crucial details, ensuring that important standards are not entirely disregarded in the moment. This balanced approach makes you remarkably versatile, enabling you to thrive in both refined environments and more practical or slightly messier settings without experiencing significant energy loss.
<br/><br/>
Your Silver-Gold Alchemy ultimately provides you with a valuable blend of practicality and quality-consciousness. This combination allows you to navigate various life situations with ease, maintaining high standards where it matters most while also embracing the practicalities of everyday life. This balanced perspective contributes to your overall effectiveness and adaptability in different environments and situations.
",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Silver')
                                        <?php
                                        $boundaryHeading = 'Silver';
                                        $boundaryImage = 'img/silver.png';

                                        $boundaryTextArray = [
                                            "0" => "Your Silver Alchemy provides you with a realistic and feasible approach to life and interactions. You maintain a neat environment where everything has its place, but you're also comfortable creating piles of papers (or things) to address when time allows.
<br/><br/>
Your practical nature allows for small concessions to daily life's demands, such as leaving a rinsed dish (or dishes) in the sink when you're in a hurry. This flexibility is a key aspect of your Silver Alchemy, enabling you to adapt easily to various situations and people.
<br/><br/>
Your Silver Alchemy makes you naturally versatile. You can comfortably exist in diverse environments, including messier spaces, without experiencing significant energy loss. This resilience makes you solid and durable in the face of changing circumstances.
<br/><br/>
Your Silver Alchemy also facilitates easy friendship-making. In fact, among all the Alchemies, Silver is often considered the easiest to get along with. This quality allows you to connect with a broader range of people, as you can effortlessly shift and adapt to life's various situations and challenges.
<br/><br/>
Perhaps most importantly, your Silver Alchemy fosters a high degree of acceptance and tolerance for a wide spectrum of people, places, and things. This openness and adaptability essentially puts the world at your disposal, allowing you to navigate diverse social and environmental contexts with ease.
<br/><br/>
Your ability to maintain practicality while being flexible and accepting makes you uniquely equipped to handle life's various challenges and opportunities. This balanced approach not only serves you well but also makes you a valuable and adaptable presence in any situation or relationship.
",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Silver-Copper')
                                        <?php
                                        $boundaryHeading = 'Silver-Copper';
                                        $boundaryImage = 'img/silver-copper.png';

                                        $boundaryTextArray = [
                                            "0" => "Your Silver-Copper Alchemy manifests as a practical and utilitarian approach to life. You prioritize usefulness and functionality over refinement and quality, particularly in your environment and daily operations. This adaptability allows you to easily adjust to the present moment without becoming overly attached to specific conditions. For instance, you're unfazed by the minor inconveniences that come with children, messes, or clutter, though you might tidy up a bit before guests arrive.
<br/><br/>
Your inherent flexibility grants you a natural resilience, enabling you to navigate life's changes with ease. Your relaxed demeanor doesn't shy away from hands-on work or creating in more rustic settings, and you're comfortable with a more organic, low-maintenance lifestyle. Your social circle is diverse, reflecting your broad acceptance of various people and situations.
<br/><br/>
However, your Silver-Copper Alchemy's nature might occasionally clash with others who have less patience for your unhurried approach or perceived lack of attention to detail, especially regarding tidiness. Nonetheless, your Alchemy's core strength lies in its wide-ranging acceptance and tolerance of diverse individuals, environments, and circumstances. Your easygoing attitude towards life makes you a pleasant companion, fostering comfortable interactions with those around you.
",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Copper-Silver')
                                        <?php
                                        $boundaryHeading = 'Copper-Silver';
                                        $boundaryImage = 'img/copper-silver.png';

                                        $boundaryTextArray = [
                                            "0" => "Your Copper-Silver Alchemy is characterized by a strong inclination towards utility and practicality. You have a natural tendency to maximize the use and value of everything around you, often opting for the most practical solution in any given situation. Your relaxed demeanor allows you to be comfortable with accumulating items and clutter, rarely feeling the need to tidy up even when expecting company. This organic approach to your living space reflects your low-maintenance nature, which thrives in a more lived-in environment.
<br/><br/>
However, this aspect of your nature can sometimes be misunderstood or judged unfairly by others. To bridge this gap and expand your sphere of influence, it's recommended that you occasionally lean into the Silver aspect of your Alchemy. This doesn't mean changing your fundamental nature, but rather adapting slightly to different audiences by paying a bit more attention to the upkeep of your home and personal appearance. Since adaptability and flexibility are inherent aspects of your Alchemy, making these minor adjustments shouldn't prove too challenging.
<br/><br/>
Your Copper-Silver Alchemy grants you a wide range of choices in life due to your high tolerance for various conditions. You can easily adapt to coarser, more rugged environments and are unfazed by things that might deter others, such as strong smells or a lack of creature comforts. This resilience opens up numerous opportunities for you to experience and participate in aspects of life that individuals with a higher Alchemy might avoid.
<br/><br/>
Ultimately, your Copper-Silver Alchemy provides you with a unique ability to engage with life in its many forms, allowing you to embrace experiences that others might find challenging or uncomfortable.
",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Copper')
                                        <?php
                                        $boundaryHeading = 'Copper';
                                        $boundaryImage = 'img/copper.png';

                                        $boundaryTextArray = [
                                            "0" => "Your Copper Alchemy is characterized by a strong emphasis on utility. You prioritize functionality and maximizing the use of things over their quality or refinement. This inclination naturally draws you towards concepts like recycling, refurbishing, and acquiring pre-owned or thrifted items.
<br/><br/>
Your relaxed nature finds comfort in a lived-in environment, which gives you a sense of connection to life itself. This easygoing approach rarely leads you to judge others; instead, it often encourages those around you to relax as well. Your organic lifestyle doesn't shy away from getting your hands dirty, living or creating in rustic settings, or consuming less processed, more natural foods.
<br/><br/>
The Copper element in your Alchemy attracts you to natural environments. You find alignment and energy in nature and natural ways of living. Your lifestyle is inherently low-maintenance, continuously adapting to simplicity. You're unfazed by a lack of creature comforts, easily tolerating messes, odors, unwashed dishes, dirty laundry, unmade beds, and even the smell of garbage. This tolerance extends to pet ownership as well, as none of these factors drain your energy.
<br/><br/>
However, your organic nature can sometimes be misinterpreted by others as neglect, when in reality, it's an intentional choice for personal comfort. It's crucial to remind yourself of the natural differences between various Alchemies to continue embracing your own while respecting others' differences.
<br/><br/>
Your Copper Alchemy's relaxed nature makes you less susceptible to many day-to-day stresses and pressures that might challenge those with higher Alchemies. This easygoing approach to life naturally attracts you to people and environments that align with your Copper Alchemy, as these are where you feel most comfortable and at ease.
",
                                        ];

                                        $randomKey = array_rand($boundaryTextArray);
                                        $boundaryText = $boundaryTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch

                                    <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOU HAVE
                                        A {{ $boundaryHeading }} Alchemy</h2>
                                    <div class="mt-4" style="border: 0px solid #ccc;">
                                        <img
                                            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/' . $boundaryImage))) }}"
                                            style="background:#351a0d; padding: 0px; max-width: 500px"/>
                                    </div>
                                    <p class="text-white mt-4" style="text-align: justify">{!! $boundaryText !!}</p>
                                    <!-- Render HTML tags -->
                                @endif

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR COMMUNICATION
                                    STYLE</h2>
                                <p class="text-white" style="text-align: justify">“ENERGY CENTERS” define your
                                    "COMMUNICATION STYLE" and they reveal how you uniquely relate, connect and learn
                                    from your environment. They are responsible for how every individual commits
                                    information and experiences to memory. There are four centers: Emotional,
                                    Instinctual, Intellectual and Moving. Your pronounced center of energy largely
                                    determines how you initially connect with the moment. Everyone is different, and
                                    knowing this information can be vital in communicating and connecting effectively
                                    with the world in which we live. The centers are listed below from most prominent to
                                    least prominent in you. They exemplify the “doors” to your “vehicle of self”. The
                                    first door represents the front door. Opening this door is essential in initiating
                                    the process of accessing those that follow. When all are open, a memory is
                                    established.</p>

                                @foreach($topCommunication as $index => $communication)
                                    <?php
                                    $communicationHeading = '';
                                    $communicationText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($communication['public_name'])
                                        @case('Emotional')
                                        <?php
                                        $communicationHeading = 'The Emotional Energy Center';
                                        $communicationTextArray = [
                                            "0" => "The Emotional Energy Center is deeply attuned to the taste of life as it relates to emotion.  It thrives on the profound sense of connection that comes from being in the presence of living things, whether they be people, animals, plants, or the earth itself. This center also finds fulfillment through physical expressions of emotion, such as embracing, holding hands, or being held.
<br/><br/>
The Emotional Energy Center finds solace and alignment expressing feelings openly and authentically. It places a high value on friendships and social connections, often turning to food for comfort when relationships are strained. Engaging in networking and social activities is crucial for this center to maintain emotional equilibrium.
<br/><br/>
The Emotional Energy Center tends to take words and actions to heart and it can be challenging to disengage from established relationships, even when it might be in one’s best interest.
<br/><br/>
Overall, the Emotional Energy Center strives for deep, meaningful connections in all aspects of life.
",
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break

                                        @case('Instinctual')
                                        <?php
                                        $communicationHeading = 'The Instinctual Energy Center';
                                        $communicationTextArray = [
                                            "0" => "The Instinctual Energy Center is deeply rooted in the five senses. This center is acutely aware of sensory input and craves a full, rich sensory experience to feel truly engaged and alive. For the Instinctual Center to 'open', it requires stimulation that appeals to the five senses - sight, sound, smell, taste, and touch.
<br/><br/>
Because the Instinctual Energy Center, by nature, is highly sensitive, when one’s Instinctual Energy Center is their “front door”, they may get a bad rap from time to time as being judgmental because of their sensitivity. It is difficult for them to hide the manner in which they naturally deduce the moment.
<br/><br/>
In other words, if the look, the sound, the smell, the taste or the touch does not align with them, it will be distracting for them and they’ll have a difficult time connecting with the moment at hand.
At the same time, when all five senses are activated in an aligned and connected manner, this is when the Instinctual Center can rely on its “gut instincts”.
<br/><br/>
In order to keep the Instinctual Energy Center open, this center needs to regularly engage in sensory-rich activities. This practice can lead to a more grounded, present, and instinctually aware state of being.
",
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break

                                        @case('Intellectual')
                                        <?php
                                        $communicationHeading = 'The Intellectual Energy Center';
                                        $communicationTextArray = [
                                            "0" => "The Intellectual Energy Center is most connected when entertaining new concepts. Individuals who are Intellectually centered first, will sit still and listen, ask questions, curl up with a good book, search the internet, visit the library or a bookstore, and even engage in heady discussions. Intellectually centered people love to think rather than do. They tend to be more preoccupied with the “workings of something” vs “the something itself.”  Because things need to make sense to the Intellectually centered person, they often question the fabric of life as it intersects with their own. It’s important to recognize, if your Intellectual center is your “front door”, that the result of your “need to know” can often get in your way of finding the answer. It’s important to acknowledge that others may not open up to the moment in the same way that an Intellectually centered person will. And remember, in a relationship or when communicating with others, if an Intellectually centered person is not intellectually challenged they can find themselves bored and look for mental stimulation elsewhere. ",
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break

                                        @case('Moving')
                                        <?php
                                        $communicationHeading = 'The Moving Energy Center';
                                        $communicationTextArray = [
                                            "0" => "The Moving Energy Center needs to physically move in order to connect.  For individuals whose first Energy Center is Moving, the act of physical movement helps in the comprehension and assimilation of information.
<br/><br/>
Those who are Moving centered first often find that they have a constant need to fidget, stretch, or make physical adjustments during activities that others might consider stationary. It's not uncommon to see Moving centered individuals pacing while on phone calls, tapping their feet during meetings, or even reading while walking or exercising.
<br/><br/>
Athletes, dancers, and highly active individuals are frequently Moving centered. Their need for constant motion isn't just about physical fitness; it's an integral part of how they connect, relate and learn. Interestingly, many Moving centered people can comfortably read in moving vehicles or while using exercise equipment like treadmills - activities that might cause discomfort or difficulty for others.
<br/><br/>
For those who are primarily Moving centered, it's crucial to recognize this aspect of your nature, especially when faced with creative blocks or mental challenges. Engaging in physical activities during brainstorming sessions or problem-solving exercises can lead to surprising bursts of inspiration and clarity. This could involve something as simple as taking a walk while mulling over a problem, or as intense as a full workout session to stimulate creative thinking.
",
                                        ];
                                        $randomKey = array_rand($communicationTextArray);
                                        $communicationText = $communicationTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify"> {{ $index == 0 ? 'Your First “Door” is ' : ($index == 1 ? 'Your Second “Door” is ' : ($index == 2 ? 'Your Third “Door” is ' : 'Your Fourth “Door” is ')) }}{{ $communicationHeading }}</h2>
                                    <p class="text-white" style="text-align: justify">{!! $communicationText !!}</p>
                                @endforeach

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR PERCEPTION OF
                                    LIFE</h2>
                                <p class="text-white" style="text-align: justify">Your "PERCEPTION OF LIFE” defines your
                                    electromagnetic potential. It is either positive, negative or neutral and
                                    demonstrates whether you find what is working or what is not working first in a
                                    situation or environment, or both simultaneously. All are good. It's just your
                                    personal approach. Each individual in your life has a set of 'glasses' through which
                                    they perceive every situation. Recognizing and understanding differences in
                                    another's perception of life provides a space for mutual respect.</p>

                                @if($perception)
                                    <?php
                                    $perceptionHeading = '';
                                    $perceptionText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($perception['public_name'])
                                        @case('Polarity Neutral')
                                        <?php
                                        $perceptionHeading = 'Neutral Perception of Life';
                                        $perceptionTextArray = [
                                            "0" => "You have a neutral Perception of Life. This means that you naturally perceive both what is working and what's not working, simultaneously, in a situation or environment. And this causes a slight delay while you weigh both perspectives. And it's in the delay where you can sometimes fall prey in judgment by others. In other words, meaning can be made of the delay or the “pause”. For example, a positively charged person can potentially feel like you're not on board, and a negatively charged person can potentially be left wondering if you are in agreement. This can lead to others feeling you're being distant or aloof, when in fact, you're just simply weighing the options before you “launch.” Remember to honor the “pause” for yourself. And educating others on your need to weigh both perspectives can alleviate some of the judgment if you find yourself challenged by it. Your natural Neutral Perception gives you the ability to access whole picture perspectives and approaches, and it's an extremely valuable asset to overcoming problems and finding solutions.",
                                        ];
                                        $randomKey = array_rand($perceptionTextArray);
                                        $perceptionText = $perceptionTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Polarity Positive')
                                        <?php
                                        $perceptionHeading = 'Positive Perception of Life';
                                        $perceptionTextArray = [
                                            "0" => "You have a positive Perception of Life. This means that overall, you are what we call solution- oriented. In other words, most of the time, you identify what is working first in-the-moment, and because of this, you come across as uplifting. You have a can-do attitude. And this can-do attitude doesn't mean that you're always the one to execute, it simply means that you recognize the elements that are working in and for the moment. You actually do really well with those individuals who are somewhat more negatively charged than you as it complements your nature and it allows you to conserve valuable energy. It's the true meaning of opposites attract. It's literally an electromagnetic pull towards that opposite pole. And yet, too much of a negative pull from another can actually make you feel uncomfortable and even somewhat oxygen deprived or claustrophobic. So consider this for yourself..those times where stepping away from another is the most intelligent choice in-the-moment… in order to maintain and sustain the integrity of your natural positive Perception of Life.",
                                        ];
                                        $randomKey = array_rand($perceptionTextArray);
                                        $perceptionText = $perceptionTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case('Polarity Negative')
                                        <?php
                                        $perceptionHeading = 'Negative Perception of Life';
                                        $perceptionTextArray = [
                                            "0" => "You possess a negatively charged Perception of Life, and this just means that, overall, you are more problem-oriented. In other words, most of the time you are attuned to identify what is not working first in-the-moment. And this, “what's not working” Perception of Life can often get a bad rap from others as being critical towards them or kill-joys. But what's really occurring is that you simply have this natural ability to evaluate what needs repair in the moment. And the reality is without identifying the problem, dynamics can be overlooked. Your Negative Perception of Life is a very necessary component of the whole picture. This natural part of you gives you the ability to bring the necessary to the forefront. And this, in turn, often eliminates unnecessary collateral damage or even future hardship. ",
                                        ];
                                        $randomKey = array_rand($perceptionTextArray);
                                        $perceptionText = $perceptionTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $perceptionHeading }}</h2>
                                    <p class="text-white" style="text-align: justify">{!! $perceptionText !!}</p>
                                @endif

                                <h2 class="mt-4" style="color: #f2661c; text-align: justify">YOUR ENERGY POOL</h2>
                                <p class="text-white mt-4" style="text-align: justify">Your “ENERGY POOL” represents how
                                    much physical energy you have to expend on a daily basis. Much like staying
                                    hydrated, you must guard its appropriation of use. Some activities, choices, people,
                                    places and things can rob you of vital energy, and depending upon the nature of
                                    those things or choices, you may or may not be able to recoup the energy. Throughout
                                    life your goal is to maintain an average or better volume of energy. This makes life
                                    more manageable and keeps you from being vulnerable to the stresses of life. The
                                    need for fortifying your presentation can be met by living a more naturally suited
                                    life.</p>

                                @if($energyPool)
                                    <?php
                                    $energyPoolHeading = '';
                                    $energyPoolText = '';
                                    $randomKey = 0;
                                    ?>
                                    @switch($energyPool['public_name'])
                                        @case(' Excellent')
                                        <?php
                                        $energyPoolHeading = 'Energy - Excellent';
                                        $energyPoolTextArray = [
                                            "0" => "Your energy pool is extremely large and very impressive. You likely are very successful in any and all pursuits. Manifesting and realizing dreams is a natural talent for you because there is an abundance of life force available. This means when everyone else has run out of steam, you still keep going with little or no energy loss. Like being muscularly fit, your energy pool allows you to get more done without experiencing premature fatigue. Maintaining energy efficiency is a sign of self-mastery. Given this insight, it is vital that you always come from a place of integrity in all your pursuits. Make your gifts count and remain accountable.",
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case(' Above Excellent')
                                        <?php
                                        $energyPoolHeading = 'Energy - Above Excellent';
                                        $energyPoolTextArray = [
                                            "0" => "Your energy pool exceeds the usual standard and has much to offer you and your environment in terms of engagement and stamina. This level of efficiency is usually a sign of personal care and conscious living, however it is not impervious to toxicity. A large toxic energy pool can wreak a lot of havoc and do a significant amount of damage, where a clear field largely contributes to the whole and the betterment of humankind. In an effort to maintain the integrity of your own self-preservation, take time to clear your mind and soothe the physical nature of who you are. Pick your friends and colleagues wisely. Your choices in this lifetime will produce successful outcomes when properly initiated.",
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case(' Average')
                                        <?php
                                        $energyPoolHeading = 'Energy - Average';
                                        $energyPoolTextArray = [
                                            "0" => "Your energy pool is satisfactory and responds naturally to the ebb and flow of life. Realizing that life can get difficult at times impresses upon us the need to stock up. With an average life source available, you may want to consider choosing three days a week where you invest some of your time specifically on self-renewal so as not to fall prey to unwarranted intrusion. Oftentimes, being broad-sided unexpectedly can throw us into a state of deficit. Know that you have enough energy to present well but having a little extra in the bank is not to be taken lightly. We live in a toxic world and you will encounter challenges in this life. We suggest you be prepared for all kinds of opportunities that require vital energy.",
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                        @case(' Fair')
                                        <?php
                                        $energyPoolHeading = 'Energy - Fair';
                                        $energyPoolTextArray = [
                                            "0" => "Your energy pool at this point in time is compromised. Oftentimes unexpected life changes, fatigue, worry, toxic work environments or health issues will begin to tap into our energy as a means of survival. When your energy reaches a FAIR assessment, there is concern for your total health and well being. Staying in a FAIR state too long leaves you vulnerable to physical strain and mental exhaustion. Take time everyday to do something that you love. This could be quiet time, listening to music, going to the movies, spending time in nature, singing, journaling and personal pampering. It is vital that you incorporate one or more healthy activities into your daily routine so as to begin to restore your energy pool to a more natural state.",
                                        ];
                                        $randomKey = array_rand($energyPoolTextArray);
                                        $energyPoolText = $energyPoolTextArray[$randomKey];
                                        ?>
                                        @break
                                    @endswitch
                                    <h2 class="mt-4"
                                        style="color: #f2661c; text-align: justify">{{ $energyPoolHeading }}</h2>
                                    <p class="text-white" style="text-align: justify">{!! $energyPoolText !!}</p>
                                @endif

                                <div style="text-align: justify" class="text-white mt-4"
                                     style="text-align: justify">
                                    The results documented in your HumanOp Summary Report address the uniqueness and
                                    perfection of YOU. We call this Summary Report your “Operating Manual.”<br/><br/>


                                    Your Operating Manual identifies what is required for you to maintain a healthy
                                    state. <br/><br/>


                                    Because the HumanOp technology is so multifaceted and multidimensional, to maximize
                                    the value of your Operating Manual, we invite you to explore your HumanOp Dashboard
                                    where you can ask questions of HAI (HumanOp Authentic Intelligence) or message us
                                    through the Human Network. Many resources are available to help you optimize your
                                    “vehicle” as you navigate the path ahead. <br/><br/>

                                </div>

                                <p class="text-white mt-4"
                                   style="text-align: justify; padding-bottom: 20px; border-bottom: 2px solid #f2661c">
                                    Your practitioner
                                    is N/A
                                    <br>{{\Illuminate\Support\Facades\Auth::user()['email']}}</p>
                                <p class="text-white mt-4" style="text-align: justify">For internal use only. <br>Compatibility
                                    values for
                                    BR {{\Illuminate\Support\Facades\Auth::user()['gender'] == 1 ? '(F)' : '(M)'}}
                                    Interval</p>

                                <p class="text-white mt-4" style="text-align: justify">S {{$style_position}}</p>
                                <p class="text-white mt-4" style="text-align: justify">F {{$feature_position}}</p>
                                <p class="text-white mt-4" style="text-align: justify">Alch {{$alchl_code}}</p>
                                <p class="text-white mt-4" style="text-align: justify">
                                    PV {{$pv > 0 ? '+' : ''}} {{$pv}} REP
                                    ARC {{$pv - $ep}} to +{{$pv + $ep}}</p>
                                <p class="text-white mt-4" style="text-align: justify">REP {{$ep}}</p>
                                <p class="text-white mt-4" style="text-align: justify">TEP {{$ep * 2}}</p>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
</div>
</body>
</html>
