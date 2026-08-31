<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $questionnaire = Questionnaire::updateOrCreate(
            ['title' => 'Niveau de préparation aux requis de la Loi 25'],
            [
                'description' => "Questionnaire de conformité à la Loi 25. Dans le doute ou si vous n'êtes pas certain de votre réponse, veuillez inscrire \"non\". En cas de notation négative : votre résultat indique qu'il faut entreprendre sans délai le processus de mise en conformité. À partir du 22 septembre 2023, les organisations qui contreviennent aux nouvelles obligations introduites par la Loi 25 s'exposeront à des amendes pouvant atteindre 10 000 000 $ ou 2% du chiffre d'affaires mondial.",
                'status' => 'published',
            ]
        );

        $types = QuestionType::pluck('id', 'name');

        $questions = [
            // Partie 1 – Évaluation des besoins d'affaires
            [
                'question' => "Pouvez-vous décrire brièvement votre entreprise, y compris son secteur d'activité, son modèle d'affaires ?",
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 1,
                'required' => false,
            ],



            [
                'question' => 'Pouvez-vous nous parler de votre infrastructure technologique et de la manière dont elle soutient vos activités ?',
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 2,
                'required' => false,
            ],

            // Partie 2 – Questionnaire (Loi 25)
            [
                'question' => 'Votre organisation collecte-t-elle des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => "Les renseignements personnels sont des informations confidentielles qui portent sur une personne physique et permettent de l'identifier.",
                'options' => ['Oui', 'Non'],
                'position' => 3,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation collecte-t-elle des renseignements personnels sensibles ?',
                'type' => 'unique_choice',
                'description' => "Un renseignement personnel est considéré sensible lorsqu'il suscite un haut degré d'attente raisonnable en matière de vie privée.",
                'options' => ['Oui', 'Non'],
                'position' => 4,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation a-t-elle nommé un responsable de la protection des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => "En vertu de la Loi 25, il est obligatoire de nommer un responsable de la protection des renseignements personnels au sein de l'organisation.",
                'options' => ['Oui', 'Non'],
                'position' => 5,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation dispose-t-elle d\'un registre de confidentialité ?',
                'type' => 'unique_choice',
                'description' => "Depuis le 22 septembre 2022, les organisations doivent tenir un registre des incidents de confidentialité. Un incident de confidentialité est (liste non exhaustive) : un accès non autorisé par la loi à un renseignement personnel ; une utilisation non autorisée par la loi d'un renseignement personnel ; une communication non autorisée par la loi d'un renseignement personnel ; une perte d'un renseignement personnel ou toute autre atteinte à la protection d'un tel renseignement.",
                'options' => ['Oui', 'Non'],
                'position' => 6,
                'required' => true,
            ],


            [
                'question' => 'Avez-vous sensibilisé les membres de votre organisation aux nouvelles obligations en matière de protection des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 7,
                'required' => true,
            ],

        ];

        foreach ($questions as $data) {
            Question::updateOrCreate(
                [
                    'questionnaire_id' => $questionnaire->id,
                    'question' => $data['question'],
                ],
                [
                    'question_type_id' => $types[$data['type']],
                    'description' => $data['description'],
                    'options' => $data['options'],
                    'position' => $data['position'],
                    'required' => $data['required'],
                ]
            );
        }
    }
}
