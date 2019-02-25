<?php
namespace Edhub\CMS\Containers\LearningPath\Domain\Repositories;

use Edhub\CMS\Containers\LearningPath\Domain\Collection\NovisLearningPaths;
use Edhub\CMS\Containers\LearningPath\Domain\Entity\NovisLearningPath;
use Edhub\CMS\Containers\LearningPath\Domain\Values\NovisLearningPathId;

class MockNovisLearningPathRepository implements NovisLearningPathRepository
{
    public function getList(): NovisLearningPaths
    {
        $mockedData = [

            ["id" => 1,
                "title" => "Novis Learning Path 1"
            ],

            ["id" => 2,
                "title" => "Novis Learning Path 2"
            ],

            ["id" => 3,
                "title" => "Novis Learning Path 3"
            ],

            ["id" => 4,
                "title" => "Novis Learning Path 4"
            ],

            ["id" => 5,
                "title" => "Novis Learning Path 5"
            ]
        ];

        $mockedCollection = NovisLearningPaths::make();

        foreach ($mockedData as $novisLearningPath) {
            $id = NovisLearningPathId::new($novisLearningPath['id']);
            $title = $novisLearningPath['title'];
            $mockedCollection->add(NovisLearningPath::new($id, $title));
        }

        return $mockedCollection;
    }
}