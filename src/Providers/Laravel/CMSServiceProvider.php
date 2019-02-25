<?php

namespace Edhub\CMS\Providers\Laravel;

use Edhub\CMS\Auth\CmsPermissions;
use Edhub\Shared\Criteria\CriteriaFactory;
use Edhub\Shared\Exceptions\RuntimeException;
use Edhub\CMS\Containers\Common\Services\AvailableUserCompanies;
use Edhub\CMS\Containers\Common\Services\CudAvailableUserCompanies;
use Edhub\CMS\Containers\Course\Domain\Repositories\Criteria\CategoriesCriteria;
use Edhub\CMS\Containers\Common\Criteria\CompanyCriteria;
use Edhub\CMS\Containers\Course\Domain\Repositories\Criteria\LearningPathCriteria;
use Edhub\CMS\Containers\Common\Criteria\IdsCriteria;
use Edhub\Shared\Criteria\SortingCriteria;
use Edhub\CMS\Containers\Course\Domain\Repositories\Criteria\PublicCompanyCriteria;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\Criteria\DocumentRolesCriteria;
use Edhub\CMS\Containers\Document\Domain\Repositories\DBDocumentRepository;
use Edhub\CMS\Containers\Document\Domain\Repositories\DocumentRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\Criteria\CourseRelationInPublicCompanyCriteria;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\Criteria\SearchCriteria;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\DatabaseLearningPathRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\LearningPathRepository;
use Edhub\CMS\Containers\Common\Criteria\StatusCriteria;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\MockNovisLearningPathRepository;
use Edhub\CMS\Containers\LearningPath\Domain\Repositories\NovisLearningPathRepository;
use Edhub\CMS\Providers\CurrentUserCompanyRoleProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Prettus\Repository\Providers\RepositoryServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Translatable\TranslatableServiceProvider;
use Symfony\Component\Finder\Finder;

class CMSServiceProvider extends ServiceProvider
{
    public const MODULE_NAME = 'cms';
    const CONFIG_KEY = 'modules.cms.config';
    private $configPath = __DIR__ . '/../../Config/cms.php';
    private $repositoryCriteria = [
        'companies' => CompanyCriteria::class,
        'sorting' => SortingCriteria::class,
        'categories' => CategoriesCriteria::class,
        StatusCriteria::NAME => StatusCriteria::class,
        'paths' => LearningPathCriteria::class,
        'search' => SearchCriteria::class,
        IdsCriteria::NAME => IdsCriteria::class,
        PublicCompanyCriteria::NAME => PublicCompanyCriteria::class,
        CourseRelationInPublicCompanyCriteria::NAME => CourseRelationInPublicCompanyCriteria::class,
        DocumentRolesCriteria::NAME => DocumentRolesCriteria::class,
    ];

    public function configurations(): array
    {
        return [
            'title' => 'Content Management System (CMS)',
            'name' => self::MODULE_NAME,
            'migrationsPath' => __DIR__ . '/../../../data/migrations',
            'permissions' => [
                [
                    'name' => CmsPermissions::MANAGE_CONTENT,
                    'title' => 'CMS: Management Permission',
                    'description' => 'Get course info, Get list of courses, Update course, Manage learning paths.',
                ],
            ],
            'seeder' => \CMSDatabaseSeeder::class,
            'menu' => $this->getMenuList(),
        ];
    }

    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom($this->configurations()['migrationsPath']);
    }

    public function register()
    {
        $this->app->register(CMSRouteServiceProvider::class);
        /** Register 3rd party packages. */
        $this->app->register(TranslatableServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(MediaLibraryServiceProvider::class);

        $this->mergeConfig();
        $this->registerModuleExtraProviders();

        $this->app->bind(LearningPathRepository::class, DatabaseLearningPathRepository::class);
        $this->app->bind(NovisLearningPathRepository::class, MockNovisLearningPathRepository::class);
        $this->app->bind(DocumentRepository::class, DBDocumentRepository::class);

        $this->app->bind(AvailableUserCompanies::class, CudAvailableUserCompanies::class);
        $this->app->afterResolving(CriteriaFactory::class, function (CriteriaFactory $criteriaFactory) {
            foreach ($this->repositoryCriteria as $criteriaName => $criteria) {
                $criteriaFactory->addCriteria($criteriaName, function (array $arguments) use ($criteria) {
                    return new $criteria($arguments);
                });
            }
        });

        $this->registerEntityFactories();
    }

    protected function registerConfig()
    {
        $this->publishes([
            $this->configPath => config_path('modules/cms/config.php'),
        ], 'config');
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom($this->configPath, self::CONFIG_KEY);
    }

    private function getMenuList(): array
    {
        return [
            'admin' => [
                'menu' => [
//                    "courses_categories" => [
//                    "title" => "attributes.category",
//                    "route" => "/courses/categories",
//                    "icon" => "fa fa-map",
//                    "menu" => []
//                    ],
                    'courses' => [
                        'title' => 'attributes.courses',
                        'route' => 'AdminCoursesOverview',
                        'icon' => 'fa fa-book',
                        'permissions' => [CmsPermissions::MANAGE_CONTENT],
                        'submenu' => []
                    ],
                    'learnpaths' => [
                        'title' => 'attributes.learnpaths',
                        'route' => 'AdminLearnpathsOverview',
                        'icon' => 'fa fa-map',
                        'permissions' => [CmsPermissions::MANAGE_CONTENT],
                        'submenu' => []
                    ],
                    'documents' => [
                        'title' => 'attributes.documents',
                        'route' => 'DocumentsOverview',
                        'icon' => 'fa fa-book',
                        'permissions' => [CmsPermissions::MANAGE_CONTENT],
                        'submenu' => []
                    ],
                ]
            ]
        ];
    }

    private function registerEntityFactories(): void
    {
        $this->app->afterResolving(Factory::class, function (Factory $factory) {
            $factoryDir = realpath(__DIR__ . '/../../../data/factories');
            if (is_dir($factoryDir)) {
                foreach (Finder::create()->files()->in($factoryDir) as $file) {
                    require $file->getRealPath();
                }
            }
        });
    }

    private function registerModuleExtraProviders()
    {
        $cmsConfig = $this->app['config']->get(self::CONFIG_KEY);

        $this->app->bind(CurrentUserCompanyRoleProvider::class, function ($app) use ($cmsConfig) {
            $menuProviderClass = $cmsConfig['providers']['role'] ?? '';
            if (empty($menuProviderClass)) {
                throw new RuntimeException(sprintf('Role provider for CMS module is not setup.'));
            }

            return $this->app->make($menuProviderClass);
        });
    }
}