<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WellbeingGuideController extends Controller
{
    public function index(): View
    {
        return view('wellbeing.guide', [
            'quickSteps' => [
                [
                    'title' => 'Pause and ground yourself',
                    'text' => 'Look around and name five things you can see, four things you can feel, three things you can hear, two things you can smell, and one thing you can taste.',
                    'icon' => 'bi-bullseye',
                ],
                [
                    'title' => 'Slow your breathing',
                    'text' => 'Breathe in for four counts, hold for two counts, and breathe out for six counts. Repeat this five times.',
                    'icon' => 'bi-wind',
                ],
                [
                    'title' => 'Write one clear sentence',
                    'text' => 'Write: “Right now I feel ___ because ___, and the next small step I can take is ___.”',
                    'icon' => 'bi-pencil-square',
                ],
                [
                    'title' => 'Reach one safe person',
                    'text' => 'Message a friend, family member, lecturer, roommate, or counsellor. You do not need to explain everything perfectly.',
                    'icon' => 'bi-person-heart',
                ],
            ],
            'situations' => [
                [
                    'label' => 'Stress or tension',
                    'description' => 'When your body feels tight, restless, or overloaded.',
                    'steps' => [
                        'Take a five-minute break away from the screen.',
                        'Stretch your neck, shoulders, hands, and back.',
                        'Break the task into one small 10-minute action.',
                        'Drink water and eat something simple if you skipped a meal.',
                    ],
                    'badge' => 'wb-badge-neutral',
                    'icon' => 'bi-lightning-charge',
                ],
                [
                    'label' => 'Low mood or depressive feelings',
                    'description' => 'When you feel heavy, numb, hopeless, or lose interest in usual things.',
                    'steps' => [
                        'Do one basic care task: shower, eat, tidy your desk, or step outside.',
                        'Avoid staying alone with painful thoughts for too long.',
                        'Write down what has changed in sleep, appetite, energy, or concentration.',
                        'Request counselling support if the feeling continues or affects daily life.',
                    ],
                    'badge' => 'wb-badge-negative',
                    'icon' => 'bi-cloud-rain',
                ],
                [
                    'label' => 'Anxiety or panic',
                    'description' => 'When worry feels fast, physical, or difficult to control.',
                    'steps' => [
                        'Put both feet on the floor and press your toes gently into the ground.',
                        'Breathe out slowly. Longer exhales can help the body settle.',
                        'Name the worry, then name what is happening right now in the room.',
                        'Reduce caffeine and pause doom-scrolling when your body is already activated.',
                    ],
                    'badge' => 'wb-badge-positive',
                    'icon' => 'bi-heart-pulse',
                ],
                [
                    'label' => 'Study pressure',
                    'description' => 'When assignments, deadlines, or exams feel too much.',
                    'steps' => [
                        'Write every task down, then choose only the next one.',
                        'Use a 25-minute focus block followed by a five-minute break.',
                        'Ask your lecturer or friend one specific question instead of hiding the problem.',
                        'Sleep before an exam when possible. Exhaustion makes thinking harder.',
                    ],
                    'badge' => 'wb-badge-neutral',
                    'icon' => 'bi-journal-check',
                ],
            ],
        ]);
    }
}
