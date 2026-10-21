<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds for Edward SEO articles.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Mastering the Cover Drive: The Ultimate Guide for Junior & Senior Batters',
                'slug' => 'mastering-the-cover-drive-cricket-guide',
                'content' => <<<HTML
<p>The cover drive remains cricket’s most aesthetically pleasing yet technically demanding stroke. Whether you are playing club cricket in South Africa or aiming for representative honors, developing a crisp, fluent cover drive requires precise footwork, head alignment, and soft hands.</p>

<h2>1. The Stance and Initial Trigger Movement</h2>
<p>Everything starts with balance. Your weight should be evenly distributed between the balls of your feet. As the bowler approaches release, a slight, calm forward press or trigger movement prepares your body to commit either forward or back without locking your knees.</p>

<h2>2. Head Position and Eye Line</h2>
<p>One of the most frequent faults coaches observe at PlayCricket is a dipping head or tilted helmet grille. Keep your eyes parallel to the pitch. Your head must lead your front foot toward the pitch of the ball. Remember the classic coaching maxim: <em>where your head goes, your weight follows</em>.</p>

<h2>3. Front Foot Stride and Knee Bend</h2>
<p>Step toward the line of the delivery, but avoid over-striding. Over-striding locks your hips and prevents smooth weight transfer. Bend your front knee slightly so your head is positioned directly over or slightly ahead of the ball at the moment of impact.</p>

<h2>4. Swing Plane and Wrist Control</h2>
<p>Keep your top hand firm and your bottom hand relaxed. The bat face should come through straight from third-man or slip, presenting a wide, full blade. Resist the urge to flick your wrists at the point of contact—let the natural momentum of the bat carry the stroke smoothly along the turf.</p>

<h2>5. Practicing With Bowling Machines at PlayCricket</h2>
<p>Consistency comes from deliberate repetitions. At our high-performance facility at PlayCricket, our programmable bowling machines can deliver repeated full-length balls just outside off-stump at speeds tailored from junior academy levels to senior premier league pace. 50 deliberate cover drives twice a week in our indoor lanes will build muscle memory faster than any outdoor net session.</p>

<blockquote>"A great cover drive is not about brute strength; it is about balance, stillness at release, and letting the blade do the work." — PlayCricket Coaching Team</blockquote>
HTML,
                'excerpt' => 'A masterclass on executing the perfect cricket cover drive. Learn footwork, head alignment, swing plane, and indoor lane drills to elevate your batting.',
                'featured_image' => '/images/private-coaching.jpg',
                'author' => 'Head Coach Lazarus',
                'category' => 'Batting Techniques',
                'tags' => ['cover drive', 'batting technique', 'cricket coaching', 'junior academy'],
                'meta_title' => 'Mastering the Cover Drive | PlayCricket Coaching Guide',
                'meta_description' => 'Comprehensive cricket batting guide on mastering the cover drive: footwork, head position, swing plane, and lane drills at PlayCricket.',
                'focus_keyword' => 'cover drive cricket technique',
                'status' => 'published',
                'source' => 'seed',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Maximizing Your Indoor Cricket Lane Hire: A Step-by-Step Training Blueprint',
                'slug' => 'maximizing-indoor-cricket-lane-hire-training-blueprint',
                'content' => <<<HTML
<p>Hiring an indoor cricket lane for 60 to 120 minutes is an investment in your performance. Too often, players turn up, bowl without warm-ups, and swing wildly at every delivery. To maximize your session, structure your training with intention.</p>

<h2>Phase 1: Warm-Up and Movement Preparation (0–15 mins)</h2>
<p>Begin with dynamic mobility: shoulder rotations, thoracic mobility, hamstring sweeps, and light jog bursts. Spend five minutes doing shadow batting or light underarm feed drills to get your hands moving freely.</p>

<h2>Phase 2: Technical Skill Focus (15–40 mins)</h2>
<p>Isolate one specific technique per session. If you are batting against a bowling machine, set the machine to a consistent length and work exclusively on playing late with soft hands under your eyes. If you are a bowling pair, practice landing the ball on a taped 1-meter target zone on a good length.</p>

<h2>Phase 3: Match Simulation and Scenario Drills (40–55 mins)</h2>
<p>Bring intensity into the net. Set realistic field conditions:
<ul>
    <li><strong>Scenario A:</strong> 12 runs needed off 2 overs with 2 wickets in hand. Practice rotating strike and boundary options.</li>
    <li><strong>Scenario B:</strong> First 5 overs of a red-ball match. Bowlers have a new ball with slip cordon and short cover; batters must leave on length and defend stoutly.</li>
</ul>
</p>

<h2>Phase 4: Cool Down and Video Review (55–60 mins)</h2>
<p>Take five minutes to review video footage captured on your phone. Record your front-foot plant, release point, or bat lift. Comparing your setup with coach recommendations helps cement visual learning.</p>

<p>Book your indoor lane session today at PlayCricket starting from R350/hour, complete with state-of-the-art bowling machines and full-length turf-feel nets.</p>
HTML,
                'excerpt' => 'How to get the most out of your 60-minute cricket lane hire. Warm-up routines, targeted technical drills, match simulation, and bowling machine settings.',
                'featured_image' => '/images/lane-hire.png',
                'author' => 'PlayCricket Coaching Staff',
                'category' => 'Lane Hire & Practice',
                'tags' => ['lane hire', 'cricket nets', 'training drills', 'bowling machine'],
                'meta_title' => 'Maximizing Indoor Cricket Lane Hire | PlayCricket Guide',
                'meta_description' => 'Get the most value from your indoor cricket lane booking. Discover how to structure your 60-minute net session for peak performance.',
                'focus_keyword' => 'indoor cricket lane hire practice',
                'status' => 'published',
                'source' => 'seed',
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Junior Cricket Academy: Building Strong Foundations for Young Cricketers',
                'slug' => 'junior-cricket-academy-strong-foundations-young-cricketers',
                'content' => <<<HTML
<p>Youth cricket development in South Africa is flourishing, but transitioning young players from casual backyard games to hard-ball match play requires patience, structured coaching, and an encouraging environment.</p>

<h2>Why Early Fundamentals Matter</h2>
<p>Developing poor habits at ages 8–13 can be difficult to unlearn later. Our Junior Cricket Academy focuses on athletic agility, hand-eye coordination, balance, and spatial awareness before introducing complex biomechanical instructions.</p>

<h2>Safety and Confidence With Hard Balls</h2>
<p>Many young cricketers experience a natural apprehension when facing real leather cricket balls for the first time. We utilize progressive stages—starting with soft incrediballs, graduating to composite safety balls, and finally transitioning to official match balls in controlled net environments.</p>

<h2>Age-Group Lane Allocations</h2>
<p>At PlayCricket, we maintain dedicated lane separations (Lane 1 for Under 13s, Lane 2 for Above 13s) ensuring younger players train in an age-appropriate, safe atmosphere with customized crease dimensions and pitch lengths.</p>

<p>Our certified coaching team offers specialized Junior Coaching sessions at R450/hour, empowering the next generation of South African cricketers with skills, passion, and sportsmanship.</p>
HTML,
                'excerpt' => 'A guide for parents and aspiring juniors on building cricket fundamentals, developing confidence with hard balls, and structured age-group coaching.',
                'featured_image' => '/images/junior-academy.png',
                'author' => 'Coach Ryan',
                'category' => 'Junior Cricket Academy',
                'tags' => ['junior cricket', 'academy', 'youth sports', 'cricket coaching'],
                'meta_title' => 'Junior Cricket Academy Foundations | PlayCricket',
                'meta_description' => 'Discover how PlayCricket Junior Academy develops young cricketers with age-appropriate lane hire, confidence-building, and expert coaching.',
                'focus_keyword' => 'junior cricket academy south africa',
                'status' => 'published',
                'source' => 'seed',
                'published_at' => now(),
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
