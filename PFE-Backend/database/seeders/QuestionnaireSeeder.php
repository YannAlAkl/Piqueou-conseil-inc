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
                'description' => "Partie 1 – Évaluation des besoins d'affaires, puis Partie 2 – Questionnaire de conformité à la Loi 25. Dans le doute ou si vous n'êtes pas certain de votre réponse, veuillez inscrire \"non\". En cas de notation négative : votre résultat indique qu'il faut entreprendre sans délai le processus de mise en conformité. À partir du 22 septembre 2023, les organisations qui contreviennent aux nouvelles obligations introduites par la Loi 25 s'exposeront à des amendes pouvant atteindre 10 000 000 $ ou 2% du chiffre d'affaires mondial.",
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
                'question' => "Quelles sont les valeurs et les principes directeurs qui guident vos décisions et vos actions en tant qu'entreprise ?",
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 2,
                'required' => false,
            ],
            [
                'question' => 'Comment vous positionnez-vous sur le marché et quels sont les principaux produits/services que vous proposez ?',
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 3,
                'required' => false,
            ],
            [
                'question' => "Quelle est la taille actuelle de votre entreprise en termes d'effectifs et de présence géographique ?",
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 4,
                'required' => false,
            ],
            [
                'question' => "Avez-vous des projets d'expansion ou d'ouverture de nouveaux marchés à l'échelle nationale ou internationale ?",
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 5,
                'required' => false,
            ],
            [
                'question' => 'Pouvez-vous nous parler de votre infrastructure technologique et de la manière dont elle soutient vos activités ?',
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 6,
                'required' => false,
            ],
            [
                'question' => 'Quelle est la répartition de votre clientèle entre différents segments de marché ou régions ?',
                'type' => 'text',
                'description' => null,
                'options' => null,
                'position' => 7,
                'required' => false,
            ],

            // Partie 2 – Questionnaire (Loi 25)
            [
                'question' => 'Votre organisation collecte-t-elle des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => "Les renseignements personnels sont des informations confidentielles qui portent sur une personne physique et permettent de l'identifier.",
                'options' => ['Oui', 'Non'],
                'position' => 8,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation collecte-t-elle des renseignements personnels sensibles ?',
                'type' => 'unique_choice',
                'description' => "Un renseignement personnel est considéré sensible lorsqu'il suscite un haut degré d'attente raisonnable en matière de vie privée.",
                'options' => ['Oui', 'Non'],
                'position' => 9,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation a-t-elle nommé un responsable de la protection des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => "En vertu de la Loi 25, il est obligatoire de nommer un responsable de la protection des renseignements personnels au sein de l'organisation.",
                'options' => ['Oui', 'Non'],
                'position' => 10,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation dispose-t-elle d\'un registre de confidentialité ?',
                'type' => 'unique_choice',
                'description' => "Depuis le 22 septembre 2022, les organisations doivent tenir un registre des incidents de confidentialité. Un incident de confidentialité est (liste non exhaustive) : un accès non autorisé par la loi à un renseignement personnel ; une utilisation non autorisée par la loi d'un renseignement personnel ; une communication non autorisée par la loi d'un renseignement personnel ; une perte d'un renseignement personnel ou toute autre atteinte à la protection d'un tel renseignement.",
                'options' => ['Oui', 'Non'],
                'position' => 11,
                'required' => true,
            ],
            [
                'question' => "Votre organisation a-t-elle les ressources (humaines et techniques) nécessaires pour prévenir et détecter les incidents de confidentialité tels qu'une attaque par rançongiciel, le vol de données, etc.?",
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 12,
                'required' => true,
            ],
            [
                'question' => "Si un incident de confidentialité survient au sein de votre organisation impliquant un accès non autorisé aux renseignements personnels, seriez-vous en mesure de déployer un plan d'intervention pour mitiger les risques ?",
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 13,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation a-t-elle une ou des politiques, procédures, bonnes pratiques relatives à l\'accès à des documents contenant des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 14,
                'required' => true,
            ],
            [
                'question' => 'Votre organisation recueille-t-elle un tel consentement ?',
                'type' => 'unique_choice',
                'description' => "La Loi 25 introduit de nouvelles exigences en matière de consentement. Le consentement doit être obtenu pour chaque collecte, utilisation ou communication de renseignements personnels.",
                'options' => ['Oui', 'Non'],
                'position' => 15,
                'required' => true,
            ],
            [
                'question' => 'Avez-vous sensibilisé les membres de votre organisation aux nouvelles obligations en matière de protection des renseignements personnels ?',
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 16,
                'required' => true,
            ],
            [
                'question' => "Votre organisation a-t-elle déterminé les actions à prendre pour la mise en conformité avec Loi 25 d'ici le 22 septembre 2023 ?",
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 17,
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
