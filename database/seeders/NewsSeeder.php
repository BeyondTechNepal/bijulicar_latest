<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Hydrogen Combustion:',
                'title_highlight' => 'The Silent Rival',
                'title_suffix' => 'to EV Dominance',
                'slug' => Str::slug('Hydrogen Combustion The Silent Rival'),
                'author_initials' => 'AT',
                'author_name' => 'Alex Thorne',
                'author_role' => 'Chief Technical Analyst',
                'hero_image' => 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2000',
                'figure_caption' => 'Prototype H2-ICE Direct Injection Rail System (March 2026 Testing Phase)',
                'lead_paragraph' => 'The automotive industry is currently standing at a crossroads where the chemistry of the fuel tank is battling the energy density of the battery cell.',
                'section_1_title' => 'I. Beyond the Fuel Cell',
                'section_1_content' => 'It is critical to distinguish between <strong>Hydrogen Fuel Cells (FCEV)</strong> and Hydrogen Internal Combustion Engines (H2-ICE). H2-ICE utilizes the same mechanical principles used for a century.',
                'tech_specs' => json_encode([['label' => 'Hydrogen (Gas)', 'value' => '120 MJ/kg'], ['label' => 'Diesel', 'value' => '45 MJ/kg'], ['label' => 'Li-ion Battery', 'value' => '0.9 MJ/kg']]),
                'tech_note' => 'Despite the higher MJ/kg, storage volume remains the primary engineering challenge.',
                'section_2_title' => 'II. Solving the NOx Problem',
                'section_2_content' => 'Critics often point to Nitrogen Oxides (NOx). However, cryogenic injection allows engines to run "ultra-lean," suppressing NOx formation significantly.',
                'quote_text' => 'The infrastructure for ICE exists in every corner of the globe. If we change the fuel, we don\'t have to rebuild the world.',
                'quote_author' => 'Dr. Helena Vane',
                'quote_author_title' => 'Propulsion Lead',
                'section_3_title' => 'III. The Industrial Pivot',
                'section_3_content' => 'H2-ICE allows giants like Cummins and Toyota to utilize 90% of their existing supply chains, offering a faster path to net-zero.',
                'is_published' => true,
            ],
            [
                'title' => 'Solid-State Cells:',
                'title_highlight' => 'The 1000-Mile',
                'title_suffix' => 'Battery Breakthrough',
                'slug' => Str::slug('Solid State Cells Breakthrough'),
                'author_initials' => 'SK',
                'author_name' => 'Sarah Koenig',
                'author_role' => 'Energy Systems Researcher',
                'hero_image' => 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2000',
                'figure_caption' => 'Cross-section of a Ceramic Electrolyte Interface (Lab Sample #409)',
                'lead_paragraph' => 'Liquid electrolytes have reached their physical limit. To unlock the next tier of mobility, we must move to a solid-state future.',
                'section_1_title' => 'I. Density Overload',
                'section_1_content' => 'Solid-state batteries replace volatile liquids with solid ceramics, allowing for significantly higher energy density and improved safety.',
                'tech_specs' => json_encode([['label' => 'Solid-State (Target)', 'value' => '500 Wh/kg'], ['label' => 'Current Li-ion', 'value' => '260 Wh/kg']]),
                'tech_note' => 'Current prototypes show 80% charge capacity in under 10 minutes.',
                'section_2_title' => 'II. Manufacturing Hurdles',
                'section_2_content' => 'The challenge isn\'t chemistry; it\'s scale. Maintaining a vacuum-tight interface between layers is notoriously difficult in mass production.',
                'quote_text' => 'We aren\'t just making a better battery; we are changing the fundamental architecture of portable energy.',
                'quote_author' => 'Marcus Chen',
                'quote_author_title' => 'CTO of VoltEdge',
                'section_3_title' => 'III. Market Arrival',
                'section_3_content' => 'Expect premium luxury vehicles to lead the rollout by 2028, with consumer electronics following shortly after.',
                'is_published' => true,
            ],
            [
                'title' => 'Quantum Supremacy:',
                'title_highlight' => 'Breaking the Encryption',
                'title_suffix' => 'Standard',
                'slug' => Str::slug('Quantum Supremacy Breaking Encryption'),
                'author_initials' => 'LZ',
                'author_name' => 'Lin Zhang',
                'author_role' => 'Cryptographic Strategist',
                'hero_image' => 'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?q=80&w=2000',
                'figure_caption' => 'Dilution Refrigerator housing a 433-qubit processor.',
                'lead_paragraph' => 'The mathematical walls protecting the world\'s data are about to be scaled by subatomic particles.',
                'section_1_title' => 'I. Qubits vs Bits',
                'section_1_content' => 'Classical bits are 0 or 1. Qubits exist in superposition, allowing for parallel computations that baffle traditional logic.',
                'tech_specs' => json_encode([['label' => 'Classical Search', 'value' => 'N Steps'], ['label' => 'Grover\'s Algorithm', 'value' => '√N Steps']]),
                'tech_note' => 'Quantum decoherence remains the biggest threat to long-term computation.',
                'section_2_title' => 'II. The RSA Threat',
                'section_2_content' => 'Shor\'s Algorithm could theoretically factor large primes in minutes, rendering current RSA encryption obsolete.',
                'quote_text' => 'The day a quantum computer breaks RSA is the day we redefine privacy for the entire species.',
                'quote_author' => 'Elena Rossi',
                'quote_author_title' => 'Director of Cybersecurity',
                'section_3_title' => 'III. Post-Quantum Era',
                'section_3_content' => 'The migration to lattice-based cryptography is no longer a luxury; it is a survival requirement for the digital age.',
                'is_published' => true,
            ],
            [
                'title' => 'Artificial Neural:',
                'title_highlight' => 'The Rise of',
                'title_suffix' => 'Neuromorphic Hardware',
                'slug' => Str::slug('Neuromorphic Hardware Rise'),
                'author_initials' => 'JB',
                'author_name' => 'James Byron',
                'author_role' => 'Hardware Architect',
                'hero_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=2000',
                'figure_caption' => 'Synaptic Chip Architecture mimicking the human neocortex.',
                'lead_paragraph' => 'Modern AI is fast, but it is power-hungry. The future of intelligence isn\'t more GPUs—it is hardware that thinks like a brain.',
                'section_1_title' => 'I. Efficiency Gap',
                'section_1_content' => 'The human brain operates on ~20 watts. A modern AI cluster requires megawatts. Neuromorphic chips aim to close this staggering 10,000x gap.',
                'tech_specs' => json_encode([['label' => 'Human Brain', 'value' => '20 Watts'], ['label' => 'H100 Cluster', 'value' => '700+ Watts']]),
                'tech_note' => 'Event-based processing only consumes energy when spikes (data) occur.',
                'section_2_title' => 'II. Spiking Networks',
                'section_2_content' => 'Unlike traditional neural nets, Spiking Neural Networks (SNNs) communicate via discrete pulses, mimicking biological neurons.',
                'quote_text' => 'We are finally moving past the Von Neumann bottleneck toward truly cognitive machines.',
                'quote_author' => 'Dr. Aris Thorne',
                'quote_author_title' => 'Bio-Computing Lead',
                'section_3_title' => 'III. Edge Intelligence',
                'section_3_content' => 'Expect neuromorphic chips to dominate IoT and robotics, where power constraints are the primary bottleneck.',
                'is_published' => true,
            ],
            [
                'title' => 'Sustainable Fusion:',
                'title_highlight' => 'Igniting the Star',
                'title_suffix' => 'on Earth',
                'slug' => Str::slug('Sustainable Fusion Ignition'),
                'author_initials' => 'DP',
                'author_name' => 'David Phal',
                'author_role' => 'Lead Energy Consultant',
                'hero_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2000',
                'figure_caption' => 'Toroidal Magnetic Field generating 150M degree plasma.',
                'lead_paragraph' => 'Clean, limitless, and safe. Fusion is the holy grail of energy, and we are finally seeing the first net-energy gains.',
                'section_1_title' => 'I. The Magnetic Trap',
                'section_1_content' => 'Tokamaks use massive superconducting magnets to suspend hydrogen plasma at temperatures hotter than the sun\'s core.',
                'tech_specs' => json_encode([['label' => 'Fusion Temp', 'value' => '150M °C'], ['label' => 'Sun Core', 'value' => '15M °C']]),
                'tech_note' => 'High-Temperature Superconductors (HTS) have reduced the required reactor size by 40%.',
                'section_2_title' => 'II. Q-Factor Progress',
                'section_2_content' => 'The goal is Q > 10, where the energy produced is tenfold the energy required to start the reaction.',
                'quote_text' => 'When fusion scales, the word "energy crisis" will become a historical footnote.',
                'quote_author' => 'Sameer Gautam',
                'quote_author_title' => 'Nuclear Systems Engineer',
                'section_3_title' => 'III. The 2030 Roadmap',
                'section_3_content' => 'Private startups are now outpacing government projects, aiming for commercial grid connection by the mid-2030s.',
                'is_published' => true,
            ],
        ];

        foreach ($articles as $article) {
            DB::table('news')->insert($article);
        }
    }
}
